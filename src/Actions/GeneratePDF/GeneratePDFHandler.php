<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\GeneratePDF;

use N1ebieski\KSEFClient\Actions\AbstractHandler;
use N1ebieski\KSEFClient\DTOs\KsefPDFs;
use N1ebieski\KSEFClient\DTOs\QRCodes;
use N1ebieski\KSEFClient\ValueObjects\QRCode;
use N1ebieski\KSEFClient\ValueObjects\Requests\KsefNumber;
use RuntimeException;
use setasign\Fpdi\Fpdi;

final class GeneratePDFHandler extends AbstractHandler
{
    public function __construct(
        private readonly Fpdi $fpdi
    ) {
    }

    public function handle(GeneratePDFAction $action): KsefPDFs
    {
        $documents = array_filter([
            'invoice' => $action->invoiceDocument,
            'upo' => $action->upoDocument
        ]);

        $pdfs = [];

        foreach ($documents as $key => $document) {
            $xmlFile = tempnam(sys_get_temp_dir(), 'xml_');

            if ($xmlFile === false) {
                throw new RuntimeException(
                    sprintf('Unable to create temp file for xml in %s.', sys_get_temp_dir())
                );
            }

            $pdfFile = tempnam(sys_get_temp_dir(), 'pdf_');

            if ($pdfFile === false) {
                throw new RuntimeException(
                    sprintf('Unable to create temp file for pdf in %s.', sys_get_temp_dir())
                );
            }

            file_put_contents($xmlFile, $document);

            $command = "node {$action->ksefFeInvoiceConverterPath->value} {$key} {$xmlFile} {$pdfFile}";

            if ($key === 'invoice' && $action->ksefNumber instanceof KsefNumber) {
                $command .= " --nr-ksef {$action->ksefNumber->value}";
            }

            $process = proc_open(
                $command,
                [
                    ["pipe", "r"],  // stdin
                    ["pipe", "w"],  // stdout
                    ["pipe", "w"],  // stderr
                ],
                $pipes
            );

            if ( ! is_resource($process)) {
                throw new RuntimeException('Unable to start Node.js process.');
            }

            fclose($pipes[0]);

            $stderr = stream_get_contents($pipes[2]);

            fclose($pipes[1]);
            fclose($pipes[2]);

            $returnVar = proc_close($process);

            if ($returnVar !== 0) {
                throw new RuntimeException("Node.js process exited with code {$returnVar}:\n{$stderr}");
            }

            $pdfs[] = $pdfFile;
        }

        /** @var non-falsy-string $invoiceFile */
        $invoiceFile = $pdfs[0];

        $pdfs = array_map(file_get_contents(...), $pdfs);

        if ($action->qrCodes instanceof QRCodes) {
            $pageCount = $this->fpdi->setSourceFile($invoiceFile);

            $lastPage = $pageCount;

            $pageId = $this->fpdi->importPage($lastPage);
            /** @var array{orientation: string, width: int, height: int} $size */
            $size = $this->fpdi->getTemplateSize($pageId);

            $this->fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $this->fpdi->useTemplate($pageId);

            $y = $size['height'] - $action->qrCodeImageSize - $action->qrCodeMargin;

            $x = $size['width'] - $action->qrCodeMargin;

            $x1 = $x - $action->qrCodeImageSize;

            if ($action->qrCodes->code2 instanceof QRCode) {
                $x1 = $x1 - $action->qrCodePadding - $action->qrCodeImageSize;

                $x2 = $x - $action->qrCodeImageSize;

                $code2File = tempnam(sys_get_temp_dir(), 'code2_');

                if ($code2File === false) {
                    throw new RuntimeException(
                        sprintf('Unable to create temp file for qr code in %s.', sys_get_temp_dir())
                    );
                }

                $code2File .= '.png';

                file_put_contents($code2File, $action->qrCodes->code2->raw);

                $this->fpdi->Image($code2File, $x2, $y, $action->qrCodeImageSize, $action->qrCodeImageSize);
            }

            $code1File = tempnam(sys_get_temp_dir(), 'code1_');

            if ($code1File === false) {
                throw new RuntimeException(
                    sprintf('Unable to create temp file for qr code in %s.', sys_get_temp_dir())
                );
            }

            $code1File .= '.png';

            file_put_contents($code1File, $action->qrCodes->code1->raw);

            $this->fpdi->Image($code1File, $x1, $y, $action->qrCodeImageSize, $action->qrCodeImageSize);

            $pdfs[0] = $this->fpdi->Output('', 'S');
        }

        /** @var array<int, string> $pdfs */
        return new KsefPDFs(...$pdfs);
    }

}

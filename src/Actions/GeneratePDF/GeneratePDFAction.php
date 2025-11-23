<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Actions\GeneratePDF;

use N1ebieski\KSEFClient\Actions\AbstractAction;
use N1ebieski\KSEFClient\DTOs\QRCodes;
use N1ebieski\KSEFClient\ValueObjects\KsefFeInvoiceConverterPath;
use N1ebieski\KSEFClient\ValueObjects\Requests\KsefNumber;

final class GeneratePDFAction extends AbstractAction
{
    public function __construct(
        public readonly string $invoiceDocument,
        public readonly KsefFeInvoiceConverterPath $ksefFeInvoiceConverterPath,
        public readonly ?string $upoDocument = null,
        public readonly ?KsefNumber $ksefNumber = null,
        public readonly ?QRCodes $qrCodes = null,
        public readonly int $qrCodeImageSize = 30,
        public readonly int $qrCodeMargin = 12,
        public readonly int $qrCodePadding = 5,
    ) {
    }
}

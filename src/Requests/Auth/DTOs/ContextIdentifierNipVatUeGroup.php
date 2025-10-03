<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Auth\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\NipVatUe;

final readonly class ContextIdentifierNipVatUeGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public NipVatUe $nipVatUe,
    ) {
    }

    public function getValue(): NipVatUe
    {
        return $this->nipVatUe;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $contextIdentifierNipVatUeGroup = $dom->createElement('ContextIdentifierNipVatUeGroup');
        $dom->appendChild($contextIdentifierNipVatUeGroup);

        $nipVatUe = $dom->createElement('NipVatUe');
        $nipVatUe->appendChild($dom->createTextNode((string) $this->nipVatUe->value));

        $contextIdentifierNipVatUeGroup->appendChild($nipVatUe);

        return $dom;
    }
}

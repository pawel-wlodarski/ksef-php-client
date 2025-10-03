<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Auth\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class ContextIdentifierNipGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public NIP $nip,
    ) {
    }

    public function getValue(): NIP
    {
        return $this->nip;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $contextIdentifierNipGroup = $dom->createElement('ContextIdentifierNipGroup');
        $dom->appendChild($contextIdentifierNipGroup);

        $nip = $dom->createElement('Nip');
        $nip->appendChild($dom->createTextNode((string) $this->nip->value));

        $contextIdentifierNipGroup->appendChild($nip);

        return $dom;
    }
}

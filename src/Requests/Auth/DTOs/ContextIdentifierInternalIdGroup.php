<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Auth\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\InternalId;

final readonly class ContextIdentifierInternalIdGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public InternalId $internalId,
    ) {
    }

    public function getValue(): InternalId
    {
        return $this->internalId;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $contextIdentifierInternalIdGroup = $dom->createElement('ContextIdentifierInternalIdGroup');
        $dom->appendChild($contextIdentifierInternalIdGroup);

        $internalId = $dom->createElement('InternalId');
        $internalId->appendChild($dom->createTextNode((string) $this->internalId->value));

        $contextIdentifierInternalIdGroup->appendChild($internalId);

        return $dom;
    }
}

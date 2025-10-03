<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Auth\DTOs;

use DOMDocument;
use N1ebieski\KSEFClient\Contracts\DomSerializableInterface;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\PeppolId;

final readonly class ContextIdentifierPeppolIdGroup extends AbstractDTO implements DomSerializableInterface
{
    public function __construct(
        public PeppolId $peppolId,
    ) {
    }

    public function getValue(): PeppolId
    {
        return $this->peppolId;
    }

    public function toDom(): DOMDocument
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $contextIdentifierPeppolIdGroup = $dom->createElement('ContextIdentifierPeppolIdGroup');
        $dom->appendChild($contextIdentifierPeppolIdGroup);

        $peppolId = $dom->createElement('PeppolId');
        $peppolId->appendChild($dom->createTextNode((string) $this->peppolId->value));

        $contextIdentifierPeppolIdGroup->appendChild($peppolId);

        return $dom;
    }
}

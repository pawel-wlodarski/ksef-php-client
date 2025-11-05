<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Permissions\Query\Personal\Grants;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\PageOffset;
use N1ebieski\KSEFClient\ValueObjects\Requests\PageSize;

final class GrantsRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public readonly Optional | PageOffset $pageOffset = new Optional(),
        public readonly Optional | PageSize $pageSize = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        /** @var array<string, mixed> $data */
        $data = $this->toArray();

        return [
            ...$data,
            'subjectIdentifier' => [
                'type' => $this->subjectIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->subjectIdentifierGroup->getIdentifier(),
            ],
            'contextIdentifier' => [
                'type' => $this->contextIdentifierGroup->getIdentifier()->getType(),
                'value' => (string) $this->contextIdentifierGroup->getIdentifier(),
            ],
        ];
    }
}

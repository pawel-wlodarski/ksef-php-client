<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Tokens\Create;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Tokens\ValueObjects\TokenPermissionType;

final readonly class CreateRequest extends AbstractRequest implements BodyInterface
{
    /**
     * @param array<int, TokenPermissionType> $permissions
     * @return void
     */
    public function __construct(
        public array $permissions,
        public string $description,
    ) {
    }

    public function toBody(): array
    {
        return $this->toArray();
    }
}

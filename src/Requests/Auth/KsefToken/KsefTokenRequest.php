<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Auth\KsefToken;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\DTOs\Requests\Auth\AuthorizationPolicy;
use N1ebieski\KSEFClient\DTOs\Requests\Auth\ContextIdentifierGroup;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Requests\Auth\Challenge;
use N1ebieski\KSEFClient\ValueObjects\Requests\Auth\EncryptedToken;

final readonly class KsefTokenRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public Challenge $challenge,
        public ContextIdentifierGroup $contextIdentifierGroup,
        public EncryptedToken $encryptedToken,
        public Optional | AuthorizationPolicy $authorizationPolicy = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        return [
            ...$this->toArray(only: ['challenge', 'encryptedToken', 'authorizationPolicy']),
            'contextIdentifier' => $this->contextIdentifierGroup->toBody(),
        ];
    }
}

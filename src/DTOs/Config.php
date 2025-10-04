<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs;

use N1ebieski\KSEFClient\ValueObjects\HttpClient\BaseUri;
use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\ValueObjects\AccessToken;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\RefreshToken;
use SensitiveParameter;

final readonly class Config extends AbstractDTO
{
    public function __construct(
        public BaseUri $baseUri,
        #[SensitiveParameter]
        public ?AccessToken $accessToken = null,
        #[SensitiveParameter]
        public ?RefreshToken $refreshToken = null,
        #[SensitiveParameter]
        public ?EncryptionKey $encryptionKey = null
    ) {
    }

    public function withAccessToken(AccessToken $accessToken): self
    {
        return self::from([
            ...$this->toArray(),
            'accessToken' => $accessToken
        ]);
    }

    public function withRefreshToken(RefreshToken $refreshToken): self
    {
        return self::from([
            ...$this->toArray(),
            'refreshToken' => $refreshToken
        ]);
    }
}

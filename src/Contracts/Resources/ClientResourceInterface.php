<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources;

use DateTimeInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Auth\AuthResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Certificates\CertificatesResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Security\SecurityResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Sessions\SessionsResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Testdata\TestdataResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Tokens\TokensResourceInterface;
use N1ebieski\KSEFClient\ValueObjects\AccessToken;
use N1ebieski\KSEFClient\ValueObjects\RefreshToken;

interface ClientResourceInterface
{
    public function getAccessToken(): ?AccessToken;

    public function getRefreshToken(): ?RefreshToken;

    public function withAccessToken(AccessToken $accessToken, ?DateTimeInterface $validUntil = null): self;

    public function withRefreshToken(RefreshToken $refreshToken, ?DateTimeInterface $validUntil = null): self;

    public function auth(): AuthResourceInterface;

    public function security(): SecurityResourceInterface;

    public function sessions(): SessionsResourceInterface;

    public function certificates(): CertificatesResourceInterface;

    public function tokens(): TokensResourceInterface;

    public function testdata(): TestdataResourceInterface;
}

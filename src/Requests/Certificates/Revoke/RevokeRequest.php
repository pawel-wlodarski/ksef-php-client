<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Certificates\Revoke;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\ValueObjects\Requests\Certificates\CertificateSerialNumber;
use N1ebieski\KSEFClient\ValueObjects\Requests\Certificates\RevocationReason;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Support\KeyType;

final readonly class RevokeRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public CertificateSerialNumber $certificateSerialNumber,
        public Optional | RevocationReason | null $revocationReason = new Optional()
    ) {
    }

    public function toBody(): array
    {
        return $this->toArray(only: ['revocationReason']);
    }
}

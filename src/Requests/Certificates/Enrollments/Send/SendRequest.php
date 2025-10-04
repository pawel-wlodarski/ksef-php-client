<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Certificates\Enrollments\Send;

use DateTime;
use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\ParametersInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\ValueObjects\Requests\Certificates\CertificateName;
use N1ebieski\KSEFClient\ValueObjects\Requests\Certificates\CertificateType;
use N1ebieski\KSEFClient\Support\Concerns\HasToBody;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Support\KeyType;

final readonly class SendRequest extends AbstractRequest implements BodyInterface
{
    use HasToBody;

    public function __construct(
        public CertificateName $certificateName,
        public CertificateType $certificateType,
        public string $csr,
        public Optional | DateTime | null $validFrom = new Optional()
    ) {
    }
}

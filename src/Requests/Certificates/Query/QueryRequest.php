<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Certificates\Query;

use DateTime;
use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Contracts\ParametersInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Certificates\ValueObjects\CertificateName;
use N1ebieski\KSEFClient\Requests\Certificates\ValueObjects\CertificateSerialNumber;
use N1ebieski\KSEFClient\Requests\Certificates\ValueObjects\CertificateStatus;
use N1ebieski\KSEFClient\Requests\Certificates\ValueObjects\CertificateType;
use N1ebieski\KSEFClient\Requests\ValueObjects\PageOffset;
use N1ebieski\KSEFClient\Requests\ValueObjects\PageSize;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class QueryRequest extends AbstractRequest implements BodyInterface, ParametersInterface
{
    public function __construct(
        public Optional | CertificateName | null $name = new Optional(),
        public Optional | CertificateType | null $type = new Optional(),
        public Optional | CertificateStatus | null $status = new Optional(),
        public Optional | CertificateSerialNumber | null $certificateSerialNumber = new Optional(),
        public Optional | DateTime | null $expiresAfter = new Optional(),
        public Optional | PageSize $pageSize = new Optional(),
        public Optional | PageOffset $pageOffset = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        return $this->toArray(only: ['name', 'type', 'status', 'certificateSerialNumber', 'expiresAfter']);
    }

    public function toParameters(): array
    {
        return $this->toArray(only: ['pageSize', 'pageOffset']);
    }
}

<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Certificates\Enrollments\Status;

use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\ValueObjects\Requests\ReferenceNumber;
use SensitiveParameter;

final readonly class StatusRequest extends AbstractRequest
{
    public function __construct(
        #[SensitiveParameter]
        public ReferenceNumber $referenceNumber,
    ) {
    }
}

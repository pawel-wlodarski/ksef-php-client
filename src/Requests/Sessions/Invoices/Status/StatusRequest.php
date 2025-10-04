<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Invoices\Status;

use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\ValueObjects\Requests\ReferenceNumber;

final readonly class StatusRequest extends AbstractRequest
{
    public function __construct(
        public ReferenceNumber $referenceNumber,
        public ReferenceNumber $invoiceReferenceNumber
    ) {
    }
}

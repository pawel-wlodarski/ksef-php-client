<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Testdata\Person\Remove;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Support\ValueObjects\KeyType;
use N1ebieski\KSEFClient\ValueObjects\Nip;

final readonly class RemoveRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public Nip $nip,
    ) {
    }

    public function toBody(): array
    {
        return $this->toArray();
    }
}

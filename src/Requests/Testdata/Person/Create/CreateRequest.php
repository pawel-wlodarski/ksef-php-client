<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Testdata\Person\Create;

use DateTime;
use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\ValueObjects\Requests\Testdata\Person\Create\Pesel;
use N1ebieski\KSEFClient\Support\Optional;
use N1ebieski\KSEFClient\ValueObjects\Support\KeyType;
use N1ebieski\KSEFClient\ValueObjects\NIP;

final readonly class CreateRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public NIP $nip,
        public Pesel $pesel,
        public string $description,
        public bool $isBailiff = false,
        public Optional | DateTime | null $createdDate = new Optional(),
    ) {
    }

    public function toBody(): array
    {
        return $this->toArray();
    }
}

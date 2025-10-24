<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\ValueObjects;

use N1ebieski\KSEFClient\Support\AbstractValueObject;
use Stringable;

final class QRCode extends AbstractValueObject implements Stringable
{
    public function __construct(
        public readonly string $raw,
        public readonly Url $url
    ) {
    }

    public function __toString(): string
    {
        return $this->raw;
    }

    public static function from(string $raw, Url | string $url): self
    {
        if ( ! $url instanceof Url) {
            $url = Url::from($url);
        }

        return new self($raw, $url);
    }
}

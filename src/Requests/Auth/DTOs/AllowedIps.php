<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Auth\DTOs;

use N1ebieski\KSEFClient\Support\AbstractDTO;
use N1ebieski\KSEFClient\Support\Optional;

final readonly class AllowedIps extends AbstractDTO
{
    /**
     * @param Optional|array<int, string> $ip4Addresses
     * @param Optional|array<int, string> $ip4Ranges
     * @param Optional|array<int, string> $ip4Masks
     * @return void
     */
    public function __construct(
        public Optional | array $ip4Addresses,
        public Optional | array $ip4Ranges,
        public Optional | array $ip4Masks
    ) {
    }
}

<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\DTOs;

use N1ebieski\KSEFClient\Support\AbstractDTO;

final class KsefPDFs extends AbstractDTO
{
    public function __construct(
        public readonly string $invoice,
        public readonly ?string $upo = null,
    ) {
    }
}

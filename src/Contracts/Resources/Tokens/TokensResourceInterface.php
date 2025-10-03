<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Tokens;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Requests\Tokens\Create\CreateRequest;

interface TokensResourceInterface
{
    /**
     * @param CreateRequest|array<string, mixed> $request
     */
    public function create(CreateRequest | array $request): ResponseInterface;
}

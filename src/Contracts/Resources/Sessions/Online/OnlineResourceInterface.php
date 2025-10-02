<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\Resources\Sessions\Online;

use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Requests\Sessions\Online\Invoices\InvoicesRequest;
use N1ebieski\KSEFClient\Requests\Sessions\Online\Open\OpenRequest;

interface OnlineResourceInterface
{
    /**
     * @param OpenRequest|array<string, mixed> $request
     */
    public function open(OpenRequest | array $request): ResponseInterface;

    /**
     * @param InvoicesRequest|array<string, mixed> $request
     */
    public function invoices(InvoicesRequest | array $request): ResponseInterface;
}

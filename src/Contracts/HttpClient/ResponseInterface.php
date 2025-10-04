<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Contracts\HttpClient;

use N1ebieski\KSEFClient\Contracts\ArrayableInterface;
use Psr\Http\Message\ResponseInterface as BaseResponseInterface;

interface ResponseInterface extends ArrayableInterface
{
    public BaseResponseInterface $baseResponse { get; }

    public function status(): int;

    /**
     * @return array<string, mixed>
     */
    public function json(): array;

    public function object(): object | array;

    public function body(): string;
}

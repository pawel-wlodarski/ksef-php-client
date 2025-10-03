<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Tokens;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Tokens\TokensResourceInterface;
use N1ebieski\KSEFClient\Requests\Tokens\Create\CreateHandler;
use N1ebieski\KSEFClient\Requests\Tokens\Create\CreateRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;

final class TokensResource extends AbstractResource implements TokensResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client
    ) {
    }

    public function create(CreateRequest | array $request): ResponseInterface
    {
        if ($request instanceof CreateRequest === false) {
            $request = CreateRequest::from($request);
        }

        return new CreateHandler($this->client)->handle($request);
    }
}

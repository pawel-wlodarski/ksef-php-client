<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Online\Query\Invoice\Sync;

use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\DTOs\HttpClient\Request;
use N1ebieski\KSEFClient\ValueObjects\HttpClient\Method;
use N1ebieski\KSEFClient\ValueObjects\HttpClient\Uri;
use N1ebieski\KSEFClient\Requests\AbstractHandler;

final readonly class SyncHandler extends AbstractHandler
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    public function handle(SyncRequest $request): ResponseInterface
    {
        return $this->client->sendRequest(new Request(
            method: Method::Post,
            uri: Uri::from('online/Query/Invoice/Sync'),
            parameters: $request->toParameters(),
            body: $request->toBody()
        ));
    }
}

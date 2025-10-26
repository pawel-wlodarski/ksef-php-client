<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Resources\Sessions;

use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\HttpClientInterface;
use N1ebieski\KSEFClient\Contracts\HttpClient\ResponseInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Sessions\Batch\BatchResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Sessions\Invoices\InvoicesResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Sessions\Online\OnlineResourceInterface;
use N1ebieski\KSEFClient\Contracts\Resources\Sessions\SessionsResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Requests\Sessions\Status\StatusHandler;
use N1ebieski\KSEFClient\Requests\Sessions\Status\StatusRequest;
use N1ebieski\KSEFClient\Resources\AbstractResource;
use N1ebieski\KSEFClient\Resources\Sessions\Batch\BatchResource;
use N1ebieski\KSEFClient\Resources\Sessions\Invoices\InvoicesResource;
use N1ebieski\KSEFClient\Resources\Sessions\Online\OnlineResource;
use Psr\Log\LoggerInterface;
use Throwable;

final class SessionsResource extends AbstractResource implements SessionsResourceInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly Config $config,
        private readonly ExceptionHandlerInterface $exceptionHandler,
        private readonly ?LoggerInterface $logger = null
    ) {
    }

    public function online(): OnlineResourceInterface
    {
        try {
            return new OnlineResource($this->client, $this->config, $this->exceptionHandler, $this->logger);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function batch(): BatchResourceInterface
    {
        try {
            return new BatchResource($this->client, $this->config, $this->exceptionHandler, $this->logger);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function status(StatusRequest | array $request): ResponseInterface
    {
        try {
            if ($request instanceof StatusRequest === false) {
                $request = StatusRequest::from($request);
            }

            return (new StatusHandler($this->client))->handle($request);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }

    public function invoices(): InvoicesResourceInterface
    {
        try {
            return new InvoicesResource($this->client, $this->exceptionHandler);
        } catch (Throwable $throwable) {
            throw $this->exceptionHandler->handle($throwable);
        }
    }
}

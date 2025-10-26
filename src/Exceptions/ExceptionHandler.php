<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Exceptions;

use N1ebieski\KSEFClient\Contracts\ContextInterface;
use N1ebieski\KSEFClient\Contracts\Exception\ExceptionHandlerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class ExceptionHandler implements ExceptionHandlerInterface
{
    public function __construct(
        private readonly ?LoggerInterface $logger = null
    ) {
    }

    public function handle(Throwable $exception): Throwable
    {
        if ($this->logger instanceof LoggerInterface) {
            $message = $exception->getCode() > 0
                ? "{$exception->getCode()} {$exception->getMessage()}"
                : $exception->getMessage();

            $context = $exception instanceof ContextInterface
                ? (array) $exception->context : [];

            $this->logger->error($message, $context);
        }

        return $exception;
    }
}

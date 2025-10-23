<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Limits\Context\ContextResponseFixture;

use function N1ebieski\KSEFClient\Tests\getClientStub;

/**
 * @return array<string, array{ContextResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $responses = [
        new ContextResponseFixture(),
    ];

    $combinations = [];

    foreach ($responses as $response) {
        $combinations[$response->name] = [$response];
    }

    /** @var array<string, array{ContextResponseFixture}> */
    return $combinations;
});

test('valid response', function (ContextResponseFixture $responseFixture): void {
    $clientStub = getClientStub($responseFixture);

    $response = $clientStub->limits()->context()->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        $clientStub = getClientStub($responseFixture);

        $clientStub->limits()->context();
    })->toBeExceptionFixture($responseFixture->data);
});

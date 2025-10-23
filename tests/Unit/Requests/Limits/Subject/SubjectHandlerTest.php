<?php

declare(strict_types=1);

use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Limits\Subject\SubjectResponseFixture;

use function N1ebieski\KSEFClient\Tests\getClientStub;

/**
 * @return array<string, array{SubjectResponseFixture}>
 */
dataset('validResponseProvider', function (): array {
    $responses = [
        new SubjectResponseFixture(),
    ];

    $combinations = [];

    foreach ($responses as $response) {
        $combinations[$response->name] = [$response];
    }

    /** @var array<string, array{SubjectResponseFixture}> */
    return $combinations;
});

test('valid response', function (SubjectResponseFixture $responseFixture): void {
    $clientStub = getClientStub($responseFixture);

    $response = $clientStub->limits()->subject()->object();

    expect($response)->toBeFixture($responseFixture->data);
})->with('validResponseProvider');

test('invalid response', function (): void {
    $responseFixture = new ErrorResponseFixture();

    expect(function () use ($responseFixture): void {
        $clientStub = getClientStub($responseFixture);

        $clientStub->limits()->subject();
    })->toBeExceptionFixture($responseFixture->data);
});

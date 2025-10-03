<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Tokens\Create;

use N1ebieski\KSEFClient\Requests\Tokens\Create\CreateRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Tokens\Create\CreateRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Tokens\Create\CreateResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class CreateHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{CreateRequestFixture, CreateResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new CreateRequestFixture(),
        ];

        $responses = [
            new CreateResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{CreateRequestFixture, CreateResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(CreateRequestFixture $requestFixture, CreateResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $request = CreateRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->tokens()->create($requestFixture->data)->object();

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new CreateRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->tokens()->create($requestFixture->data);
    }
}

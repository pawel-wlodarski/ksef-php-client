<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Sessions\Online\Open;

use N1ebieski\KSEFClient\Requests\Sessions\Online\Open\OpenRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Open\OpenRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Open\OpenResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class OpenHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{OpenRequestFixture, OpenResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new OpenRequestFixture(),
        ];

        $responses = [
            new OpenResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{OpenRequestFixture, OpenResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(OpenRequestFixture $requestFixture, OpenResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $request = OpenRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->sessions()->online()->open($requestFixture->data)->object();

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new OpenRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->sessions()->online()->open($requestFixture->data);
    }
}

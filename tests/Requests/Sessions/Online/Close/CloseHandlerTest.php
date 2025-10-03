<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Sessions\Online\Close;

use N1ebieski\KSEFClient\Requests\Sessions\Online\Close\CloseRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Close\CloseRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Close\CloseResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class CloseHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{CloseRequestFixture, CloseResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new CloseRequestFixture(),
        ];

        $responses = [
            new CloseResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{CloseRequestFixture, CloseResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(CloseRequestFixture $requestFixture, CloseResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $request = CloseRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->sessions()->online()->close($requestFixture->data)->status();

        $this->assertEquals($responseFixture->statusCode, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new CloseRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->sessions()->online()->close($requestFixture->data);
    }
}

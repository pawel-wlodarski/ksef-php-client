<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Tests\Requests\Auth\KsefToken;

use N1ebieski\KSEFClient\Requests\Auth\KsefToken\KsefTokenRequest;
use N1ebieski\KSEFClient\Testing\AbstractTestCase;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Auth\KsefToken\KsefTokenRequestFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Auth\KsefToken\KsefTokenResponseFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Error\ErrorResponseFixture;
use PHPUnit\Framework\Attributes\DataProvider;

final class KsefTokenHandlerTest extends AbstractTestCase
{
    /**
     * @return array<string, array{KsefTokenRequestFixture, KsefTokenResponseFixture}>
     */
    public static function validResponseProvider(): array
    {
        $requests = [
            new KsefTokenRequestFixture(),
        ];

        $responses = [
            new KsefTokenResponseFixture(),
        ];

        $combinations = [];

        foreach ($requests as $request) {
            foreach ($responses as $response) {
                $combinations["{$request->name}, {$response->name}"] = [$request, $response];
            }
        }

        /** @var array<string, array{KsefTokenRequestFixture, KsefTokenResponseFixture}> */
        return $combinations;
    }

    #[DataProvider('validResponseProvider')]
    public function testValidResponse(KsefTokenRequestFixture $requestFixture, KsefTokenResponseFixture $responseFixture): void
    {
        $clientStub = $this->getClientStub($responseFixture);

        $request = KsefTokenRequest::from($requestFixture->data);

        $this->assertFixture($requestFixture->data, $request);

        $response = $clientStub->auth()->ksefToken($requestFixture->data)->object();

        $this->assertFixture($responseFixture->data, $response);
    }

    public function testInvalidResponse(): void
    {
        $requestFixture = new KsefTokenRequestFixture();
        $responseFixture = new ErrorResponseFixture();

        $this->assertExceptionFixture($responseFixture->data);

        $clientStub = $this->getClientStub($responseFixture);

        $clientStub->auth()->ksefToken($requestFixture->data);
    }
}

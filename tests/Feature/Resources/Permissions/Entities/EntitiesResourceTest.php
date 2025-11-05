<?php

use N1ebieski\KSEFClient\ClientBuilder;
use N1ebieski\KSEFClient\Exceptions\HttpClient\BadRequestException;
use N1ebieski\KSEFClient\Factories\EncryptionKeyFactory;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\Testing\Fixtures\DTOs\Requests\Sessions\FakturaSprzedazyTowaruFixture;
use N1ebieski\KSEFClient\Testing\Fixtures\Requests\Sessions\Online\Send\SendRequestFixture;
use N1ebieski\KSEFClient\Tests\Feature\AbstractTestCase;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\Requests\Testdata\Subject\SubjectType;

/** @var AbstractTestCase $this */

beforeEach(function (): void {
    $client = (new ClientBuilder())
        ->withMode(Mode::Test)
        ->build();

    try {
        $client->testdata()->subject()->create([
            'subjectNip' => $_ENV['NIP_2'],
            'subjectType' => SubjectType::EnforcementAuthority,
            'description' => 'Subject who gives InvoiceWrite permission',
        ])->status();
    } catch (BadRequestException $exception) {
        if (str_starts_with($exception->getMessage(), '30001')) {
            // ignore
        }
    }
});

afterAll(function (): void {
    $client = (new ClientBuilder())
        ->withMode(Mode::Test)
        ->build();

    $client->testdata()->subject()->remove([
        'nip' => $_ENV['NIP_2'],
    ]);
});

test('give InvoiceWrite permission and send invoice', function (): void {
    /** @var AbstractTestCase $this */
    /** @var array<string, string> $_ENV */

    $client = $this->createClient(
        identifier: $_ENV['NIP_2'],
        certificatePath: $_ENV['CERTIFICATE_PATH_2'],
        certificatePassphrase: $_ENV['CERTIFICATE_PASSPHRASE_2']
    );

    $grantsResponse = $client->permissions()->entities()->grants([
        'subjectIdentifierGroup' => [
            'nip' => $_ENV['NIP_1']
        ],
        'permissions' => [
            [
                'type' => 'InvoiceWrite'
            ]
        ],
        'description' => 'Give InvoiceWrite permission TO NIP_1'
    ])->object();

    $statusResponse = Utility::retry(function (int $attempts) use ($client, $grantsResponse) {
        $statusResponse = $client->permissions()->operations()->status([
            'referenceNumber' => $grantsResponse->referenceNumber,
        ])->object();

        try {
            expect($statusResponse->status->code)->toBe(200);

            return $statusResponse;
        } catch (Throwable $exception) {
            if ($attempts > 2) {
                throw $exception;
            }
        }
    });

    expect($statusResponse->status->code)->toBe(200);

    $this->revokeCurrentSession($client);

    $encryptionKey = EncryptionKeyFactory::makeRandom();

    $client = $this->createClient(
        identifier: $_ENV['NIP_2'],
        encryptionKey: $encryptionKey
    );

    $openResponse = $client->sessions()->online()->open([
        'formCode' => 'FA (3)',
    ])->object();

    $fakturaFixture = (new FakturaSprzedazyTowaruFixture())
        ->withNip($_ENV['NIP_2'])
        ->withTodayDate()
        ->withRandomInvoiceNumber();

    $fixture = (new SendRequestFixture())->withFakturaFixture($fakturaFixture);

    $sendResponse = $client->sessions()->online()->send([
        ...$fixture->data,
        'referenceNumber' => $openResponse->referenceNumber,
    ])->object();

    $client->sessions()->online()->close([
        'referenceNumber' => $openResponse->referenceNumber
    ]);

    $statusResponse = Utility::retry(function (int $attempts) use ($client, $openResponse, $sendResponse) {
        $statusResponse = $client->sessions()->invoices()->status([
            'referenceNumber' => $openResponse->referenceNumber,
            'invoiceReferenceNumber' => $sendResponse->referenceNumber
        ])->object();

        try {
            expect($statusResponse->status->code)->toBe(200);

            return $statusResponse;
        } catch (Throwable $exception) {
            if ($attempts > 2) {
                throw $exception;
            }
        }
    });

    // $revokeSession = $client->permissions()->common()->revoke([

    // ])->object();

    $this->revokeCurrentSession($client);
});

<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient;

use DateTimeImmutable;
use DateTimeInterface;
use Http\Discovery\Psr18ClientDiscovery;
use InvalidArgumentException;
use N1ebieski\KSEFClient\Contracts\Resources\ClientResourceInterface;
use N1ebieski\KSEFClient\DTOs\Config;
use N1ebieski\KSEFClient\Factories\LoggerFactory;
use N1ebieski\KSEFClient\HttpClient\HttpClient;
use N1ebieski\KSEFClient\HttpClient\ValueObjects\BaseUri;
use N1ebieski\KSEFClient\Requests\Auth\DTOs\ContextIdentifierGroup;
use N1ebieski\KSEFClient\Requests\Auth\DTOs\XadesSignature;
use N1ebieski\KSEFClient\Requests\Auth\Status\StatusRequest;
use N1ebieski\KSEFClient\Requests\Auth\ValueObjects\Challenge;
use N1ebieski\KSEFClient\Requests\Auth\ValueObjects\SubjectIdentifierType;
use N1ebieski\KSEFClient\Requests\Auth\XadesSignature\XadesSignatureRequest;
use N1ebieski\KSEFClient\Requests\ValueObjects\ReferenceNumber;
use N1ebieski\KSEFClient\Resources\ClientResource;
use N1ebieski\KSEFClient\Support\Utility;
use N1ebieski\KSEFClient\ValueObjects\AccessToken;
use N1ebieski\KSEFClient\ValueObjects\ApiUrl;
use N1ebieski\KSEFClient\ValueObjects\CertificatePath;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\InternalId;
use N1ebieski\KSEFClient\ValueObjects\KsefToken;
use N1ebieski\KSEFClient\ValueObjects\LogPath;
use N1ebieski\KSEFClient\ValueObjects\Mode;
use N1ebieski\KSEFClient\ValueObjects\Nip;
use N1ebieski\KSEFClient\ValueObjects\NipVatUe;
use N1ebieski\KSEFClient\ValueObjects\RefreshToken;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use RuntimeException;

final class ClientBuilder
{
    private ClientInterface $httpClient;

    private ?LoggerInterface $logger = null;

    private Mode $mode = Mode::Production;

    private ApiUrl $apiUrl;

    private ?KsefToken $ksefToken = null;

    private ?AccessToken $accessToken = null;

    private ?RefreshToken $refreshToken = null;

    private ?CertificatePath $certificatePath = null;

    private NIP $identifier;

    private ?EncryptionKey $encryptionKey = null;

    public function __construct()
    {
        $this->httpClient = Psr18ClientDiscovery::find();
        $this->logger = LoggerFactory::make();
        $this->apiUrl = $this->mode->getApiUrl();
    }

    public function withMode(Mode | string $mode): self
    {
        if ($mode instanceof Mode === false) {
            $mode = Mode::from($mode);
        }

        $this->mode = $mode;

        $this->apiUrl = $this->mode->getApiUrl();

        if ($this->mode->isEquals(Mode::Test)) {
            $this->identifier = new NIP('1111111111');
        }

        return $this;
    }


    public function withEncryptionKey(EncryptionKey | string $encryptionKey, ?string $iv = null): self
    {
        if (is_string($encryptionKey)) {
            if ($iv === null) {
                throw new InvalidArgumentException('IV is required when key is string.');
            }

            $encryptionKey = new EncryptionKey($encryptionKey, $iv);
        }

        $this->encryptionKey = $encryptionKey;

        return $this;
    }

    public function withApiUrl(ApiUrl | string $apiUrl): self
    {
        if ($apiUrl instanceof ApiUrl === false) {
            $apiUrl = ApiUrl::from($apiUrl);
        }

        $this->apiUrl = $apiUrl;

        return $this;
    }

    public function withKsefToken(KsefToken | string $ksefToken): self
    {
        if ($ksefToken instanceof KsefToken === false) {
            $ksefToken = KsefToken::from($ksefToken);
        }

        $this->certificatePath = null;

        $this->ksefToken = $ksefToken;

        return $this;
    }

    public function withAccessToken(AccessToken | string $accessToken, DateTimeInterface | string | null $validUntil = null): self
    {
        if ($accessToken instanceof AccessToken === false) {
            if (is_string($validUntil)) {
                $validUntil = new DateTimeImmutable($validUntil);
            }

            $accessToken = AccessToken::from($accessToken, $validUntil);
        }

        $this->accessToken = $accessToken;

        return $this;
    }

    public function withRefreshToken(RefreshToken | string $refreshToken, DateTimeInterface | string | null $validUntil = null): self
    {
        if ($refreshToken instanceof RefreshToken === false) {
            if (is_string($validUntil)) {
                $validUntil = new DateTimeImmutable($validUntil);
            }

            $refreshToken = RefreshToken::from($refreshToken, $validUntil);
        }

        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function withCertificatePath(CertificatePath | string $certificatePath, ?string $passphrase = null): self
    {
        if ($certificatePath instanceof CertificatePath === false) {
            $certificatePath = CertificatePath::from($certificatePath, $passphrase);
        }

        $this->ksefToken = null;

        $this->certificatePath = $certificatePath;

        return $this;
    }

    public function withHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;

        return $this;
    }

    public function withLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function withIdentifier(Nip | NipVatUe | InternalId | string $identifier): self
    {
        if (is_string($identifier)) {
            $identifier = Nip::from($identifier);
        }

        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @param null|LogLevel::* $level
     */
    public function withLogPath(LogPath | string | null $logPath, ?string $level = LogLevel::DEBUG): self
    {
        if (is_string($logPath)) {
            $logPath = LogPath::from($logPath);
        }

        $this->logger = null;

        if ($level !== null) {
            $this->logger = LoggerFactory::make($logPath, $level);
        }

        return $this;
    }

    public function build(): ClientResourceInterface
    {
        $config = new Config(
            baseUri: new BaseUri($this->apiUrl->value),
            accessToken: $this->accessToken,
            refreshToken: $this->refreshToken,
        );

        $httpClient = new HttpClient(
            client: $this->httpClient,
            config: $config,
            logger: $this->logger
        );

        $client = new ClientResource($httpClient, $config, $this->logger);

        if ($this->isAuthorisation()) {
            /** @var object{challenge: string, timestamp: string} $authorisationChallengeResponse */
            $authorisationChallengeResponse = $client->auth()->challenge()->object();

            $authorisationAccessResponse = match (true) { //@phpstan-ignore-line
                $this->certificatePath instanceof CertificatePath => $client->auth()->xadesSignature(
                    new XadesSignatureRequest(
                        certificatePath: $this->certificatePath,
                        xadesSignature: new XadesSignature(
                            challenge: Challenge::from($authorisationChallengeResponse->challenge),
                            contextIdentifierGroup: ContextIdentifierGroup::fromIdentifier($this->identifier),
                            subjectIdentifierType: SubjectIdentifierType::CertificateSubject
                        )
                    )
                ),
                // TODO: add KSEF tokens support
            };

            /** @var object{referenceNumber: string, authenticationToken: object{token: string}} $authorisationAccessResponse */
            $authorisationAccessResponse = $authorisationAccessResponse->object();

            $client = $client->withAccessToken(AccessToken::from($authorisationAccessResponse->authenticationToken->token));

            Utility::retry(function () use ($client, $authorisationAccessResponse) {
                $authorisationStatusResponse = $client->auth()->status(
                    new StatusRequest(ReferenceNumber::from($authorisationAccessResponse->referenceNumber))
                )->object();

                if ($authorisationStatusResponse->status->code === 200) {
                    return $authorisationStatusResponse;
                }

                if ($authorisationStatusResponse->status->code >= 400) {
                    throw new RuntimeException(
                        $authorisationStatusResponse->status->description,
                        $authorisationStatusResponse->status->code
                    );
                }
            });

            /** @var object{refreshToken: object{token: string, validUntil: string<date-time>}, accessToken: object{token: string, validUntil: string<date-time>}} $authorisationTokenResponse */
            $authorisationTokenResponse = $client->auth()->token()->redeem()->object();

            $client = $client
                ->withAccessToken(AccessToken::from(
                    token: $authorisationTokenResponse->accessToken->token,
                    validUntil: new DateTimeImmutable($authorisationTokenResponse->accessToken->validUntil)
                ))
                ->withRefreshToken(RefreshToken::from(
                    token: $authorisationTokenResponse->refreshToken->token,
                    validUntil: new DateTimeImmutable($authorisationTokenResponse->refreshToken->validUntil)
                ));
        }

        return $client;
    }

    private function isAuthorisation(): bool
    {
        return ! $this->accessToken instanceof AccessToken && (
            $this->ksefToken instanceof KsefToken || $this->certificatePath instanceof CertificatePath
        );
    }
}

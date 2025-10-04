<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use DateTime;
use DateTimeInterface;
use N1ebieski\KSEFClient\ValueObjects\Requests\Auth\EncryptedToken;
use N1ebieski\KSEFClient\ValueObjects\KsefPublicKey;
use N1ebieski\KSEFClient\ValueObjects\KsefToken;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\RSA\PublicKey as RSAPublicKey;
use RuntimeException;
use SensitiveParameter;

final readonly class EncryptedTokenFactory extends AbstractFactory
{
    public static function make(
        #[SensitiveParameter]
        KsefToken $ksefToken,
        #[SensitiveParameter]
        DateTimeInterface $timestamp,
        KsefPublicKey $ksefPublicKey,
    ): EncryptedToken {
        $secondsWithMicro = (float) $timestamp->format('U.u');
        $timestampAsMiliseconds = (int) floor($secondsWithMicro * 1000);

        $data = "{$ksefToken->value}|{$timestampAsMiliseconds}";

        /** @var RSAPublicKey $pub */
        $pub = PublicKeyLoader::load($ksefPublicKey->value);

        $pub = $pub
            ->withPadding(RSA::ENCRYPTION_OAEP)
            ->withHash('sha256')
            ->withMGFHash('sha256');

        $encryptedToken = $pub->encrypt($data);

        if ($encryptedToken === false) {
            throw new RuntimeException('Unable to encrypt token');
        }

        /** @var string $encryptedToken */
        $encryptedToken = base64_encode($encryptedToken);

        return new EncryptedToken($encryptedToken);
    }
}

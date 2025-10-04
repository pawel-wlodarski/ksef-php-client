<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use N1ebieski\KSEFClient\ValueObjects\Requests\Sessions\EncryptedKey;
use N1ebieski\KSEFClient\ValueObjects\EncryptionKey;
use N1ebieski\KSEFClient\ValueObjects\KsefPublicKey;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\RSA\PublicKey as RSAPublicKey;
use RuntimeException;

final readonly class EncryptedKeyFactory extends AbstractFactory
{
    public static function make(EncryptionKey $encryptionKey, KsefPublicKey $ksefPublicKey): EncryptedKey
    {
        /** @var RSAPublicKey $pub */
        $pub = PublicKeyLoader::load($ksefPublicKey->value);

        $pub = $pub
            ->withPadding(RSA::ENCRYPTION_OAEP)
            ->withHash('sha256')
            ->withMGFHash('sha256');

        $encryptedKey = $pub->encrypt($encryptionKey->key);

        if ($encryptedKey === false) {
            throw new RuntimeException('Unable to encrypt key');
        }

        /** @var string $encryptedKey */
        $encryptedKey = base64_encode($encryptedKey);

        /** @var string $encryptedIv */
        $encryptedIv = base64_encode($encryptionKey->iv);

        return new EncryptedKey($encryptedKey, $encryptedIv);
    }
}

<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Online\Open;

use N1ebieski\KSEFClient\Contracts\BodyInterface;
use N1ebieski\KSEFClient\Requests\AbstractRequest;
use N1ebieski\KSEFClient\Requests\Sessions\ValueObjects\EncryptedKey;
use N1ebieski\KSEFClient\Requests\Sessions\ValueObjects\FormCode;

final readonly class OpenRequest extends AbstractRequest implements BodyInterface
{
    public function __construct(
        public FormCode $formCode,
        public EncryptedKey $encryptedKey
    ) {
    }

    public function toBody(): array
    {
        return [
            'formCode' => [
                'systemCode' => $this->formCode->value,
                'schemaVersion' => $this->formCode->getSchemaVersion(),
                'value' => $this->formCode->getValue(),
            ],
            'encryption' => [
                'encryptedSymmetricKey' => $this->encryptedKey->key,
                'initializationVector' => $this->encryptedKey->iv
            ]
        ];
    }
}

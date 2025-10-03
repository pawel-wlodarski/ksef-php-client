<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Tokens\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;
use N1ebieski\KSEFClient\Support\Concerns\HasEquals;

enum TokenPermissionType: string implements EnumInterface
{
    use HasEquals;

    case InvoiceRead = 'InvoiceRead';

    case InvoiceWrite = 'InvoiceWrite';

    case CredentialsRead = 'CredentialsRead';

    case CredentialsManage = 'CredentialsManage';

    case SubunitManage = 'SubunitManage';

    case EnforcementOperations = 'EnforcementOperations';
}

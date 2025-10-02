<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Requests\Sessions\Online\ValueObjects;

use N1ebieski\KSEFClient\Contracts\EnumInterface;

enum RolaPU: string implements EnumInterface
{
    case OrganEgzekucyjny = '1';

    case KomornikSadowy = '2';

    case PrzedstawicielPodatkowy = '3';
}

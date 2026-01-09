<?php

declare(strict_types=1);

if (PHP_VERSION_ID < 80200 && ! class_exists('SensitiveParameter')) {
    #[\Attribute(\Attribute::TARGET_PARAMETER)]
    class SensitiveParameter
    {
    }
}

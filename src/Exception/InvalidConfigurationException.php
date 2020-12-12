<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Exception;

use InvalidArgumentException;

class InvalidConfigurationException extends InvalidArgumentException implements ExceptionInterface
{
    // ...
}

<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Partitioning;

class Context
{
    /**
     * @var string|float|int|bool|null
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
    }

    /**
     * @return string|float|int|bool|null
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @param string|float|int|bool|null $value
     */
    private function setValue($value): void
    {
        if (is_scalar($value)) {
            $this->value = $value;
        }
    }
}

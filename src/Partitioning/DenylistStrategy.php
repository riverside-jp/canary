<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Partitioning;

class DenylistStrategy implements Strategy
{
    use ListStrategyTrait {
        ListStrategyTrait::__construct as listStrategyConstructor;
    }

    /**
     * @param array<mixed> $list
     */
    public function __construct(array $list = [])
    {
        $this->listStrategyConstructor($list);
    }

    /**
     * @param Context $context
     *
     * @return bool
     */
    public function isExecutable(Context $context): bool
    {
        $value = $context->value();

        if ($value === null) {
            return true; // fail-safe
        }

        return !in_array($value, $this->list);
    }
}

<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Partitioning;

class NullStrategy implements Strategy
{
    /**
     * @param Context $context
     *
     * @return bool
     */
    public function isExecutable(Context $context): bool
    {
        return true;
    }
}

<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Partitioning;

interface Strategy
{
    /**
     * @var string
     */
    public const TYPE_ALLOWLIST  = 'allowlist';

    /**
     * @var string
     */
    public const TYPE_DATE_RANGE = 'date_range';

    /**
     * @var string
     */
    public const TYPE_DENYLIST   = 'denylist';

    /**
     * @var string
     */
    public const TYPE_PERCENTAGE = 'percentage';

    /**
     * @var string
     */
    public const TYPE_NULL = 'null';

    /**
     * @param Context $context
     *
     * @return bool
     */
    public function isExecutable(Context $context): bool;
}

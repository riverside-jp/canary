<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Partitioning;

trait ListStrategyTrait
{
    /**
     * @var array<string|float|int>
     */
    protected $list;

    /**
     * @param array<string|float|int> $list
     */
    public function __construct(array $list = [])
    {
        $this->setList($list);
    }

    /**
     * @param array<string|float|int> $list
     *
     * @return void
     */
    protected function setList(array $list): void
    {
        $this->list = $list;
    }
}

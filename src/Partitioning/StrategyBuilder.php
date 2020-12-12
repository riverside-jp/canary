<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Partitioning;

use RiversideHotel\Canary\Exception\InvalidStrategyArgumentException;
use RiversideHotel\Canary\Exception\UndefinedStrategyArgumentException;

class StrategyBuilder
{
    /**
     * @var string
     */
    private $strategyType;

    /**
     * @var array<mixed>
     */
    private $strategyArgs = [];

    /**
     * @param string $strategyType
     * @param array<mixed> $strategyArgs
     */
    private function __construct(string $strategyType, array $strategyArgs = [])
    {
        $this->strategyType = $strategyType;
        $this->strategyArgs = $strategyArgs;
    }

    /**
     * @return self
     */
    public static function createWithDefaultStrategy(): self
    {
        return new self(Strategy::TYPE_NULL, []);
    }

    /**
     * @throws InvalidStrategyArgumentException
     *
     * @return Strategy
     */
    public function build(): Strategy
    {
        switch ($this->strategyType) {
            case Strategy::TYPE_ALLOWLIST:
                return new AllowlistStrategy(...$this->strategyArgs);

            case Strategy::TYPE_DATE_RANGE:
                return new DateRangeStrategy(...$this->strategyArgs);

            case Strategy::TYPE_DENYLIST:
                return new DenylistStrategy(...$this->strategyArgs);

            case Strategy::TYPE_PERCENTAGE:
                return new PercentageStrategy(...$this->strategyArgs);

            case Strategy::TYPE_NULL:
                return new NullStrategy();

            default:
                throw new UndefinedStrategyArgumentException('undefined strategy: ' . $this->strategyType);
        }
    }

    /**
     * @param string $strategyType
     *
     * @return self
     */
    public function setStrategyType(string $strategyType): self
    {
        return new self($strategyType, $this->strategyArgs);
    }

    /**
     * @param array<mixed> $strategyArgs
     *
     * @return self
     */
    public function setStrategyArgs(array $strategyArgs): self
    {
        return new self($this->strategyType, $strategyArgs);
    }
}

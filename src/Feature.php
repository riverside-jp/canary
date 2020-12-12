<?php

declare(strict_types=1);

namespace RiversideHotel\Canary;

use RiversideHotel\Canary\Exception\InvalidFeatureArgumentException;
use RiversideHotel\Canary\Exception\InvalidStrategyArgumentException;
use RiversideHotel\Canary\Partitioning\Context;
use RiversideHotel\Canary\Partitioning\NullStrategy;
use RiversideHotel\Canary\Partitioning\Strategy;
use RiversideHotel\Canary\Partitioning\StrategyBuilder;

class Feature
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var Strategy
     */
    private $strategy;

    /**
     * @param string $name
     * @param bool $enabled
     * @param Strategy|null $strategy
     *
     * @throws InvalidFeatureArgumentException
     */
    public function __construct(string $name, bool $enabled, Strategy $strategy = null)
    {
        $this->setName($name);
        $this->setEnabled($enabled);
        $this->setStrategy($strategy);
    }

    /**
     * @param string $name
     * @param bool $enabled
     * @param string|null $strategyType
     * @param array<mixed>|null $strategyArgs
     *
     * @throws InvalidFeatureArgumentException
     * @throws InvalidStrategyArgumentException
     *
     * @return self
     */
    public static function create(string $name, bool $enabled, ?string $strategyType, ?array $strategyArgs = []): self
    {
        $builder = StrategyBuilder::createWithDefaultStrategy();

        if ($strategyType !== null && $strategyArgs !== null) {
            $builder = $builder->setStrategyType($strategyType)->setStrategyArgs($strategyArgs);
        }

        return new self($name, $enabled, $builder->build());
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return self
     */
    public function enable(): self
    {
        if ($this->isEnabled()) {
            return $this;
        }

        return $this->isEnabled() ? $this : new self($this->name, true, $this->strategy);
    }

    /**
     * @return self
     */
    public function disable(): self
    {
        return $this->isDisabled() ? $this : new self($this->name, false, $this->strategy);
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return !$this->enabled;
    }

    /**
     * @param Context $context
     *
     * @return bool
     */
    public function isAvailable(Context $context): bool
    {
        return $this->isEnabled() && $this->strategy->isExecutable($context);
    }

    /**
     * @param string $name
     *
     * @return void
     */
    private function setName(string $name): void
    {
        if (empty(trim($name))) {
            throw new InvalidFeatureArgumentException('empty feature name');
        }

        $this->name = $name;
    }

    /**
     * @param bool $enabled
     */
    private function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @param Strategy|null $strategy
     *
     * @return void
     */
    private function setStrategy(?Strategy $strategy): void
    {
        if ($strategy !== null) {
            $this->strategy = $strategy;
        } else {
            $this->strategy = new NullStrategy();
        }
    }
}

<?php

declare(strict_types=1);

namespace RiversideHotel\Canary\Partitioning;

use RiversideHotel\Canary\Exception\InvalidStrategyArgumentException;
use DateTime;

class DateRangeStrategy implements Strategy
{
    /**
     * @var DateTime
     */
    private $since;

    /**
     * @var DateTime
     */
    private $until;

    /**
     * @param DateTime|null $since
     * @param DateTime|null $until
     *
     * @throws InvalidStrategyArgumentException
     */
    public function __construct(?DateTime $since = null, ?DateTime $until = null)
    {
        $this->setSince($since);
        $this->setUntil($until, $since);
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
            return false; // fail-safe
        }

        return $this->since->getTimestamp() <= $value && $value < $this->until->getTimestamp();
    }

    /**
     * @param DateTime|null $since
     *
     * @return void
     */
    private function setSince(?DateTime $since): void
    {
        if ($since !== null) {
            $this->since = $since;
        } else {
            $this->since = (new DateTime())->setTimestamp(0);
        }
    }

    /**
     * @param DateTime|null $until
     * @param DateTime|null $since
     *
     * @return void
     */
    private function setUntil(?DateTime $until, ?DateTime $since): void
    {
        if ($until !== null) {
            if ($until <= $since) {
                throw new InvalidStrategyArgumentException('invalid until date: ' . $until->format(DateTime::ATOM));
            }

            $this->until = $until;
        } else {
            $this->until = (new DateTime())->setTimestamp(PHP_INT_MAX);
        }
    }
}

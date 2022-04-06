<?php

namespace App\Components\Traits;

trait ConvertPriceTrait
{
    protected function fromDbValueToMoney(int $db_value): float
    {
        return $db_value / $this->getMultiplier();
    }

    protected function fromMoneyToDbValue(float $money): int
    {
        return (int) ($money * $this->getMultiplier());
    }

    protected function getMultiplier(): int
    {
        return 100;
    }
}

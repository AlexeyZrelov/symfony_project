<?php 

namespace App\Services;

class PromotionCalculator
{

    public function calculatePriceAfterPromotion(...$prices): int
    {
        $start = 0;

        foreach ($prices as $price) {
            $start += $price;
        }

        return $start - ($start * $this->getPromotionPercentage() / 100);

    }

    public function getPromotionPercentage(): int
    {
        return (int) \file_get_contents('file.txt');
    }

}
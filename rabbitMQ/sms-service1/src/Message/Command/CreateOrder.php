<?php

namespace App\Message\Command;

class CreateOrder
{

    private int $productId;
    private int $productAmount;

    public function __construct(int $productId, int $productAmount)
    {
        $this->productId = $productId;
        $this->productAmount = $productAmount;
    }

    public function getProductAmount(): int
    {
        return $this->productAmount;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }
}
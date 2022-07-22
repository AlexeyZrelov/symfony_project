<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class GiftsService
{
    public array $gifts = ['flowers', 'car', 'piano', 'money'];

    public function __construct(LoggerInterface $logger)
    {
        $logger->info('Gifts were randomized!');
        shuffle($this->gifts);
    }
}
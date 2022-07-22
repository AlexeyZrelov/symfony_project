<?php

namespace App\MessageHandler\Command;

use App\Message\Command\CreateOrder;

class CreateOrderHandler
{

    public function __invoke(CreateOrder $createsOrder)
    {
        // send an email to client confirming the order (product name, amount, price, etc.)
        // update warehouse database to keep stock up to date in physical stores
        sleep(4);
        var_dump($createsOrder);
    }

}
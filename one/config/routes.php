<?php

use App\Controller\LuckyController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('lucky_char', '/char')
        ->controller([LuckyController::class, 'char']);
};
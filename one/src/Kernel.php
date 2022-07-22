<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * added 18.06.2022
     */
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $extensions = '{php,yaml}';

        $routes->import('../config/{routes}/' . $this->environment . "/*.$extensions");
        $routes->import("../config/{routes}/*.$extensions");
        $routes->import("../config/{routes}.$extensions");
    }

}

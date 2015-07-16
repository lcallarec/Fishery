<?php

namespace Lc\Fishery\Provider;

use Lc\Fishery\FisheryContainerInterface;

interface ServiceProviderInterface
{

    /**
     * Registers services on the given Container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(FisheryContainerInterface $container);

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(FisheryContainerInterface $container);

}
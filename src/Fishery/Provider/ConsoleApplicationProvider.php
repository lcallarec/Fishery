<?php

namespace Lc\Fishery\Provider;

use Lc\Fishery\Command\Console;
use Lc\Fishery\Command\LoadCommand;
use Lc\Fishery\FisheryContainerInterface;
use Silex\Application;

class ConsoleApplicationProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(FisheryContainerInterface $container)
    {
        $container['console.application'] = $container->share(function ($c) {
            $console = new Console($c);
            $console->add(new LoadCommand());

            return $console;
        });
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(FisheryContainerInterface $container)
    {

    }

}
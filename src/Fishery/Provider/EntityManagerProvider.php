<?php

namespace Lc\Fishery\Provider;

use Lc\Fishery\FisheryContainerInterface;
use Lc\Fishery\Schema\EntityManager;
use Lc\Fishery\Storage\Persistent\PersistentStorageInterface;
use Silex\Application;

class EntityManagerProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(FisheryContainerInterface $container)
    {
        $container['entity.manager'] = $container->share(function ($c) {
            return $this->create($c['data.provider.dbal']);
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

    protected function create(PersistentStorageInterface $storage)
    {
        return new EntityManager($storage);
    }

}
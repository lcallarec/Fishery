<?php

namespace Lc\Fishery\Provider;

use Doctrine\DBAL\DriverManager;
use Lc\Fishery\FisheryContainerInterface;
use Lc\Fishery\Storage\Persistent\SqlStorage;
use Silex\Application;

class StorageProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(FisheryContainerInterface $container)
    {
        $container['data.provider.dbal'] = $container->share(function ($c) {
            return $this->create($c['dbal']['dsn']);
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

    /**
     * @param string $dsn
     * @return SqlStorage
     */
    protected function create($dsn)
    {
        $connection = DriverManager::getConnection(['url' => $dsn]);

        return new SqlStorage($connection);
    }
}
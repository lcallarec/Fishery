<?php

namespace Lc\Fishery\Provider;

use Lc\Fishery\FisheryContainerInterface;
use Lc\Fishery\Schema\EntityManager;
use Lc\Fishery\Schema\Schema;
use Silex\Application;
use Symfony\Component\Finder\Finder;

class SchemaManagerProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     */
    public function register(FisheryContainerInterface $container)
    {
        $container['schema.manager'] = $container->share(function ($c) {
            return $this->create(
                array_merge_recursive(
                    $c['schemas'],
                    $c['schema.definition']
                ),
                $c['parameters']['schemas.dir'],
                $c['entity.manager']

            );
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
     * @param array $schemas
     * @return Schema
     */
    protected function create(array $schemas, $schemaDir, EntityManager $entityManager)
    {
        foreach ($schemas as $alias => &$schemaConfigs) {
            if (null === $schemaConfigs['realname']) {
                $schemaConfigs['realname'] = $alias;
            }
        }

        return new Schema($entityManager, $schemas, new Finder(), $schemaDir);
    }

}
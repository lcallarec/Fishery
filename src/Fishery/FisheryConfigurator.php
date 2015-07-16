<?php

namespace Lc\Fishery;

use Lc\Fishery\Config\Loader\ConfigurationLoader;
use Lc\Fishery\Config\Resolver;
use Lc\Fishery\Config\Tree\ConfigurationTree;
use Lc\Fishery\Config\Tree\ParameterTree;
use Lc\Fishery\Config\Tree\SchemaTree;
use Lc\Fishery\Provider\EntityManagerProvider;
use Lc\Fishery\Provider\SchemaManagerProvider;
use Lc\Fishery\Provider\StorageProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class Fishery
 *
 * @package Lc\Fishery
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class FisheryConfigurator
{
    /** @var FisheryContainerInterface */
    private $app;

    /** @var FileLocator */
    private $locator;

    /** @var array */
    private $parameters;

    /**
     * @param FisheryContainerInterface $app
     * @param string                    $rootPath
     */
    public function __construct(FisheryContainerInterface $app, $rootPath)
    {
        $this->app = $app;

        $this->rootPath = $rootPath;

        $this->parameters = $this->getParameters($rootPath . DIRECTORY_SEPARATOR . 'config');

        $this->locator = new FileLocator($this->parameters['parameters']['config.dir']);

        $app['parameters'] = $this->parameters['parameters'];

    }

    /**
     *
     */
    public function configure()
    {

        $configPath = $this->parameters['parameters']['config.dir'];

        $configResolver = new Resolver($this->parameters);

        foreach($this->getConfigurationIn($configPath, 'config.yml') as $data) {
            $configResolver->addConfig($data);
        }

        $config = $configResolver->resolve(new ConfigurationLoader(new ConfigurationTree()));


        $configResolver = new Resolver();

        foreach($this->getConfigurationIn($configPath, 'schemas.yml') as $data) {
            $configResolver->addConfig($data);
        }

        $entitiesConfigPath = $configPath . DIRECTORY_SEPARATOR . 'data';

        foreach($this->getConfigurationIn($entitiesConfigPath, '*.yml', ' >= 0') as $data) {
            $configResolver->addConfig($data);
        }

        $schemas = $configResolver->resolve(new ConfigurationLoader(new SchemaTree()));

        foreach ($config as $parameter => $value) {
            $this->app[$parameter] = $value;
        }

        $this->app['schema.definition'] = $schemas['schemas'];
        $this->app['entity.definition'] = $schemas['entities'];

        $this->registerDefaultProviders();
    }

    /**
     * @param string $in            Directory to search in
     * @param string $depth
     * @param string $filePattern   File pattern to match
     *
     * @return \Generator
     */
    protected function getConfigurationIn($in, $filePattern = '*.yml', $depth = '== 0')
    {
        $finder = new Finder();
        $finder->files()->in($in)->name($filePattern)->depth($depth);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            yield $file->getContents();
        }
    }

    /**
     * @return array
     */
    protected function getParameters($configPath)
    {
        $parameters = [
            'config.dir'  => $this->rootPath . DIRECTORY_SEPARATOR . 'config',
            'schemas.dir' => '%config.dir%/schemas'
        ];

        $parametersLoader = new ConfigurationLoader(new ParameterTree());

        $_parameters = '';
        foreach($this->getConfigurationIn($configPath, 'parameters.yml', '== 0') as $data) {
            $_parameters .= $data;
        }

        $merged = array_merge_recursive(['parameters' => $parameters], $parametersLoader->load($data));

        $reference = $merged['parameters'];

        array_walk_recursive($merged['parameters'], function(&$item) use ($reference) {
           foreach ($reference as $parameter => $value) {
               $item = str_replace('%'. $parameter .'%', $reference[$parameter], $item);
           }
        });

        return $merged;
    }

    /**
     *
     */
    protected function registerDefaultProviders()
    {
        $this->app->register(new StorageProvider());
        $this->app->register(new SchemaManagerProvider());
        $this->app->register(new EntityManagerProvider());
    }
}

<?php

namespace Lc\Fishery\Config\Loader;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ConfigurationLoader
 *
 * @package Lc\Fishery\Config\Loader
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class ConfigurationLoader extends Loader
{
    /** @var ConfigurationInterface */
    protected $configurationInterface;

    /**
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
         $this->configuration = $configuration;
    }

    /**
     * Loads a resource.
     *
     * @param mixed       $resource The resource
     * @param string|null $type     The resource type or null if unknown
     *
     * @return array
     * @throws \Exception If something went wrong
     */
    public function load($resource, $type = null)
    {
        $data = Yaml::parse($resource);

        $processor = new Processor();

        return $processor->processConfiguration(
            $this->configuration,
            [$data]
        );

    }

    /**
     * {@inheritDoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}

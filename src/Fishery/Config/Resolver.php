<?php

namespace Lc\Fishery\Config;

use Lc\Fishery\Config\Loader\ConfigurationLoader;

/**
 * Class Resolver
 *
 * @package Lc\Fishery\Config
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class Resolver
{
    /** @var array */
    protected $parameters = [];
    /** @var array */
    protected $configStack = [];

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Add a config file is the config stack
     *
     * @param string $config
     */
    public function addConfig($config)
    {
        $this->configStack[] = $config;
    }

    /**
     * Resolve the configuration of the config stack
     *
     * @param ConfigurationLoader $loader
     * @return array
     */
    public function resolve(ConfigurationLoader $loader)
    {
        $configStack = [];
        foreach ($this->configStack as $config) {
            if (!empty($this->parameters)) {
                foreach ($this->parameters as $parameter => $value) {
                    $configStack[] = str_replace(
                        "%$parameter%",
                        $value,
                        $config
                    );
                }
            } else {
                $configStack[] = $config;
            }
        }

        return $loader->load(implode(PHP_EOL, $configStack));
    }
}

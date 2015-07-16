<?php

namespace Lc\Fishery\Application;

use Lc\Fishery\FisheryContainerInterface;
use Silex\Application as SilexApplication;
use Lc\Fishery\Provider\ServiceProviderInterface;

/**
 * Class FisheryHttp
 *
 * @package Lc\Fishery\Application
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class FisheryHttp extends SilexApplication implements FisheryContainerInterface
{
    /**
     * Registers a service provider.
     *
     * @param ServiceProviderInterface $provider A ServiceProviderInterface instance
     * @param array                    $values   An array of values that customizes the provider
     *
     * @return FisheryHttp
     */
    public function register(ServiceProviderInterface $provider, array $values = array())
    {
        $this->providers[] = $provider;

        $provider->register($this);

        foreach ($values as $key => $value) {
            $this[$key] = $value;
        }

        return $this;
    }
}

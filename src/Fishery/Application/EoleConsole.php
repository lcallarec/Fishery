<?php

namespace Lc\Fishery\Application;

use Lc\Fishery\Command\Console;
use Lc\Fishery\FisheryContainerInterface;
use Lc\Fishery\Provider\ServiceProviderInterface;
use Symfony\Component\Console\Application;

/**
 * Class Container
 *
 * @package Lc\Fishery
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class FisheryConsole extends \Pimple implements FisheryContainerInterface
{
    /** @var array<ServiceProviderInterface> */
    private $providers;

     /**
     * Registers a service provider.
     *
     * @param ServiceProviderInterface $provider A ServiceProviderInterface instance
     * @param array                    $values   An array of values that customizes the provider
     *
     * @return Application
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

    public function run()
    {
        if (isset($this['console.application']) && $this['console.application'] instanceof Console) {
            $this['console.application']->run();
        }
    }
}

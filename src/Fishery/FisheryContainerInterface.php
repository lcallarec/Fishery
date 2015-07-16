<?php

namespace Lc\Fishery;

use Lc\Fishery\Provider\ServiceProviderInterface;

/**
 * Class Fishery
 *
 * @package Lc\Fishery
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
interface FisheryContainerInterface
{
    public function register(ServiceProviderInterface $provider);

    /**
     * Returns a closure that stores the result of the given service definition
     * for uniqueness in the scope of this instance of Pimple.
     *
     * @param callable $callable A service definition to wrap for uniqueness
     *
     * @return \Closure The wrapped closure
     */
    public static function share($callable);
}

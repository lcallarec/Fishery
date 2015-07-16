<?php

namespace Lc\Fishery\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Console
 *
 * @package Lc\Fishery\Console
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class Console extends Application
{
    /** Current application version */
    const VERSION = '0.5.0';


    /** @var \Pimple */
    protected $container;

    public function __construct(\Pimple $container)
    {
        $this->container = $container;

        parent::__construct('Fishery', self::VERSION);
    }

    /**
     * {@inheritDoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        parent::run($input, $output);
    }

    /**
     * @return \Pimple
     */
    public function getContainer()
    {
        return $this->container;
    }
}

<?php

namespace Lc\Fishery\Config\Tree;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class ParameterTree
 *
 * @package Lc\Fishery\Config\Tree
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class ParameterTree implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('application');

        $rootNode
            ->children()
                ->arrayNode('parameters')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

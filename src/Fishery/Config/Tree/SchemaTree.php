<?php

namespace Lc\Fishery\Config\Tree;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class SchemaTree
 *
 * @package Lc\Fishery\Config\Tree
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class SchemaTree implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('schemas');

        $rootNode
            ->children()
                ->arrayNode('schemas')
                    ->prototype('array')
                        ->prototype('array')
                            ->children()
                            ->arrayNode('columns')
                                ->isRequired()
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('identifiers')
                                ->isRequired()
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('entities')
                ->prototype('array')
                        ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

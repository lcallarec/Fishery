<?php

namespace Lc\Fishery\Config\Tree;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class ConfigurationTree
 *
 * @package Lc\Fishery\Config\Tree
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class ConfigurationTree implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('application');

        $this->build($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $rootNode
     */
    private function build(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('dbal')
                    ->children()
                        ->arrayNode('schemas')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('dsn')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('schemas')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('realname')->defaultValue(null)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}

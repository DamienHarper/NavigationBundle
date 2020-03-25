<?php

namespace DH\NavigationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('dh_navigation');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->append($this->getProvidersNode())
        ;

        return $treeBuilder;
    }

    private function getProvidersNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('providers');
        $node = $treeBuilder->getRootNode();

        $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
            ->fixXmlConfig('plugin')
                ->children()
                    ->scalarNode('factory')->isRequired()->cannotBeEmpty()->end()
                    ->variableNode('options')->defaultValue([])->end()
                    ->arrayNode('aliases')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}

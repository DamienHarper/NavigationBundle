<?php

namespace DH\NavigationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\ParentNodeDefinitionInterface;
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

        // Keep compatibility with symfony/config < 4.2
        /** @var ParentNodeDefinitionInterface $rootNode */
        $rootNode = method_exists($treeBuilder, 'getRootNode') ? $treeBuilder->getRootNode() : $treeBuilder->root('dh_navigation');

        $rootNode
            ->children()
            ->append($this->getProvidersNode())
//                ->arrayNode('providers')
//                ->requiresAtLeastOneElement()
//                    ->prototype('array')
//                        ->children()
//                            ->scalarNode('factory')
//                                ->defaultValue('')
//                            ->end()
//                            ->arrayNode('options')
//                                ->canBeUnset()
//                                ->prototype('scalar')->end()
//                            ->end()
////                            ->booleanNode('enabled')
////                                ->defaultTrue()
////                            ->end()
//                        ->end()
//                    ->end()
//                ->end()
//            ->end()
        ;

        return $treeBuilder;
    }

    /**
     * @return ArrayNodeDefinition
     */
    private function getProvidersNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('providers');

        $node
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
            ->fixXmlConfig('plugin')
                ->children()
                    ->scalarNode('factory')->isRequired()->cannotBeEmpty()->end()
                    ->variableNode('options')->defaultValue([])->end()
                    ->scalarNode('cache')->defaultNull()->end()
                    ->scalarNode('cache_lifetime')->defaultNull()->end()
                    ->scalarNode('cache_precision')
                        ->defaultNull()
                        ->info('Precision of the coordinates to cache.')
                        ->end()
                    ->scalarNode('limit')->defaultNull()->end()
                    ->scalarNode('locale')->defaultNull()->end()
                    ->scalarNode('logger')->defaultNull()->end()
                    ->arrayNode('aliases')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}

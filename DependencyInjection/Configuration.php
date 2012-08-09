<?php

namespace WhiteOctober\SwiftMailerDBBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Defines our bundle configuration
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root("wo_swiftmailer_db");

        $rootNode
            ->children()
                ->scalarNode("entity_class")->isRequired()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

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
        $rootNode = $treeBuilder->root("white_october_swift_mailer_db");

        $rootNode
            ->children()
                ->scalarNode("entity_class")->isRequired()->end()
                ->scalarNode("keep_sent_messages")->defaultFalse()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

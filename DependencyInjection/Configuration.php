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
                ->enumNode("driver")->values(array('orm', 'odm'))->isRequired()->end()
                ->scalarNode("model")->isRequired()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

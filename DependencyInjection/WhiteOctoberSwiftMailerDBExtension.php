<?php

namespace WhiteOctober\SwiftMailerDBBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\Config\Definition\Processor;

class WhiteOctoberSwiftMailerDBExtension extends Extension
{
    /**
     * Loads any resources/services we need
     *
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->setupConfiguration($configs, $container);

        // Service config
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * Sets up configuration for the extension
     *
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function setupConfiguration(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter("white_october.swiftmailer_db.spool.entity_class", $config["entity_class"]);
        $container->setParameter("white_october.swiftmailer_db.spool.keep_sent_messages", $config["keep_sent_messages"]);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return "white_october_swift_mailer_db";
    }
}

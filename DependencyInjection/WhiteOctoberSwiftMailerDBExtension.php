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
        $this->loadDriver($container, $configs[0]['driver']);
        $this->setupConfiguration($configs, $container);
    }

    protected function loadDriver(ContainerBuilder $container, $driver)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load($driver.'.xml');
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

        $container->setParameter("white_october.swiftmailer_db.spool.model", $config["model"]);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return "white_october_swift_mailer_db";
    }
}

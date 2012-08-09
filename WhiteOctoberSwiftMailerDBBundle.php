<?php

namespace WhiteOctober\SwiftMailerDBBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\HttpKernel\Bundle\Bundle;
use WhiteOctober\SwiftMailerDBBundle\DependencyInjection\WhiteOctoberSwiftMailerDBExtension;

/**
 * WhiteOctoberSwiftMailerDBBundle class
 */
class WhiteOctoberSwiftMailerDBBundle extends Bundle
{
    public function build(ContainerBuilder $container)
     {
         // Using a different alias - need to set the extension here
         $this->extension = new WhiteOctoberSwiftMailerDBExtension();
     }
}

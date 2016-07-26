<?php

namespace SKyosev\FormTranslateBundle;

use SKyosev\FormTranslateBundle\DependencyInjection\Compiler\FormTypesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SKyosevFormTranslateBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FormTypesCompilerPass());
    }
}

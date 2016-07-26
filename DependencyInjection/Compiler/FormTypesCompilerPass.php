<?php

namespace SKyosev\FormTranslateBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FormTypesCompilerPass implements CompilerPassInterface
{
    /**
     * Process compilation of containers.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container
            ->findDefinition('shapecode_raas.doctrine.repository_factory')
            ->setClass('SKyosev\FormTranslateBundle\Repository\Factory')
            ->addMethodCall('setLocaleProvider', [new Reference('form_trans.locale_provider')]);

        foreach (array_keys($container->findTaggedServiceIds('form.type')) as $service_id) {
            $definition = $container->findDefinition($service_id);
            $uses = class_uses($definition->getClass());

            if (in_array('SKyosev\FormTranslateBundle\Form\TranslatableTypeTrait', $uses)) {
                $definition->addMethodCall('setDataMapper', [new Reference('form_trans.data_mapper')]);
            }
        }
    }
}
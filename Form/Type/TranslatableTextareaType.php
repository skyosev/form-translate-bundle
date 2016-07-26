<?php

namespace SKyosev\FormTranslateBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslatableTextareaType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(['compound' => true])
            ->setRequired(['compound'])
            ->setAllowedValues('compound', true);

    }

    public function getParent()
    {
        return TextareaType::class;
    }
}
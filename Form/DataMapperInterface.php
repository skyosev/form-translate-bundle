<?php

namespace SKyosev\FormTranslateBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

interface DataMapperInterface extends \Symfony\Component\Form\DataMapperInterface
{
    /**
     * @param FormBuilderInterface $builderInterface
     */
    public function setBuilder(FormBuilderInterface $builderInterface);

    /**
     * @param $name
     * @param \Symfony\Component\Form\AbstractType $type
     * @param array $options
     * @return DataMapper
     * @throws \Exception
     */
    public function add($name, $type, $options = []);
} 
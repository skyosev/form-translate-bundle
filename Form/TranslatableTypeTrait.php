<?php

namespace SKyosev\FormTranslateBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

trait TranslatableTypeTrait
{
    /**
     * @var DataMapperInterface
     */
    private $mapper;

    /**
     * @param DataMapperInterface $dataMapper
     */
    public function setDataMapper(DataMapperInterface $dataMapper)
    {
        $this->mapper = $dataMapper;
    }

    /**
     * @param FormBuilderInterface $builderInterface
     * @return DataMapperInterface
     */
    protected function createTranslatableMapper(FormBuilderInterface $builderInterface)
    {
        $this->mapper->setBuilder($builderInterface);
        $builderInterface->setDataMapper($this->mapper);

        return $this->mapper;
    }
}
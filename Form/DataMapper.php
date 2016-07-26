<?php

namespace SKyosev\FormTranslateBundle\Form;

use SKyosev\FormTranslateBundle\Locale\Provider;
use Doctrine\ORM\EntityManager;
use Gedmo\Translatable\Translatable;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\FormInterface;

class DataMapper implements DataMapperInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TranslationRepository
     */
    private $repository;

    /**
     * @var FormBuilderInterface
     */
    private $builder;

    /**
     * @var Provider
     */
    private $localeProvider;

    /**
     * @var array
     */
    private $translations = [];

    /**
     * @var array
     */
    private $property_names = [];

    /**
     * @param EntityManager $entityManager
     * @param Provider $localeProvider
     */
    public function __construct(EntityManager $entityManager, Provider $localeProvider)
    {
        $this->em             = $entityManager;
        $this->localeProvider = $localeProvider;
        $this->repository     = $this->em->getRepository('Gedmo\Translatable\Entity\Translation');

    }

    /**
     * @param FormBuilderInterface $builderInterface
     */
    public function setBuilder(FormBuilderInterface $builderInterface)
    {
        $this->builder = $builderInterface;
    }

    /**
     * @param Translatable $entity
     * @return array
     */
    public function getTranslations(Translatable $entity)
    {
        if (!count($this->translations)) {
            $translations   = $this->repository->findTranslations($entity);
            $default_locale = $this->localeProvider->getDefaultLocale();

            if (!array_key_exists($default_locale, $translations)) {
                $translations[$default_locale] = [];

                foreach (array_keys(reset($translations)) as $db_field) {
                    $translations[$default_locale][$db_field] = $entity->{'get' . ucfirst($db_field)}();
                }
            }

            $this->translations = $translations;
        }

        return $this->translations;
    }

    /**
     * @param string $name
     * @param mixed $type
     * @param array $options
     * @return DataMapper
     * @throws \Exception
     */
    public function add($name, $type, $options = [])
    {
        $this->property_names[] = $name;

        $field = $this->builder
            ->add($name, $type)
            ->get($name);

        foreach ($this->localeProvider->getLocales() as $locale) {
            $options = [
                'label'    => $locale,
                'required' => $locale == $this->localeProvider->getDefaultLocale()
            ];

            $field->add($locale, get_class($field->getType()->getParent()->getInnerType()), $options);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms)
    {
        foreach($forms as $form) {
            $translations = $this->getTranslations($data);

            if (false !== in_array($form->getName(), $this->property_names)) {
                $values = [];

                foreach($this->localeProvider->getLocales() as $locale) {
                    if(isset($translations[$locale])){
                        $values[$locale] =  $translations[$locale][$form->getName()];
                    }
                }

                $form->setData($values);
            } else {
                if (false === $form->getConfig()->getOption('mapped')) {
                    continue;
                }

                $accessor = PropertyAccess::createPropertyAccessor();
                $form->setData($accessor->getValue($data, $form->getName()));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        /**
         * @var $form FormInterface
         */
        foreach ($forms as $form) {
            $entityInstance = $data;

            if (false !== in_array($form->getName(), $this->property_names)) {
                $translations = $form->getData();

                foreach($this->localeProvider->getLocales() as $locale) {
                    if (isset($translations[$locale])) {
                        $this->repository->translate($entityInstance, $form->getName(), $locale, $translations[$locale]);
                    }
                }
            } else {
                if (false === $form->getConfig()->getOption('mapped')) {
                    continue;
                }

                $accessor = PropertyAccess::createPropertyAccessor();
                $accessor->setValue($entityInstance, $form->getName(), $form->getData());
            }
        }
    }
}
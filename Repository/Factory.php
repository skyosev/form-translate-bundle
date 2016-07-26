<?php

namespace SKyosev\FormTranslateBundle\Repository;

use Shapecode\Bundle\RasSBundle\Doctrine\Repository\ServiceRepositoryFactory;
use Doctrine\ORM\EntityManagerInterface;
use SKyosev\FormTranslateBundle\Locale\Provider;

class Factory extends ServiceRepositoryFactory
{
    /**
     * @var Provider
     */
    private $localeProvider;

    /**
     * @param Provider $localeProvider
     */
    public function setLocaleProvider(Provider $localeProvider)
    {
        $this->localeProvider = $localeProvider;
    }

    /**
     * @inheritdoc
     */
    public function getRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $repository = parent::getRepository($entityManager, $entityName);

        if ($repository instanceof TranslatableRepository && $this->localeProvider !== null) {
            $repository->setLocaleProvider($this->localeProvider);
        }

        return $repository;
    }
}
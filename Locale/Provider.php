<?php

namespace SKyosev\FormTranslateBundle\Locale;

use Symfony\Component\HttpFoundation\RequestStack;

class Provider
{
    /** @var string */
    protected $default_locale;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /** @var array */
    protected $locales;
    
    public function __construct(RequestStack $requestStack, $default_locale, array $locales = [])
    {
        $this->requestStack   = $requestStack;
        $this->default_locale = $default_locale;
        $this->locales        = $locales;
    }

    /**
     * @return array
     */
    public function getLocales()
    {
        $locales = $this->locales;

        if (!in_array($this->default_locale, $locales, true)) {
            array_push($locales, $this->default_locale);
        }

        return $locales;
    }

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->default_locale;
    }

    /**
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->requestStack->getCurrentRequest()->getLocale();
    }
}
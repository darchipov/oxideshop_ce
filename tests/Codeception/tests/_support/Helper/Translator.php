<?php

namespace Helper;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

class Translator
{
    /**
     * @var array
     */
    private $sfTranslator;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->sfTranslator = new SymfonyTranslator('en');

        $this->sfTranslator->addLoader('array', new ArrayLoader());

        $deLanguageArray = $this->_getLanguageArray('de');
        $this->sfTranslator->addResource('array', $deLanguageArray, 'de');
        $enLanguageArray = $this->_getLanguageArray('en');
        $this->sfTranslator->addResource('array', $enLanguageArray, 'en');

    }
    /**
     * @param string $string
     *
     * @return string
     */
    public function translate($string)
    {
        return $this->sfTranslator->trans($string);
    }

    /**
     * Returns language map array
     *
     * @param string $language Language index
     *
     * @return array
     */
    private function _getLanguageArray($language)
    {
        $languageArray = [];

        $languageFiles = $this->_getLanguageFiles($language);

        foreach ($languageFiles as $languageFile) {
            $aLang = [];
            if (file_exists($languageFile) && is_readable($languageFile)) {
                include $languageFile;
            }
            $languageArray = array_merge($languageArray, $aLang);
        }

        return $languageArray;
    }


    /**
     * Returns language map array
     *
     * @param string $language Language index
     *
     * @return array
     */
    private function _getLanguageFiles($language)
    {
        $languageFiles = [];

        $languageFiles[] = '/var/www/oxideshop/source/Application/translations/' . $language . '/lang.php';
        $languageFiles[] = '/var/www/oxideshop/source/Application/views/flow/' . $language . '/lang.php';
        $languageFiles[] = '/var/www/oxideshop/source/Application/views/flow/' . $language . '/cust_lang.php';

        return $languageFiles;
    }

}
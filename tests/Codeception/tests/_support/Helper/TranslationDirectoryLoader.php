<?php

namespace Helper;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator as SymfonyTranslator;

class TranslationDirectoryLoader
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

        $this->sfTranslator->addLoader('oxphp', new LanguageDirectoryReader());

        $languageFiles = $this->_getLanguageFiles('de');

        foreach ($languageFiles as $languageFile) {
            $this->sfTranslator->addResource('oxphp', $languageFile, 'de');
        }

        $languageFiles = $this->_getLanguageFiles('en');

        foreach ($languageFiles as $languageFile) {
            $this->sfTranslator->addResource('oxphp', $languageFile, 'en');
        }
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
    private function _getLanguageFiles($language)
    {
        $languageFiles = [];

        $languageFiles[] = '/var/www/oxideshop/source/Application/translations/' . $language . '/lang.php';
        $languageFiles[] = '/var/www/oxideshop/source/Application/views/flow/' . $language . '/lang.php';

        return $languageFiles;
    }

}
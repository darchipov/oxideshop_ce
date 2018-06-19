<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Core\Module;

/**
 * Class ModuleSmartyPluginDirectoryDao
 *
 * @internal Do not make a module extension for this class.
 * @see      https://oxidforge.org/en/core-oxid-eshop-classes-must-not-be-extended.html
 *
 * @ignore   This class will not be included in documentation.
 *
 */
class ModuleSmartyPluginDirectoryDao
{
    /**
     * @var string The key under which the value will be stored.
     */
    const STORAGE_KEY = 'aModuleSmartyPluginDirectories';

    /** @var \OxidEsales\Eshop\Core\Config  */
    private $config = null;

    /**
     * @var \OxidEsales\Eshop\Core\Module\ModuleVariablesLocator
     *
     * Necessary for caching
     */
    private $moduleVariablesLocator;

    /**
     * ModuleSmartyPluginDirectoryDao constructor.
     *
     * @param \OxidEsales\Eshop\Core\Config                        $config                 For database connection
     * @param \OxidEsales\Eshop\Core\Module\ModuleVariablesLocator $moduleVariablesLocator For caching
     */
    public function __construct(
        \OxidEsales\Eshop\Core\Config $config,
        \OxidEsales\Eshop\Core\Module\ModuleVariablesLocator $moduleVariablesLocator
    ) {
        $this->config = $config;
        $this->moduleVariablesLocator = $moduleVariablesLocator;
    }

    /**
     * @return array The smarty plugin directories of all modules with absolute path as numeric array
     */
    public function get()
    {
        $smartyPluginDirectories  = $this->moduleVariablesLocator->getModuleVariable(self::STORAGE_KEY);

        if ($smartyPluginDirectories === false) {
            return [];
        }

        return $smartyPluginDirectories;
    }

    /**
     * @param array $value The smarty plugin directories of all modules separated by module
     */
    public function set($value)
    {
        $this->config->saveShopConfVar('aarr', self::STORAGE_KEY, $value);
        $this->moduleVariablesLocator->setModuleVariable(self::STORAGE_KEY, $value);
    }

}
<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Core\Module;

/**
 * Class ModuleSmartyPluginDirectoryStorage
 *
 * @internal Do not make a module extension for this class.
 * @see      https://oxidforge.org/en/core-oxid-eshop-classes-must-not-be-extended.html
 *
 * @ignore   This class will not be included in documentation.
 *
 */
class ModuleSmartyPluginDirectoryRepository
{

    /**
     * @var \OxidEsales\Eshop\Core\Module\Module
     *
     * Needed to get the absolute path to a module directory
     */
    private $module = null;

    /**
     * @var \OxidEsales\EshopCommunity\Core\Module\ModuleSmartyPluginDirectoryDao
     *
     * We want to have an external dependecy for better testability
     */
    private $dao = null;

    /**
     * SmartyPluginDirectoryBridge constructor.
     *
     * @param \OxidEsales\EshopCommunity\Core\Module\ModuleSmartyPluginDirectoryDao $moduleSmartyPluginDirectoryDao
     * @param \OxidEsales\EshopCommunity\Core\Module\Module                         $module
     */
    public function __construct(
        \OxidEsales\EshopCommunity\Core\Module\ModuleSmartyPluginDirectoryDao $moduleSmartyPluginDirectoryDao,
        \OxidEsales\EshopCommunity\Core\Module\Module $module
    ) {
        $this->dao = $moduleSmartyPluginDirectoryDao;
        $this->module = $module;
    }

    /**
     * @param array  $moduleSmartyPluginDirectories
     * @param string $moduleId
     */
    public function add($moduleSmartyPluginDirectories, $moduleId)
    {
        $smartyPluginDirectories = $this->dao->get();
        $smartyPluginDirectories[$moduleId] = $moduleSmartyPluginDirectories;
        $this->dao->set($smartyPluginDirectories);
    }

    /**
     * Delete the smarty plugin directories for the module, given by its ID, from the storage.
     *
     * @param string $moduleId The ID of the module, for which we want to delete the controllers from the storage.
     */
    public function remove($moduleId)
    {
        $smartyPluginDirectories = $this->dao->get();
        unset($smartyPluginDirectories[$moduleId]);

        $this->dao->set($smartyPluginDirectories);
    }

    /**
     * @return array The smarty plugin directories of all modules with absolute path as numeric array
     */
    public function get()
    {
        $smartyPluginDirectories  = $this->dao->get();

        if ($smartyPluginDirectories === false) {
            return [];
        }

        return $this->getSmartyPluginDirectoriesWithFullPath($smartyPluginDirectories);
    }

    /**
     * @param array $smartyPluginDirectories
     *
     * @return array
     */
    private function getSmartyPluginDirectoriesWithFullPath(array $smartyPluginDirectories)
    {
        $smartyPluginDirectoriesWithFullPath = [];

        foreach ($smartyPluginDirectories as $moduleId => $smartyDirectoriesOfOneModule) {
            foreach ($smartyDirectoriesOfOneModule as $smartyPluginDirectory) {
                $smartyPluginDirectoriesWithFullPath[] = $this->module->getModuleFullPath($moduleId) .
                                                         DIRECTORY_SEPARATOR .
                                                         $smartyPluginDirectory;
            }
        }

        return $smartyPluginDirectoriesWithFullPath;
    }
}

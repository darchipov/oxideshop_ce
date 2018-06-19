<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
namespace OxidEsales\EshopCommunity\Tests\Unit\Core\Module;

use \OxidEsales\EshopCommunity\Core\Module\ModuleSmartyPluginDirectoryRepository;
use \OxidEsales\EshopCommunity\Core\Module\ModuleSmartyPluginDirectoryDao;

/**
 * Class ModuleSmartyPluginDirectoryStorageTest
 *
 * @package OxidEsales\EshopCommunity\Tests\Unit\Core\Module
 */
class ModuleSmartyPluginDirectoryRepositoryTest extends \OxidEsales\TestingLibrary\UnitTestCase
{

    /** @var \OxidEsales\EshopCommunity\Core\Module\ModuleSmartyPluginDirectoryRepository  */
    private $moduleSmartyPluginDirectoryStorageTest = null;

    protected function setUp()
    {
        parent::setUp();
    }


    public function testAddAppendsSmartyPluginDirectoriesAfterExistingSmartyPluginDirectories()
    {
        $smartyPluginDirectoriesModule1 = ['Smarty/Plugin1', 'Smarty/Plugin2'];
        $moduleIdModule1 = 'oemodule1';

        $smartyPluginDirectoriesModule2 = ['Smarty/MyPlugin1'];
        $moduleIdModule2 = 'oemodule2';

        $moduleSmartyPluginDirectoryDaoMock = $this->getMockBuilder(
            ModuleSmartyPluginDirectoryDao::class
        )
            ->disableOriginalConstructor()
            ->getMock();
        $moduleSmartyPluginDirectoryDaoMock->method('get')
            ->will($this->returnValue([$moduleIdModule1 => $smartyPluginDirectoriesModule1]));
        $moduleSmartyPluginDirectoryDaoMock->method('set')
            ->with(
                $this->equalTo(
                    [$moduleIdModule1 => $smartyPluginDirectoriesModule1,
                     $moduleIdModule2 => $smartyPluginDirectoriesModule2]
                )
            );

        $module = oxNew(\OxidEsales\Eshop\Core\Module\Module::class);

        $moduleSmartyPluginDirectoryRepository = new ModuleSmartyPluginDirectoryRepository(
            $moduleSmartyPluginDirectoryDaoMock,
            $module
        );

        $moduleSmartyPluginDirectoryRepository->add($smartyPluginDirectoriesModule2, $moduleIdModule2);
    }

    public function testDeleteRemovesCorrectSmartyPluginDirectories()
    {
        $smartyPluginDirectoriesModule1 = ['Smarty/Plugin1', 'Smarty/Plugin2'];
        $moduleIdModule1 = 'oemodule1';

        $smartyPluginDirectoriesModule2 = ['Smarty/MyPlugin2'];
        $moduleIdModule2 = 'oemodule2';

        $smartyPluginDirectoriesModule3 = ['Smarty/ModulePlugin3'];
        $moduleIdModule3 = 'oemodule3';

        $moduleSmartyPluginDirectoryDaoMock = $this->getMockBuilder(
            ModuleSmartyPluginDirectoryDao::class
        )
            ->disableOriginalConstructor()
            ->getMock();
        $moduleSmartyPluginDirectoryDaoMock->method('get')
            ->will($this->returnValue([
                $moduleIdModule1 => $smartyPluginDirectoriesModule1,
                $moduleIdModule2 => $smartyPluginDirectoriesModule2,
                $moduleIdModule3 => $smartyPluginDirectoriesModule3
            ]));
        $moduleSmartyPluginDirectoryDaoMock->method('set')
            ->with(
                $this->equalTo([
                    $moduleIdModule1 => $smartyPluginDirectoriesModule1,
                    $moduleIdModule3 => $smartyPluginDirectoriesModule3
                    ])
            );

        $module = oxNew(\OxidEsales\Eshop\Core\Module\Module::class);

        $moduleSmartyPluginDirectoryRepository = new ModuleSmartyPluginDirectoryRepository(
            $moduleSmartyPluginDirectoryDaoMock,
            $module
        );

        $moduleSmartyPluginDirectoryRepository->remove($moduleIdModule2);
    }

    public function testGetOutputsOrderedArray()
    {
        $moduleIdModule1 = 'oemodule1';
        $pathToModule1 = '/path/to/my/oemodule1';
        $module1Directory1 = 'Smarty/Plugin1';
        $module1Directory2 = 'Smarty/Plugin2';
        $smartyPluginDirectoriesModule1 = [$module1Directory1, $module1Directory2];


        $moduleIdModule2 = 'oemodule2';
        $pathToModule2 = '/path/to/my/oemodule2';
        $module2Directory1 = 'Smarty/Plugin2';
        $smartyPluginDirectoriesModule2 = [$module2Directory1];


        $moduleIdModule3 = 'oemodule3';
        $pathToModule3 = '/path/to/my/oemodule3';
        $module3Directory1 = 'Smarty/ModulePlugin3';
        $smartyPluginDirectoriesModule3 = [$module3Directory1];


        $moduleSmartyPluginDirectoryDaoMock = $this->getMockBuilder(
            ModuleSmartyPluginDirectoryDao::class
        )
            ->disableOriginalConstructor()
            ->getMock();
        $moduleSmartyPluginDirectoryDaoMock->method('get')
            ->will($this->returnValue([
                $moduleIdModule1 => $smartyPluginDirectoriesModule1,
                $moduleIdModule2 => $smartyPluginDirectoriesModule2,
                $moduleIdModule3 => $smartyPluginDirectoriesModule3
            ]));

        $moduleStub = $this->getMock(\OxidEsales\Eshop\Core\Module\Module::class);
        $moduleStub->method('getModuleFullPath')
            ->with($this->equalTo($moduleIdModule2))
            ->will($this->returnValue());
        $moduleStub->method('getModuleFullPath')
            ->with($this->equalTo($moduleIdModule3))
            ->will($this->returnValue($pathToModule3));

        $moduleStub->method('getModuleFullPath')
            ->will($this->returnValue($pathToModule3));

        $moduleSmartyPluginDirectoryRepository = new ModuleSmartyPluginDirectoryRepository(
            $moduleSmartyPluginDirectoryDaoMock,
            $moduleStub
        );

        $smartyPluginDirectoriesWithAbsolutePath = $moduleSmartyPluginDirectoryRepository->get();
        $this->assertEquals(
            $smartyPluginDirectoriesWithAbsolutePath,
            [
                $pathToModule1 . $module1Directory1,
                $pathToModule1 . $module1Directory2,
                $pathToModule2 . $module2Directory1,
                $pathToModule3 . $module3Directory1
            ]
        );
    }

    /**
     * @return \OxidEsales\Eshop\Core\Module\ModuleVariablesLocator
     */
    private function getModuleVariablesLocator()
    {
        $moduleVariablesLocator = new \OxidEsales\Eshop\Core\Module\ModuleVariablesLocator(
            $this->getSubShopSpecificFileCache(),
            $this->getShopIdCalculator()
        );
        return $moduleVariablesLocator;
    }

    /**
     * @return \OxidEsales\Eshop\Core\SubShopSpecificFileCache
     */
    private function getSubShopSpecificFileCache()
    {
        return new \OxidEsales\Eshop\Core\SubShopSpecificFileCache($this->getShopIdCalculator());
    }

    /**
     * @return \OxidEsales\Eshop\Core\ShopIdCalculator
     */
    private function getShopIdCalculator()
    {
        if (is_null($this->shopIdCalculator)) {
            $moduleVariablesCache = new \OxidEsales\Eshop\Core\FileCache();
            $this->shopIdCalculator = new \OxidEsales\Eshop\Core\ShopIdCalculator($moduleVariablesCache);
        }
        return $this->shopIdCalculator;
    }
}
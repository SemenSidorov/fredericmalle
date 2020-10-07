<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use DDP\Mod\ExampleTable;

Loc::loadMessages(__FILE__);

class ddp_mod extends CModule
{
    public function __construct()
    {
        $arModuleVersion = array();
        
        include __DIR__ . '/version.php';

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
        
        $this->MODULE_ID = 'ddp.mod';
        $this->MODULE_NAME = Loc::getMessage('DDP_MOD_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('DDP_MOD_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('DDP_MOD_MODULE_PARTNER_NAME');
        $this->PARTNER_URI = 'http://ddplanet.ru';
    }

    public function doInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
        $this->installEvents();
    }

    public function doUninstall()
    {
        $this->uninstallDB();
        $this->unInstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function installDB()
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            //ExampleTable::getEntity()->createDbTable();
        }
    }

    public function uninstallDB()
    {
        if (Loader::includeModule($this->MODULE_ID))
        {
            /*$connection = Application::getInstance()->getConnection();
            $connection->dropTable(ExampleTable::getTableName());*/
        }
    }

    public function installEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();

        $eventManager->registerEventHandlerCompatible(
            'iblock',
            'OnAfterIBlockElementUpdate',
            'ddp.mod',
            '\DDP\Mod\Handler',
            'volUpdate'
        );

        $eventManager->registerEventHandlerCompatible(
            'iblock',
            'OnAfterIBlockElementAdd',
            'ddp.mod',
            '\DDP\Mod\Handler',
            'volUpdate'
        );
    }

    public function unInstallEvents()
    {
        $eventManager = \Bitrix\Main\EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            'ddp.mod',
            '\DDP\Mod\Handler',
            'volUpdate'
        );

        $eventManager->unRegisterEventHandler(
            'iblock',
            'OnAfterIBlockElementAdd',
            'ddp.mod',
            '\DDP\Mod\Handler',
            'volUpdate'
        );
    }

}

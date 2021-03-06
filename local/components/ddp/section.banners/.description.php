<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array( 
    "NAME" => "Баннеры раздела",
    "DESCRIPTION" => "Баннеры раздела",
    "ICON" => '/images/icon.gif',
    "SORT" => 20,
    "PATH" => array(
        "ID" => 'DDP',
        "NAME" => "DD Planet",
        "SORT" => 10,
        "CHILD" => array(
            "ID" => 'catalog',
            "NAME" => Loc::getMessage('CATALOG_LIST_DESCRIPTION_DIR'),
            "SORT" => 10
        )
    )
);

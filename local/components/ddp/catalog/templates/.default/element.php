<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<? $APPLICATION->IncludeComponent(
    'ddp:catalog.detail',
    '',
    array(
        'CODE' => $arResult["VARIABLES"]["CODE"],
        'IBLOCK_ID' => $arParams["IBLOCK_ID"],
        'IBLOCK_SKU_ID' => $arParams["IBLOCK_SKU_ID"],
        'SECTION' => $arResult["SECTION"],
        'SET_TITLE' => 'Y'
    ),
    $component,
    array('HIDE_ICONS' => 'Y')
);
?>

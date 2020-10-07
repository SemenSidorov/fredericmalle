<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
            <?$APPLICATION->IncludeComponent(
                "ddp:breadcrumb", 
                "castom", 
                Array(
                    "PATH" => "",	// Путь, для которого будет построена навигационная цепочка (по умолчанию, текущий путь)
                    "SITE_ID" => "s1",	// Cайт (устанавливается в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
                    "START_FROM" => "1",	// Номер пункта, начиная с которого будет построена навигационная цепочка
                ),
                false
            );?>
    <section class="listing-title">
    <h2 class="listing__title"><?=$arResult["SECTION"]["UF_SHORTNAME"]?></h2>
    <p class="listing__text"><?=$arResult["SECTION"]["DESCRIPTION"]?></p>
<?
$filterAr = $APPLICATION->IncludeComponent(
    'ddp:catalog.filter',
    'perfume',
    array(
        "IBLOCK_TYPE_ID" => "catalog",
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "IBLOCK_SKU_ID" => $arParams["IBLOCK_SKU_ID"],
        'GET_FILTERS' => 'Y',
        'MAIN_FOLDER' => "/products/perfume",
        "FILTER" => $arResult["FILTER"],
        "FILTER_NAME" => "perfumeFilter",
        'SECT_ID' => $arResult["SECTION"]['ID'],
    ),
    false
);?>
    </section>
<?
$APPLICATION->IncludeComponent(
    'ddp:catalog.section',
    'perfume',
    array(
        "IBLOCK_TYPE_ID" => "catalog",
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "IBLOCK_SKU_ID" => $arParams["IBLOCK_SKU_ID"],
        "FILTER_AR" => $filterAr["filter"],
        "SORT_AR" => $filterAr["sort"],
        'SECTION' => $filterAr["section"],
        'GET_RANGES' => 'Y',
        'SET_TITLE' => 'Y'
    ),
    false
);?>
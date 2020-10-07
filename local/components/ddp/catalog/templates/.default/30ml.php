<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
    <section class="title">
        <h2>Коллекция 30мл</h2>
        <p>Практичный и продуманный дизайн нашего парфюмерного спрея объемом 30 мл для дома и путешествий, однозначно соответствует основополагающим принципам парфюмерного дома. Первоначальная миссия дома состояла не только в том, чтобы возродить умирающее искусство изысканного парфюмерного творчества, предоставляя полную творческую свободу лучшим парфюмерам в отрасли, но и в том, чтобы доносить ценные результаты нашей работы до  широкой аудитории.</p>
    </section>
    <section class="promo-product">
        <img class="promo-product__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/collection/header.jpg" alt="">
    </section>
<?
            $APPLICATION->IncludeComponent(
                "ddp:breadcrumb", 
                "castom", 
                Array(
                    "PATH" => "",	// Путь, для которого будет построена навигационная цепочка (по умолчанию, текущий путь)
                    "SITE_ID" => "s1",	// Cайт (устанавливается в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
                    "START_FROM" => "1",	// Номер пункта, начиная с которого будет построена навигационная цепочка
                ),
                false
            );
        
$filterAr = $APPLICATION->IncludeComponent(
    'ddp:catalog.filter',
    '',
    array(
        "IBLOCK_TYPE_ID" => "catalog",
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "IBLOCK_SKU_ID" => $arParams["IBLOCK_SKU_ID"],
        'GET_FILTERS' => 'Y',
        'MAIN_FOLDER' => "/products/perfume/",
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
    '30mlcollection',
    array(
        "IBLOCK_TYPE_ID" => "catalog",
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "IBLOCK_SKU_ID" => $arParams["IBLOCK_SKU_ID"],
        "FILTER_AR" => $filterAr["filter"],
        "SKU_FILTER" => ["PROPERTY_VOL_NUM" => 30],
        "SORT_AR" => $filterAr["sort"],
        'SECTION' => $filterAr["section"],
        'GET_RANGES' => 'N',
        'SET_TITLE' => 'N'
    ),
    false
);?>
<?global $bannefFilter;
$bannefFilter["PROPERTY_TYPE"] = "2";
$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "banner_section",
    Array(
        "IBLOCK_TYPE" => "info",
        "IBLOCK_ID" => 7,
        "NEWS_COUNT" => 3,
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "ID",
        "SORT_ORDER2" => "DESC",
        "FIELD_CODE" => [],
        "PROPERTY_CODE" => ["LINK", "COLLECTION"],
        "DETAIL_URL" => false,
        "SECTION_URL" => false,
        "IBLOCK_URL" => false,
        "DISPLAY_PANEL" => "Y",
        "SET_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "MESSAGE_404" => "",
        "SET_STATUS_404" => "",
        "SHOW_404" => false,
        "FILE_404" => false,
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => 3600000,
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "PAGER_TITLE" => "",
        "PAGER_TEMPLATE" => "",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_DESC_NUMBERING" => "",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_BASE_LINK" => "N",
        "PAGER_PARAMS_NAME" => "",
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "",
        "DISPLAY_PREVIEW_TEXT" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "",
        "USE_PERMISSIONS" => "N",
        "GROUP_PERMISSIONS" => "",
        "FILTER_NAME" => "bannefFilter",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "CHECK_DATES" => "Y",
    ),
    $component
);?>

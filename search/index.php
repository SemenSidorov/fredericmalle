<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Поиск — Французский парфюмерный издательский дом Editions de Parfums Frederic Malle.");
$APPLICATION->SetTitle("Поиск");
?><?$APPLICATION->IncludeComponent(
    "ddp:search.title",
    "",
    Array(
        "CATEGORY_0" => array("iblock_products"),
        "CATEGORY_0_TITLE" => "",
        "CATEGORY_0_iblock_products" => array("1", "4", "5"),
        "CATEGORY_1" => array("iblock_info"),
        "CATEGORY_1_iblock_products" => array("3"),
        "CHECK_DATES" => "N",
        "CONTAINER_ID" => "title-search",
        "INPUT_ID" => "title-search-input",
        "NUM_CATEGORIES" => "2",
        "ORDER" => "date",
        "PAGE" => "/search/index.php",
        "SHOW_INPUT" => "Y",
        "SHOW_OTHERS" => "N",
        "TOP_COUNT" => "5",
        "USE_LANGUAGE_GUESS" => "Y",
        "USE_SUGGEST" => "Y",
        "FULL_RESULT" => "Y"
    )
);?><?/*$APPLICATION->IncludeComponent(
	"bitrix:search.page",
	"",
	Array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "N",
		"DEFAULT_SORT" => "rank",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "Y",
		"FILTER_NAME" => "",
		"NO_WORD_LOGIC" => "N",
		"PAGER_SHOW_ALWAYS" => "Y",
		"PAGER_TEMPLATE" => "",
		"PAGER_TITLE" => "Результаты поиска",
		"USE_LANGUAGE_GUESS" => "Y",
		"PAGE_RESULT_COUNT" => "50",
		"RESTART" => "N",
		"SHOW_WHEN" => "N",
		"SHOW_WHERE" => "N",
		"USE_LANGUAGE_GUESS" => "Y",
		"USE_SUGGEST" => "Y",
		"USE_TITLE_RANK" => "N",
		"arrFILTER" => array("iblock_products", "iblock_info"),
		"arrFILTER_iblock_products" => array("1", "4", "5"),
        "arrFILTER_iblock_info" => array("3"),
		"arrWHERE" => array()
	)
);*/?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
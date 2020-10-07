<?
define("WITHOUT_CONTENT_DIV", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Адреса бутиков Editions de Parfums Frederic Malle в городах России, Европы, Америки, Ближнего Востока и Африки и Азии");
$APPLICATION->SetTitle("Магазины");
?>
<div class="content">

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

<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "page",
		"AREA_FILE_SUFFIX" => "text",
		"EDIT_TEMPLATE" => ""
	)
);?><br>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"stores",
	Array(
		"ACTIVE_DATE_FORMAT" => "",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => 3600000,
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => false,
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PANEL" => "Y",
		"DISPLAY_PICTURE" => "",
		"DISPLAY_PREVIEW_TEXT" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => [],
		"FILE_404" => false,
		"FILTER_NAME" => "bannefFilter",
		"GROUP_PERMISSIONS" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => 10,
		"IBLOCK_TYPE" => "info",
		"IBLOCK_URL" => false,
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => 100000,
		"PAGER_BASE_LINK" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "",
		"PAGER_PARAMS_NAME" => "",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_TITLE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => ["*"],
		"SECTION_URL" => false,
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "",
		"SET_TITLE" => "N",
		"SHOW_404" => false,
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "NAME",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC",
		"USE_PERMISSIONS" => "N"
	)
);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
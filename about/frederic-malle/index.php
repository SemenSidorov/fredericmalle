<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Информация о бренде Editions de Parfums Frederic Malle: история бренда и ароматов");
$APPLICATION->SetTitle("ФРЕДЕРИК МАЛЬ");
?><section class="story-title">
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "page",
		"AREA_FILE_SUFFIX" => "story_text",
		"EDIT_TEMPLATE" => ""
	)
);?> <img src="/local/templates/fm/_html/Result/Content/images/story/Signature.png" class="story-title__img" alt=""> <a href="#video"><img src="/local/templates/fm/_html/Result/Content/images/story/Arrow.png" class="story-title__img" alt=""></a> </section> <section class="story-video">
<h2><?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "page",
		"AREA_FILE_SUFFIX" => "story-video-title",
		"EDIT_TEMPLATE" => ""
	)
);?></h2>
<p>
	 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "page",
		"AREA_FILE_SUFFIX" => "story-video-text",
		"EDIT_TEMPLATE" => ""
	)
);?>
</p>
 <!-- Якорь на видео --> <a name="video">
        <?$APPLICATION->IncludeComponent(
	"bitrix:player", 
	"custom", 
	array(
		"ADVANCED_MODE_SETTINGS" => "Y",
		"AUTOSTART" => "N",
		"AUTOSTART_ON_SCROLL" => "N",
		"HEIGHT" => "680",
		"MUTE" => "N",
		"PATH" => "/upload/video/Brand_F_MALLE_ProRes422_MASTER_RU_1_55fps.mp4",
		"PLAYBACK_RATE" => "1",
		"PLAYER_ID" => "fm",
		"PLAYER_TYPE" => "videojs",
		"PRELOAD" => "Y",
		"PREVIEW" => "/upload/photo/FM_falabracks_still-1-1.jpg",
		"REPEAT" => "none",
		"SHOW_CONTROLS" => "Y",
		"SIZE_TYPE" => "fluid",
		"SKIN" => "sublime.css",
		"SKIN_PATH" => "/bitrix/js/fileman/player/videojs/skins",
		"START_TIME" => "0",
		"TYPE" => "",
		"USE_PLAYLIST" => "N",
		"VOLUME" => "90",
		"WIDTH" => "1280",
		"COMPONENT_TEMPLATE" => "custom"
	),
	false
);?>
 </section>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"instagram",
	Array(
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => 360000,
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "N",
		"DISPLAY_PANEL" => "N",
		"FIELD_CODE" => [],
		"IBLOCK_ID" => "12",
		"IBLOCK_TYPE" => "info",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"NEWS_COUNT" => "3",
		"PROPERTY_CODE" => ["LINK"],
		"SET_LAST_MODIFIED" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "NAME",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC"
	),
$component
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogProductsViewedComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */
$this->setFrameMode(true);
$item = $arResult["ITEM"];
$sku = $arResult["SKU"];
$section_text = "";
if ($arParams["SECTION"]["UF_DETAIL_NAME"])
    $section_text = $arParams["SECTION"]["UF_DETAIL_NAME"];

if ($item["COLLECTION"]["PROPERTY_SECOND_TITLE_VALUE"])
    $section_text = $item["COLLECTION"]["PROPERTY_SECOND_TITLE_VALUE"];

$preview_length = 300;
if ($sku["DETAIL_TEXT"] && mb_strlen($sku["DETAIL_TEXT"]) > $preview_length) {
    $sku["PREVIEW_TEXT"] = ELCPreview($sku["DETAIL_TEXT"], $preview_length);
} else {
    $sku["PREVIEW_TEXT"] = "";
}

$url = explode('/', $_SERVER['REQUEST_URI']);
foreach ($url as $u)
{
    if ($u == 'products')
    {
        $APPLICATION->SetPageProperty('description', $arResult['IPROPERTY_VALUES']['ELEMENT_META_DESCRIPTION']);
        $APPLICATION->SetPageProperty('title', $arResult['IPROPERTY_VALUES']['ELEMENT_META_TITLE']);
    }
}
?>
<?
define("SITE_SERVER_PROTOCOL", (CMain::IsHTTPS()) ? "https://" : "http://");
$curPage = $APPLICATION->GetCurPage();
?>
<div id="fm_product">
		<?$APPLICATION->IncludeComponent(
			"ddp:breadcrumb", 
			"castom",
			Array(
				"PATH" => "",	// Путь, для которого будет построена навигационная цепочка (по умолчанию, текущий путь)
				"SITE_ID" => "s1",	// Cайт (устанавливается в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
				"START_FROM" => "0",	// Номер пункта, начиная с которого будет построена навигационная цепочка
			),
			false
		);?>
    <section itemscope itemtype="https://schema.org/Product" class="product">
        <div class="product-block">
            <?if (count($sku["GALLERY"])):?>
            <div class="product-item">
                <div class="product-item__cell">
                    <?foreach ($sku["GALLERY"] as $key=>$photo):?>
                    <img itemprop="image" class="product-item__img <?if ($key == 0):?>product-item__img--active<?endif?>" src="<?=$photo["src"]?>"
                        alt="<?=$sku["NAME"]?>" title="<?=$sku["NAME"]?>">
                    <?endforeach;?>
                </div>
                <div class="product-pagination">
                    <p class="product-pagination__about">Изображения</p>
                    <?foreach ($sku["GALLERY"] as $key=>$photo):?>
                    <?if ($key>0):?><span
                class="product__slash">/</span><?endif?><p class="product-pagination__item <?if ($key == 0):?>product__active<?endif?>"><?=$key+1?></p>
                    <?endforeach;?>
                </div>
            </div>
            <?endif?>
            <div class="product-item">

                <link itemprop="url" href="<?=SITE_SERVER_PROTOCOL . SITE_SERVER_NAME . $curPage?>" />
                <meta itemprop="brand" content="Frederic Malle" />
                <meta itemprop="manufacturer" content="<?=$arResult["ITEM"]["CREATOR"]["NAME"]?>" />
                <meta itemprop="model" content="<?$arParams["SECTION"]["UF_DETAIL_NAME"]?>" />

                <h1 itemprop="name" class="product__subtitle"><?=$sku["NAME"]?></h1>
                <?if ($section_text):?><h2 class="product-item__text"><?=$section_text?></h2><?endif?>
                <div class="product-menu">
                    <p class="product-menu__about">О продукте</p>
                    <? if ($sku["PROPERTY_CONTENT_VALUE"]): ?>
                        <p class="product-menu__item product__active">Описание<span
                                    class="product__slash">/</span></p>
                        <p class="product-menu__item">Состав</p><? endif ?>
                </div>                <div class="product__text-block">
                <div itemprop="description" class="product__text">
                    <?if ($sku["PREVIEW_TEXT"]):?>
                        <p class="product-details-preview"><?=$sku["PREVIEW_TEXT"]?></p>
                        <p class="product-details-inner" style="display: none;">
                            <?=$sku["DETAIL_TEXT"]?>
                        </p>
                        <a href="#" class="product-details-toggle-new">показать больше</a>
                    <?else:?>
                        <p><?=$sku["DETAIL_TEXT"]?></p>
                    <?endif?>
                </div>
                <div class="product__text">
                    <?=$sku["PROPERTY_CONTENT_VALUE"]?>
                </div>
                </div>
                <?if (($arResult["SKU_TYPE"]=="SKU" && count($arResult["ALL_SKU"])) || ($arResult["SKU_TYPE"]!="SKU" && count($arResult["ALL_SKU"])>1)):?>
                <div class="product__select"><?if ($arResult["SKU_TYPE"]=="SKU"):?><?=$sku["PROPERTY_VOL_VALUE"]?><?else:?>Другие коллекции<?endif?>
                    <?if (count($arResult["ALL_SKU"])>1):?>
                    <img class="product__select-arrow" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/arrow1.png" alt="">
                    <div class="product__select-item">
                        <?foreach($arResult["ALL_SKU"] as $allSku):?>
                            <?if ($arResult["SKU_TYPE"]=="SKU"):?>
                                <a class="product__select-item__link <?if ($allSku["ID"] == $sku["ID"]):?>product__select-item__link--active<?endif?>" href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$allSku["PROPERTY_BQSKU_VALUE"]?>"><?=$allSku["PROPERTY_VOL_VALUE"]?></a>
                            <?else:?>
                                <a class="product__select-item__link <?if ($allSku["ID"] == $item["ID"]):?>product__select-item__link--active<?endif?>" href="<?=$allSku["DETAIL_PAGE_URL"]?>"><?=$allSku["NAME"]?></a>
                            <?endif?>
                        <?endforeach;?>
                    </div>
                    <?endif?>
                </div>
                <?endif?>
                <?
                $prices = [];
                foreach ($arResult["SPIDERS"] as $spider => $desc) {
                    if ($sku["PROPERTY_".$desc["PRICE"]."_VALUE"] && $sku["PROPERTY_".$desc["URL"]."_VALUE"] && $sku["PROPERTY_".$desc["STOCK"]."_VALUE"]) {
                    //if ($sku["PROPERTY_".$desc["PRICE"]."_VALUE"] && $sku["PROPERTY_".$desc["URL"]."_VALUE"]) {
                        $prices[] = [
                                "PRICE" => $sku["PROPERTY_".$desc["PRICE"]."_VALUE"],
                                "URL"  => $sku["PROPERTY_".$desc["URL"]."_VALUE"],
                                "DESC" => $desc["DESC"],
                        ];
                    }
                }
                ?>
                <?if (count($prices)):?>
                <h3 class="product__title">Где купить онлайн:</h3>
                <?foreach ($prices as $price):
                        //if (!strstr($_SERVER["HTTP_HOST"],"elc.ddplanet.ru") && $arResult["ITEM"]["ID"] == 3053 && $price["URL"] == "https://goldapple.ru/82403400004-essential-collection-first-encounter-for-women") continue;
                        //if (!strstr($_SERVER["HTTP_HOST"],"elc.ddplanet.ru") && $arResult["ITEM"]["ID"] == 3215 && $price["URL"] == "https://goldapple.ru/82403400003-essential-collection-first-encounter-for-men") continue;
                        ?>
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="product-cell">
                    <p class="product-cell__title"><?=$price["DESC"]?></p>
                    <p itemprop="price" class="product-cell__price"><?=$price["PRICE"]?> Р</p>
                    <meta itemprop="priceCurrency" content="RUB">
                    <button itemprop="availability" href="http://schema.org/InStock" class="product__btn" type="submit" data-url="<?=$price["URL"]?>">Перейти в магазин</button>
                </div>
                <?endforeach;?>

                <?else:?>
                    <div class="product-absence">
                        <button class="product__btn product__btn--absence" type="submit">Уведомить о поступлении</button>
                    </div>
                <?endif?>
            </div>
        </div>
    </section>


    <?$banners = [];
    if ($arResult["ITEM"]["PROPERTY_COLLECTION_VALUE"]  && $arParams["SECTION"]["CODE"] == "perfume"):?>
    <?global $bannefFilter;
    $bannefFilter["PROPERTY_TYPE"] = "4";
    $bannefFilter["PROPERTY_COLLECTION"] = $arResult["ITEM"]["PROPERTY_COLLECTION_VALUE"];
    $banners = $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "banner_card",
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
    <?elseif ($arParams["SECTION"]["CODE"] != "perfume" && $arResult["ITEM"]["IBLOCK_SECTION_ID"]):?>
        <?global $bannefFilter;
        //pre($arResult);
        $bannefFilter["PROPERTY_TYPE"] = "4";
        $bannefFilter["PROPERTY_CATEGORY"] = $arResult["ITEM"]["IBLOCK_SECTION_ID"];
        $banners = $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "banner_card",
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
    <?endif?>
    <?if (count($banners) && $arResult["ITEM"]["PROPERTY_COLLECTION_PROPERTY_QUOTE_VALUE"]):?>
        <section class="quote">
            <p class="quote__text">“<?=$arResult["ITEM"]["PROPERTY_COLLECTION_PROPERTY_QUOTE_VALUE"]?>”</p>
            <?if ($arResult["ITEM"]["PROPERTY_COLLECTION_PROPERTY_QUOTE_AUTHOR_VALUE"]):?>
            <p class="quote__author">- <?=$arResult["ITEM"]["PROPERTY_COLLECTION_PROPERTY_QUOTE_AUTHOR_VALUE"]?></p>
            <?endif?>
        </section>
    <?endif?>
    <?if (count($banners) && $arResult["ITEM"]["PROPERTY_COLLECTION_VALUE"]  && $arParams["SECTION"]["CODE"] == "perfume"):?>
        <?global $bannefFilter;
        $bannefFilter = [];
        $bannefFilter["PROPERTY_TYPE"] = "4";
        $bannefFilter["PROPERTY_COLLECTION"] = $arResult["ITEM"]["PROPERTY_COLLECTION_VALUE"];
        $bannefFilter["!ID"] = $banners;
        $banners = $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "banner_card",
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
                "SECOND_BANNERS" => "Y"
            ),
            $component
        );?>
    <?elseif (count($banners) && $arParams["SECTION"]["CODE"] != "perfume" && $arResult["ITEM"]["IBLOCK_SECTION_ID"]):?>
        <?global $bannefFilter;
        $bannefFilter = [];
        $bannefFilter["PROPERTY_TYPE"] = "4";
        $bannefFilter["PROPERTY_CATEGORY"] = $arResult["ITEM"]["IBLOCK_SECTION_ID"];
        $bannefFilter["!ID"] = $banners;
        $banners = $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "banner_card",
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
                "SECOND_BANNERS" => "Y"
            ),
            $component
        );?>
    <?endif?>
    <?if ($arResult["ITEM"]["COLLECTION"]["PROPERTY_VIDEO_VALUE"]["path"]):?>
        <section class="video-block">
        <h2 class="section__title">НОВЫЙ УРОВЕНЬ ХУДОЖЕСТВЕННОЙ ВЫРАЗИТЕЛЬНОСТИ В ПАРФЮМЕРИИ</h2>
	<?if($arResult["ITEM"]["COLLECTION"]["PROPERTY_PREVIEW_PHOTO_VALUE"]) $preview_photo = CFile::GetPath($arResult["ITEM"]["COLLECTION"]["PROPERTY_PREVIEW_PHOTO_VALUE"]); else $preview_photo = "";?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:player",
        "custom",
        Array(
            "ADVANCED_MODE_SETTINGS" => "Y",
            "AUTOSTART" => "N",
            "AUTOSTART_ON_SCROLL" => "N",
            "HEIGHT" => "680",
            "MUTE" => "N",
            "PATH" => $arResult["ITEM"]["COLLECTION"]["PROPERTY_VIDEO_VALUE"]["path"],
            "PLAYBACK_RATE" => "1",
            "PLAYER_ID" => "fm",
            "PLAYER_TYPE" => "videojs",
            "PRELOAD" => "N",
            "PREVIEW" => $preview_photo,
            "REPEAT" => "none",
            "SHOW_CONTROLS" => "Y",
            "SIZE_TYPE" => "fluid",
            "SKIN" => "sublime.css",
            "SKIN_PATH" => "/bitrix/js/fileman/player/videojs/skins",
            "START_TIME" => "0",
            "VOLUME" => "90",
            "USE_PLAYLIST" => "N",
            "WIDTH" => "1280"
        )
    );?>
        </section>
    <?endif?>
    <?if ($arResult["ITEM"]["CREATOR"]["ID"] && $arParams["SECTION"]["CODE"] == "perfume"):
        $creator = $arResult["ITEM"]["CREATOR"];
        $img = ELCPicture($creator["PREVIEW_PICTURE"], "perf_slider");
        $creator["PREVIEW_TEXT"] = mb_str_replace("<p", "<p class='collaboration__text'", $creator["PREVIEW_TEXT"]);
        $text = str_replace("#QUOTE#", '<p class="collaboration-item__title">Парфюмер</p>', $creator["PREVIEW_TEXT"]);
        ?>
        <section class="collaboration">
            <h2 class="collaboration__title">НОВЫЙ УРОВЕНЬ ХУДОЖЕСТВЕННОЙ ВЫРАЗИТЕЛЬНОСТИ В ПАРФЮМЕРИИ</h2>

            <div class="collaboration-block active_flex">
                <div class="collaboration-item collaboration-item--width">
                    <img class="collaboration__img" src="<?=$img["src"]?>" alt="<?=$creator["NAME"]?>" title="<?=$creator["NAME"]?>">
                </div>
                <div class="collaboration-item collaboration-item_order">
                    <?=$text?>
                    <a href="<?=$creator["DETAIL_PAGE_URL"]?>" class="collaboration__more">Узнайте больше</a>
                </div>


            </div>
        </section>
    <?endif?>
    <?if ($arResult["COLLECTIONS"]):?>
        <section class="spectrum">
            <h2 class="section__title">ОЛЬФАКТИВНАЯ ПИРАМИДА</h2>
            <img class="spectrum__img spectrum__img_mobil" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/singleParfumesNew/pyromide.png" alt="image_pyromide" title="image_pyromide">
            <div class="spectrum-item">
                <svg class="spectrum__img" viewBox="0 0 60 50" xmlns="http://www.w3.org/2000/svg">
                    <polygon fill="#fff" stroke="#000" stroke-width="0.2px" points="0 50, 30 0, 60 50"/>
                </svg>
                <?foreach ($arResult["COLLECTIONS"] as $item):
                    if (!$item["PROPERTY_TRIAN_VALUE"]) continue;?>
                    <a class="spectrum__link <?=$item["PROPERTY_TRIAN_VALUE"]?> <?if ($item["ID"] == $arResult["ITEM"]["PROPERTY_COLLECTION_VALUE"]):?>spectrum__link_active<?endif?>" href="<?=$item["URL"]?>"><?=$item["NAME"]?></a>
                <?endforeach;?>
                <p class="spectrum__text spectrum__text_1">чистый</p>
                <p class="spectrum__text spectrum__text_2">яркий</p>
                <p class="spectrum__text spectrum__text_3">свежий</p>
                <p class="spectrum__text spectrum__text_4">невинный</p>
                <p class="spectrum__text spectrum__text_5">бодрящий</p>
                <p class="spectrum__text spectrum__text_6">нежный</p>
                <p class="spectrum__text spectrum__text_7">комфортный</p>
                <p class="spectrum__text spectrum__text_8">сладкий</p>
                <p class="spectrum__text spectrum__text_9">урбанистичный</p>
                <p class="spectrum__text spectrum__text_10">теплый</p>
                <p class="spectrum__text spectrum__text_11">элегантный</p>
                <p class="spectrum__text spectrum__text_12">чувственный</p>
            </div>
            <a class="spectrum-block__link" href="/products/perfume/">Изучите нашу коллекцию парфюмов</a>
        </section>
    <?endif?>
    <?if ($arResult["ITEM"]["PROPERTY_COLLECTION_VALUE"]):?>
        <?global $bannefFilter;
        $bannefFilter["PROPERTY_COLLECTION"] = $arResult["ITEM"]["PROPERTY_COLLECTION_VALUE"];
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "glossary",
            Array(
                "IBLOCK_TYPE" => "info",
                "IBLOCK_ID" => 9,
                "NEWS_COUNT" => 1,
                "SORT_BY1" => "SORT",
                "SORT_ORDER1" => "ASC",
                "SORT_BY2" => "ID",
                "SORT_ORDER2" => "DESC",
                "FIELD_CODE" => [],
                "PROPERTY_CODE" => [],
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
    <?endif?>
    <?if (count($arResult["ITEM"]["LINK"])):?>
        <section class="collection">
            <h2 class="section__title">ВАМ ТАКЖЕ МОЖЕТ ПОНРАВИТСЯ</h2>
            <div class="collection-content">
                <?foreach ($arResult["ITEM"]["LINK"] as $link):
                    $img = ELCPicture($link["SKU"]["PROPERTY_PROMO_VALUE"][0],"perfume_section", false);
                    ?>
                <div class="collection__item"><a class="collection-item__link" href="<?=$link["DETAIL_PAGE_URL"]?>?sku=<?=$link["SKU"]["PROPERTY_BQSKU_VALUE"]?>">
                        <img class="collection__img" src="<?=$img["src"]?>" alt="<?=$link["SKU"]["NAME"]?>" title="<?=$link["SKU"]["NAME"]?>"></a>
                    <a class="collection-item__link" href="<?=$link["DETAIL_PAGE_URL"]?>?sku=<?=$link["SKU"]["PROPERTY_BQSKU_VALUE"]?>"><h3 class="collection__item-title"><?=$link["NAME"]?></h3></a>
                    <div class="collection__cell">
                                        <span class="collection__text">Категория</span>
                        <div class="collection__cell-item">
                            <a class="collection__link" href="<?=$arResult["SECTIONS"][$link["IBLOCK_SECTION_ID"]]["SECTION_PAGE_URL"]?>"><?=$arResult["SECTIONS"][$link["IBLOCK_SECTION_ID"]]["NAME"]?></a>
                        </div>
                    </div>
                </div>
                <?endforeach;?>
            </div>
        </section>
    <?endif?>
    <?foreach ($arResult["SECTIONS"] as $tmpSect)
        if ($tmpSect["UF_PROMO_IMAGE"])
            $arResult["PROMO_SECTIONS"][] = $tmpSect;?>

    <?if (count($arResult["PROMO_SECTIONS"])):?>
    <?$APPLICATION->IncludeComponent("ddp:section.banners", "", array(
    "IS_SEF" => "Y",
    "IBLOCK_TYPE" => "products",
    "IBLOCK_ID" => 1,
    "CACHE_TYPE" => "A",
    ), false, Array('HIDE_ICONS' => 'Y'));?>
    <?endif?>
    </div>
<?//pre($arResult);?>
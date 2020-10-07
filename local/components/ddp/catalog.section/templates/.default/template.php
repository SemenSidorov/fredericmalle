<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
	$url = explode('/', $_SERVER['REQUEST_URI']);
	$canon_url = explode('?', $_SERVER['REQUEST_URI']);
	foreach($url as $u){
		if($u == 'products' && $canon_url[0] !== '/products/body'){
			if(str_replace(' ', '', $arResult["FOLDER"]['IPROPERTY_VALUES']['SECTION_META_TITLE']) !== '' && $arResult["FOLDER"]['IPROPERTY_VALUES']['SECTION_META_TITLE'] !== ''){
				$APPLICATION->SetPageProperty('description', $arResult["FOLDER"]['IPROPERTY_VALUES']['SECTION_META_DESCRIPTION']);
				$APPLICATION->SetPageProperty('title', $arResult["FOLDER"]['IPROPERTY_VALUES']['SECTION_META_TITLE']);
			}
		}
	}
?>
<?foreach ($arResult["SECTIONS"] as $section):?>
    <?if (!count($arResult["ITEMS_BY_SECTIONS"][$section["ID"]])) continue;?>
    <section class="listing-product">
        <h2 class="listing-product__title"><?=$section["NAME"]?></h2>
        <div class="listing-product-block">
            <?foreach ($arResult["ITEMS_BY_SECTIONS"][$section["ID"]] as $item_id):
                foreach ($arResult["ITEMS"] as $tmpItem)
                    if ($tmpItem["ID"] == $item_id)
                        $item = $tmpItem;
                    $sku = $item["sku"][0];
                    $img = ELCPicture($sku["PROPERTY_PROMO_VALUE"][0],"other_section", false);
                    ?>
            <div class="listing-product-item">
                <a href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>" class="listing-product-item__link">
                    <img src="<?=$img["src"]?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>" class="listing-product__img">
                </a>
                <a href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>" class="listing-product-item__link"><?=$item["NAME"]?></a>
                <p class="listing-product__text"><?=$item["PROPERTY_CREATOR_NAME"]?></p>
            </div>
            <?endforeach;?>
        </div>
    </section>
<?endforeach;?>
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
<?//pre($arResult);?>

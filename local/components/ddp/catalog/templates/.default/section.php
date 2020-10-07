<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
$img = "";
if ($arResult["FOLDER"]["PROPERTY_PHOTO_VALUE"]) {
    $img = ELCPicture($arResult["FOLDER"]["PROPERTY_PHOTO_VALUE"],"section_picture", false);
	$pictMobile = CFile::ResizeImageGet($arResult["FOLDER"]["PROPERTY_PHOTO_VALUE"], array('width' => 1187, 'height' => 1595), BX_RESIZE_IMAGE_EXACT, true);
	$retina = ELCPicture($arResult["FOLDER"]["PROPERTY_PHOTO_VALUE"],"section_picture", true);
    $arResult["SECTION"]["NAME"] = $arResult["FOLDER"]["NAME"];
} elseif ($arResult["SECTION"]["PICTURE"]) {
    $img = ELCPicture($arResult["SECTION"]["PICTURE"], "section_picture", false);
    $retina = ELCPicture($arResult["SECTION"]["PICTURE"], "section_picture", true);
	$pictMobile = CFile::ResizeImageGet($arResult["SECTION"]["PICTURE"], array('width' => 1187, 'height' => 1595), BX_RESIZE_IMAGE_EXACT, true);
} elseif ($arResult["PARENT_PICTURE"]) {
    $img = ELCPicture($arResult["PARENT_PICTURE"], "section_picture", false);
    $retina = ELCPicture($arResult["PARENT_PICTURE"], "section_picture", true);
	$pictMobile = CFile::ResizeImageGet($arResult["PARENT_PICTURE"], array('width' => 1187, 'height' => 1595), BX_RESIZE_IMAGE_EXACT, true);
}
if ($img) {
    ?>
    <section class="promo-product">
<!--        <img class="promo-product__img" src="<?=$img["src"]?>" alt="<?=$arResult["SECTION"]["NAME"]?>">-->
		<picture>
			<source class="promo-product__img" srcset="<?=$pictMobile["src"]?>" media="(max-width: 768px)">
			<source class="promo-product__img" srcset="<?=$retina["src"]?>">
			<img class="promo-product__img" src="<?=$img["src"]?>" alt="<?=$arResult["SECTION"]["NAME"]?>" title="<?=$arResult["SECTION"]["NAME"]?>">
		</picture>
    </section>
    <?
}
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
        "FILTER" => $arResult["FILTER"],
        "FILTER_NAME" => "perfumeFilter",
        'SECT_ID' => $arResult["SECTION"]['ID'],
    ),
    false
);?>

<?
$APPLICATION->IncludeComponent(
    'ddp:catalog.section',
    '',
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
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
if (count($arResult["ITEMS"])){?>
<section class="new-banners">

<?foreach($arResult["ITEMS"] as $key => $item){?>

    <?if(($key % 2) == 0){?>

    <div class="main-promo border-bottom">

    <?}else{?>

    <div class="main-promo border-bottom main-promo--link">

    <?}?>

        <div class="main-promo__content">
            <h3><?=$item["NAME"]?></h3>
            <p><?=$item["PREVIEW_TEXT"]?></p>
            <p><a href="<?=$item["PROPERTIES"]["LINK"]["VALUE"]?>"><?=$item["PROPERTIES"]["BUTTON_NAME"]["VALUE"]?></a></p>
        </div>
        <div class="main-promo__pic">
            <img src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" alt="">
        </div>
    </div>

<?}?>

</section>

<?}?>
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
$item = $arResult;
$img = ELCPicture($item["DETAIL_PICTURE"]["ID"],"perf_detail", false);
?>
<section class="parfumer-promo">
    <div class="parfumer__item">
        <img class="parfumer__img" src="<?=$img["src"]?>" alt="<?=$item["NAME"]?>">
    </div>
    <div class="parfumer__item">
        <h2><?=$item["NAME"]?></h2>
        <?if ($item["PROPERTIES"]["SECOND_TITLE"]["VALUE"]):?><span><?=$item["PROPERTIES"]["SECOND_TITLE"]["VALUE"]?></span><?endif?>
        <?=$item["DETAIL_TEXT"]?>
    </div>
</section>
<?if (false):?>
<section class="parfumer-video">
    <img class="video__img" src="Content/images/special/special-poster.jpg" alt="">
    <video class="video__item" src="Content/video/MASTER_MALLE_PAL_PRORES_SubsEN.mp4" controls
           poster="Content/images/special/special-poster.jpg"></video>
    <p class="parfumer-video__subtitle">Dominique discusses his collaboration with Frédéric</p>
</section>
<?endif?>
<?if (false):?>
<section class="parfumer-product">
    <h2>CARNAL FLOWER</h2>
    <span>the perfume</span>
    <p>An initial impression of innocence, of transparent, flower-shop freshness, disperses to reveal a more lustful tuberose: camphorous notes surrender in turn to ones of milky comfort, enhanced by white musk to draw out tuberose’s aura of lingering sensuality. Carnal Flower contains by far the highest concentration of natural tuberose in the perfume industry.</p>
    <a class="parfumer-product__link" href="#"><img class="parfumer__img" src="Content/images/product/fm_sku_h3xh01_256x31-min.jpg" alt=""></a>
</section>
<?endif?>
<?
//pre($arResult);
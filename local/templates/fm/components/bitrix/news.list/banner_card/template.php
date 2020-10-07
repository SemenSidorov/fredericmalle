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
if (count($arResult["ITEMS"])):?>
    <section class="promo">
        <?if ($arParams["SECOND_BANNERS"]!="Y"):?><h2 class="section__title">НОВЫЙ УРОВЕНЬ ХУДОЖЕСТВЕННОЙ ВЫРАЗИТЕЛЬНОСТИ В ПАРФЮМЕРИИ</h2><?endif?>
        <div class="promo-block hidden-block">
             <?foreach ($arResult["ITEMS"] as $item):?>
            <a href="<?=$item["PROPERTIES"]["LINK"]["VALUE"]?>"><img class="promo__img"
                                                     src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>"></a>
            <?endforeach;?>
        </div>
        <div class="promo-block show-block slider-three">
            <?foreach ($arResult["ITEMS"] as $item):?>
            <a href="<?=$item["PROPERTIES"]["LINK"]["VALUE"]?>"><img class="promo__img"
                                                     src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>"></a>
            <?endforeach;?>
        </div>
    </section>
<?endif;
//pre($arResult);
?>
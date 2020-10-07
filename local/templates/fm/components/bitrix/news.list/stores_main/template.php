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
<section class="stores">

    <h2 class="stores__title">ПОСЕТИТЕ МАГАЗИНЫ ПО ВСЕМУ МИРУ</h2>

    <div class="slider-city">

    <?foreach($arResult["ITEMS"] as $item){?>

        <a class="slider-city__link" href="<?=$item["PROPERTIES"]["LINK"]["VALUE"]?>">
            <picture>
				<?//$img = ELCPicture($item["PREVIEW_PICTURE"]["ID"], "instagram", false);?>
                <source class="slider-city__img" srcset="<?=$item["DETAIL_PICTURE"]["SRC"]?>" media="(max-width: 768px)">
                <source class="slider-city__img" srcset="<?=$item["PREVIEW_PICTURE"]["SRC"]?>">
                <img class="slider-city__img" src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>">
            </picture>
        </a>

    <?}?>

    </div>

</section>

<?}?>
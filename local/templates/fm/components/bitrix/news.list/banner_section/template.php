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
    <section class="discover">

        <h2 class="discover__title">Узнайте больше</h2>

        <div class="discover-item discover-item_min_height">
            <?foreach ($arResult["ITEMS"] as $item):?>
            <a class="discover__link" href="<?=$item["PROPERTIES"]["LINK"]["VALUE"]?>">
                <div class="discover__cell">
                    <img class="discover__img" src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>">
                    <p class="discover__text"><?=$item["PREVIEW_TEXT"]?></p>
                </div>
            </a>
            <?endforeach;?>
        </div>
    </section>
<?endif;
//pre($arResult);
?>
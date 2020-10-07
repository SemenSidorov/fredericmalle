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
    <section class="glossary">
        <div class="glossary-block">
            <?foreach ($arResult["ITEMS"] as $item):?>
            <div class="glossary-item">
                <h2 class="section__title">The Frederic Malle Glossary</h2>
                <?=$item["PREVIEW_TEXT"]?>
                <a href="<?=$item["DETAIL_PAGE_URL"]?>" class="collaboration__more">Узнайте больше</a>
            </div>
            <div class="glossary-item">
                <img src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>" class="glossary__img">
            </div>
            <?endforeach;?>
        </div>
    </section>
<?endif;
//pre($arResult);
?>
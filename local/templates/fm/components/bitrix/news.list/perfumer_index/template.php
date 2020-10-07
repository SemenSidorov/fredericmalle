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
?>




<?if (count($arResult["ITEMS"])):?>

    <section class="collaboration">
    <?$flag = false;foreach ($arResult["ITEMS"] as $item):
        //$img = ELCPicture($item["PREVIEW_PICTURE"]["ID"],"perf_slider", false);
        $item["PREVIEW_TEXT"] = mb_str_replace("<p", "<p class='collaboration__text'", $item["PREVIEW_TEXT"]);
        $item["PREVIEW_TEXT"] = mb_str_replace("#QUOTE#", '<p class="collaboration-item__title">Парфюмер</p>', $item["PREVIEW_TEXT"]);

        ?>

        <div class="collaboration-block <?if (!$flag):?>active_flex<?endif?>">
            <div class="collaboration-item collaboration-item_order">
                <?=$item["PREVIEW_TEXT"]?>
                <a href="<?=$item["DETAIL_PAGE_URL"]?>" class="collaboration__more">Узнайте больше</a>
            </div>
            <div class="collaboration-item collaboration-item--width">
                <div><img class="arrow arrow_prev" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/arrow.png" alt=""></div>
                <img class="story-list__cell-img" src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>">
                <div><img class="arrow arrow_next" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/arrow.png" alt=""></div>
            </div>

        </div>
        <?$flag = true;endforeach;?>
    </section>
<?endif;
//pre($arResult);
?>
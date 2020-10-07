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
    <div class="story-list__block slider-nav">
        <?$flag = false;foreach ($arResult["ITEMS"] as $item):
            $img = ELCPicture($item["PROPERTY_SLIDER_PHOTO_VALUE"],"perf_list", false);
            ?>
        <div class="story-list__block-item">
            <img class="story-list__block-img <?if (!$flag):?>active_img<?endif?>" src="<?=$img["src"]?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>">
            <div class="story-list__block-desc" >
                <p><?=$item["NAME"]?></p>
            </div>
        </div>
        <?$flag = true;endforeach;?>
    </div>
    <div class="slider-for">
        <?$flag = false;foreach ($arResult["ITEMS"] as $item):
        $img = ELCPicture($item["PREVIEW_PICTURE"]["ID"],"perf_slider", false);
        if ($item["PROPERTY_QUOTE_AUTHOR_VALUE"]) 
            $author = $item["PROPERTY_QUOTE_AUTHOR_VALUE"]; 
        else 
            $author = $item["NAME"];
        if ($item["PROPERTY_QUOTE_VALUE"])
            $quote = '<q>'.$item["PROPERTY_QUOTE_VALUE"].'</q>
                                <span> – '.$author.'</span>';
        else
            $quote = '';

        $item["PREVIEW_TEXT"] = mb_str_replace("#QUOTE#", $quote, $item["PREVIEW_TEXT"]);
        ?>

            <div class="story-list__item">
                <div class="story-list__content">
                    <div class="story-list__cell">
                        <?=$item["PREVIEW_TEXT"]?>
                        <a href="<?=$item["DETAIL_PAGE_URL"]?>" class="collaboration__more">Узнайте больше</a>
                    </div>
                    <div class="story-list__cell">
                        <div><img class="arrow arrow_prev" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/arrow.png" alt=""></div>
                        <img class="story-list__cell-img" src="<?=$img["src"]?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>">
                        <div><img class="arrow arrow_next" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/arrow.png" alt=""></div>
                    </div>
                </div>
            </div>

        <?$flag = true;endforeach;?>
    </div>
<?endif;
//pre($arResult);
?>
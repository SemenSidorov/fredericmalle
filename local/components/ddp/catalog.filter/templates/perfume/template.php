<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<div class="listing-nav">
    <?if (count($arResult["FILTER"]["COLLECTION"]["ITEMS"])):?>
    <div class="listing-nav__block">
        <p class="listing-nav__text"><?if ($arResult["FILTER"]["COLLECTION"]["SELECTED"]):?><?=$arResult["FILTER"]["COLLECTION"]["SELECTED"]["NAME"]?><?else:?>Коллекция<?endif?><img class="listing-nav__arrow" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/arrow1.png" alt=""></p>
        <div class="listing-perfume listing-more">
            <?if ($arResult["FILTER"]["COLLECTION"]["SELECTED"]):?>
                <a href="<?=$arParams["MAIN_FOLDER"]?>">Все</a>
            <?endif?>
            <?foreach ($arResult["FILTER"]["COLLECTION"]["ITEMS"] as $item):?>
                <a href="<?=$item["DETAIL_PAGE_URL"]?>"><?=$item["NAME"]?></a>
            <?endforeach;?>
        </div>
    </div>
    <?endif?>
    <?if (count($arResult["FILTER"]["NOTE"]["ITEMS"])):?>
    <div class="listing-nav__block">
        <p class="listing-nav__text"><?if ($arResult["FILTER"]["NOTE"]["SELECTED"]):?><?=$arResult["FILTER"]["NOTE"]["SELECTED"]["NAME"]?><?else:?>Ольфактивная категория<?endif?><img class="listing-nav__arrow" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/arrow1.png" alt=""></p>
        <div class="listing-category listing-more" >
            <?if ($arResult["FILTER"]["NOTE"]["SELECTED"]):?>
                <a href="<?=$arParams["MAIN_FOLDER"]?>">Все</a>
            <?endif?>
            <?foreach ($arResult["FILTER"]["NOTE"]["ITEMS"] as $item):?>
                <a href="<?=$item["DETAIL_PAGE_URL"]?>"><?=$item["NAME"]?></a>
            <?endforeach;?>
        </div>
    </div>
    <?endif?>
    <?if (count($arResult["FILTER"]["SIZE"]["ITEMS"])):?>
    <div class="listing-nav__block">
        <p class="listing-nav__text"><?if ($arResult["FILTER"]["SIZE"]["SELECTED"]):?><?=$arResult["FILTER"]["SIZE"]["SELECTED"]["PROPERTY_VOL_VALUE"]?><?else:?>Объем<?endif?><img class="listing-nav__arrow" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/arrow1.png" alt=""></p>
        <div class="listing-size listing-more">
            <?if ($arResult["FILTER"]["SIZE"]["SELECTED"]):?>
               <a href="<?=$arParams["MAIN_FOLDER"]?>">Все</a>
            <?endif?>
            <?foreach ($arResult["FILTER"]["SIZE"]["ITEMS"] as $item):?>
                <a href="<?=$item["DETAIL_PAGE_URL"]?>"><?=$item["PROPERTY_VOL_VALUE"]?></a>
            <?endforeach;?>
        </div>
    </div>
    <?endif?>
</div>
<?//pre($arResult)?>
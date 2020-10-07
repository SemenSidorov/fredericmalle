<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if(!empty($arResult["CATALOG"])):?>
    <!-- Быстрые результаты поиска -->
    <div class="header-search__cell">
    <?$i = 0;foreach ($arResult["CATALOG"] as $item):$i++;
        if ($i > 5) continue;
        $img = ELCPicture($item["sku"][0]["PROPERTY_PROMO_VALUE"][0],"search", false);
        ?>
        <div class="collection__item">
            <a class="collection-item__link collection-item__link--height" href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$item["sku"][0]["PROPERTY_BQSKU_VALUE"]?>">
                <img class="collection__img" src="<?=$img["src"]?>" alt="<?=$item["NAME"]?>">
            </a>
            <a class="collection-item__link" href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$item["sku"][0]["PROPERTY_BQSKU_VALUE"]?>">
                <h3 class="collection__item-title"><?=($item["SECTION_NAME"])? $item["SECTION_NAME"]:$item["NAME"]?></h3>
            </a>
        </div>
    <?endforeach?>
    </div>
    <div class="header-search__block">
        <a class="header-search__link" href="/search/?q=<?=$arResult["query"]?>">Показать все (<span class="header-search__number"><?=count($arResult["CATALOG"])?></span>)</a>
    </div>
<?endif;?>
<?//pre($arResult)?>


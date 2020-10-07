<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="header-search__block">
    <form action="/search/" method="GET">
        <input type="search" id="search" placeholder="ПОИСК (ПО ПАРФЮМУ, КАТЕГОРИИ, ПАРФЮМЕРУ, КОЛЛАБОРАЦИИ...)" name="q" value="<?=$arResult["REQUEST"]["QUERY"]?>">
        <img class="header-seach__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/close.svg" alt="">
    </form>
</div>
<section class="search-title">
    <h2 class="search__title">Результаты поиска</h2>
    <?/*<span class="search__result">16 results for rose</span>*/?>
</section>

<section class="search-content">
    <?if (is_array($arResult["PRODUCTS"]) && count($arResult["PRODUCTS"] )):?>
        <?foreach ($arResult["PRODUCTS"] as $item):
            $sku = $item["sku"][0];
            $img = ELCPicture($sku["PROPERTY_PROMO_VALUE"][0],"other_section", false);

            ?>
        <div class="listing-product-block">
            <div class="listing-product-item">
                <a href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>" class="listing-product-item__link listing-product-item__link--height"><img src="<?=$img["src"]?>" alt="<?=$item["NAME"]?>" class="listing-product__img"></a>
                <a href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>" class="listing-product-item__link"><?=$item["NAME"]?></a>
                <p class="listing-product__text"><?=$item["PROPERTY_CREATOR_NAME"]?></p>
            </div>
        </div>
        <?endforeach;?>
    <?else:?>
        <div class="search-fail">
            По запросу "<?=htmlspecialchars($_REQUEST["q"])?>" ничего не найдено.  Мы предлагаем вам проверить текст на наличие опечаток, расширить поисковый запрос и ввести тип продукта, который вам нужен.
        </div>
    <?endif?>
</section>

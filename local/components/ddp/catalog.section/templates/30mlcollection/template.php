<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?if (count($arResult["ITEMS"])):?>
    <section class="listing-product collection-list">
        <h2 class="listing-product__title">Коллекция 30мл</h2>
        <div class="listing-product-block">
    <?foreach ($arResult["ITEMS"] as $item):
        $sku = $item["sku"][0];
        if ($sku):
            $img = ELCPicture($sku["PROPERTY_PROMO_VALUE"][0],"promo_section", false);
            //$imgRetina = ELCPicture($sku["PREVIEW_PICTURE"],"perfume_section", true);
            ?>
            <div class="listing-product-item">
                <a href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>" class="listing-product-item__link"><img src="<?=$img['src']?>" alt="<?=$item["NAME"]?>" class="listing-product__img" title="<?=$item["NAME"]?>"></a>
                <a href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>" class="listing-product-item__link"><?=$item["NAME"]?></a>
                <p class="listing-product__text"><?=$item["PROPERTY_CREATOR_NAME"]?></p>
            </div>
        <?endif?>
    <?endforeach;?>
        </div>
</section>
<?endif?>
<?//pre($arResult)?>

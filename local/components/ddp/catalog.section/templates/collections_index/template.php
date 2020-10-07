<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?if (count($arResult["ITEMS"])):?>
    <section class="collection hidden-block">
        <h2 class="collection__title"><?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "page",
                    "AREA_FILE_SUFFIX" => "collections_title",
                    "EDIT_TEMPLATE" => ""
                )
            );?></h2>
        <div class="collection-block ">
    <?foreach ($arResult["ITEMS"] as $item):
        $sku = $item["sku"][0];
        if ($sku):
            $img = ELCPicture($arParams["COL_PHOTOS"][$item["PROPERTY_COLLECTION_VALUE"]],"index_collections", false);
            //$imgRetina = ELCPicture($sku["PREVIEW_PICTURE"],"perfume_section", true);
            ?>
            <div class="collection__item"><a class="collection-item__link" href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>">
                    <img class="collection__img collection__img--no_width" src="<?=$img['src']?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>"></a>
                <a class="collection-item__link" href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>"><h3 class="collection__item-title"><?=$item["NAME"]?></h3></a>
                <div class="collection__cell">
				                <span class="collection__text">Категория</span>
                    <div class="collection__cell-item">
                        <?$flag = false;foreach ($arResult["SECTIONS"] as $section):?>
                            <?if (!$item["COLLECTIONS"][$section["ID"]]) continue;?>
                            <?if ($flag):?>/
                            <?endif?><a class="collection__link" href="<?=$item["COLLECTIONS"][$section["ID"]]["DETAIL_PAGE_URL"]?>"><?=$section["NAME"]?></a>
                            <?$flag = true;endforeach;?>
                    </div>
                </div>
                <?/*<a class="collection__textbelow" href="#">Explore the world</a>*/?>
            </div>
        <?endif?>
    <?endforeach;?>
        </div>
    </section>
<?endif?>
<?if (count($arResult["ITEMS"])):?>
    <section class="collection show-block">
        <h2 class="collection__title"><?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "page",
                    "AREA_FILE_SUFFIX" => "collections_title",
                    "EDIT_TEMPLATE" => ""
                )
            );?></h2>
        <div class=" slider-three collection-block">
            <?foreach ($arResult["ITEMS"] as $item):
                $sku = $item["sku"][0];
                if ($sku):
                    $img = ELCPicture($arParams["COL_PHOTOS"][$item["PROPERTY_COLLECTION_VALUE"]],"index_collections", false);
                    //$imgRetina = ELCPicture($sku["PREVIEW_PICTURE"],"perfume_section", true);
                    ?>
                    <div class="collection__item"><a class="collection-item__link" href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>">
                            <img class="collection__img" src="<?=$img['src']?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>"></a>
                        <a class="collection-item__link" href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>"><h3 class="collection__item-title"><?=$item["NAME"]?></h3></a>
                        <div class="collection__cell">
				                <span class="collection__text">Категория</span>
                            <div class="collection__cell-item">
                                <?$flag = false;foreach ($arResult["SECTIONS"] as $section):?>
                                    <?if (!$item["COLLECTIONS"][$section["ID"]]) continue;?>
                                    <?if ($flag):?>/
                                    <?endif?><a class="collection__link" href="<?=$item["COLLECTIONS"][$section["ID"]]["DETAIL_PAGE_URL"]?>"><?=$section["NAME"]?></a>
                                    <?$flag = true;endforeach;?>
                            </div>
                        </div>
                        <?/*<a class="collection__textbelow" href="#">Explore the world</a>*/?>
                    </div>
                <?endif?>
            <?endforeach;?>
        </div>
    </section>
<?endif?>

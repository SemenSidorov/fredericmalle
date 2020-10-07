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
$this->setFrameMode(true);?>
<?if (!$arParams["FULL_RESULT"]):
    $INPUT_ID = trim($arParams["~INPUT_ID"]);
    if(strlen($INPUT_ID) <= 0)
        $INPUT_ID = "title-search-input";
    $INPUT_ID = CUtil::JSEscape($INPUT_ID);

    $CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
    if(strlen($CONTAINER_ID) <= 0)
        $CONTAINER_ID = "title-search";
    $CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

    if($arParams["SHOW_INPUT"] !== "N"):?>
        <div class="header-search__drop">
            <div class="header-search__block">
                <form action="<?echo $arResult["FORM_ACTION"]?>">
                    <input type="search" id="<?echo $INPUT_ID?>" name="q" placeholder="ПОИСК (ПО ПАРФЮМУ, КАТЕГОРИИ, ПАРФЮМЕРУ, КОЛЛАБОРАЦИИ...)">
                    <img class="header-seach__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/close.svg" alt="">
                </form>
            </div>
            <div id="<?echo $CONTAINER_ID?>"></div>
            <div class="header-search__item"></div>
        </div>
    <?endif?>
    <script>
        BX.ready(function(){
            new JCTitleSearch({
                'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
                'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
                'INPUT_ID': '<?echo $INPUT_ID?>',
                'MIN_QUERY_LEN': 2
            });
        });
    </script>
<?else:?>
    <?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
    <div class="header-search__block">
        <form action="/search/" method="GET">
            <input type="search" id="search" placeholder="ПОИСК (ПО ПАРФЮМУ, КАТЕГОРИИ, ПАРФЮМЕРУ, КОЛЛАБОРАЦИИ...)" name="q" value="<?=$arResult["query"]?>">
            <img class="header-seach__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/close.svg" alt="">
        </form>
    </div>
    <section class="search-title">
        <h2 class="search__title">Результаты поиска</h2>
        <?/*<span class="search__result">16 results for rose</span>*/?>
    </section>

    <section class="search-content">
        <?if (is_array($arResult["CATALOG"]) && count($arResult["CATALOG"] )):?>
            <?foreach ($arResult["CATALOG"] as $item):
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
<?endif?>
<?php
//pre($arResult)
    ?>

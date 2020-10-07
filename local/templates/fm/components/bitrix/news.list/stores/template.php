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

    <div class="b-center">
        <section class="content">
            <section class="tabs-row hidden-block">
                <?$flag = false;foreach ($arResult["SECTIONS_FIRST"] as $sect):?>
                <div class="tabs-row__btn <?if (!$flag):?>active<?endif?>"><?=$sect["NAME"]?></div>
                <?$flag = true; endforeach;?>
            </section>
            <div class="tabs-row__content hidden-block">
                <div class="compound-content">
                    <div class="compound-content__aside">
                        <?$flag = false;foreach ($arResult["SECTIONS_FIRST"] as $sect):?>
                        <ul class="tabs-caption <?if (!$flag):?>active<?endif?>">
                            <?foreach ($arResult["SECTIONS_SECOND"][$sect["ID"]] as $newSect):?>
                            <li class="tabs-caption__btn <?if (!$flag):?>active<?endif?>" id="sec_<?=$newSect["ID"]?>"><?=$newSect["NAME"]?></li>
                            <?$flag = true; endforeach;?>
                        </ul>
                        <?endforeach;?>
                    </div>
                    <div class="compound-content__main">
                        <div class="tabs__caption-content">
                            <?$flag = false;foreach ($arResult["SECTIONS_FIRST"] as $sect):?>
                            <?foreach ($arResult["SECTIONS_SECOND"][$sect["ID"]] as $newSect):?>
                            <div class="address-group <?if (!$flag):?>active_flex<?endif?>" id="sec_<?=$newSect["ID"]?> List">
                                <?foreach ($arResult["ITEMS_BY_SECTIONS"][$newSect["ID"]]["CITIES"] as $city):?>
                                    <?foreach ($arResult["ITEMS_BY_SECTIONS"][$newSect["ID"]]["ITEMS"][$city] as $item):?>
                                    <div itemscope itemtype="http://schema.org/Organization" class="address">
                                        <h3  class="address__row address--title"><?=$item["NAME"]?></h3>
                                        <div itemprop="name" class="address__row address--name"><?=$item["PROPERTIES"]["STORE"]["VALUE"]?></div>
                                        <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" class="address__row address--street">
                                            <span itemprop="addressLocality"><?=$item["PROPERTIES"]["CITY"]["VALUE"]?></span>
                                            <span itemprop="streetAddress"><?=str_replace($item["PROPERTIES"]["CITY"]["VALUE"], '', $item["PROPERTIES"]["ADDRESS"]["VALUE"])?></span>
                                        </div>
                                        <div class="address__row address--post"><?=$item["PROPERTIES"]["CITY"]["VALUE"]?>, <?=$item["PROPERTIES"]["INDEX"]["VALUE"]?></div>
                                        <div itemprop="telephone" class="address__row address--phone"><?=$item["PROPERTIES"]["PHONE"]["VALUE"]?></div>
                                        <?if ($item["PROPERTIES"]["MAP"]["VALUE"]):?>
                                            <div class="address__row address--help">
                                                <a class="tabs__link" href="<?=$item["PROPERTIES"]["MAP"]["VALUE"]?>" rel="noreferrer" target="_blank">Смотреть на карте</a>
                                            </div>
                                        <?endif?>
                                    </div>
                                    <?endforeach?>
                                <?endforeach;?>
                            </div>
                            <?$flag = true; endforeach;?>
                            <?endforeach;?>
                        </div>
                    </div> <!-- compound-content__main -->
                </div><!-- compound-content -->
            </div><!-- tabs-row__content -->
        </section>
    </div>


    <div class="stores-select show-block">
        <p class="stores-select__text"><?=$arResult["SECTIONS_SECOND"]["ALL"][0]["NAME"]?></p>
        <img class="stores-select__arrow" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/select-arrow.png" alt="">
        <div class="stores-select__list">
            <?$flag = false;foreach ($arResult["SECTIONS_FIRST"] as $sect):?>
                <p class="stores-select__region"><?=$sect["NAME"]?></p>
                <?foreach ($arResult["SECTIONS_SECOND"][$sect["ID"]] as $newSect):?>
                    <a href="#" class="stores-select__link"><?=$newSect["NAME"]?></a>
                <?endforeach;?>
            <?endforeach;?>
        </div>
    </div>

    <?$flag = false;foreach ($arResult["SECTIONS_FIRST"] as $sect):?>
        <?foreach ($arResult["SECTIONS_SECOND"][$sect["ID"]] as $newSect):?>
        <section class="page-stores-list">
            <?foreach ($arResult["ITEMS_BY_SECTIONS"][$newSect["ID"]]["CITIES"] as $city):?>

                <div itemscope itemtype="http://schema.org/Organization" class="page-stores__item">
                    <a class="page-stores__link" href="#"><h3  class="address__row address--title"><?=$city?></h3></a>
                    <?foreach ($arResult["ITEMS_BY_SECTIONS"][$newSect["ID"]]["ITEMS"][$city] as $item):?>
                    <div class="page-stores__cell">
                        <div itemprop="name" class="address__row address--name"><?=$item["PROPERTIES"]["STORE"]["VALUE"]?></div>
                        <div itemprop="address" class="address__row address--street">
                            <span itemprop="addressLocality"><?=$item["PROPERTIES"]["CITY"]["VALUE"]?></span>
                            <span itemprop="streetAddress"><?=str_replace($item["PROPERTIES"]["CITY"]["VALUE"], '', $item["PROPERTIES"]["ADDRESS"]["VALUE"])?></span>
                        </div>
                        <div class="address__row address--post"><?=$item["PROPERTIES"]["CITY"]["VALUE"]?>, <?=$item["PROPERTIES"]["INDEX"]["VALUE"]?></div>
                        <div itemprop="telephone" class="address__row address--phone">TEL: <?=$item["PROPERTIES"]["PHONE"]["VALUE"]?></div>
                        <?if ($item["PROPERTIES"]["MAP"]["VALUE"]):?>
                            <div class="address__row address--help">
                                <a class="tabs__link" href="<?=$item["PROPERTIES"]["MAP"]["VALUE"]?>" onclick="window.open('<?=$item["PROPERTIES"]["MAP"]["VALUE"]?>')" rel="noreferrer" target="_blank">Смотреть на карте</a>
                            </div>
                        <?endif?>
                    </div>
                    <?endforeach?>
                </div>

             <?endforeach;?>
        </section>
        <?endforeach;?>
    <?endforeach;?>
<?endif;
//pre($arResult);
?>
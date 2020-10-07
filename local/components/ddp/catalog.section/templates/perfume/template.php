<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?if (count($arResult["ITEMS"])):?>
<?if(strpos($_SERVER['REQUEST_URI'], '/about/perfumer/') !== false){?>
    <section class="parfumer-list">
        <h2 class="parfumer__title"><?=$APPLICATION->GetTitle()?> ПРЕДСТАВЛЯЕТ</h2>
<?}?>
<section class="listing-collection listing-collection--slider">
    <div class="listing-rows">столбцы
        <span class="listing-rows__number-3 active__number">3</span>
        <span class="listing-rows__number-6">6</span>
    </div>
<?
	$url = explode('/', $_SERVER['REQUEST_URI']);
	foreach($url as $u){
		if($u == 'products'){
			$APPLICATION->SetPageProperty('description', $arResult['IPROPERTY_VALUES']['SECTION_META_DESCRIPTION']);
			$APPLICATION->SetPageProperty('title', $arResult['IPROPERTY_VALUES']['SECTION_META_TITLE']);
		}
	}
?>
    <?foreach ($arResult["ITEMS"] as $item):

        $sku = $item["sku"][0];
        
        if ($sku):
            $img = ELCPicture($sku["PROPERTY_PROMO_VALUE"][0],"other_section", false);
            //$imgRetina = ELCPicture($sku["PREVIEW_PICTURE"],"perfume_section", true);
            ?>
        <div class="collection__item">
            <a class="collection-item__link collection-item__link--height" href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>">
                <img class="collection__img collection__img--active" src="<?=$img['src']?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>">
            </a>
            <a class="collection-item__link" href="<?=$item["DETAIL_PAGE_URL"]?>?sku=<?=$sku["PROPERTY_BQSKU_VALUE"]?>"><h3 class="collection__item-title"><?=$item["NAME"]?></h3></a>
            <div class="collection__cell">
                <?if (count($item["COLLECTIONS"])):?>
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
                <?endif?>
            </div>
        </div></a>
        <?endif?>
    <?endforeach;?>
</section>
<?endif?>
<?//pre($arResult)?>

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
        <?foreach ($arResult["ITEMS"] as $item):
            //$img = ELCPicture($item["PREVIEW_PICTURE"],"perf_list", false);
            ?>
        <section class="preview transparency">
            <a href="<?=$item["PROPERTIES"]["LINK"]["VALUE"]?>">
                <picture>
                    <source class="preview__img" srcset="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" media="(min-width: 1921px)">
                    <source class="preview__img" srcset="<?=$item["DETAIL_PICTURE"]["SRC"]?>" media="(max-width: 768px)">
                    <source class="preview__img" srcset="<?=$item["PREVIEW_PICTURE"]["SRC"]?>">
                    <img class="preview__img" src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>">
                </picture>
            </a>
        </section>
        <?endforeach;?>
<?endif;
//pre($arResult);
?>
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
    <div class="story-list__block slider-parfumers">
        <?foreach ($arResult["ITEMS"] as $item):
            $img = ELCPicture($item["PREVIEW_PICTURE"],"perf_list", false);
            ?>
        <div class="story-list__block-item">
            <img class="story-list__block-img active_img" src="<?=$img["src"]?>" alt="<?=$item["NAME"]?>">
            <p class="story-list__block-desc" ><?=$item["NAME"]?></p>
        </div>
        <?endforeach;?>
    </div>
<?endif;
pre($arResult);
?>
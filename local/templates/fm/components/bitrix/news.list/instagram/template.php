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

<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"EDIT_TEMPLATE" => "",
		"PATH" => "/local/templates/fm/components/bitrix/news.list/instagram/index_instagram_title.php"
	)
);?>
    <section class="story-follow">
 

        <div class="story-follow__block">
            <?foreach ($arResult["ITEMS"] as $item):
                $img = ELCPicture($item["PREVIEW_PICTURE"]["ID"],"instagram", false);
                ?>
                <a class="story-follow__block-link" href="https://www.instagram.com/fredericmalle/" target="_blank" rel="nofollow"><img src="<?=$img["src"]?>" class="story-follow__img"  alt="<?=$item["NAME"]?>" title="<?=$item["NAME"]?>"></a>
            <?endforeach;?>
        </div>
    </section>
<?endif;
//pre($arResult);
?>
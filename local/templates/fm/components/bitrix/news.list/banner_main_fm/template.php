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
?>

<?if(count($arResult["ITEMS"][0])){?>
<?$item = $arResult["ITEMS"][0]?>
<section class="collaboration">
	<h2 class="collaboration__title"><?=$item["NAME"]?></h2>
	<div class="collaboration-frederic">
		<div class="collaboration-item collaboration-item--width">
			<img class="collaboration__img" src="<?=$item["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$item["NAME"]?>">
		</div>
		<div class="collaboration-item">
			<p class="collaboration__text"><?=$item["PREVIEW_TEXT"]?></p>
			<p class="collaboration-item__title">Парфюмер</p>
			<p class="collaboration__text"><?=$item["DETAIL_TEXT"]?></p>
			<a href="/about/" class="collaboration__more">УЗНАЙТЕ БОЛЬШЕ</a>
		</div>
	</div>
</section>
<?}?>
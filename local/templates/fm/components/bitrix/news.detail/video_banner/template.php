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

<!--test_div<?print_r($arResult);?>-->

<section class="event-video">
    <video src="<?=$arResult["DISPLAY_PROPERTIES"]["VIDEO"]["FILE_VALUE"]["SRC"]?>" autoplay loop muted width="100%"></video>
</section>
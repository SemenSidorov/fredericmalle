<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>

<?if (count($arResult)){?>
<section class="banner">
    <h2><a class="banner__link" href="<?=$arResult[0]["URL"]?>"><?=$arResult[0]["PREVIEW_TEXT"]?></a></h2>
	<a class="banner__link" href="<?=$arResult[0]["URL"]?>">
        <picture>
            <source class="banner__img" srcset="<?=$arResult[0]["DETAIL_PICTURE"]?>" media="(max-width: 768px)">
            <source class="banner__img" srcset="<?=$arResult[0]["PREVIEW_PICTURE"]?>" alt="<?=$arResult[0]["NAME"]?>" title="<?=$arResult[0]["NAME"]?>">
            <img class="banner__img" src="<?=$arResult[0]["PREVIEW_PICTURE"]?>" alt="<?=$arResult[0]["NAME"]?>" title="<?=$arResult[0]["NAME"]?>">
        </picture>
    </a>
</section>
<?}?>

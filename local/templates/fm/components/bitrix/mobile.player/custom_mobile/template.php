<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

?><div class="disk-mobile-player-container">
	<video preload="metadata" style="width: 100%; height: auto;"<?
if(isset($arParams['PLAYER_ID']))
{
	?> id="<?=htmlspecialcharsbx($arParams['PLAYER_ID']);?>"<?
}
?> poster="<?=htmlspecialcharsbx($arParams['PREVIEW']);?>"<?
?> controls controlsList="nodownload"><?
if($arParams['USE_PLAYLIST_AS_SOURCES'] === 'Y' && is_array($arParams['TRACKS']))
{
	foreach($arParams['TRACKS'] as $key => $source)
	{
		?>
		<source src="<?=htmlspecialcharsbx($source['src']);?>" type="<?=htmlspecialcharsbx($source['type']);?>"
        onerror="BX.onCustomEvent(this, 'MobilePlayer:onError', [this.parentNode, this.src]);">
		<?
	}
}
else
{
	?>
	<source src="<?=htmlspecialcharsbx($arParams['PATH']);?>" type="<?=htmlspecialcharsbx($arParams['TYPE']);?>"
    onerror="BX.onCustomEvent(this, 'MobilePlayer:onError', [this.parentNode, this.src]);"
	><?
}
?>
	</video>
</div>
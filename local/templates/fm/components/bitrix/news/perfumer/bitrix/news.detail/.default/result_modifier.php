<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
	$img = ELCPicture($arResult["DETAIL_PICTURE"]["ID"],"perf_detail", false);

    $arResult['OGImage'] = $img["src"];
    
    $cp = $this->__component; // объект компонента
    
    if (is_object($cp))
    {
       // добавим в arResult компонента поля
       $cp->SetResultCacheKeys(array('OGImage'));
    }
?>
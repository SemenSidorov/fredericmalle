<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<? if(strpos($_SERVER['REQUEST_URI'], '/detail/') === false && strpos($_SERVER['REQUEST_URI'], '/products/') !== false){
		$APPLICATION->SetPageProperty("OGImage", $arResult['OGImage']);
	}
?>
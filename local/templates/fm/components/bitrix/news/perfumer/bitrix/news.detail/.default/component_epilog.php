<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<? if(strpos($_SERVER['REQUEST_URI'], '/about/perfumer/') !== false){
		$APPLICATION->SetPageProperty("OGImage", $arResult['OGImage']);
	}
?>
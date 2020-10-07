<?
$image_social = CFile::ResizeImageGet($arResult["SKU"]["GALLERY"][0], array('width'=>'1200', 'height'=>'630'), BX_RESIZE_IMAGE_EXACT, true);
$arResult["SKU"]["GALLERY"][0]["SOCIAL"] = $image_social["src"];

$this->__component->SetResultCacheKeys(array(
	"SKU"
));
?>
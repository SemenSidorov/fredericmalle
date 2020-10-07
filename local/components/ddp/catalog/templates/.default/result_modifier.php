<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
    if ($arResult["FOLDER"]["PROPERTY_PHOTO_VALUE"]) {
        $img = ELCPicture($arResult["FOLDER"]["PROPERTY_PHOTO_VALUE"],"section_picture", false);
    } elseif ($arResult["SECTION"]["PICTURE"]) {
        $img = ELCPicture($arResult["SECTION"]["PICTURE"], "section_picture", false);
    }
	if(empty($img["src"])){
		$nav = CIBlockSection::GetNavChain(1, $arResult["SECTION"]["ID"]);
		if($sect_id = $nav->GetNext()){
			$rsFile = CFile::GetByID($sect_id["PICTURE"]);
			$img = $rsFile->GetNext();
			$img["src"] = '/upload/'.$img["SUBDIR"]."/".$img["FILE_NAME"];
			$arResult["PARENT_PICTURE"] = $sect_id["PICTURE"];
		}
	}
    $arResult['OGImage'] = $img["src"];

    $cp = $this->__component; // объект компонента
    
    if (is_object($cp))
    {
       // добавим в arResult компонента поля
       $cp->SetResultCacheKeys(array('OGImage'));
       $cp->SetResultCacheKeys(array('PARENT_PICTURE'));
    }
?>
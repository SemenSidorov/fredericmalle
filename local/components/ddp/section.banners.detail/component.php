<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

if($this->StartResultCache())
{
	if(!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
	}
	else
	{
		$arFilter = array(
			"ID"=>$arParams["ID"],
			"IBLOCK_ID"=>$arParams["IBLOCK_ID"],
			"ACTIVE"=>"Y"
		);

		$Elements = CIBlockElement::GetList(array(), $arFilter, false, false, array(
			"ID",
			"NAME",
			"PREVIEW_TEXT",
			"PREVIEW_PICTURE"
		));
		while($Elem = $Elements->GetNext())
		{//pre($arSection);
			$url = CIBlockElement::GetProperty($arParams["IBLOCK_ID"], $arParams["ID"], array(), Array("CODE"=>"LINK"));
			$url1 = $url->GetNext();
			$arResult[] = array(
				"ID" => $Elem["ID"],
				"NAME" => $Elem["NAME"],
				"URL" => $url1["VALUE"],
				"PREVIEW_TEXT" => $Elem["PREVIEW_TEXT"],
				"PREVIEW_PICTURE" => CFile::GetPath($Elem["PREVIEW_PICTURE"])
			);
		}
		$this->includeComponentTemplate();
		$this->EndResultCache();
	}
}

?>

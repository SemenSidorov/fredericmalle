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

$arParams["ID"] = intval($arParams["ID"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);

$arParams["DEPTH_LEVEL"] = intval($arParams["DEPTH_LEVEL"]);
if($arParams["DEPTH_LEVEL"]<=0)
	$arParams["DEPTH_LEVEL"]=1;

$arResult["SECTIONS"] = array();
$arResult["ELEMENT_LINKS"] = array();

if($this->StartResultCache())
{
	if(!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
	}
	else
	{
		$arFilter = array(
			"IBLOCK_ID"=>$arParams["IBLOCK_ID"],
			"GLOBAL_ACTIVE"=>"Y",
			"IBLOCK_ACTIVE"=>"Y",
			"<="."DEPTH_LEVEL" => $arParams["DEPTH_LEVEL"],
		);
		$arOrder = array(
			"left_margin"=>"asc",
		);

		$rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, array(
			"ID",
			"DEPTH_LEVEL",
			"NAME",
			"CODE",
			"UF_SHORT_NAME",
			"UF_PROMO_IMAGE",
			"SECTION_PAGE_URL",
		));
		if($arParams["IS_SEF"] !== "Y")
			$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
		else
			$rsSections->SetUrlTemplates("", $arParams["SEF_BASE_URL"].$arParams["SECTION_PAGE_URL"]);
		while($arSection = $rsSections->GetNext())
		{//pre($arSection);
			$arResult["SECTIONS"][] = array(
				"ID" => $arSection["ID"],
				"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
				"PROMO_IMAGE" => $arSection["UF_PROMO_IMAGE"],
				"~NAME" => ($arSection["UF_SHORT_NAME"]) ? $arSection["UF_SHORT_NAME"]:$arSection["~NAME"],
				"~SECTION_PAGE_URL" => $arSection["~SECTION_PAGE_URL"],
			);
			$arResult["ELEMENT_LINKS"][$arSection["ID"]] = array();
			if ($arSection["CODE"]=="perfume") {
				/*$dbNotes = CIblockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 4], false, false, ["ID","NAME","DETAIL_PAGE_URL"]);
				while ($arNote = $dbNotes->GetNext()) {
					$arResult["SECTIONS"][] = array(
						"ID" => $arNote["ID"],
						"DEPTH_LEVEL" => 2,
						"~NAME" => $arNote["NAME"],
						"~SECTION_PAGE_URL" => $arNote["DETAIL_PAGE_URL"],
					);
					$arResult["ELEMENT_LINKS"][$arNote["ID"]] = array();
				}*/
			} else {
				$flagFolders = false;
				$dbFolders = CIblockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 5,"PROPERTY_SECTION"=>$arSection["ID"], "ACTIVE" => "Y"], false, false, ["ID","NAME","DETAIL_PAGE_URL"]);
				while ($arFolder = $dbFolders->GetNext()) {
					$arResult["SECTIONS"][] = array(
						"ID" => $arFolder["ID"],
						"DEPTH_LEVEL" => 2,
						"~NAME" => $arFolder["NAME"],
						"~SECTION_PAGE_URL" => str_replace("#PAR_SECTION_CODE#", $arSection["CODE"], $arFolder["DETAIL_PAGE_URL"]),
					);
					$arResult["ELEMENT_LINKS"][$arFolder["ID"]] = array();
					$flagFolders = true;
				}

				if (!$flagFolders) {
					$rsSubSections = CIBlockSection::GetList($arOrder, ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "SECTION_ID" => $arSection["ID"]], false, array(
						"ID",
						"DEPTH_LEVEL",
						"NAME",
						"CODE",
						"UF_SHORT_NAME",
						"SECTION_PAGE_URL",
					));
					while ($arSubSection = $rsSubSections->GetNext()) {
						$arResult["SECTIONS"][] = array(
							"ID" => $arSubSection["ID"],
							"DEPTH_LEVEL" => $arSubSection["DEPTH_LEVEL"],
							"~NAME" => ($arSubSection["UF_SHORT_NAME"]) ? $arSubSection["UF_SHORT_NAME"]:$arSubSection["~NAME"],
							"~SECTION_PAGE_URL" => $arSubSection["~SECTION_PAGE_URL"],
						);
						$arResult["ELEMENT_LINKS"][$arSubSection["ID"]] = array();
					}
				}
			}

		}
		$this->EndResultCache();
	}
}

//In "SEF" mode we'll try to parse URL and get ELEMENT_ID from it
if($arParams["IS_SEF"] === "Y")
{
	$engine = new CComponentEngine($this);
	if (CModule::IncludeModule('iblock'))
	{
		$engine->addGreedyPart("#SECTION_CODE_PATH#");
		$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
	}
	$componentPage = $engine->guessComponentPath(
		$arParams["SEF_BASE_URL"],
		array(
			"section" => $arParams["SECTION_PAGE_URL"],
			"detail" => $arParams["DETAIL_PAGE_URL"],
		),
		$arVariables
	);
	if($componentPage === "detail")
	{
		CComponentEngine::InitComponentVariables(
			$componentPage,
			array("SECTION_ID", "ELEMENT_ID"),
			array(
				"section" => array("SECTION_ID" => "SECTION_ID"),
				"detail" => array("SECTION_ID" => "SECTION_ID", "ELEMENT_ID" => "ELEMENT_ID"),
			),
			$arVariables
		);
		$arParams["ID"] = intval($arVariables["ELEMENT_ID"]);
	}
}

if(($arParams["ID"] > 0) && (intval($arVariables["SECTION_ID"]) <= 0) && CModule::IncludeModule("iblock"))
{
	$arSelect = array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID");
	$arFilter = array(
		"ID" => $arParams["ID"],
		"ACTIVE" => "Y",
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	);
	$rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
	if(($arParams["IS_SEF"] === "Y") && (strlen($arParams["DETAIL_PAGE_URL"]) > 0))
		$rsElements->SetUrlTemplates($arParams["SEF_BASE_URL"].$arParams["DETAIL_PAGE_URL"]);
	while($arElement = $rsElements->GetNext())
	{
		$arResult["ELEMENT_LINKS"][$arElement["IBLOCK_SECTION_ID"]][] = $arElement["~DETAIL_PAGE_URL"];
	}
}

$aMenuLinksNew = array();
$menuIndex = 0;
$previousDepthLevel = 1;
$first = true;
foreach($arResult["SECTIONS"] as $arSection)
{
	if ($menuIndex > 0)
		$aMenuLinksNew[$menuIndex - 1][3]["IS_PARENT"] = $arSection["DEPTH_LEVEL"] > $previousDepthLevel;
	$previousDepthLevel = $arSection["DEPTH_LEVEL"];

	$arResult["ELEMENT_LINKS"][$arSection["ID"]][] = urldecode($arSection["~SECTION_PAGE_URL"]);
	$aMenuLinksNew[$menuIndex++] = array(
		htmlspecialcharsbx($arSection["~NAME"]),
		$arSection["~SECTION_PAGE_URL"],
		$arResult["ELEMENT_LINKS"][$arSection["ID"]],
		array(
			"FROM_IBLOCK" => true,
			"IS_PARENT" => false,
			"FIRST" => $first,
			"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
		),
	);
	$first = false;
}
return $aMenuLinksNew;
?>

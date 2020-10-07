<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$dbSect = CIblockSection::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => $arParams["IBLOCK_ID"]], false, ["ID", "NAME", "IBLOCK_SECTION_ID", "DEPTH_LEVEL"]);
while ($arSect = $dbSect->GetNext()) {
    if ($arSect["DEPTH_LEVEL"] == 1) {
        $arResult["SECTIONS_FIRST"][] = $arSect;
    } else {
        $arResult["SECTIONS_SECOND"][$arSect["IBLOCK_SECTION_ID"]][] = $arSect;
        $arResult["SECTIONS_SECOND"]["ALL"][] = $arSect;
    }
    $arResult["SECTIONS"][$arSect["ID"]] = $arSect["NAME"];
}

foreach ($arResult["ITEMS"] as $item)
{
    if (!$arResult["ITEMS_BY_SECTIONS"][$item["IBLOCK_SECTION_ID"]]["CITIES"] || !in_array(trim($item["NAME"]), $arResult["ITEMS_BY_SECTIONS"][$item["IBLOCK_SECTION_ID"]]["CITIES"]))
        $arResult["ITEMS_BY_SECTIONS"][$item["IBLOCK_SECTION_ID"]]["CITIES"][] = trim($item["NAME"]);
    $arResult["ITEMS_BY_SECTIONS"][$item["IBLOCK_SECTION_ID"]]["ITEMS"][trim($item["NAME"])][] = $item;
}
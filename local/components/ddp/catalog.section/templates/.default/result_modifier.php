<?
foreach ($arResult["ITEMS"] as $item)
    $arResult["ITEMS_BY_SECTIONS"][$item["IBLOCK_SECTION_ID"]][] = $item["ID"];
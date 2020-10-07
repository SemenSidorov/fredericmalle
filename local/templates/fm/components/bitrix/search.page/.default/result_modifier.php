<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
pre($arResult);
$prods = [];
$notes = [];
$parfumers = [];
$folders = [];
$sections = [];
if (count($arResult["SEARCH"]))
    foreach ($arResult["SEARCH"] as $item)
        switch ($item["PARAM2"]) {
            case 1:
                if (mb_substr($item["ITEM_ID"],0,1) == "S")
                    $sections[] = str_replace("S","",$item["ITEM_ID"]);
                else
                    $prods[] = $item["ITEM_ID"];
            case 3:
                $parfumers[] = $item["ITEM_ID"];
                break;
            case 4:
                $notes[] = $item["ITEM_ID"];
                break;
            case 5:
                $folders[] = $item["ITEM_ID"];
                break;
        }


if (count($sections)) {
    $res = CIBlockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 1, "SECTION_ID" => $sections, "INCLUDE_SUBSECTIONS" => "Y"], false, false, ["ID"]);
    while ($row = $res->GetNext()) {
        $prods[] = $row["ID"];
    }
}

if (count($notes)) {
    $cols = [];
    $dbCol = CIBlockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 6, "PROPERTY_NOTES" => $notes], false, false, ["ID"]);
    while ($arCol = $dbCol->GetNext()) {
        $cols[] = $arCol["ID"];
    }

    $res = CIBlockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 1, "PROPERTY_COLLECTION" => $cols], false, false, ["ID"]);
    while ($row = $res->GetNext()) {
        $prods[] = $row["ID"];
    }
}

if (count($folders)) {
    $res = CIBlockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 1, "PROPERTY_FOLDERS" => $folders], false, false, ["ID"]);
    while ($row = $res->GetNext()) {
        $prods[] = $row["ID"];
    }
}

if (count($parfumers)) {
    $res = CIBlockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 1, "PROPERTY_CREATOR" => $parfumers], false, false, ["ID"]);
    while ($row = $res->GetNext()) {
        $prods[] = $row["ID"];
    }
}


if (count($prods)) {
    $arSelect = [
        'ID',
        'IBLOCK_SECTION_ID',
        'NAME',
        'DETAIL_TEXT',
        'PROPERTY_CREATOR.NAME',
        'PROPERTY_NOTES',
        'PROPERTY_COLLECTION',
        'DETAIL_PAGE_URL'

    ];
    $dbProds = CIblockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 1, "ID" => $prods], false, false, $arSelect);
    while ($arProds = $dbProds->GetNext()) {
        $dbSection = CIblockSection::GetList([],["IBLOCK_ID" => 1, "ID" => $arProds["IBLOCK_SECTION_ID"]]);
        if ($arSection = $dbSection->GetNext()) {
            if ($arSection["CODE"]!="perfume" && $arProds["PROPERTY_COLLECTION_VALUE"])
                $arProds["NAME"] = $arSection["NAME"];
        }
        $arResult["PRODUCTS"][] = $arProds;
    }

    $arSelect = [
        'ID',
        'NAME',
        'PROPERTY_VOL',
        'DETAIL_PAGE_URL',
        'PROPERTY_WEIGHT',
        'PROPERTY_PROMO',
        'PROPERTY_BQSKU',
        'PROPERTY_CML2_LINK'
    ];

    $arFilter = [
        "IBLOCK_ID" => 2,
        "PROPERTY_CML2_LINK" => $prods,
    ];

    $res2 = CIBlockElement::GetList(["SORT" => "ASC", "PROPERTY_VOL_NUM" => "DESC"], $arFilter, false, false, $arSelect);

    $i = 0;
    while ($row = $res2->GetNext()) {
        foreach ($arResult["PRODUCTS"] as &$item) {
            if ($item['ID'] == $row['PROPERTY_CML2_LINK_VALUE']) {
                $item["sku"][] = $row;
            }
        }
        $i++;

    }
}
//pre($arResult);


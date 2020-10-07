<?php
if ((is_array($arResult["CATEGORIES"][0]["ITEMS"]) && count($arResult["CATEGORIES"][0]["ITEMS"])) || (is_array($arResult["CATEGORIES"][1]["ITEMS"]) && count($arResult["CATEGORIES"][1]["ITEMS"]))) {

    $ids = [];
    $sections = [];
    $products = [];
    $folders = [];
    $notes = [];
    $parfumers = [];


    if (count($arResult["CATEGORIES"][0]["ITEMS"]))
        foreach ($arResult["CATEGORIES"][0]["ITEMS"] as $item) {
            switch ($item["PARAM2"]) {
                case 1:
                    if (mb_substr($item["ITEM_ID"],0,1) == "S")
                        $sections[] = str_replace("S","",$item["ITEM_ID"]);
                    else
                        $ids[] = $item["ITEM_ID"];
                    break;
                case 4:
                    $notes[] = $item["ITEM_ID"];
                    break;

                case 5:
                    $folders[] = $item["ITEM_ID"];
                    break;
            }
        }

    if (count($arResult["CATEGORIES"][1]["ITEMS"]))
        foreach ($arResult["CATEGORIES"][1]["ITEMS"] as $item) {
            switch ($item["PARAM2"]) {
                case 3:
                    $parfumers[] = $item["ITEM_ID"];
                    break;
            }
        }

    if (count($sections)) {
        $res = CIBlockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 1, "SECTION_ID" => $sections, "INCLUDE_SUBSECTIONS" => "Y"], false, false, ["ID"]);
        while ($row = $res->GetNext()) {
            $ids[] = $row["ID"];
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
            $ids[] = $row["ID"];
        }
    }

    if (count($folders)) {
        $res = CIBlockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 1, "PROPERTY_FOLDERS" => $folders], false, false, ["ID"]);
        while ($row = $res->GetNext()) {
            $ids[] = $row["ID"];
        }
    }

    if (count($parfumers)) {
        $res = CIBlockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 1, "PROPERTY_CREATOR" => $parfumers], false, false, ["ID"]);
        while ($row = $res->GetNext()) {
            $ids[] = $row["ID"];
        }
    }

    if (count($ids)) {
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

        $res = CIBlockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 1, "ID" => $ids, "ACTIVE" => "Y"], false, false, $arSelect);
        while ($row = $res->GetNext()) {
            $dbSection = CIblockSection::GetList([],["IBLOCK_ID" => 1, "ID" => $row["IBLOCK_SECTION_ID"]]);
            if ($arSection = $dbSection->GetNext()) {
                if ($arSection["CODE"]!="perfume" && $row["PROPERTY_COLLECTION_VALUE"])
                    $row["SECTION_NAME"] = $arSection["NAME"];
            }
           // pre($row);
            $products[] = $row;
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
            "PROPERTY_CML2_LINK" => $ids,
        ];

        $res2 = CIBlockElement::GetList(["SORT" => "ASC", "PROPERTY_VOL_NUM" => "DESC"], $arFilter, false, false, $arSelect);

        while ($row = $res2->GetNext()) {
            foreach ($products as &$item) {
                if ($item['ID'] == $row['PROPERTY_CML2_LINK_VALUE']) {
                    $item["sku"][] = $row;
                }
            }
        }
        $arResult["CATALOG"] = $products;
    }
}
?>
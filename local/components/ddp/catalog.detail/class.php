<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Catalog;

class CatalogItemDetailComponent extends CBitrixComponent
{
    private function getCardDetail($code, $section)
    {
        $estee = new \DDP\Mod\FM();

        if(!isset($code)){
            $this->status404();
        }

        $arSelect = [
            "ID",
            "NAME",
            "PROPERTY_CREATOR",
            "PROPERTY_CREATOR.NAME",
            "PROPERTY_COLLECTION",
            "PROPERTY_COLLECTION.PROPERTY_QUOTE",
            "PROPERTY_COLLECTION.PROPERTY_QUOTE_AUTHOR",
            "PROPERTY_LINK",
            "IBLOCK_SECTION_ID"
        ];

        $arFilter = [
            "IBLOCK_ID" => $this->arParams['IBLOCK_ID'],
            "SECTION_ID" => $section["ID"],
            "INCLUDE_SUBSECTIONS" => "Y",
            "ACTIVE" => "Y",
            'CODE' => $code
        ];

        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        if ($arItem = $res->GetNext()) {
            $result["ITEM"] = $arItem;
            if (count($arItem["PROPERTY_LINK_VALUE"])) {
                $dbLink = CIblockElement::GetList(["ID" => $arItem["PROPERTY_LINK_VALUE"]], ["IBLOCK_ID" => $this->arParams['IBLOCK_ID'], "ID" => $arItem["PROPERTY_LINK_VALUE"]], false, false, ["ID" ,"NAME", "DETAIL_PAGE_URL"]);
                while ($arLink = $dbLink->GetNext()) {
                    $dbSku = CIblockElement::GetList(["PROPERTY_VOL_NUM" => "DESC"], ["IBLOCK_ID" => $this->arParams['IBLOCK_SKU_ID'], 'PROPERTY_CML2_LINK' => $arLink["ID"]], false, false, ["ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_BQSKU", "PROPERTY_PROMO"]);
                    if ($arSku = $dbSku->GetNext()) {
                        $arLink["SKU"] = $arSku;
                    }
                    $result["ITEM"]["LINK"][] = $arLink;
                }
            }
            $arSelectSKU = [
                "ID",
                "NAME",
                "ACTIVE",
                "PREVIEW_PICTURE",
                "PROPERTY_VOL",
                "PROPERTY_WEIGHT",
                "PROPERTY_PHOTO",
                "PROPERTY_PROMO",
                "PROPERTY_BQSKU",
                "PROPERTY_CONTENT",
                "DETAIL_TEXT"
            ];
            $spiders = $estee->getSpiders();
            foreach ($spiders as $desc) {
                $arSelectSKU[] = "PROPERTY_".$desc["PRICE"];
                $arSelectSKU[] = "PROPERTY_".$desc["URL"];
                $arSelectSKU[] = "PROPERTY_".$desc["STOCK"];
            }
            $result["SPIDERS"] = $spiders;

            $arFilterSKU = [
                "IBLOCK_ID" => $this->arParams['IBLOCK_SKU_ID'],
                'PROPERTY_CML2_LINK' => $arItem["ID"],
                "ACTIVE" => "Y"
            ];

            $ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues(
                $this->arParams['IBLOCK_SKU_ID'],
                $arItem["ID"]
            );

            $result['IPROPERTY_VALUES'] = $ipropValues->getValues();
            $arFilterSKU["ACTIVE"] = "Y";
            //pre($arFilterSKU);
            $dbSku = CIblockElement::GetList(["SORT" => "ASC", "PROPERTY_VOL_NUM" => "DESC"], $arFilterSKU, false,false, $arSelectSKU);
            while ($arSku = $dbSku->GetNext()) {
                if(!$_REQUEST["sku"]){
                    if($arSku["PROPERTY_VOL_NUM_VALUE"] == 50){
                        $result["SKU"] = $arSku;
                    }else{
                        if (isset($result["SKU"])) {
                            if ($arSku["PROPERTY_VOL_VALUE"] > $result["SKU"]["PROPERTY_VOL_VALUE"])
                                $result["SKU"] = $arSku;
                        } else {
                            $result["SKU"] = $arSku;
                        }
                    }
                }else{
                        if ($arSku["PROPERTY_BQSKU_VALUE"] == $_REQUEST["sku"])
                            $result["SKU"] = $arSku;
                }
                //pre($arSku);
                $result["ALL_SKU"][] = $arSku;
                $result["SKU_TYPE"] = "SKU";
            }
            if ($_REQUEST["sku"] && !$result["SKU"]) {
                $this->status404();
            }

            if ($result["ITEM"]["PROPERTY_CREATOR_VALUE"]) {
                $dbCreator = CIblockElement::GetList([], ["IBLOCK_ID" => 3, "ID" => $result["ITEM"]["PROPERTY_CREATOR_VALUE"]], false, false, ["ID" ,"NAME" ,"PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_QUOTE"]);
                if ($arCreator = $dbCreator->GetNext()) {
                    $result["ITEM"]["CREATOR"] = $arCreator;
                }
            }

            if ($section["CODE"]=="perfume") {
                if ($result["ITEM"]["PROPERTY_COLLECTION_VALUE"]) {
                    $dbCollections = CIblockElement::GetList([], ["IBLOCK_ID" => 6], false ,false, ["ID", "NAME", "DETAIL_PAGE_URL", "PROPERTY_TRIAN", "PROPERTY_SECOND_TITLE", "PROPERTY_VIDEO", "PROPERTY_PREVIEW_PHOTO"]);
                    while ($arCol = $dbCollections->GetNext()) {
                        $dbParfume = CIblockElement::GetList([], ["IBLOCK_ID" => $this->arParams['IBLOCK_ID'], "SECTION_ID" => 86, "PROPERTY_COLLECTION" => $arCol["ID"]], false, false, ["ID", "NAME", "DETAIL_PAGE_URL"]);
                        if ($arParfume = $dbParfume->GetNext()) {
                            $dbParfumeSku = CIblockElement::GetList([], ["IBLOCK_ID" => $this->arParams['IBLOCK_SKU_ID'],
                                'PROPERTY_CML2_LINK' => $arParfume["ID"]], false, false, ["ID", "PROPERTY_BQSKU"]);
                            if ($arParfumeSku = $dbParfumeSku->GetNext()) {
                                $arCol["URL"] = $arParfume["DETAIL_PAGE_URL"]."?sku=".$arParfumeSku["PROPERTY_BQSKU_VALUE"];
                            }
                        }

                        if ($arCol["ID"] == $result["ITEM"]["PROPERTY_COLLECTION_VALUE"]) {
                            $result["ITEM"]["COLLECTION"] = $arCol;
                        }
                        $result["COLLECTIONS"][] = $arCol;
                    }
                }
            }

            if ($section["CODE"]!="perfume" && count($result["ALL_SKU"])==1) {
                $arFilterCol = $arFilter;
                unset($arFilterCol["CODE"]);
                unset($result["ALL_SKU"][0]);
                //$arFilterCol["!ID"] = $arItem["ID"];
                $arFilterCol["ACTIVE"] = "Y";
                $dbColItems = CIblockElement::GetList(["SORT" => "ASC","NAME" => "ASC"], $arFilterCol , false,false, ["ID", "NAME", "DETAIL_PAGE_URL", "ACTIVE"]);
                while ($arColItems = $dbColItems->GetNext()) {
                    $arFilterColSKU = [
                        //"IBLOCK_ID" => $this->arParams["IBLOCK_SKU_ID"],
                        "PROPERTY_CML2_LINK" => $arColItems["ID"],
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => 2
                    ];
                    $dbColSku = CIblockElement::GetList([], $arFilterColSKU, false, false, ["ID"]);
                    if ($arColSku = $dbColSku->GetNext()) {
                        $result["ALL_SKU"][] = $arColItems;
                        $result["SKU_TYPE"] = "COLLECTION";
                    }
                }
            }

        } else {
            $this->status404();
        }

        $dbSections = CIblockSection::GetList([], ["IBLOCK_ID" => $this->arParams['IBLOCK_ID']], false, ["ID", "NAME", "SECTION_PAGE_URL", "UF_PROMO_IMAGE"]);
        while ($arSection = $dbSections->GetNext()) {
            $result["SECTIONS"][$arSection["ID"]] = $arSection;
        }

        $galleryAr = [];
        if (count($result["SKU"]["PROPERTY_PROMO_VALUE"])) {
            $galleryAr = $result["SKU"]["PROPERTY_PROMO_VALUE"];
        } else {
            if ($result["SKU"]["PREVIEW_PICTURE"])
                $galleryAr[] = $result["SKU"]["PREVIEW_PICTURE"];

            if (count($result["SKU"]["PROPERTY_PHOTO_VALUE"]))
                foreach ($result["SKU"]["PROPERTY_PHOTO_VALUE"] as $val)
                    $galleryAr[] = $val;
        }

        foreach ($galleryAr as &$item) {
            if (!$item) continue;
            $item =  ELCPicture($item, "detail");
        }
		
		$result["SKU"]['GALLERY'] = $galleryAr;

		$_SESSION['VIEWED_ENABLE'] = 'Y';
		$fields = array(
			'PRODUCT_ID' => $result["ITEM"]["ID"],
			'MODULE' => 'catalog',
			'LID' => $this->getSiteId()
		);
		\CSaleViewedProduct::Add($fields);        
		
		Catalog\CatalogViewedProductTable::refresh(
						$result["SKU"]["ID"],
						\CSaleBasket::GetBasketUserID(),
						$this->getSiteId(),
                        $result["ITEM"]["ID"]
					);		

        return $result;

    }

    private function setTitle()
    {

        if ($this->arParams['SET_TITLE'] == 'Y' && !empty($this->arResult["SKU"]['NAME'])) {
            global $APPLICATION;
            $APPLICATION->SetTitle($this->arResult["SKU"]['NAME']);
        }
    }

    private function addBreadcrumsItem()
    {
        global $APPLICATION;
        if(!empty($this->arParams['SECTION_ID'])){
            $res = CIBlockSection::GetNavChain(false, $this->arParams['SECTION_ID']);
            while ($row  = $res->GetNext()){

                $APPLICATION->AddChainItem($row['NAME'], $row['SECTION_PAGE_URL']);
            }

        }

        if(!empty($this->arResult['NAME'] && !empty($this->arResult['DETAIL_PAGE_URL']))){
            $APPLICATION->AddChainItem($this->arResult['NAME'], $this->arResult['DETAIL_PAGE_URL']);
        }
    }
    private function status404()
    {
        Bitrix\Iblock\Component\Tools::process404(
            '',
            true,
            true,
            true,
            false
        );
    }

    public function executeComponent()
    {
        CModule::IncludeModule("sale");
        Bitrix\Main\Loader::includeModule('ddp.mod');
        $this->arResult = $this->getCardDetail($this->arParams['CODE'], $this->arParams["SECTION"]);
        $this->setTitle();
        //$this->addBreadcrumsItem();
        $this->includeComponentTemplate();
    }
}
<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Iblock\Component\ElementList;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Error;
use \Bitrix\Catalog;

if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    return;
}
Loc::loadMessages(__FILE__);


class CatalogSectionComponent extends ElementList
{
    protected $sortOptions = [];
    protected $sortDefault = "name";
    protected $arSkip = [];

    /**
     * @param $arOrder
     * @param $arFilter
     * @return array
     */
    private function getList($arOrder, $arFilter, $getRanges = false)
    {

		$folders = CIBlockElement::GetList(array(), array('SECTION_ID' => $arFilter['SECTION_ID'], 'ID' => $arFilter['PROPERTY_FOLDERS']), false, false, array());
	
		$folder = $folders->GetNext();

            $result = [];
            
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
            
            $arPageNavigation = [
	            "nPageSize" => $this->arParams["PAGE_ELEMENT_COUNT"],
            ];
            if ($_REQUEST["ajax"] == "yes") $arPageNavigation["iNumPage"]=1;
//			pre($arFilter);

			if(strpos($_SERVER['REQUEST_URI'], '/about/perfumer/') !== false){
            	unset($arFilter['SECTION_ID']);
			}

			$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues(
				$folder['IBLOCK_ID'],
				$arFilter['PROPERTY_FOLDERS']
			);
		
			$result['FOLDER']['IPROPERTY_VALUES'] = $ipropValues->getValues();

            $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
			$ids = [];
			$products = [];
            $collections = [];

            if ($getRanges) {
                $dbSection = CIblockSection::GetList(["SORT" => "ASC", "NAME" => "ASC"],["IBLOCK_ID" => $arFilter["IBLOCK_ID"]]);
                while ($arSection = $dbSection->GetNext()) {
                    $result["SECTIONS"][] = $arSection;
                }
            }

            while ($row = $res->GetNext()) {
                $tmpCollection = $row["PROPERTY_COLLECTION_VALUE"];
                if ($tmpCollection && $getRanges) {
                    $dbElts = CIblockElement::GetList([], ["IBLOCK_ID" => $arFilter["IBLOCK_ID"], "PROPERTY_COLLECTION" => $tmpCollection], false, false, ["ID","NAME","DETAIL_PAGE_URL", "IBLOCK_SECTION_ID", "SORT"]);
                    while ($arElts = $dbElts->GetNext()) {
                        $row["COLLECTIONS"][$arElts["IBLOCK_SECTION_ID"]] = $arElts;
                    }
                }
                $ids[] = $row["ID"];
                $products[] = $row;
            }
            $arSelect = [
                'ID',
                'NAME',
                'ACTIVE',
                'PROPERTY_VOL',
                'DETAIL_PAGE_URL',
                'PROPERTY_WEIGHT',
                'PROPERTY_PROMO',
                'PROPERTY_BQSKU',
                'PROPERTY_CML2_LINK'
            ];
            
            $arFilter = [
	            //"IBLOCK_ID" => $this->arParams["IBLOCK_SKU_ID"],
	            "PROPERTY_CML2_LINK" => $ids,
            ];

            if ($_REQUEST["size"]) {
                $arFilter["PROPERTY_VOL_NUM"] = intval($_REQUEST["size"]);
            }

            if (is_array($this->arParams["SKU_FILTER"]) && count($this->arParams["SKU_FILTER"])) {
                $arFilter += $this->arParams["SKU_FILTER"];
            }
            $arFilter["ACTIVE"] = "Y";

            $res2 = CIBlockElement::GetList(["SORT" => "ASC", "PROPERTY_VOL_NUM" => "DESC"], $arFilter, false, false, $arSelect);

            $i = 0;
            while ($row = $res2->GetNext()) {
                foreach ($products as &$item) {
                    if ($item['ID'] == $row['PROPERTY_CML2_LINK_VALUE']) {
                        $item["sku"][] = $row;
                    }
                }
                $i++;

				$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues(
					$this->arParams["IBLOCK_SKU_ID"],
					$row["PROPERTY_CML2_LINK_VALUE"]
				);
	
				$result['IPROPERTY_VALUES'] = $ipropValues->getValues();
            }
			foreach ($products as $arItem) {
			    if (isset($arItem["sku"]) && count($arItem["sku"]))
                {
                    $result["ITEMS"][] = $arItem;
                }
            }
            return $result;
     }

    private function setTitle()
    {
        if ($this->arParams["SECTION"]["NAME"]) {
            global $APPLICATION;
            $APPLICATION->SetTitle($this->arParams["SECTION"]["NAME"]);
        }
    }

    private function addBreadcrumsItem($name, $link)
    {
        global $APPLICATION;
        if ($this->arParams["SECTION"]["ID"]) {
            $res = CIBlockSection::GetNavChain(false, $this->arParams["SECTION"]["ID"]);
            while ($row = $res->GetNext()) {
                $APPLICATION->AddChainItem($row['NAME'], $row['SECTION_PAGE_URL']);
            }

        }
    }

    public function getAction()
    {
        return $this->action;
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
        die();
    }

    public function executeComponent()
    {
        global $APPLICATION;
        $this->arResult = $this->getList($this->arParams["SORT_AR"], $this->arParams["FILTER_AR"], $this->arParams["GET_RANGES"]=="Y");

        if ($this->arParams["SET_TITLE"] == "Y")
            $this->setTitle();

        $this->addBreadcrumsItem($this->arParams['SECTION_NAME'], $this->arParams['SECTION_PAGE_URL']);
		if ($_REQUEST["ajax"] == "yes") {
			$this->arResult["AJAX"] = "Y";
			$this->setFrameMode(false);
			define("BX_COMPRESSION_DISABLED", true);
			ob_start();
			$this->IncludeComponentTemplate();
			$html = ob_get_contents();
			$APPLICATION->RestartBuffer();
			while(ob_end_clean());
			CMain::FinalActions();
			echo $html;
			die();
		} else {
			$this->includeComponentTemplate();	
		}    
    }
}
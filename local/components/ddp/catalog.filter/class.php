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


class EsteeCatalogFilterComponent extends ElementList
{
    protected $sortOptions = [];
    protected $sortDefault = "p";
    protected $arSkip = [];

    private function initParams($component = null)
    {
        global $APPLICATION;
        parent::__construct($component);
        $this->rowsCount = [3, 6];
        $this->sortOptions = [
            "p" => [
                "arOrder" => ["PROPERTY_INDEX_SORT" => "DESC"],
                "name" => "по популярности",
            ],
            "n" => [
                "arOrder" => ["NAME" => "ASC"],
                "name" => "по названию",
            ],
            "c" => [
                "arOrder" => ["CODE" => "ASC"],
                "name" => "по артикулу",
            ],
            "d" => [
                "arOrder" => ['PROPERTY_DISCOUNT' => 'DESC'],
                "name" => "по экономии",
            ],
            "up" => [
                "arOrder" => ['CATALOG_PRICE_2' => 'ASC'],
                "name" => "по возрастанию цены",
            ],
            "down" => [
                "arOrder" => ['CATALOG_PRICE_2' => 'DESC'],
                "name" => "по убыванию цены",
            ]
        ];
        $this->arSkip = array(
            "AUTH_FORM" => true,
            "TYPE" => true,
            "USER_LOGIN" => true,
            "USER_CHECKWORD" => true,
            "USER_PASSWORD" => true,
            "USER_CONFIRM_PASSWORD" => true,
            "USER_EMAIL" => true,
            "captcha_word" => true,
            "captcha_sid" => true,
            "login" => true,
            "Login" => true,
            "backurl" => true,
            "ajax" => true,
            "mode" => true,
            "bxajaxid" => true,
            "AJAX_CALL" => true,
            "MUL_MODE" => true
        );

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

    private function getViewedList($iBlock, $elementID = 0) {

        $ids = array();
        $basketUserId = (int)CSaleBasket::GetBasketUserID(true);
        $filter = array(
            '=FUSER_ID' => $basketUserId,
            '=SITE_ID' => $this->getSiteId()
        );

        if ($elementID > 0)
        {
            $filter['!=ELEMENT_ID'] = $elementID;
        }

        $viewedIterator = Catalog\CatalogViewedProductTable::getList(array(
            'select' => array('ELEMENT_ID'),
            'filter' => $filter,
            'order' => array('DATE_VISIT' => 'DESC'),
            'limit' => $this->arParams['PAGE_ELEMENT_COUNT'] * 10
        ));
        while ($viewedProduct = $viewedIterator->fetch())
        {
            $ids[] = (int)$viewedProduct['ELEMENT_ID'];
        }
        return $ids;
    }

    private function getSmartFilter()
    {
        global $perfumeFilter, $APPLICATION;
        $result = [];

        $uri = new \Bitrix\Main\Web\Uri($this->request->getRequestUri());
        $uri->deleteParams(\Bitrix\Main\HttpRequest::getSystemParameters());
        $pageURL = $uri->GetUri();
        $paramsToDelete = array("set_filter", "del_filter", "ajax", "bxajaxid", "AJAX_CALL", "mode", "MUL_MODE");

        $clearURL = CHTTP::urlDeleteParams($pageURL, $paramsToDelete, array("delete_system_params" => true));


        //$result["FORM_ACTION"] = $clearURL;
        /*$result["HIDDEN"] = array();
        foreach(array_merge($_GET, $_POST) as $key => $value)
        {
            if(
                !isset($arInputNames[$key])
                && !isset($this->arSkip[$key])
                && !is_array($value)
            )
            {
                $result["HIDDEN"][] = array(
                    "CONTROL_ID" => htmlspecialcharsbx($key),
                    "CONTROL_NAME" => htmlspecialcharsbx($key),
                    "HTML_VALUE" => htmlspecialcharsbx($value),
                );
            }
        }*/
        $result["FORM_ACTION"] = $APPLICATION->GetCurPage();

        $arOrder = [];
        $arOrder["SORT"] = "ASC";
        $arOrder["NAME"] = "ASC";

        //offers
        $arFilter = [
            'IBLOCK_ID' => $this->arParams["IBLOCK_ID"],
            'ACTIVE' => 'Y'
        ];

        if (is_array($perfumeFilter) && count($perfumeFilter)) {
            $arFilter = $arFilter + $perfumeFilter;
        }

        if ($this->arParams['SECT_ID'] !== null) {
            $dbSection = CIblockSection::GetList(array(),array("IBLOCK_ID" => $this->arParams["IBLOCK_ID"],"ID" => $this->arParams['SECT_ID']),false, array("ID", "NAME"));
            if ($arSection = $dbSection->GetNext()) {
                $result["section"] = $arSection;
                $result["mode"] = "section";
                $arFilter['SECTION_ID'] = $this->arParams['SECT_ID'];
                $arFilter['INCLUDE_SUBSECTIONS'] = "Y";
                $arFilter['SECTION_GLOBAL_ACTIVE'] = "Y";
            } else {
                $this->status404();
            }
        }


        if ($this->arParams["GET_FILTERS"] == "Y") {
            /*$dbFolder = CIblockElement::GetList(array(),array("IBLOCK_ID" => 5),false, false, array("ID", "NAME", "CODE", "DETAIL_PAGE_URL"));
            while ($arFolder = $dbFolder->GetNext()) {
                if ($this->arParams["FILTER"]["FOLDER"] && $arFolder["CODE"] == $this->arParams["FILTER"]["FOLDER"])
                    $arFolder["SELECTED"] = "Y";
                $result["FILTER"]["FOLDER"][] = $arFolder;
            }*/
            $allIDs = [];
            $allColID = [];
            $dbElts = CIblockElement::GetList([],$arFilter,false, false, array("ID", "PROPERTY_COLLECTION"));
            while ($arElt = $dbElts->GetNext()) {
                $allIDs[]=$arElt["ID"];
                $allColID[] = $arElt["PROPERTY_COLLECTION_VALUE"];
            }
            $allColID = array_unique($allColID);


            $dbNote = CIblockElement::GetList(array("SORT"=>"ASC", "NAME"=>"ASC"),array("IBLOCK_ID" => 4),false, false, array("ID", "NAME", "CODE", "DETAIL_PAGE_URL"));
            while ($arNote = $dbNote->GetNext()) {
                if ($this->arParams["FILTER"]["NOTE"] && $arNote["CODE"] == $this->arParams["FILTER"]["NOTE"]) {
                    $arNote["SELECTED"] = "Y";
                    $result["FILTER"]["NOTE"]["SELECTED"] = $arNote;
                }
                $result["FILTER"]["NOTE"]["ITEMS"][] = $arNote;
            }

            $dbCollection = CIblockElement::GetList(["SORT"=>"ASC", "NAME"=>"ASC"],["IBLOCK_ID" => 6, "ID" => $allColID], false, false, ["ID", "NAME", "CODE", "DETAIL_PAGE_URL"]);
            while ($arCollection = $dbCollection->GetNext()) {
                if ($this->arParams["FILTER"]["COLLECTION"] && $arCollection["CODE"] == $this->arParams["FILTER"]["COLLECTION"]) {
                    $arCollection["SELECTED"] = "Y";
                    $result["FILTER"]["COLLECTION"]["SELECTED"] = $arCollection;
                }
                $result["FILTER"]["COLLECTION"]["ITEMS"][] = $arCollection;
            }

            $dbSizes = CIblockElement::GetList(["PROPERTY_VOL_NUM" => "DESC"], ["IBLOCK_ID"=>$this->arParams["IBLOCK_SKU_ID"], "PROPERTY_CML2_LINK" => $allIDs], ["PROPERTY_VOL"], false, ["PROPERTY_VOL"]);
            while ($arSize = $dbSizes->GetNext()) {
                $arSize["DETAIL_PAGE_URL"] = $APPLICATION->GetCurPageParam("size=".$arSize["PROPERTY_VOL_NUM_VALUE"],["size"]);
                if ($_REQUEST["size"]==$arSize["PROPERTY_VOL_NUM_VALUE"]) {
                    $arSize["SELECTED"] = "Y";
                    $result["FILTER"]["SIZE"]["SELECTED"] = $arSize;
                }
                $result["FILTER"]["SIZE"]["ITEMS"][] = $arSize;
            }
        }

        if ($this->arParams["FILTER"]["FOLDER"]) {
            $dbFolder = CIblockElement::GetList(array(),array("IBLOCK_ID" => 5,"CODE" => $this->arParams["FILTER"]["FOLDER"]),false, false, array("ID", "NAME"));
            if ($arFolder = $dbFolder->GetNext()) {
                $arFilter["PROPERTY_FOLDERS"] = $arFolder["ID"];
            } else {
                $this->status404();
            }
        }

        if ($this->arParams["FILTER"]["COLLECTION"]) {
            $dbCollection = CIblockElement::GetList(array(),array("IBLOCK_ID" => 6,"CODE" => $this->arParams["FILTER"]["COLLECTION"]),false, false, array("ID", "NAME"));
            if ($arCollection = $dbCollection->GetNext()) {
                $arFilter["PROPERTY_COLLECTION"] = $arCollection["ID"];
            } else {
                $this->status404();
            }
        }

        if ($this->arParams["FILTER"]["NOTE"]) {
            $dbNote = CIblockElement::GetList(array(),array("IBLOCK_ID" => 4,"CODE" => $this->arParams["FILTER"]["NOTE"]),false, false, array("ID", "NAME"));
            if ($arNote = $dbNote->GetNext()) {
                $dbCollection = CIblockElement::GetList([],["IBLOCK_ID" => 6, "PROPERTY_NOTES" => $arNote["ID"]], false, false, ["ID", "NAME"]);
                while ($arCollection = $dbCollection->GetNext()) {
                    $arFilter["PROPERTY_COLLECTION"][] = $arCollection["ID"];
                }

            } else {
                $this->status404();
            }
        }



        foreach ($_REQUEST as $code => $value) {
            switch ($code) {
                case "min":
                    $value = intval($value);
                    $arFilter[">=CATALOG_PRICE_2"] = intval($value);
                    $result["SET_FILTER"]["MIN"] = $value;
                    break;

                case "max":
                    $value = intval($value);
                    $arFilter["<=CATALOG_PRICE_2"] = intval($value);
                    $result["SET_FILTER"]["MAX"] = $value;
                    break;

                case "c":
                    if (count($value)) {
                        foreach ($value as $val) {
                            foreach ($result["SMART_SECTIONS"] as $sect) {
                                if ($sect["ID"] == $val) {
                                    if (!is_array($arFilter["SECTION_ID"])) {
                                        $arFilter["SECTION_ID"] = [];
                                    }
                                    $result["SET_FILTER"]["SECTIONS"][] = $val;
                                    $arFilter["SECTION_ID"][] = $val;
                                }
                            }
                        }
                    }
                    break;

                default:
                    if (strlen($code)>2)
                        if (substr($code,0,2)=="p_") {
                            $PID = intval(str_replace("p_", "", $code));
                            if ($PID && $result["SMART_FILTER"]["PROPS"][$PID]) {
                                foreach ($value as $val) {
                                    if ($result["SMART_FILTER"]["PROPS"][$PID]["VALUES"][$val]) {
                                        $result["SET_FILTER"]["PROPS"][$PID][] = $val;
                                        $arFilter["PROPERTY_".$PID][] = $result["SMART_FILTER"]["PROPS"][$PID]["VALUES_FILTER"][$val];
                                    }
                                }
                            }
                        }
                    break;

            }
        }
        if ($this->arParams["GET_VIEWED"]) {
            $arFilter["ID"] = $this->getViewedList($this->arParams['IBLOCK_ID'], $this->arParams["ELEMENT_ID"]);
        }
        //pre($result["SMART_FILTER"]);
        //pre($arFilter);
        $this->arResult = $result;
        return ["filter" => $arFilter, "sort" => $arOrder, "section" => $result["section"], "section_path" => $result["section_path"], "items_cnt" => $result["items_cnt"]];
    }

    public function executeComponent()
    {
        $this->initParams();
        $ret = $this->getSmartFilter();
        $this->includeComponentTemplate();
        return $ret;
    }
}
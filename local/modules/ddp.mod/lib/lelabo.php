<?php

namespace DDP\Mod;
set_time_limit(0);
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\Validator;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Iblock;
//use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

Loc::loadMessages(__FILE__);
//require_once($_SERVER['DOCUMENT_ROOT'] . "/local/vendor/spout/src/Spout/Autoloader/autoload.php");
\CModule::IncludeModule("iblock");
\CModule::IncludeModule("sale");

class LeLabo {
	CONST IBLOCKBASE = 1;
	CONST IBLOCKTYPES = 3;
    CONST PARFUM = 2875;

    protected $token = "SU9IMT6AT9NXPK9C9J2I";
    protected $appid = "ELTA";
    protected $domain = "esteelauder.brandquad.ru";

    protected $tokenEcom = "D72L30Q45T4NEOHAHWHE";
    protected $urlEcom = "bfbapi2.brandquad.ru/items_api/public/v2/ELTA/";

    protected $curl;
    protected $types = [];

    protected $importProductFields = [
			"PROD_ID" => 0,
            "CODE" => 1,
			"CAT_1" => 2,
            "CAT_2" => 3,
            "NAME" => 4,
            "TYPE" => 7,
            "PREVIEW_TEXT" => 9,
            "DETAIL_TEXT" => 10
		];

    protected $attrTypes = [
        1 => "string",
        6 => "file",
        12 => "list"
    ];
    
    private $brands;
    private $attrGroups;
    private $attrAll;
    private $groupAll;
    private $bqCategories = [16140, 16141]; // Категория BFB, подкатегория BFB в bquad
    private $firstCat; // Соответствие ID категорий КорпШопа и bquad
    private $secondCat;
    private $iblockBQProps;
    private $iblockSKUProps;
    private $iblockBASEProps;

    private $mainAttrsNew = [
        "assets" => [
            16088 => "PROPERTY_PHOTO",

        ],
        "meta" => [
            "id" => "CODE",
            "name" => "NAME"
        ],
        "attributes" => [
            "15970" => "PROPERTY_BQ_SIZE",
            "9235" => "DETAIL_TEXT",
            "9236" => "PREVIEW_TEXT",
        ]
    ];

	function __construct() {
	}
	
	private function callBQ($method = "", $params = array()) {
        if ($method)
            $url = $method."/";
        else
            $url = "";

        $this->curl = curl_init();
        if (count($params)) $url.="?".http_build_query($params);
        curl_setopt($this->curl, CURLOPT_URL, "https://".$this->domain."/api/public_v2/".$url);
        curl_setopt($this->curl,CURLOPT_HTTPHEADER, array('TOKEN:'.$this->token, "APPID:".$this->appid));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($this->curl);
        //echo $url."<br/>";pre(json_decode($res, true));die();
        return json_decode($res, true);
    }

    private function callBQEcom($method = "", $params = array(), $numPage = 1) {
        $ret = [];

	    if ($method)
            $url = $method."/";
        else
            $url = "";

        $this->curl = curl_init();
        $params["page"] = $numPage;

        if (count($params)) $url.="?".http_build_query($params);
        curl_setopt($this->curl, CURLOPT_URL, "https://".$this->urlEcom.$url);
        curl_setopt($this->curl,CURLOPT_HTTPHEADER, array('TokenAPI:'.$this->tokenEcom));
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($this->curl), true);
        return $res;
    }

    private function getItemsFromBQ($codes) {
        $tmpRet = $this->callBQ("products",array("use_attributes_id"=>"true", 'filters'=>json_encode(array("type"=>"_id","exp"=>"in","val"=>$codes))));
        $ret = [];
        $nonBQ = [];
        
        foreach ($codes as $code) {
	        $flag = false;
	        foreach ($tmpRet["results"] as $key=>$item) {
		        if ($item["meta"]["id"] == $code) {
			    	$ret[$code] = $item;    
			    	$flag = true;
		        }
	        }
	        if (!$flag)
	        	$nonBQ[] = $code;
        }
        return ["items"=> $ret, "nonBQ"=> $nonBQ];
	}

	private function getSimpleItemsFromBQ($codes, $timeStamp = 0, $page = 1, $filter = []) {
	    $filterBQ = array(
            "use_attributes_id"=>"true",
            "with_attributes" => "true",
            "with_assets" => "true",
            "with_categories" => "false",
            "with_relations" => "false",
        );

	    if (count($codes))
	        $filterBQ['filters'] = json_encode(array("type"=>"_id","exp"=>"in","val"=>$codes));
	    elseif (count($filters))
            foreach ($filters as $key=>$code) {
	            $filterBQ['filters'] = json_encode(array("type"=>$key,"exp"=>"in","val"=>$code));
            }

	    if ($timeStamp)
            $filterBQ['last_ts'] = intval($timeStamp);

	    if ($page > 1)
            $filterBQ['page'] = intval($page);

	    $tmpRet = $this->callBQ("products", $filterBQ);

        $ret = [];
        $retCodes = [];
	    if (count($codes)) {

            $nonBQ = [];
            foreach ($codes as $code) {
                $flag = false;
                foreach ($tmpRet["results"] as $key=>$item) {
                    if ($item["meta"]["id"] == $code) {
                        $ret[$code] = $item;
                        $flag = true;
                    }
                }
                if (!$flag)
                    $nonBQ[] = $code;
            }
            return ["items"=> $ret, "nonBQ"=> $nonBQ];
        } else {
            foreach ($tmpRet["results"] as $item) {
                $articul = $item["meta"]["id"];
                $ret[$articul] = $item;
                $retCodes[] = $articul;
            }
            return ["items" => $ret, "codes" => $retCodes, "next" => $tmpRet["next"]];
        }
	}

    private function getAttrsFromBQ($refresh = false)
    {
        $curPage = 1;
        $params = array();
        $attr = array();
        if (count($this->attrAll) && !$refresh)
            return;
        else {
            $res = $this->callBQ("attributes/groups");
            if (count($res["results"])) {
                foreach ($res["results"] as $key => $value) {
                    $this->groupAll[$value["id"]] = $value;
                }
            }

            do {
                $params["page"] = $curPage;
                $res = $this->callBQ("attributes", $params);
                foreach ($res["results"] as $key => $value) {
                    if ($this->attrTypes[$value["type"]])
                        $value["type"] = $this->attrTypes[$value["type"]];
                    $attr[$value["id"]] = $value;
                }
                if ($res["next"]) {
                    $curPage++;
                }
            } while ($res["next"]);
            $this->attrAll = $attr;
            foreach ($attr as $key => $value) {
                $this->attrGroups[$this->groupAll[$value["group"]]['name']][] = $value["name"];
            }
        }
    }

    private function clearLineCSV($data) {
        $ret = [];
        foreach ($data as $key=>$value) {
            $ret[$key] = trim($value);
        }
        return $ret;
    }

    private function parseCsv($data, $type) {
	    $fields = [];
	    switch ($type) {
            case "products":
                foreach ($this->importProductFields as $code => $key) {
                    $fields[$code] = $data[$key];
                }
                break;

            default:
                return false;
                break;
        }
        return $fields;
    }

    private function clearPriceCSV($data) {
        return str_replace(" ","",str_replace(",",".",trim(str_replace(array("\r\n", "\r", "\n", "\t", ' ','  ', '    ', '    '),"",$data), $character_mask = " \t\n\r\0\x0B")));
    }

    private function getCSVData($file) {
        $errors = [];
        if(($handle_f = fopen($file, "r" )) !== FALSE)
        {
            fseek($handle_f, $im);
            $firstLine = [];
            $lines = [];
            $count = 0;
            while(!feof($handle_f))
            {
                $buffer = fgets($handle_f);
                $data = explode(';', $buffer);
                if (!count($firstLine))
                    $firstLine = $data;
                else {
                    $lines[] = $data;
                }
            }
            fclose($handle_f);
        }
        return ["firstline" => $firstLine, "data" => $lines];
    }

    private function getXLSData($file) {
        $reader = ReaderEntityFactory::createReaderFromFile($file);
        $reader->open($file);
        $firstLine = [];
        $lines = [];
        $count = 0;
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $data = [];
                $cells = $row->getCells();

                foreach ($cells as $key=>$value) {
                    $data[] = $value->getValue();
                }
                if (count($firstLine))
                    $lines[] = $data;
                elseif ($data[0] == "Сотрудник") {
                    $firstLine = $data;
                }

            }
        }
        return ["firstline" => $firstLine, "data" => $lines];
    }

    private function processBQItemNew($item) {
	    //pre($item);
        $start = time();
        $ret = [];
        foreach ($this->mainAttrsNew as $type=>$ar)
            foreach ($ar as $id=>$code) {
                switch ($type) {
                    case "meta":
                        $ret[$code] = $item[$type][$id];
                        break;
                    case "assets":
                        foreach ($item[$type] as $key=>$value) {
                            if ($value["attribute"]["id"] == $id)
                                if (strstr($code,"PROPERTY_")) {
                                    $ret[$code]["type"] = "file";
                                    $ret[$code]["name"] = $value["attribute"]["name"];
                                    $ret[$code]["value"][] = [
                                        "name" => $value["dam"]["name"],
                                        "type" => $value["dam"]["mimetype"],
                                        "url" => $value["dam"]["url"]
                                    ];
                                } else {
//	                                pre($value);
                                    if ($value["dam"]["mimetype"]=="image/tiff") continue;
                                    $ret[$code] = [
                                        "type" => "file",
                                        "value" => [
                                            "name" => $value["dam"]["name"],
                                            "type" => $value["dam"]["mimetype"],
                                            "url" => $value["dam"]["url"]
                                        ]
                                    ];
                                }
                        }
                        break;
                    case "attributes":
                        $value = (is_array($item[$type][$id])) ? $item[$type][$id][0]:$item[$type][$id];

                        $ret[$code] = [
                            "type" => "string",
                            "name" => $this->attrAll[$id]["name"],
                            "value" => $value
                        ];
                        break;
                }
            }
        foreach ($ret as $code => $prop) {
            if ($prop["type"]) {
                switch ($prop["type"]) {
                    case "string":
                        if (strstr($code,"PROPERTY_")) {
                            $ret["PROPERTY_VALUES"][str_replace("PROPERTY_", "", $code)] = $prop["value"];
                            unset($ret[$code]);
                        } else {
                            $ret[$code] = $prop["value"];
                        }
                        break;
                    case "file":
                        if (!$prop["value"]["url"]) {
                            foreach ($prop["value"] as $tmpVal) {
                                $ret["PROPERTY_VALUES"][str_replace("PROPERTY_", "", $code)][$tmpVal["name"]] = $tmpVal["url"];
                            }
                            unset($ret[$code]);
                        } else {
                            $ret[$code] = $prop["value"]["url"];
                        }
                        break;
                }
            }
        }

        if (count($ret["PROPERTY_VALUES"]["PHOTO"])) {
            ksort($ret["PROPERTY_VALUES"]["PHOTO"]);
            $tmpPhoto = [];
            foreach ($ret["PROPERTY_VALUES"]["PHOTO"] as $key=>$value) {
                $tmpPhoto[] = $value;
            }
            $ret["PROPERTY_VALUES"]["PHOTO"] = $tmpPhoto;
            $ret["PREVIEW_PICTURE"] = $ret["PROPERTY_VALUES"]["PHOTO"][0];
        }

        $preview_length = 300;
        if ($ret["DETAIL_TEXT"] && mb_strlen($ret["DETAIL_TEXT"]) > $preview_length) {
            $ret["PREVIEW_TEXT"] = ELCPreview($ret["DETAIL_TEXT"], $preview_length);
        } else {
            $ret["PREVIEW_TEXT"] = "";
        }

        return $ret;
    }

    private function copyItemFromBQ($item, $copyFiles = false) {
        $SKU_ID = 0;
        $PRODUCT_ID = 0;
        $product = [];
        $productBrand = "";
        $el = new \CIBlockElement;

	    if (!count($this->iblockBQProps)) {
	        $dbProps = \CIBlockProperty::GetList([],["IBLOCK_ID" => self::IBLOCKBQ]);
	        while ($arProp = $dbProps->GetNext()) {
	            $this->iblockBQProps[$arProp["ID"]] = $arProp;
            }
        }
        /*if (!count($this->iblockSKUProps)) {
            $dbProps = \CIBlockProperty::GetList([],["IBLOCK_ID" => self::IBLOCKSKU]);
            while ($arProp = $dbProps->GetNext()) {
                $this->iblockSKUProps[$arProp["ID"]] = $arProp;
            }
        }
        if (!count($this->iblockBASEProps)) {
            $dbProps = \CIBlockProperty::GetList([],["IBLOCK_ID" => self::IBLOCKBASE]);
            while ($arProp = $dbProps->GetNext()) {
                $this->iblockBASEProps[$arProp["ID"]] = $arProp;
            }
        }*/

        $cat = "";
        $subCat = "";
        $product["CODE"] = $item["CODE"];
        $product["NAME"] = $item["NAME"];
        $product["PREVIEW_TEXT"] = $item["PREVIEW_TEXT"];
        $product["DETAIL_TEXT"] = $item["DETAIL_TEXT"];
        $product["TMP_ID"] = $item["TMP_ID"];

        foreach ($item as $key=>$value) {
            if (strpos($key,"PROPERTY_")==0) {
                $key = str_replace("PROPERTY_","",$key);
                if ($this->iblockBQProps[$key]["CODE"]) {
                    $newKey = str_replace("BQ_","",$this->iblockBQProps[$key]["CODE"]);
                    switch ($newKey) {
                        case "BRAND":
                            $product["PROPERTY_VALUES"][$newKey] = $this->getBrand($value);
                            $productBrand = $value;
                            break;
                        case "PREVIEW_PICTURE":
                            if ($copyFiles) {
                                if (is_array($value))
                                    $product["PREVIEW_PICTURE"] = \CFile::MakeFileArray($value[0]);
                                else
                                    $product["PREVIEW_PICTURE"] = \CFile::MakeFileArray($value);
                            }
                            break;

                        case "CAT":
                            $cat = $value;
                            break;

                        case "SUBCAT":
                            $subCat = $value;
                            break;

                        case "MORE_PHOTO":
                        case "SWATCH":
                            if ($copyFiles)
                                foreach ($value as $val) {
                                    $fileAr = \CFile::MakeFileArray($val);
                                    $product["PROPERTY_VALUES"][$newKey][] = $fileAr;
                                }
                            break;

                        default:
                            $product["PROPERTY_VALUES"][$newKey] = $value;
                            break;
                    }
                }
            }
        }

        if ($cat && $subCat) {
            $product["IBLOCK_SECTION_ID"] = $this->getCat($cat, $subCat);
        }

        // Загрузим торговое предложение

        $arLoadProductArray = $product;
        $arLoadProductArray["ACTIVE"] = "Y";
        $arLoadProductArray["IBLOCK_ID"] = self::IBLOCKSKU;
        foreach ($arLoadProductArray["PROPERTY_VALUES"] as $key=>$value) {
            if (!in_array("PROPERTY_" . $key, $this->mainAttrsSKU))
                unset($arLoadProductArray["PROPERTY_VALUES"][$key]);
        }

        unset($arLoadProductArray["PREVIEW_PICTURE"]);
        unset($arLoadProductArray["PREVIEW_TEXT"]);
        unset($arLoadProductArray["DETAIL_TEXT"]);
        unset($arLoadProductArray["IBLOCK_SECTION_ID"]);

        $dbElt = \CIBlockElement::GetList(array(),array("IBLOCK_ID" => self::IBLOCKSKU, "CODE" => $arLoadProductArray["CODE"]),false,false,array("ID" ,"PROPERTY_BQ_PBASE", "PROPERTY_CML2_LINK"));
        if ($arElt = $dbElt->GetNext()) {
            $SKU_ID = $arElt["ID"];
            \CIBlockElement::SetPropertyValuesEx($SKU_ID, self::IBLOCKSKU, array("MORE_PHOTO" => ["del" => "Y"], "SWATCH" => ["del" => "Y"]));
            $el->Update($SKU_ID, $arLoadProductArray);
        } else {
            $SKU_ID = $el->Add($arLoadProductArray);
            \CCatalogProduct::Add(array(
                'QUANTITY' => 0,
                'ID'    => $SKU_ID
            ));
        }

        // Загрузим товар

        $arLoadProductArray = $product;
        $arLoadProductArray["ACTIVE"] = "Y";
        $arLoadProductArray["IBLOCK_ID"] = self::IBLOCKBASE;
        foreach ($arLoadProductArray["PROPERTY_VALUES"] as $key=>$value)
            if (!in_array("PROPERTY_".$key, $this->mainAttrsBase))
                unset($arLoadProductArray["PROPERTY_VALUES"][$key]);

        $arLoadProductArray["PROPERTY_VALUES"]["BRAND_SEARCH"] = $productBrand;
        $arLoadProductArray["PROPERTY_VALUES"]["INDEX_SORT"] = $item["INDEX_SORT"];

        $dbElt = \CIBlockElement::GetList(array(),array("IBLOCK_ID" => self::IBLOCKBASE, "CODE" => $arLoadProductArray["CODE"]),false,false,array("ID"));
        if ($arElt = $dbElt->GetNext()) {
            $PRODUCT_ID = $arElt["ID"];
            $el->Update($PRODUCT_ID, $arLoadProductArray);
        } else {
            $PRODUCT_ID = $el->Add($arLoadProductArray);
        }
        \CIBlockElement::SetPropertyValuesEx($SKU_ID, self::IBLOCKSKU, array("BQ_PBASE" => $PRODUCT_ID, "CML2_LINK" => $PRODUCT_ID));

        return ["PROD_ID" => $PRODUCT_ID, "SKU_ID" => $SKU_ID];
	}

    private function updateCatalogItem($item, $newItem = false, $tmpID = "") {

		if (!($item["PROD_ID"])) return false;

        $el = new \CIBlockElement;

		$updateSort = false;
		$updateBasePrice = true;
		$updateNCPrice = true;

//		if ($newItem) {
//            $updateBasePrice = true;
//            $updateNCPrice = true;
//        }

        \CIBlockElement::SetPropertyValuesEx($item["PROD_ID"], self::IBLOCKBASE, ["INDEX_SORT" => $item["INDEX_SORT"], "DISCOUNT" => ($item["PRICE_BASE"]-$item["PRICE_NOCHARGE"]), "EXPIRE" => $item["EXPIRE"]]);

		$el->Update($item["PROD_ID"],["ACTIVE" => "Y", "TMP_ID" => $tmpID]);
        $el->Update($item["SKU_ID"],["ACTIVE" => "Y", "TMP_ID" => $tmpID]);

        if ($updateBasePrice) {
            $arFields = Array(
                "PRODUCT_ID" => $item["SKU_ID"],
                "CATALOG_GROUP_ID" => 1,
                "PRICE" => $item["PRICE_BASE"],
                "CURRENCY" => "RUB",
            );
            $res = \CPrice::GetList(
                array(),
                array(
                    "PRODUCT_ID" => $item["SKU_ID"],
                    "CATALOG_GROUP_ID" => 1
                )
            );
            if ($arr = $res->Fetch()) {
                \CPrice::Update($arr["ID"], $arFields);
            } else {
                $pEl = \CPrice::Add($arFields);
            }
        }

        if ($updateNCPrice) {
            $arFields = Array(
                "PRODUCT_ID" => $item["SKU_ID"],
                "CATALOG_GROUP_ID" => 2,
                "PRICE" => $item["PRICE_NOCHARGE"],
                "CURRENCY" => "RUB",
            );
            $res = \CPrice::GetList(
                array(),
                array(
                    "PRODUCT_ID" => $item["SKU_ID"],
                    "CATALOG_GROUP_ID" => 2
                )
            );
            if ($arr = $res->Fetch()) {
                \CPrice::Update($arr["ID"], $arFields);
            } else {
                $pEl = \CPrice::Add($arFields);
            }
        }

	    return true;
	}

	function makeGroupLinks() {
	    $groups = [];
        $el = new \CIBlockElement;
	    //$dbGroups = \CIblockElement::GetList([], ["IBLOCK_ID" => self::IBLOCKBASE, "PROPERTY_TYPE" => self::PARFUM], false, false, ["ID", "IBLOCK_SECTION_ID", "PROPERTY_VOL", "PROPERTY_TYPE.NAME", "CODE"]);
        $dbGroups = \CIblockElement::GetList([], ["IBLOCK_ID" => self::IBLOCKBASE], false, false, ["ID", "IBLOCK_SECTION_ID", "PROPERTY_VOL", "PROPERTY_TYPE.NAME", "CODE", "NAME"]);
	    while ($arGroup = $dbGroups->GetNext()) {
	        $groups[$arGroup["IBLOCK_SECTION_ID"]][] = $arGroup;
	        $code = \Cutil::translit($arGroup["NAME"]."-".$arGroup["PROPERTY_TYPE_NAME"], "ru");
            $el->Update($arGroup["ID"], ["CODE" => $code]);
	    }

        die();
    }

    function updatePrices() {
        $dbItem = \CIblockElement::GetList([],["IBLOCK_ID" => self::IBLOCKBASE], false, false, ["ID", "NAME"]);
        while ($arItem = $dbItem->GetNext()) {
            \CIBlockElement::SetPropertyValuesEx($arItem["ID"], self::IBLOCKBASE, ["PRICE_TSUM" => "", "URL_TSUM" => ""]);
        }

	    $bqPrices = [];
	    $page = 1;
        $dateStamp = strtotime("-1 day");
        $date = date('Y-m-d', $dateStamp);
        do {
            $tmpRet = $this->callBQEcom("prices", ["spider" => "tsum.ru", "brand" => "Le Labo", "date_from" => $date], $page);
            if (count($tmpRet["prices"])) {
                foreach ($tmpRet["prices"] as $prod)
                    $bqPrices[] = $prod;
            }
            $page++;
        } while ($tmpRet["meta"]["page"] < $tmpRet["meta"]["total_pages"]);
        if (count($bqPrices))
            foreach ($bqPrices as $prod) {
                if ($prod["dates"][$date] && $prod["sku"]) {
                    $price = $prod["dates"][$date]["price"];
                    $dbItem = \CIblockElement::GetList([],["IBLOCK_ID" => self::IBLOCKBASE, "PROPERTY_SKU" => $prod["sku"]], false, false, ["ID", "NAME"]);
                    if ($arItem = $dbItem->GetNext()) {
                        \CIBlockElement::SetPropertyValuesEx($arItem["ID"], self::IBLOCKBASE, ["PRICE_TSUM" => $price]);
                    }
                }
            }
        //pre($bqPrices);

	    $bqProducts = [];
	    $page = 1;
        do {
            $tmpRet = $this->callBQEcom("products", ["spider" => "tsum.ru", "brand" => "Le Labo"], $page);
            if (count($tmpRet["products"])) {
                foreach ($tmpRet["products"] as $prod)
                    $bqProducts[] = $prod;
            }
            $page++;
        } while ($tmpRet["meta"]["page"] < $tmpRet["meta"]["total_pages"]);
        if (count($bqProducts))
            foreach ($bqProducts as $prod) {
                if ($prod["sku"]) {
                    $dbItem = \CIblockElement::GetList([], ["IBLOCK_ID" => self::IBLOCKBASE, "PROPERTY_SKU" => $prod["sku"]], false, false, ["ID", "NAME"]);
                    if ($arItem = $dbItem->GetNext()) {
                        \CIBlockElement::SetPropertyValuesEx($arItem["ID"], self::IBLOCKBASE, ["URL_TSUM" => $prod["url"]]);
                    }
                }
            }

        //pre($bqProducts);
    }

	function updateBQCatalog($timeStamp = 0) {
	    $start = time();
        $page = 0;
        $addedBQ = 0;
        $updatedBQ = 0;
        $preview_picture = "PREVIEW_PICTURE";
        $photos = ["PHOTO"];
        $el = new \CIBlockElement;

        if (!count($this->iblockBQProps)) {
            $dbProps = \CIBlockProperty::GetList([],["IBLOCK_ID" => self::IBLOCKBASE]);
            while ($arProp = $dbProps->GetNext()) {
                $this->iblockBQProps[$arProp["ID"]] = $arProp;
            }
        }

        $codes = [];
        $articuls = [];
        $needUpdate = [];
        $dbProds = \CIblockElement::GetList([],["IBLOCK_ID" => self::IBLOCKBASE],false, false, ["ID", "NAME", "PROPERTY_SKU", "PROPERTY_TYPE.NAME"]);
        while ($arProds = $dbProds->GetNext()) {
            if (mb_strlen($arProds["PROPERTY_SKU_VALUE"]) == 10) {
                $codes[] = $arProds["PROPERTY_SKU_VALUE"];
                $types[$arProds["PROPERTY_SKU_VALUE"]] = \Cutil::translit($arProds["NAME"]."-".trim($arProds["PROPERTY_TYPE_NAME"]), "ru");
                $needUpdate[$arProds["PROPERTY_SKU_VALUE"]] = $arProds["ID"];
            }
        }

        $count = 0;
        $step = 30;
        foreach ($codes as $code) {
            $articuls[intval($count / $step)][] = $code;
            $count++;
        }
        foreach ($articuls as $key=>$codes) {
            $tmpRet = $this->getSimpleItemsFromBQ($codes, $timeStamp, $page);
            foreach ($tmpRet["items"] as $item) {
                $item = $this->processBQItemNew($item);
                if ($needUpdate[$item["CODE"]]) {
                    $code = $types[$item["CODE"]];
                    $vol = $item["PROPERTY_VALUES"]["BQ_SIZE"];

                    /*if ($vol)
                        $code.="_".\Cutil::translit($vol,"ru");*/

                    $arLoadProductArray = [
                        "IBLOCK_ID" => self::IBLOCKBASE,
                        "PREVIEW_TEXT" => $item["PREVIEW_TEXT"],
                        "DETAIL_TEXT" => $item["DETAIL_TEXT"],
                        "PREVIEW_PICTURE" => \CFile::MakeFileArray($item["PREVIEW_PICTURE"]),
                        "CODE" => $code
                    ];

                    $el->Update($needUpdate[$item["CODE"]], $arLoadProductArray);

                    $newProps = [
                        "HAVE_BQ" => 3,
                        "VOL" => $vol
                    ];

                    foreach ($photos as $key) {
                        if ($item["PROPERTY_VALUES"][$key]) {
                            $itemPhoto = $item["PROPERTY_VALUES"][$key];
                            foreach ($itemPhoto as $value) {
                                $newProps["PHOTO"][] = \CFile::MakeFileArray($value);
                            }
                        }
                    }

                    \CIBlockElement::SetPropertyValuesEx($needUpdate[$item["CODE"]], self::IBLOCKBASE, $newProps);
                }
            }
        }
    }

    function importProducts($file = "", $checkImport = false) {
	    if (!$file) return false;
		\CModule::IncludeModule("sale");
		$errors = [];
		$updated = [];
		$added = [];
		$new = [];
        $csv = [];

        $extension = strtolower(GetFileExtension($file));

		switch ($extension) {
            case "xlsx":
                $csv = $this->getXLSData($file);
                break;
            case "csv":
                $csv = $this->getCSVData($file);
                break;
        }
		if (count($csv["data"])) {
		    $types = [];
		    $dbTypes = \CIblockElement::GetList([],["IBLOCK_ID" => $this::IBLOCKTYPES], false, false, ["ID" => "NAME"]);
		    while ($arType = $dbTypes->GetNext()) {
		        $types[$arType["NAME"]] = $arType["ID"];
            }

			foreach ($csv["data"] as $data) {
                $data = $this->clearLineCSV($data);
                $data = $this->parseCsv($data, "products");
                if (!$data["CODE"]) continue;
                $firstCat = $data["CAT_1"];

                $dbFirstCat = \CIblockSection::GetList([], ["IBLOCK_ID" => $this::IBLOCKBASE, "EXTERNAL_ID" => $firstCat], false, ["ID", "NAME"]);
                if ($arFirstCat = $dbFirstCat->GetNext()) {
                    $fcID = $arFirstCat["ID"];
                } else {
                    $bs = new \CIBlockSection;
                    $arFields = Array(
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => $this::IBLOCKBASE,
                        "NAME" => $firstCat,
                        "CODE" => \Cutil::translit($firstCat, "ru"),
                        "EXTERNAL_ID" => $firstCat
                    );
                    if (!$fcID = $bs->Add($arFields)) {
                        echo $bs->LAST_ERROR;
                    }
                }

                $secCat = $data["CAT_2"];
                if ($secCat != $firstCat) {
                    $dbSecCat = \CIblockSection::GetList([], ["IBLOCK_ID" => $this::IBLOCKBASE, "EXTERNAL_ID" => $secCat, "SECTION_ID" => $fcID], false, ["ID", "NAME"]);
                    if ($arSecCat = $dbSecCat->GetNext()) {
                        $scID = $arSecCat["ID"];
                    } else {
                        $bs = new \CIBlockSection;
                        $arFields = Array(
                            "ACTIVE" => "Y",
                            "IBLOCK_ID" => $this::IBLOCKBASE,
                            "NAME" => $secCat,
                            "CODE" => \Cutil::translit($secCat, "ru"),
                            "EXTERNAL_ID" => $secCat,
                            "IBLOCK_SECTION_ID" => $fcID
                        );
                        $scID = $bs->Add($arFields);
                    }
                    $sectID = $scID;
                } else {
                    $sectID = $fcID;
                }

                $ext_id = $data["PROD_ID"];
                $type = trim($data["TYPE"]);
                if (!$types[$type]) {
                    $el = new \CIBlockElement;
                    $arLoadProductArray = Array(
                        "IBLOCK_ID"      => $this::IBLOCKTYPES,
                        "NAME"           => $type,
                        "ACTIVE"         => "Y",
                    );
                    $type_id = $el->Add($arLoadProductArray);
                    $types[$type] = $type_id;
                } else {
                    $type_id = $types[$type];
                }

                $dbElt = \CIblockElement::GetList([], ["IBLOCK_ID" => $this::IBLOCKBASE, "EXTERNAL_ID" => $ext_id], false, false, ["ID", "NAME"]);
                if ($arElt = $dbElt->GetNext()) {
                    $PRODUCT_ID = $arElt["ID"];
                } else {
                    $el = new \CIBlockElement;
                    $PROP = array();
                    $PROP["SKU"] = $data["CODE"];
                    $PROP["TYPE"] = $type_id;
                    $arLoadProductArray = Array(
                        "IBLOCK_SECTION_ID" => $sectID,
                        "IBLOCK_ID"      => $this::IBLOCKBASE,
                        "PROPERTY_VALUES"=> $PROP,
                        "EXTERNAL_ID" => $ext_id,
                        "NAME"           => $data["NAME"],
                        "CODE" => \Cutil::translit($data["NAME"], "ru"),
                        "ACTIVE"         => "Y",
                        "PREVIEW_TEXT"   => $data["PREVIEW_TEXT"],
                        "DETAIL_TEXT"    => $data["DETAIL_TEXT"],
                    );
                    $PRODUCT_ID = $el->Add($arLoadProductArray);
                }
            }
		}
		return ["updated" => $updated, "added" => $added, "error" => $errors, "deact" => $allUsers];
	}

	function renewRecomendations() {
        $dbItems = \CIblockElement::GetList([],["IBLOCK_ID" => self::IBLOCKBASE], false, false, ["ID", "IBLOCK_SECTION_ID", "PROPERTY_TYPE"]);
        while ($arItems = $dbItems->GetNext()) {
            $recs = [];
            $types = [];
            $dbRecItems = \CIblockElement::GetList([],["IBLOCK_ID" => self::IBLOCKBASE, "SECTION_ID" => $arItems["IBLOCK_SECTION_ID"], "!PROPERTY_TYPE" => $arItems["PROPERTY_TYPE_VALUE"]], false, false, ["ID", "PROPERTY_TYPE"]);
            while ($arRecItems = $dbRecItems->GetNext()) {
                //pre($arRecItems);
                if (!in_array($arRecItems["PROPERTY_TYPE_VALUE"], $types)) {
                    $types[] = $arRecItems["PROPERTY_TYPE_VALUE"];
                    if (count($recs) < 3)
                        $recs[] = $arRecItems["ID"];
                }
            }
            if (count($recs)) {
                pre($arRecItems);
                pre($recs);
                pre($types);
                \CIBlockElement::SetPropertyValuesEx($arItems["ID"], self::IBLOCKBASE, ["REC" => $recs]);
            }
            //\CIBlockElement::SetPropertyValuesEx($arItems["ID"], self::IBLOCKBASE, ["REC" => false]);
        }
    }

	function prepareImages() {
		$start = time();
		$pictures = [];
		$nonPic = [];
		$updated = [];
        $el = new \CIblockElement;

		$dbElt = \CIBlockElement::GetList(array(),array("IBLOCK_ID" => self::IBLOCKBASE, "ACTIVE" => "Y"),false,false,array("ID" ,"PREVIEW_PICTURE", "CODE"));
		while ($arElt = $dbElt->GetNext()) {
			if ($arElt["PREVIEW_PICTURE"]) {
				ELCPicture($arElt["PREVIEW_PICTURE"], "short_detail");
				ELCPicture($arElt["PREVIEW_PICTURE"], "short_detail", true);
                ELCPicture($arElt["PREVIEW_PICTURE"], "detail_gallery");
                ELCPicture($arElt["PREVIEW_PICTURE"], "detail_gallery", true);
                ELCPicture($arElt["PREVIEW_PICTURE"], "detail_gallery_thumb");
                ELCPicture($arElt["PREVIEW_PICTURE"], "detail_gallery_thumb", true);
			} else {
			    $dbBQItem = \CIblockElement::GetList([],["IBLOCK_ID" => self::IBLOCKBQ, "CODE" => $arElt["CODE"]], false, false, ["ID", "PROPERTY_BQ_PREVIEW_PICTURE", "PROPERTY_BQ_MORE_PHOTO", "PROPERTY_BQ_SWATCH"]);
			    if ($arBQItem = $dbBQItem->GetNext()) {
			        if (count($arBQItem["PROPERTY_BQ_PREVIEW_PICTURE_VALUE"])) {
			            $el->Update($arElt["ID"], ["PREVIEW_PICTURE" => \CFile::MakeFileArray($arBQItem["PROPERTY_BQ_PREVIEW_PICTURE_VALUE"][0])]);
			            $updated[] = $arElt["CODE"];
                    } else {
			            $nonPic[] = $arElt["CODE"];
                    }
			        $pictures[$arElt["CODE"]] = $arBQItem;
                }
            }
		}

		/*$fp = fopen("nonpic.html", "w");
		$updatedTXT = implode("<br/>", $updated);
        $nonpicTXT = implode("<br/>", $nonPic);
        fwrite($fp,"Updated: ".$updatedTXT);
        fwrite($fp,"NonPic: ".$nonpicTXT);*/

		$dbElt = \CIBlockElement::GetList(array(),array("IBLOCK_ID" => self::IBLOCKSKU, "ACTIVE" => "Y"),false,false,array("ID", "CODE" ,"PROPERTY_MORE_PHOTO", "PROPERTY_SWATCH"));
		while ($arElt = $dbElt->GetNext()) {
			$gallery = array_merge($arElt['PROPERTY_MORE_PHOTO_VALUE'], $arElt["PROPERTY_SWATCH_VALUE"]);
			if (count($gallery)) {
                foreach ($gallery as $pict) {
                    ELCPicture($pict, "short_detail");
                    ELCPicture($pict, "short_detail", true);
                    ELCPicture($pict, "detail_gallery");
                    ELCPicture($pict, "detail_gallery", true);
                    ELCPicture($pict, "detail_gallery_thumb");
                    ELCPicture($pict, "detail_gallery_thumb", true);
                }
            } else {
                $dbBQItem = \CIblockElement::GetList([],["IBLOCK_ID" => self::IBLOCKBQ, "CODE" => $arElt["CODE"]], false, false, ["ID", "PROPERTY_BQ_MORE_PHOTO", "PROPERTY_BQ_SWATCH"]);
                if ($arBQItem = $dbBQItem->GetNext()) {
                    if (count($arBQItem["PROPERTY_BQ_SWATCH_VALUE"])) {
                        $tmpPict = [];
                        foreach ($arBQItem["PROPERTY_BQ_SWATCH_VALUE"] as $pictID)
                            $tmpPict[] = \CFile::MakeFileArray($pictID);
                        echo "SWATCH:";
                        pre($tmpPict);
                        \CIBlockElement::SetPropertyValuesEx($arElt["ID"], self::IBLOCKSKU, ["SWATCH" => $tmpPict]);
                    }
                    if (count($arBQItem["PROPERTY_BQ_MORE_PHOTO_VALUE"])) {
                        $tmpPict = [];
                        foreach ($arBQItem["PROPERTY_BQ_MORE_PHOTO_VALUE"] as $pictID)
                            $tmpPict[] = \CFile::MakeFileArray($pictID);
                        echo "MORE_PHOTO:";
                        pre($tmpPict);
                        \CIBlockElement::SetPropertyValuesEx($arElt["ID"], self::IBLOCKSKU, ["MORE_PHOTO" => $tmpPict]);
                    }
                }
            }
		}
		
		$end = time();
		$allTime = $end - $start;
	}
}
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

class FM {
	CONST IBLOCKBASE = 1;
	CONST IBLOCKSKU = 2;
	CONST IBLOCKPARFUMERS = 3;
	CONST IBLOCKFOLDERS = 5;
	CONST IBLOCKCOLLECTIONS = 6;

    protected $token = "SU9IMT6AT9NXPK9C9J2I";
    protected $appid = "ELTA";
    protected $domain = "esteelauder.brandquad.ru";

    protected $tokenEcom = "D72L30Q45T4NEOHAHWHE";
    protected $urlEcom = "bfbapi2.brandquad.ru/items_api/public/v2/ELTA/";

    protected $curl;
    protected $types = [];

    protected $importProductFields = [
			"BQSKU" => 0,
            "CAT_1" => 1,
			"CAT_2" => 5,
            "FOLDER" => 6,
            "SKU" => 3,
            "PROD_ID" => 4,
            "COLLECTION" => 10,
            "NAME" => 13,
            "CREATOR" => 14,
		];

    protected $importLocationFields = [
        "REGION" => 0,
        "COUNTRY" => 1,
        "CITY" => 2,
        "NAME" => 3,
        "ADDRESS" => 4,
        "STATE" => 5,
        "INDEX" => 6,
        "PHONE" => 7,
        "MAP" => 8,
        "WORK" => 9
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
            16107 => "PROPERTY_PROMO"
        ],
        "meta" => [
            "id" => "CODE",
            "name" => "NAME"
        ],
        "attributes" => [
            "15970" => "PROPERTY_VOL",
            "15969" => "PROPERTY_WEIGHT",
            "16262" => "PROPERTY_STATUS",
            "9235" => "DETAIL_TEXT",
            "9236" => "PREVIEW_TEXT",
            "16064" => "PROPERTY_CONTENT"
        ]
    ];

    private $spiders = [
        "goldapple.ru." => [
            "PRICE" => "PRICE_GA",
            "URL" => "URL_GA",
            "STOCK" => "STOCK_GA",
            "DESC" => "ЗОЛОТОЕ ЯБЛОКО"
        ],
        "tsum.ru" => [
            "PRICE" => "PRICE_TSUM",
            "URL" => "URL_TSUM",
            "STOCK" => "STOCK_TSUM",
            "DESC" => "ЦУМ"
        ],
        "rivegauche.ru" => [
            "PRICE" => "PRIVE_RG",
            "URL" => "URL_RG",
            "STOCK" => "STOCK_RG",
            "DESC" => "РИВ ГОШ"
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
        if ($params["skus"])
            $params["skus"] = implode(",",$params["skus"]);

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

            case "locations":
                foreach ($this->importLocationFields as $code => $key) {
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

    private function getCSVData($file, $delimiter = ";") {
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
                $data = explode($delimiter, $buffer);
                foreach ($data as $key=>$value)
                    $data[$key] = iconv("MacCyrillic", "UTF-8" ,$value);
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
            if (is_array($prop) && $prop["type"]) {
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

        if (count($ret["PROPERTY_VALUES"]["PROMO"])) {
            ksort($ret["PROPERTY_VALUES"]["PROMO"]);
            $tmpPhoto = [];
            foreach ($ret["PROPERTY_VALUES"]["PROMO"] as $key=>$value) {
                $tmpPhoto[] = ["VALUE" => $value, "DESCRIPTION" => $key];
            }
            $ret["PROPERTY_VALUES"]["PROMO"] = $tmpPhoto;
            $ret["PREVIEW_PICTURE"] = $ret["PROPERTY_VALUES"]["PROMO"][0]["VALUE"];
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

    function getSpiders() {
	    return $this->spiders;
    }

    function updatePrices() {
        $strings = [];
        $strings[] =
            [
                "CODE",
                "SPIDER",
                "NAME",
                "CUR_PRICE",
                "NEW_PRICE",
                "CHANGE",
            ];

        $dateStamp = strtotime("-1 day");
        $date = date('Y-m-d', $dateStamp);

        $codes = [];
        $bqStock = [];
        $dbItem = \CIblockElement::GetList([], ["IBLOCK_ID" => self::IBLOCKSKU],false,false,["ID", "PROPERTY_BQSKU"]);
        while ($arItem = $dbItem->GetNext()) {
            $codes[] = $arItem["PROPERTY_BQSKU_VALUE"];
        }

        $count = 0;
        $step = 9;
        foreach ($codes as $code) {
            $articuls[intval($count / $step)][] = $code;
            $count++;
        }

        foreach ($this->spiders as $spider=>$desc)
        {
            foreach ($articuls as $key => $codes)
            {
                $tmpRet = $this->callBQEcom("stocks", ["spiders" => $spider, "date_from" => $date, "skus" => $codes], $page);
                foreach ($tmpRet["products"] as $prod)
                {
                    if ($prod["stocks"][$date])
                    {
                        $in_stock = intval($prod["stocks"][$date]["in_stock"]);
                        $dbItem = \CIblockElement::GetList([], ["IBLOCK_ID" => self::IBLOCKSKU, "PROPERTY_BQSKU" => $prod["sku"]], false, false, ["ID", "NAME", "PROPERTY_BQSKU", "PROPERTY_" . $desc["STOCK"]]);
                        if ($arItem = $dbItem->GetNext())
                        {
                            $change = ($arItem["PROPERTY_" . $desc["STOCK"] . "_VALUE"] != $in_stock) ? 1 : 0;
                            \CIBlockElement::SetPropertyValuesEx($arItem["ID"], self::IBLOCKSKU, [$desc["STOCK"] => $in_stock]);
                            //$strings[] = [$prod["sku"], $spider, $arItem["NAME"], $arItem["PROPERTY_" . $desc["PRICE"] . "_VALUE"], $price, $change];
                        }
                    }
                }
            }
        }

        foreach ($this->spiders as $spider=>$desc) {
            $bqPrices = [];
            $page = 1;
            do {
                $tmpRet = $this->callBQEcom("prices", ["spider" => $spider, "brand" => "Frederic Malle", "date_from" => $date], $page);
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
                        $dbItem = \CIblockElement::GetList([], ["IBLOCK_ID" => self::IBLOCKSKU, "PROPERTY_BQSKU" => $prod["sku"]], false, false, ["ID", "NAME", "PROPERTY_BQSKU", "PROPERTY_".$desc["PRICE"]]);
                        if ($arItem = $dbItem->GetNext()) {
                            $change = ($arItem["PROPERTY_".$desc["PRICE"]."_VALUE"] != $price) ? 1 : 0;
                            \CIBlockElement::SetPropertyValuesEx($arItem["ID"], self::IBLOCKSKU, [$desc["PRICE"] => $price]);
                            $strings[] = [$prod["sku"], $spider, $arItem["NAME"], $arItem["PROPERTY_".$desc["PRICE"]."_VALUE"], $price, $change];
                        } else {
                            $strings[] = [$prod["sku"], $spider, "Нет на сайте", "", $price, 0];
                        }
                    }
                }

            $bqProducts = [];
            $page = 1;
            do {
                $tmpRet = $this->callBQEcom("products", ["spider" => $spider, "brand" => "Frederic Malle"], $page);
                if (count($tmpRet["products"])) {
                    foreach ($tmpRet["products"] as $prod)
                        $bqProducts[] = $prod;
                }
                $page++;
            } while ($tmpRet["meta"]["page"] < $tmpRet["meta"]["total_pages"]);
            if (count($bqProducts))
                foreach ($bqProducts as $prod) {
                    if ($prod["sku"]) {
                        $dbItem = \CIblockElement::GetList([], ["IBLOCK_ID" => self::IBLOCKSKU, "PROPERTY_BQSKU" => $prod["sku"]], false, false, ["ID", "NAME"]);
                        if ($arItem = $dbItem->GetNext()) {
                            \CIBlockElement::SetPropertyValuesEx($arItem["ID"], self::IBLOCKSKU, [$desc["URL"] => $prod["url"]]);
                        }
                    }
                }
        }
        $filename = $_SERVER["DOCUMENT_ROOT"] . "/upload/import_logs/prices_" . date("Y_m_d-H_i_s") . ".csv";
        $fp = fopen($filename, 'w');

        foreach ($strings as $string)
            fputcsv($fp, $string, ";");

        fclose($fp);

        //pre($bqProducts);
    }

    function importLocations($file) {
        if (!$file) return false;
        \CModule::IncludeModule("sale");
        $errors = [];
        $updated = [];
        $added = [];
        $new = [];
        $csv = [];


        $regions = [];
        $dbRegions = \CIblockSection::GetList([], ["IBLOCK_ID" => 10]);
        while ($arRegion = $dbRegions->GetNext()) {
            $regions[$arRegion["NAME"]] = $arRegion["ID"];
        }

        $extension = strtolower(GetFileExtension($file));

        switch ($extension) {
            case "xlsx":
                $csv = $this->getXLSData($file);
                break;
            case "csv":
                $csv = $this->getCSVData($file);
                break;
            case "txt":
                $csv = $this->getCSVData($file, "\t");
                break;
        }
        $count = 0;
        $el = new \CIBlockElement;
        if (count($csv["data"])) {
            foreach ($csv["data"] as $data) {
                $data = $this->clearLineCSV($data);
                $data = $this->parseCsv($data, "locations");
                if (!$data["NAME"]) continue;
                pre($data);
                if (!$regions[$data["REGION"]]) {
                    $bs = new \CIBlockSection;
                    $arFields = Array(
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => 10,
                        "NAME" => $data["REGION"],
                        "DEPTH_LEVEL" => 1,
                    );
                    if (!$fcID = $bs->Add($arFields)) {
                        echo $bs->LAST_ERROR;
                    } else {
                        $regions[$data["REGION"]] = $fcID;
                    }
                } else {
                    $fcID = $regions[$data["REGION"]];
                }
                if (!$regions[$data["COUNTRY"]]) {
                    $bs = new \CIBlockSection;
                    $arFields = Array(
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => 10,
                        "NAME" => $data["COUNTRY"],
                        "IBLOCK_SECTION_ID" => $fcID
                    );
                    if (!$scID = $bs->Add($arFields)) {
                        echo $bs->LAST_ERROR;
                    } else {
                        $regions[$data["COUNTRY"]] = $scID;
                    }
                } else {
                    $scID = $regions[$data["COUNTRY"]];
                }

                $PROP = [
                    "STORE" => $data["NAME"],
                    "ADDRESS" => $data["ADDRESS"],
                    "CITY" => $data["CITY"],
                    "PHONE" => str_replace("TEL: ", "", $data["PHONE"]),
                    "INDEX" => $data["INDEX"],
                    "MAP" => $data["MAP"]
                ];

                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => $scID,
                    "IBLOCK_ID"      => 10,
                    "PROPERTY_VALUES"=> $PROP,
                    "NAME"           => $data["CITY"],
                    "CODE" => \Cutil::translit($data["CITY"], "ru"),
                    "ACTIVE"         => "Y",
                );

                $el->Add($arLoadProductArray);

                $count++;
            }
        }
        echo $count;
    }

	function updateBQCatalog($timeStamp = 0, $updatePhotos = false, $update_id = 0) {
	    $start = time();
        $page = 0;
        $addedBQ = 0;
        $updatedBQ = 0;
        $photos = ["PROMO"];
        $el = new \CIBlockElement;

        $strings = [];
        $strings[] =
            [
                "CODE",
                "INFO_UPDATE",
                "PHOTO_UPDATE",
                "DEACTIVATED"
            ];

        if (!count($this->iblockBQProps)) {
            $dbProps = \CIBlockProperty::GetList([],["IBLOCK_ID" => self::IBLOCKBASE]);
            while ($arProp = $dbProps->GetNext()) {
                $this->iblockBQProps[$arProp["ID"]] = $arProp;
            }
        }

        $codes = [];
        $articuls = [];
        $needUpdate = [];

        $prodFilter = ["IBLOCK_ID" => self::IBLOCKSKU];
        if ($update_id) {
            $prodFilter["ID"] = $update_id;
        }

        $dbProds = \CIblockElement::GetList([], $prodFilter, false, false, ["ID", "NAME", "PROPERTY_BQSKU"]);
        while ($arProds = $dbProds->GetNext()) {
            if (mb_strlen($arProds["PROPERTY_BQSKU_VALUE"]) == 10) {
                $codes[] = $arProds["PROPERTY_BQSKU_VALUE"];
                //$types[$arProds["PROPERTY_SKU_VALUE"]] = \Cutil::translit($arProds["NAME"]."-".trim($arProds["PROPERTY_TYPE_NAME"]), "ru");
                $needUpdate[$arProds["PROPERTY_BQSKU_VALUE"]] = $arProds["ID"];
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

                    $status = $item["PROPERTY_VALUES"]["STATUS"];
                    $newProps = [
                        "HAVE_BQ" => 41,
                        "VOL" => $item["PROPERTY_VALUES"]["VOL"],
                        "WEIGHT" => $item["PROPERTY_VALUES"]["WEIGHT"],
                        "CONTENT" => $item["PROPERTY_VALUES"]["CONTENT"],
                        "STATUS" => $status,
                        "VOL_NUM" => intval($item["PROPERTY_VALUES"]["VOL"])
                    ];

                    $tmpPhoto = [];
                    $tmpDesc = [];
                    foreach ($photos as $key) {
                        if ($item["PROPERTY_VALUES"][$key]) {
                            $itemPhoto = $item["PROPERTY_VALUES"][$key];
                            foreach ($itemPhoto as $value) {
                                $tmpPhoto[] = ["VALUE" => \CFile::MakeFileArray($value["VALUE"]), "DESCRIPTION" => $value["DESCRIPTION"]];
                                $tmpDesc[] = $value["DESCRIPTION"];
                            }
                        }
                    }

                    $curDesc = [];
                    $dbPhoto = \CIBlockElement::GetProperty(self::IBLOCKSKU, $needUpdate[$item["CODE"]], [], Array("CODE"=>"PROMO"));
                    while ($arPhoto = $dbPhoto->GetNext()) {
                        $curDesc[] = $arPhoto["DESCRIPTION"];
                    }

                    $flag = false; // Нужно ли обновлять фотки

                    if (count($curDesc) != count($tmpDesc))
                        $flag = true;
                    else {
                        foreach ($curDesc as $key=>$value)
                            if ($value!=$tmpDesc[$key])
                                $flag = true;
                    }

                    $arLoadProductArray = [
                        "IBLOCK_ID" => self::IBLOCKSKU,
                        "NAME" => $item["NAME"],
                        "PREVIEW_TEXT" => $item["PREVIEW_TEXT"],
                        "DETAIL_TEXT" => $item["DETAIL_TEXT"],
                        "CODE" => \Cutil::translit($item["NAME"], "ru")
                    ];

                    if ($status == "Disco")
                    {
                        $arLoadProductArray["ACTIVE"] = "N";
                        $deactivated = 1;
                    } else {
                        $deactivated = 0;
                    }

                    if ($flag) {
                        $arLoadProductArray["PREVIEW_PICTURE"] = \CFile::MakeFileArray($item["PREVIEW_PICTURE"]);
                    }

                    $el->Update($needUpdate[$item["CODE"]], $arLoadProductArray);

                    if ($flag) {
                        $newProps["PROMO"] = $tmpPhoto;
                        $strings[] = [$item["CODE"], 1, 1, $deactivated];
                    } else {
                        $strings[] = [$item["CODE"], 1, 0, $deactivated];

                    }
                    \CIBlockElement::SetPropertyValuesEx($needUpdate[$item["CODE"]], self::IBLOCKSKU, $newProps);
                }
            }
        }
        $filename = $_SERVER["DOCUMENT_ROOT"]."/upload/import_logs/catalog_".date("Y_m_d-H_i_s").".csv";
        $fp = fopen($filename, 'w');

        foreach ($strings as $string)
            fputcsv($fp, $string,";");

        fclose($fp);
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
            case "txt":
                $csv = $this->getCSVData($file, "\t");
                break;
        }
		if (count($csv["data"])) {
		    $parfumers = [];
            $folders = [];
            $collections = [];

		    $dbTypes = \CIblockElement::GetList([],["IBLOCK_ID" => $this::IBLOCKPARFUMERS], false, false, ["ID", "NAME"]);
		    while ($arType = $dbTypes->GetNext()) {
                $parfumers[$arType["NAME"]] = $arType["ID"];
            }

            $dbTypes = \CIblockElement::GetList([],["IBLOCK_ID" => $this::IBLOCKFOLDERS], false, false, ["ID", "NAME"]);
            while ($arType = $dbTypes->GetNext()) {
                $folders[$arType["NAME"]] = $arType["ID"];
            }

            $dbTypes = \CIblockElement::GetList([],["IBLOCK_ID" => $this::IBLOCKCOLLECTIONS], false, false, ["ID", "NAME"]);
            while ($arType = $dbTypes->GetNext()) {
                $collections[$arType["NAME"]] = $arType["ID"];
            }

			foreach ($csv["data"] as $data) {
                $data = $this->clearLineCSV($data);
                $data = $this->parseCsv($data, "products");
                if (!$data["BQSKU"]) continue;
                if ($data["CAT_1"] == "New") continue;

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


                $el = new \CIBlockElement;
                $PROP = [];

                $creator = trim($data["CREATOR"]);
                $creator = str_replace("by ","",$creator);
                $creator = str_replace("By ","",$creator);

                if ($creator) {
                    if (!$parfumers[$creator]) {
                        $arLoadProductArray = Array(
                            "IBLOCK_ID" => $this::IBLOCKPARFUMERS,
                            "NAME" => $creator,
                            "ACTIVE" => "Y",
                            "CODE" => \Cutil::translit($creator, "ru"),
                        );
                        $creator_id = $el->Add($arLoadProductArray);
                        $parfumers[$creator] = $creator_id;
                    } else {
                        $creator_id = $parfumers[$creator];
                    }
                    $PROP["CREATOR"] = $creator_id;
                }

                $csv_folder = trim($data["FOLDER"]);
                if ($csv_folder) {
                    $csv_folder = explode("/", $csv_folder);
                    if (count($csv_folder)) {
                        foreach ($csv_folder as $tmpFolder) {
                            if (!$folders[$tmpFolder]) {
                                $arLoadProductArray = Array(
                                    "IBLOCK_ID" => $this::IBLOCKFOLDERS,
                                    "NAME" => $tmpFolder,
                                    "ACTIVE" => "Y",
                                    "PROPERTY_VALUES" => ["SECTION" => $fcID],
                                    "CODE" => \Cutil::translit($tmpFolder, "ru"),
                                );
                                $folder_id = $el->Add($arLoadProductArray);
                                $folders[$tmpFolder] = $folder_id;
                            } else {
                                $folder_id = $folders[$tmpFolder];
                            }
                            $PROP["FOLDERS"][] = $folder_id;
                        }
                    }
                }

                $collection = trim($data["COLLECTION"]);

                if ($collection) {
                    if (!$collections[$collection]) {
                        $arLoadProductArray = Array(
                            "IBLOCK_ID" => $this::IBLOCKCOLLECTIONS,
                            "NAME" => $collection,
                            "ACTIVE" => "Y",
                            "CODE" => \Cutil::translit($creator, "ru"),
                        );
                        $collection_id = $el->Add($arLoadProductArray);
                        $collections[$collection] = $collection_id;
                    } else {
                        $collection_id = $collections[$collection];
                    }
                    $PROP["COLLECTION"] = $collection_id;
                    $name = $collection;
                } else {
                    $name = $data["NAME"];
                }

                $ext_id = $data["PROD_ID"];
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => $sectID,
                    "IBLOCK_ID"      => $this::IBLOCKBASE,
                    "PROPERTY_VALUES"=> $PROP,
                    "EXTERNAL_ID" => $ext_id,
                    "NAME"           => $name,
                    "CODE" => \Cutil::translit($name, "ru"),
                    "ACTIVE"         => "Y",
                );
                $dbElt = \CIblockElement::GetList([], ["IBLOCK_ID" => $this::IBLOCKBASE, "EXTERNAL_ID" => $ext_id], false, false, ["ID", "NAME"]);
                if ($arElt = $dbElt->GetNext()) {
                    $PRODUCT_ID = $arElt["ID"];
                    $el->Update($PRODUCT_ID, $arLoadProductArray);
                    $updated[] = $PRODUCT_ID;
                } else {

                    if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {

                    } else {
                        pre($data);
                        pre($arLoadProductArray);
                    }
                    $added[] = $PRODUCT_ID;
                }
                if (strlen($data["BQSKU"]) == 10) {
                    $dbSku = \CIblockElement::GetList([], ["IBLOCK_ID" => $this::IBLOCKSKU, "PROPERTY_BQSKU" => $data["BQSKU"]], false, false, ["ID", "NAME"]);
                    if ($arSku = $dbSku->GetNext()) {
                        $SKU_ID = $arSku["ID"];
                    } else {
                        $PROP = [];
                        $PROP["CML2_LINK"] = $PRODUCT_ID;
                        $PROP["BQSKU"] = $data["BQSKU"];

                        $arLoadProductArray = Array(
                            "IBLOCK_ID" => $this::IBLOCKSKU,
                            "PROPERTY_VALUES" => $PROP,
                            "NAME" => $name,
                            "CODE" => \Cutil::translit($name, "ru"),
                            "ACTIVE" => "Y",
                        );
                        //pre($arLoadProductArray);
                        if ($SKU_ID = $el->Add($arLoadProductArray)) {

                        } else {
                            pre($data);
                            pre($arLoadProductArray);
                        }
                    }
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

    static function subscrubeCRM($email, $name = "", $sex = "") {
        if (!\COption::GetOptionString('ddp.mod', 'CRM_USERNAME') || !\COption::GetOptionString('ddp.mod', 'CRM_PASSWORD') || !\COption::GetOptionString('ddp.mod', 'CRM_HOST')) return false;
        $crm_host = \COption::GetOptionString('ddp.mod', 'CRM_HOST');
        if (!$name)
            $name = "N/A";
        switch ($sex) {
            case "Женский":
                $sex = \COption::GetOptionString('ddp.mod', 'CRM_FEMALE');
                break;
            case "Мужской":
                $sex = \COption::GetOptionString('ddp.mod', 'CRM_MALE');
                break;
            default:
                $sex = "";
                break;
        }

        if($curl = curl_init() ) {
            //$email = "press_enter@mail.ru";
            $data = array("UserName" => \COption::GetOptionString('ddp.mod', 'CRM_USERNAME'), "UserPassword" => \COption::GetOptionString('ddp.mod', 'CRM_PASSWORD'));
            $sourceID = \COption::GetOptionString('ddp.mod', 'CRM_SOURCE');
            $brandID = \COption::GetOptionString('ddp.mod', 'CRM_BRAND');
            $data_string = json_encode($data);
            $headers = array(
                'Content-Type: application/json',
                'Content-Length: ' . mb_strlen($data_string)
            );
            curl_setopt($curl, CURLOPT_URL, $crm_host.'/ServiceModel/AuthService.svc/Login');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_VERBOSE, 1);
            curl_setopt($curl, CURLOPT_HEADER, 1);
            $out = curl_exec($curl);
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi',
                $out,  $match_found);

            $cookies = array();
            foreach($match_found[1] as $item) {
                parse_str($item,  $cookie);
                $cookies = array_merge($cookies,  $cookie);
            }
            foreach ($cookies as $key=>$cookie) {
                if ($key == "_ASPXAUTH") {
                    unset($cookies[$key]);
                    $cookies[".ASPXAUTH"] = $cookie;
                }
            }

            $cookie_string = "";
            foreach ($cookies as $key=>$value) {
                if ($cookie_string)
                    $cookie_string.="; ";
                $cookie_string.=$key."=".$value;
            }

            $BPMCSRF = $cookies["BPMCSRF"];
            curl_close($curl);
            if ($BPMCSRF) {
                //$data = array("UserName" => "Supervisor", "UserPassword" => "!YRfbVW111");
                //$data_string = json_encode($data);
                $data_string = '{
                  "rootSchemaName":"Contact",
                   "operationType":0,
                   "columns":{
                      "items":{
                         "Id":{
                            "caption":"Id",
                            "isVisible":true,
                            "expression":{
                               "expressionType":0,
                               "columnPath":"Id"
                            }
                         },
                         "Name":{"caption":"","orderDirection":0,"orderPosition":-1,"isVisible":true,"expression":{"expressionType":0,"columnPath":"Name"}},
                         "Gender":{"caption":"Пол","orderDirection":0,"orderPosition":-1,"isVisible":true,"expression":{"expressionType":0,"columnPath":"Gender"}} 
                      }
                   },
                   "filters":{
                      "items":{
                         "Email":{
                            "filterType":1,
                            "comparisonType":3,
                            "isEnabled":true,
                            "leftExpression":{
                               "expressionType":0,
                               "columnPath":"Email"
                            },
                            "rightExpression":{
                               "expressionType":2,
                               "parameter":{
                                  "dataValueType":1,
                                  "value":"'.$email.'"
                               }
                            }
                         },
                         "Brand":{
                            "filterType":1,
                            "comparisonType":3,
                            "isEnabled":true,
                            "leftExpression":{
                               "expressionType":0,
                               "columnPath":"Brand"
                            },
                            "rightExpression":{
                               "expressionType":2,
                               "parameter":{
                                  "dataValueType":0,
                                  "value":"'.$brandID.'"
                               }
                            }
                         }
                      },
                      "logicalOperation":0,
                      "isEnabled":true,
                      "filterType":6
                   }
                }';
                $headers = array(
                    'Content-Type: application/json',
                    'Cookie: '.$cookie_string,
                    'BPMCSRF: '.$BPMCSRF,
                    //'Content-Length: ' . mb_strlen($data_string)
                );
                //print_r($headers);//die();
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $crm_host.'/0/dataservice/json/reply/SelectQuery');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_VERBOSE, 0);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_COOKIE, $cookie_string);
                $out = curl_exec($curl);
                curl_close($curl);
                $out = json_decode($out, true);

                $clientID = "";
                $isMainSource = "false";

                if ($out["success"] == 1) {
                    if (count($out["rows"])) {
                        $clientID = $out["rows"][0]["Id"];
                        if ($out["rows"][0]["Name"] == "N/A" && $name) {
                            $data_string = '
                                {
                                  "rootSchemaName": "Contact",
                                  "operationType": 2,
                                  "columnValues": {
                                    "items": {
                                      "GivenName": {
                                        "expressionType": 2,
                                        "parameter": {
                                          "dataValueType": 1,
                                          "value": "'.$name.'"
                                        }
                                      }
                                    }
                                  },
                                  "filters": {
                                    "items": {
                                      "Id": {
                                        "filterType": 1,
                                        "comparisonType": 3,
                                        "isEnabled": true,
                                        "leftExpression": {
                                          "expressionType": 0,
                                          "columnPath": "Id"
                                        },
                                        "rightExpression": {
                                          "expressionType": 2,
                                          "parameter": {
                                            "dataValueType": 0,
                                            "value": "'.$clientID.'"
                                          }
                                        }
                                      }
                                    },
                                    "logicalOperation": 0,
                                    "isEnabled": true,
                                    "filterType": 6
                                  }
                                }
                            ';
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $crm_host.'/0/dataservice/json/reply/UpdateQuery');
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($curl, CURLOPT_VERBOSE, 0);
                            curl_setopt($curl, CURLOPT_HEADER, 0);
                            curl_setopt($curl, CURLOPT_COOKIE, $cookie_string);
                            $out = curl_exec($curl);
                            $out = json_decode($out, true);
                        }
                        if (!$out["rows"][0]["Gender"] && $sex) {
                            $data_string = '
                                {
                                  "rootSchemaName": "Contact",
                                  "operationType": 2,
                                  "columnValues": {
                                    "items": {
                                      "Gender": {
                                        "expressionType": 2,
                                        "parameter": {
                                          "dataValueType": 10,
                                          "value": "'.$sex.'"
                                        }
                                      }
                                    }
                                  },
                                  "filters": {
                                    "items": {
                                      "Id": {
                                        "filterType": 1,
                                        "comparisonType": 3,
                                        "isEnabled": true,
                                        "leftExpression": {
                                          "expressionType": 0,
                                          "columnPath": "Id"
                                        },
                                        "rightExpression": {
                                          "expressionType": 2,
                                          "parameter": {
                                            "dataValueType": 0,
                                            "value": "'.$clientID.'"
                                          }
                                        }
                                      }
                                    },
                                    "logicalOperation": 0,
                                    "isEnabled": true,
                                    "filterType": 6
                                  }
                                }
                            ';
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $crm_host.'/0/dataservice/json/reply/UpdateQuery');
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($curl, CURLOPT_VERBOSE, 0);
                            curl_setopt($curl, CURLOPT_HEADER, 0);
                            curl_setopt($curl, CURLOPT_COOKIE, $cookie_string);
                            $out = curl_exec($curl);
                            $out = json_decode($out, true);
                        }
                    } else {
                        $data_string = '{
                          "rootSchemaName": "Contact",
                          "operationType": 1,
                          "columnValues": {
                            "items": {
                              "GivenName": {
                                "expressionType": 2,
                                "parameter": {
                                  "dataValueType": 1,
                                  "value": "'.$name.'"
                                }
                              },
                              "Email": {
                                "expressionType": 2,
                                "parameter": {
                                  "dataValueType": 1,
                                  "value": "'.$email.'"
                                }
                              },';
                        if ($sex)
                            $data_string.='
                              "Gender": {
                                "expressionType": 2,
                                "parameter": {
                                  "dataValueType": 10,
                                  "value": "'.$sex.'"
                                }
                              },';
                        $data_string.='
                              "Brand": {
                                "expressionType": 2,
                                "parameter": {
                                  "dataValueType": 10,
                                  "value": "'.$brandID.'"
                                }
                              }
                            }
                          }
                        }';
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $crm_host.'/0/dataservice/json/reply/InsertQuery');
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($curl, CURLOPT_VERBOSE, 0);
                        curl_setopt($curl, CURLOPT_HEADER, 0);
                        curl_setopt($curl, CURLOPT_COOKIE, $cookie_string);
                        $out = curl_exec($curl);
                        $out = json_decode($out, true);
                        if ($out['success'] == 1) {
                            $clientID = $out["id"];
                            $isMainSource = "true";
                        }
                    }
                }
                if ($clientID) {
                    $data_string = '{
                      "rootSchemaName":"SmrContactLeadSource",
                       "operationType":0,
                       "columns":{
                          "items":{
                             "Id":{
                                "caption":"Id",
                                "isVisible":true,
                                "expression":{
                                   "expressionType":0,
                                   "columnPath":"Id"
                                }
                             }
                          }
                       },
                       "filters":{
                          "items":{
                             "SmrLeadSource":{
                                "filterType":1,
                                "comparisonType":3,
                                "isEnabled":true,
                                "leftExpression":{
                                   "expressionType":0,
                                   "columnPath":"SmrLeadSource"
                                },
                                "rightExpression":{
                                   "expressionType":2,
                                   "parameter":{
                                      "dataValueType":1,
                                      "value":"'.$sourceID.'"
                                   }
                                }
                             },
                             "SmrContact":{
                                "filterType":1,
                                "comparisonType":3,
                                "isEnabled":true,
                                "leftExpression":{
                                   "expressionType":0,
                                   "columnPath":"SmrContact"
                                },
                                "rightExpression":{
                                   "expressionType":2,
                                   "parameter":{
                                      "dataValueType":0,
                                      "value":"'.$clientID.'"
                                   }
                                }
                             }
                          },
                          "logicalOperation":0,
                          "isEnabled":true,
                          "filterType":6
                       }
                    }';

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $crm_host.'/0/dataservice/json/reply/SelectQuery');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($curl, CURLOPT_VERBOSE, 0);
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    curl_setopt($curl, CURLOPT_COOKIE, $cookie_string);
                    $out = curl_exec($curl);
                    $out = json_decode($out, true);
                    if ($out["success"] == 1)
                    {
                        if (count($out["rows"]))
                        {
                            return ["status" => true, "new" => false];
                        } else
                        {
                            $objDateTime = new \DateTime('NOW');
                            $curDateTime = $objDateTime->format('c');

                            $data_string = '{
                              "rootSchemaName": "SmrContactLeadSource",
                              "operationType": 1,
                              "columnValues": {
                                "items": {
                                  "SmrLeadSource": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 10,
                                      "value": "'.$sourceID.'"
                                    }
                                  },
                                  "SmrContact": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 10,
                                      "value": "'.$clientID.'"
                                    }
                                  },
                                  "SmrIsMain": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 12,
                                      "value": "'.$isMainSource.'"
                                    }
                                  },
                                  "SmrSourceDate": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 7,
                                      "value": "\"'.$curDateTime.'\""
                                    }
                                  }
                                }
                              }
                            }';

                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $crm_host.'/0/dataservice/json/reply/InsertQuery');
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($curl, CURLOPT_VERBOSE, 0);
                            curl_setopt($curl, CURLOPT_HEADER, 0);
                            curl_setopt($curl, CURLOPT_COOKIE, $cookie_string);
                            $out = curl_exec($curl);
                            $out = json_decode($out, true);
                            return ["status" => true, "new" => true];
                        }
                    }
                }
            }
        }
    }

    static function subscrubeWelcomeCRM($contact) {
        if (!\COption::GetOptionString('ddp.mod', 'CRM_USERNAME') || !\COption::GetOptionString('ddp.mod', 'CRM_PASSWORD') || !\COption::GetOptionString('ddp.mod', 'CRM_HOST')) return false;
        $crm_host = \COption::GetOptionString('ddp.mod', 'CRM_HOST');

        if($curl = curl_init() ) {
            $data = array("UserName" => \COption::GetOptionString('ddp.mod', 'CRM_USERNAME'), "UserPassword" => \COption::GetOptionString('ddp.mod', 'CRM_PASSWORD'));
            $sourceID = \COption::GetOptionString('ddp.mod', 'CRM_SOURCE');
            $brandID = \COption::GetOptionString('ddp.mod', 'CRM_BRAND');
            $eventEmailConfirmationId = \COption::GetOptionString('ddp.mod', 'CRM_EMAIL_CONFIRMATION');

            $data_string = json_encode($data);
            $headers = array(
                'Content-Type: application/json',
                'Content-Length: ' . mb_strlen($data_string)
            );
            curl_setopt($curl, CURLOPT_URL, $crm_host.'/ServiceModel/AuthService.svc/Login');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_VERBOSE, 1);
            curl_setopt($curl, CURLOPT_HEADER, 1);
            $out = curl_exec($curl);
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi',
                $out,  $match_found);

            $cookies = array();
            foreach($match_found[1] as $item) {
                parse_str($item,  $cookie);
                $cookies = array_merge($cookies,  $cookie);
            }
            foreach ($cookies as $key=>$cookie) {
                if ($key == "_ASPXAUTH") {
                    unset($cookies[$key]);
                    $cookies[".ASPXAUTH"] = $cookie;
                }
            }

            $cookie_string = "";
            foreach ($cookies as $key=>$value) {
                if ($cookie_string)
                    $cookie_string.="; ";
                $cookie_string.=$key."=".$value;
            }

            $BPMCSRF = $cookies["BPMCSRF"];
            curl_close($curl);
            if ($BPMCSRF) {
                $data_string = '{
                  "rootSchemaName":"Contact",
                   "operationType":0,
                   "columns":{
                      "items":{
                         "Id":{
                            "caption":"Id",
                            "isVisible":true,
                            "expression":{
                               "expressionType":0,
                               "columnPath":"Id"
                            }
                         },
                         "Name":{"caption":"","orderDirection":0,"orderPosition":-1,"isVisible":true,"expression":{"expressionType":0,"columnPath":"Name"}},
                         "Gender":{"caption":"Пол","orderDirection":0,"orderPosition":-1,"isVisible":true,"expression":{"expressionType":0,"columnPath":"Gender"}} 
                      }
                   },
                   "filters":{
                      "items":{
                         "Id":{
                            "filterType":1,
                            "comparisonType":3,
                            "isEnabled":true,
                            "leftExpression":{
                               "expressionType":0,
                               "columnPath":"Id"
                            },
                            "rightExpression":{
                               "expressionType":2,
                               "parameter":{
                                  "dataValueType":1,
                                  "value":"'.$contact.'"
                               }
                            }
                         },
                         "Brand":{
                            "filterType":1,
                            "comparisonType":3,
                            "isEnabled":true,
                            "leftExpression":{
                               "expressionType":0,
                               "columnPath":"Brand"
                            },
                            "rightExpression":{
                               "expressionType":2,
                               "parameter":{
                                  "dataValueType":0,
                                  "value":"'.$brandID.'"
                               }
                            }
                         }
                      },
                      "logicalOperation":0,
                      "isEnabled":true,
                      "filterType":6
                   }
                }';
                $headers = array(
                    'Content-Type: application/json',
                    'Cookie: '.$cookie_string,
                    'BPMCSRF: '.$BPMCSRF,
                    //'Content-Length: ' . mb_strlen($data_string)
                );
                //print_r($headers);//die();
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $crm_host.'/0/dataservice/json/reply/SelectQuery');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_VERBOSE, 0);
                curl_setopt($curl, CURLOPT_HEADER, 0);
                curl_setopt($curl, CURLOPT_COOKIE, $cookie_string);
                $out = curl_exec($curl);
                curl_close($curl);
                $out = json_decode($out, true);

                $clientID = 0;
                $isMainSource = "false";

                if ($out["success"] == 1) {
                    if (count($out["rows"])) {
                        $clientID = $out["rows"][0]["Id"];
                        $objDateTime = new \DateTime('NOW');
                        $curDateTime = $objDateTime->format('c');

                        $data_string = '{
                              "rootSchemaName": "SiteEvent",
                              "operationType": 1,
                              "columnValues": {
                                "items": {
                                  "Contact": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 10,
                                      "value": '.$clientID.'	//Id контакта
                                    }
                                  },
                                  "SiteEventType": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 10,
                                      "value": '.$eventEmailConfirmationId.'
                                    }
                                  },
                                  "Source": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 1,
                                      "value": '.$_SERVER["SERVER_NAME"].'
                                    }
                                  },
                                  "EventDate": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 7,
                                      "value": "\"'.$curDateTime.'\""
                                    }
                                  }
                                }
                              }
                            }';

                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $crm_host.'/0/dataservice/json/reply/InsertQuery');
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($curl, CURLOPT_VERBOSE, 0);
                        curl_setopt($curl, CURLOPT_HEADER, 0);
                        curl_setopt($curl, CURLOPT_COOKIE, $cookie_string);
                        $out = curl_exec($curl);
                        $out = json_decode($out, true);

                        $data_string = '
                                {
                                  "rootSchemaName": "Contact",
                                  "operationType": 2,
                                  "columnValues": {
                                    "items": {
                                    "EmailConfirmed": {
                                            "expressionType": 2,
                                            "parameter": {
                                              "dataValueType": 12,
                                              "value": true	
                                            }
                                          }
                                    }
                                  },
                                  "filters": {
                                    "items": {
                                      "Id": {
                                        "filterType": 1,
                                        "comparisonType": 3,
                                        "isEnabled": true,
                                        "leftExpression": {
                                          "expressionType": 0,
                                          "columnPath": "Id"
                                        },
                                        "rightExpression": {
                                          "expressionType": 2,
                                          "parameter": {
                                            "dataValueType": 0,
                                            "value": "'.$clientID.'"
                                          }
                                        }
                                      }
                                    },
                                    "logicalOperation": 0,
                                    "isEnabled": true,
                                    "filterType": 6
                                  }
                                }
                            ';
                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, $crm_host.'/0/dataservice/json/reply/UpdateQuery');
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($curl, CURLOPT_VERBOSE, 0);
                        curl_setopt($curl, CURLOPT_HEADER, 0);
                        curl_setopt($curl, CURLOPT_COOKIE, $cookie_string);
                        $out = curl_exec($curl);
                        $out = json_decode($out, true);
                    }
                }
                if ($clientID) {
                    $data_string = '{
                      "rootSchemaName":"BulkEmailSubscription",
                       "operationType":0,
                       "columns":{
                          "items":{
                             "Id":{
                                "caption":"Id",
                                "isVisible":true,
                                "expression":{
                                   "expressionType":0,
                                   "columnPath":"Id"
                                }
                             }
                          }
                       },
                       "filters":{
                          "items":{
                             "Contact":{
                                "filterType":1,
                                "comparisonType":3,
                                "isEnabled":true,
                                "leftExpression":{
                                   "expressionType":0,
                                   "columnPath":"Contact"
                                },
                                "rightExpression":{
                                   "expressionType":2,
                                   "parameter":{
                                      "dataValueType":0,
                                      "value":"'.$clientID.'"
                                   }
                                }
                             },
                            "BulkEmailType": {
                              "filterType": 1,
                              "comparisonType": 3,
                              "isEnabled": true,
                              "leftExpression": {
                                "expressionType": 0,
                                "columnPath": "BulkEmailType"
                              },
                              "rightExpression": {
                                "expressionType": 2,
                                "parameter": {
                                  "dataValueType": 0,
                                  "value": "bb1324d5-c18a-473a-8515-b65309f70ee2"
                                }
                              }
                            },
                            "SmrSubscriptionChannel": {
                              "filterType": 1,
                              "comparisonType": 3,
                              "isEnabled": true,
                              "leftExpression": {
                                "expressionType": 0,
                                "columnPath": "SmrSubscriptionChannel"
                              },
                              "rightExpression": {
                                "expressionType": 2,
                                "parameter": {
                                  "dataValueType": 0,
                                  "value": "900eb835-3503-42f2-ac85-a0322acec8bd"
                                }
                              }
                            },
                            "BulkEmailSubsStatus": {
                              "filterType": 1,
                              "comparisonType": 3,
                              "isEnabled": true,
                              "leftExpression": {
                                "expressionType": 0,
                                "columnPath": "BulkEmailSubsStatus"
                              },
                              "rightExpression": {
                                "expressionType": 2,
                                "parameter": {
                                  "dataValueType": 0,
                                  "value": "1a5cd9b8-b999-4b65-b8a8-bd3168792128"
                                }
                              }
                            },
                            "SmrSubscriptionSource": {
                              "filterType": 1,
                              "comparisonType": 3,
                              "isEnabled": true,
                              "leftExpression": {
                                "expressionType": 0,
                                "columnPath": "SmrSubscriptionSource"
                              },
                              "rightExpression": {
                                "expressionType": 2,
                                "parameter": {
                                  "dataValueType": 0,
                                  "value": "02e3003a-9128-4d0f-af24-62d0b55db0b0"
                                }
                              }
                            }
                         },
                          
                          "logicalOperation":0,
                          "isEnabled":true,
                          "filterType":6
                       }
                    }';

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $crm_host.'/0/dataservice/json/reply/SelectQuery');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($curl, CURLOPT_VERBOSE, 0);
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    curl_setopt($curl, CURLOPT_COOKIE, $cookie_string);
                    $out = curl_exec($curl);
                    $out = json_decode($out, true);

                    if ($out["success"] == 1)
                    {
                        if (count($out["rows"]))
                        {
                            return ["status" => true, "new" => false];
                        } else
                        {
                            $objDateTime = new \DateTime('NOW');
                            $curDateTime = $objDateTime->format('c');

                            $data_string = '{
                              "rootSchemaName": "BulkEmailSubscription",
                              "operationType": 1,
                              "columnValues": {
                                "items": {
                                  "Contact": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 10,
                                      "value": '.$clientID.'
                                    }
                                  },
                                  "BulkEmailType": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 10,
                                      "value": " bb1324d5-c18a-473a-8515-b65309f70ee2"
                                    }
                                  },
                                  "SmrSubscriptionChannel": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 10,
                                      "value": "900eb835-3503-42f2-ac85-a0322acec8bd"
                                    }
                                  },
                                 "BulkEmailSubsStatus": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 10,
                                      "value": "1a5cd9b8-b999-4b65-b8a8-bd3168792128"
                                    }
                                  },
                                 "SmrSubscriptionSource": {
                                    "expressionType": 2,
                                    "parameter": {
                                      "dataValueType": 10, 
                                      "value": "02e3003a-9128-4d0f-af24-62d0b55db0b0"
                                    }
                                  }
                                }
                              }
                            }';

                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, $crm_host.'/0/dataservice/json/reply/InsertQuery');
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($curl, CURLOPT_VERBOSE, 0);
                            curl_setopt($curl, CURLOPT_HEADER, 0);
                            curl_setopt($curl, CURLOPT_COOKIE, $cookie_string);
                            $out = curl_exec($curl);
                            $out = json_decode($out, true);
                            return ["status" => true, "new" => true];
                        }
                    }
                }
            }
        }
    }
}
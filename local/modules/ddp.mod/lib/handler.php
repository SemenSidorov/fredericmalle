<?php


namespace DDP\Mod;

class Handler {
    protected static $handlerDisallow = false;

    public static function volUpdate(&$arFields) {
        \CModule::IncludeModule("iblock");

        if (self::$handlerDisallow)
            return;
        if ($arFields["IBLOCK_ID"] == 2) {
            self::$handlerDisallow = true;

            $dbElt = \CIblockElement::GetList([],["IBLOCK_ID" => 2, "ID" => $arFields["ID"]], false ,false, ["ID" ,"PROPERTY_VOL", "PROPERTY_HAVE_BQ"]);
            if ($arElt = $dbElt->GetNext()) {
            /*    if ($arElt["PROPERTY_VOL_VALUE"]) {
                    $volNumber = intval($arElt["PROPERTY_VOL_VALUE"]);
                    \CIBlockElement::SetPropertyValuesEx($arElt["ID"], 2, ["VOL_NUM" => $volNumber]);
                }*/
                if ($arElt["PROPERTY_HAVE_BQ_VALUE"] != "Y") {
                    \CModule::includeModule("ddp.mod");
                    $estee = new \DDP\Mod\FM();
                    $estee->updateBQCatalog(0, true, $arElt["ID"]);
                }
            }

            self::$handlerDisallow = false;
        }
    }
}

?>
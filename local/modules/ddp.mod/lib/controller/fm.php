<?php
namespace DDP\Mod\Controller;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;

class FM extends Controller
{
    /**
     * @return array
     */
    public function configureActions()
    {
        return [
            'subscribe' => [
                'prefilters' => []
            ],
            'subscribeCRM' => [
                'prefilters' => []
            ],
            'cookie' => [
                'prefilters' => []
            ]

        ];
    }

    /**
     * @param string $param2
     * @param string $param1
     * @return array
     */
    public static function subscribeAction($name = "", $last_name = "", $email, $confirm, $country = "", $sex = "")
    {
        if (!$email)
            return [
                "result" => "error",
                "type" => "email"
            ];

        if ($confirm == "false")
            return [
                "result" => "error",
                "type" => "confirm"
            ];

        $result = \DDP\Mod\FM::subscrubeCRM($email, $name, $sex);

        if ($result["new"]) {
            return [
                "result" => "ok",
                //"id" => $subs_id
            ];
        } else {
            return [
                "result" => "already",
                //"id" => $arEmail["ID"]
            ];
        }
/*        \CModule::IncludeModule("iblock");
        $dbEmail = \CIblockElement::GetList([], ["IBLOCK_ID" => 13, "NAME" => $email], false, false, ["ID", "NAME"]);
        if ($arEmail = $dbEmail->GetNext()) {
            return [
                "result" => "already",
                "id" => $arEmail["ID"]
            ];
        } else {
            $el = new \CIBlockElement;
            $PROP = [
                "NAME" => $name,
                "LASTNAME" => $last_name,
                "COUNTRY" => $country,
                "SEX" => $sex
            ];

            $arNew = [
                "NAME" => $email,
                "IBLOCK_ID" => 13,
                "PROPERTY_VALUES"=> $PROP
            ];
            if ($subs_id = $el->Add($arNew)) {
                return [
                    "result" => "ok",
                    "id" => $subs_id
                ];
            }
        }*/
    }

    public static function subscribeCRMAction($confirm, $contact)
    {
        if (!$contact)
            return [
                "result" => "error",
                "type" => "contact"
            ];

        if ($confirm == "false")
            return [
                "result" => "error",
                "type" => "confirm"
            ];

        $result = \DDP\Mod\FM::subscrubeWelcomeCRM($contact);

        if ($result["new"]) {
            return [
                "result" => "ok",
                //"id" => $subs_id
            ];
        } else {
            return [
                "result" => "already",
                //"id" => $arEmail["ID"]
            ];
        }
        /*        \CModule::IncludeModule("iblock");
                $dbEmail = \CIblockElement::GetList([], ["IBLOCK_ID" => 13, "NAME" => $email], false, false, ["ID", "NAME"]);
                if ($arEmail = $dbEmail->GetNext()) {
                    return [
                        "result" => "already",
                        "id" => $arEmail["ID"]
                    ];
                } else {
                    $el = new \CIBlockElement;
                    $PROP = [
                        "NAME" => $name,
                        "LASTNAME" => $last_name,
                        "COUNTRY" => $country,
                        "SEX" => $sex
                    ];

                    $arNew = [
                        "NAME" => $email,
                        "IBLOCK_ID" => 13,
                        "PROPERTY_VALUES"=> $PROP
                    ];
                    if ($subs_id = $el->Add($arNew)) {
                        return [
                            "result" => "ok",
                            "id" => $subs_id
                        ];
                    }
                }*/
    }

    public static function cookieAction($confirm)
    {
        if ($confirm == "false")
            return [
                "result" => "error",
                "type" => "confirm"
            ];

        global $APPLICATION;
        $APPLICATION->set_cookie("ELC_FM_AGREEMENT_NEW", "Y", time()+60*60*24*30*12*2, "/");

        return [
            "result" => "ok"
        ];
    }
}
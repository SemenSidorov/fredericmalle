<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class Perfum_Note extends CBitrixComponent
{
    public function executeComponent()
    {
        CModule::IncludeModule("iblock");
        $this->setFrameMode(false);

        $dbNotes = CIblockElement::GetList(["SORT" => "ASC", "NAME" => "ASC"],["IBLOCK_ID" => 4], false, false, ["ID", "NAME", "DETAIL_PAGE_URL"]);
        while ($arNote = $dbNotes->GetNext()) {
            $this->arResult["NOTES"][] = $arNote;
        }

        $this->includeComponentTemplate();
    }
}

?>
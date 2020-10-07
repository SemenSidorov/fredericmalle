<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Уход — Французский парфюмерный издательский дом Editions de Parfums Frederic Malle.");
$APPLICATION->SetTitle("Уход");
?>
<?$APPLICATION->IncludeComponent(
    'ddp:catalog',
    '',
    array(
        'IBLOCK_TYPE' => 'products',
        'IBLOCK_ID' => 1,
        'IBLOCK_SKU_ID' => 2,
        'SEF_MODE'	=>	'Y',
        'SEF_FOLDER'	=> 	'/products/'
    ),
    $component,
    array('HIDE_ICONS' => 'Y')
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
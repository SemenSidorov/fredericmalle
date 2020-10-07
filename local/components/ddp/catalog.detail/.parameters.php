<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

use \Bitrix\Main;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

try
{
	$arComponentParameters = CComponentUtil::GetComponentProps('ddp:base.detail', $arCurrentValues);
}
catch (Main\SystemException $e)
{
	ShowError($e->getMessage());
}

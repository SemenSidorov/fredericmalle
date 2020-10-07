<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
define("HIDE_SIDEBAR", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");global $APPLICATION;
	$APPLICATION->SetTitle("Страница не найдена");
?>
    <section class="page-error">
        <p class="page-error__title">Извините, запрашиваемой страницы не существует.</p>
        <p class="page-error__subtitle">Попробуйте перейти на <a href="/">главную страницу</a> сайта.</p>
    </section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
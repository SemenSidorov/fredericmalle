<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
$rsSites = CSite::GetByID(SITE_ID);
$arSite = $rsSites->Fetch();
$siteUrl = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'];
?>
<?
define("SITE_SERVER_PROTOCOL", (CMain::IsHTTPS()) ? "https://" : "http://");
$curPage = $APPLICATION->GetCurPage();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta property="og:url" content="<?=SITE_SERVER_PROTOCOL . SITE_SERVER_NAME . $curPage?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?$APPLICATION->ShowTitle();?>  | Официальный веб-сайт <?=$arSite["SITE_NAME"]?> ">
    <meta property="og:description" content="<?$APPLICATION->ShowProperty('description');?>">
    <meta property="og:image" content="<?=$siteUrl?><?$APPLICATION->ShowProperty('OGImage', SITE_TEMPLATE_PATH.'/_html/Result/Content/images/main_home_logo.png');?>">

    <!-- Google Tag Manager -->

    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':

                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],

            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=

            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);

        })(window,document,'script','dataLayer','GTM-MND3TMG');</script>

    <!-- End Google Tag Manager -->

    <!-- Иконка сайта -->

    <link sizes="16x16" href="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/favicon-16x16.png" rel="icon" />
    <link sizes="32x32" href="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/favicon-32x32.png" rel="icon" />

     <!-- Иконка сайта -->

	<?
		if (isset($_SERVER['HTTPS']))
			$scheme = $_SERVER['HTTPS'];
		else
			$scheme = '';
		if (($scheme) && ($scheme != 'off')) $scheme = 'https://';
		else $scheme = 'http://';
		$sku = explode('?', $_SERVER['REQUEST_URI']);
	?>
		<link rel="canonical" href="<?=$scheme.$_SERVER['SERVER_NAME'].$sku[0]?>"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?$APPLICATION->ShowTitle();?> | Официальный веб-сайт <?=$arSite["SITE_NAME"]?></title>

    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/css/main.css">
    <?$APPLICATION->ShowHead(); ?>
</head>

<body>

<!-- Google Tag Manager (noscript) -->

<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MND3TMG"

                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

<!-- End Google Tag Manager (noscript) -->

<?$APPLICATION->ShowPanel();?>
<div class="layout">
<? $APPLICATION->ShowViewContent("custom-subscribe") ?>
    <?$APPLICATION->IncludeComponent("bitrix:menu", "header", array(
        "ROOT_MENU_TYPE" => "top",
        "MENU_CACHE_TYPE" => "Y",
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "MENU_THEME" => "site",
        "CACHE_SELECTED_ITEMS" => "N",
        "MENU_CACHE_GET_VARS" => array(),
        "MAX_LEVEL" => "2",
        "CHILD_MENU_TYPE" => "left",
        "USE_EXT" => "Y",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "N",
    ),
        false
    );?>

    <?$APPLICATION->IncludeComponent(
        "ddp:search.title",
        "",
        Array(
            "CATEGORY_0" => array("iblock_products"),
            "CATEGORY_0_TITLE" => "",
            "CATEGORY_0_iblock_products" => array("1", "4", "5"),
            "CATEGORY_1" => array("iblock_info"),
            "CATEGORY_1_iblock_products" => array("3"),
            "CHECK_DATES" => "N",
            "CONTAINER_ID" => "title-search",
            "INPUT_ID" => "title-search-input",
            "NUM_CATEGORIES" => "2",
            "ORDER" => "date",
            "PAGE" => "/search/index.php",
            "SHOW_INPUT" => "Y",
            "SHOW_OTHERS" => "N",
            "TOP_COUNT" => "5",
            "USE_LANGUAGE_GUESS" => "Y",
            "USE_SUGGEST" => "Y"
        )
    );?>

    <div id="location">
        <div class="location-main">
            <div class="close"></div>
            <p class="location__title">Выберите ваше местоположение</p>
            <ul class="location__list">
                <li class="location__item"><a class="location__link" href="https://www.fredericmalle.eu/fr-e-UF#forcedLocale">France</a> </li>
                <li class="location__item"><a class="location__link" href="https://www.fredericmalle.eu/#forcedLocale">Europe</a> </li>
                <li class="location__item"><a class="location__link" href="https://www.fredericmalle.co.uk/">United Kingdom</a> </li>
                <li class="location__item"><a class="location__link" href="https://www.fredericmalle.com/">United States</a> </li>
                <li class="location__item"><a class="location__link" href="https://www.fredericmalle.com/">International</a> </li>
                <li class="location__item"><a class="location__link" href="https://www.fredericmalle.com/jp?LOCALE=en_US&stores=1#forcedLocale">Japan</a> </li>
            </ul>
        </div>
    </div>

    <?if (!defined("WITHOUT_MAIN_DIV")):?>
    <div class="main">
        <?if (!defined("WITHOUT_CONTENT_DIV")):?>
            <div class="content">
        <?endif?>
    <?endif?>
        <?if (array_key_exists('is_ajax', $_REQUEST) && $_REQUEST['is_ajax']=='y') {
            global $APPLICATION;
            $APPLICATION->RestartBuffer();
        }?>
        <?if(explode('?', $_SERVER['REQUEST_URI'])[0] !== '/' && explode('?', $_SERVER['REQUEST_URI'])[0] !== '' && strpos($_SERVER['REQUEST_URI'], 'products') === false && strpos($_SERVER['REQUEST_URI'], '/about/special-projects') === false && strpos($_SERVER['REQUEST_URI'], '/about/stores') === false){?>    
            <?$APPLICATION->IncludeComponent(
                "ddp:breadcrumb", 
                "castom", 
                Array(
                    "PATH" => "",	// Путь, для которого будет построена навигационная цепочка (по умолчанию, текущий путь)
                    "SITE_ID" => "s1",	// Cайт (устанавливается в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
                    "START_FROM" => "1",	// Номер пункта, начиная с которого будет построена навигационная цепочка
                ),
                false
            );?>
        <?}?>

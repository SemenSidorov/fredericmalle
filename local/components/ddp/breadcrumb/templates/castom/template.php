<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";

$rsSites = CSite::GetByID(SITE_ID);
$arSite = $rsSites->Fetch();

$strReturn = '';
$coutner = 0;

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()
$css = $APPLICATION->GetCSSArray();
if(!is_array($css) || !in_array("/bitrix/css/main/font-awesome.css", $css))
{
	$strReturn .= '<link href="'.CUtil::GetAdditionalFileURL("/bitrix/css/main/font-awesome.css").'" type="text/css" rel="stylesheet" />'."\n";
}

$strReturn .= '
<section class="breadcrumbs-section">

    <main itemscope itemtype="http://schema.org/WebPage"></main>

    <ul itemscope="" itemtype="http://schema.org/BreadcrumbList" class="breadcrumbs" id="breadcrumbs">

        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a href="/" title="Главная" itemprop="item" tabindex="">
                <span itemprop="name" content="Главная">Главная</span>
                <meta itemprop="position" content="0">
            </a>
        </li>';

if(strpos($_SERVER['REQUEST_URI'], '/products/body/') !== false && strpos($_SERVER['REQUEST_URI'], '/detail/') !== false){
		
	$strReturn .= '
			<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
				<a href="/products/body" title="Уход" itemprop="item" tabindex="">
					<span itemprop="name" content="Уход">Уход</span>
					<meta itemprop="position" content="1">
				</a>
			</li>';
	$coutner += 1;
}
if(strpos($_SERVER['REQUEST_URI'], '/products/home/') !== false && strpos($_SERVER['REQUEST_URI'], '/detail/') !== false){
		
	$strReturn .= '
			<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
				<a href="/products/home" title="Для дома" itemprop="item" tabindex="">
					<span itemprop="name" content="Для дома">Для дома</span>
					<meta itemprop="position" content="1">
				</a>
			</li>';
	$coutner += 1;
}
if(strpos($_SERVER['REQUEST_URI'], '/products/gifts/') !== false && strpos($_SERVER['REQUEST_URI'], '/detail/') !== false){
		
	$strReturn .= '
			<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
				<a href="/products/gifts" title="Подарки" itemprop="item" tabindex="">
					<span itemprop="name" content="Подарки">Подарки</span>
					<meta itemprop="position" content="1">
				</a>
			</li>';
	$coutner += 1;
}
if(strpos($_SERVER['REQUEST_URI'], '/products/perfume/') !== false && strpos($_SERVER['REQUEST_URI'], '/detail/') !== false){
		
	$strReturn .= '
			<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
				<a href="/products/perfume" title="Ароматы" itemprop="item" tabindex="">
					<span itemprop="name" content="Ароматы">Ароматы</span>
					<meta itemprop="position" content="1">
				</a>
			</li>';
	$coutner += 1;
}
if(strpos($_SERVER['REQUEST_URI'], '/body/') !== false && strpos($_SERVER['REQUEST_URI'], '/detail/') === false){
	if(strpos($_SERVER['REQUEST_URI'], '/dlya_volos') !== false){
		$strReturn .= '
				<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
					<a href="/products/body" title="Уход" itemprop="item" tabindex="">
						<span itemprop="name" content="Уход">Уход</span>
						<meta itemprop="position" content="1">
					</a>
				</li>
				<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" class="breadcrumbs-nameitem">
					<span itemprop="name" content="Для волос">Для волос</span>
					<meta itemprop="position" content="2">
				</li>
			</ul>
		
		</section>';
		return $strReturn;
	}
	if(strpos($_SERVER['REQUEST_URI'], '/cleans') !== false){
		$strReturn .= '
				<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
					<a href="/products/body" title="Уход" itemprop="item" tabindex="">
						<span itemprop="name" content="Уход">Уход</span>
						<meta itemprop="position" content="1">
					</a>
				</li>
				<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" class="breadcrumbs-nameitem">
					<span itemprop="name" content="Очищение">Очищение</span>
					<meta itemprop="position" content="2">
				</li>
			</ul>
		
		</section>';
		return $strReturn;
	}
	if(strpos($_SERVER['REQUEST_URI'], '/nourish') !== false){
		$strReturn .= '
				<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
					<a href="/products/body" title="Уход" itemprop="item" tabindex="">
						<span itemprop="name" content="Уход">Уход</span>
						<meta itemprop="position" content="1">
					</a>
				</li>
				<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" class="breadcrumbs-nameitem">
					<span itemprop="name" content="Увлажнение">Увлажнение</span>
					<meta itemprop="position" content="2">
				</li>
			</ul>
		
		</section>';
		return $strReturn;
	}
}
$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	$title = str_replace('| Официальный веб-сайт '.$arSite["SITE_NAME"], '', $arResult[$index]["TITLE"]);
	$title = htmlspecialcharsex($title);
	$arrow = ($index > 0? '<i class="fa fa-angle-right"></i>' : '');

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .= '
        <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
            <a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" itemprop="item" class="camel-case" tabindex="'.($index + 1).'">
                <span itemprop="name" content="'.$title.'">'.$title.'</span>
                <meta itemprop="position" content="'.($index + 1 + $counter).'">
            </a>
        </li>';
	}
	else
	{
		$strReturn .= '
			<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" class="breadcrumbs-nameitem">
				<span itemprop="name" content="'.$APPLICATION->GetTitle().'">'.$APPLICATION->GetTitle().'</span>
				<meta itemprop="position" content="'.($index + 1 + $counter).'">
			</li>';
	}
}

$strReturn .= '
		</ul>
	
	</section>';

return $strReturn;

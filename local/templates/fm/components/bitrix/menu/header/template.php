<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
if (empty($arResult["ALL_ITEMS"]))
	return;

?>
<header class="header">
    <div class="b-center header__main">

        <div class="header__item">
            <a href="/" class="fredericmalle">FREDERICMALLE</a>

            <div class="header__cell header-btn-mobile">
                <div class="btn-menu"><span></span> <span></span> <span></span> <span></span></div>
                <div class="menu-adaptiv">
                    <ul class="menu__list">
                        <li class="menu__item">
                            <a href="#" class="menu__link">ИНФОРМАЦИЯ</a>
                            <span class="menu__arrow"></span>
                            <ul class="hidden-menu__list block">
                                <li><a href="/about/frederic-malle" class="menu__link">ФРЕДЕРИК МАЛЬ</a></li>
                                <li><a href="/about/perfumer" class="menu__link">ПАРФЮМЕРЫ</a></li>
                                <li><a href="/about/stores" class="menu__link">МАГАЗИНЫ</a></li>
                                <li><a href="/about/special-projects" class="menu__link">СПЕЦИАЛЬНЫЕ ПРОЕКТЫ</a></li>
                            </ul>
                        </li>
                        <li class="menu__item"><a href="#" class="menu__link">АРОМАТЫ</a>
                            <span class="menu__arrow"></span>
                            <ul class="hidden-menu__list">
                                <li><a href="/products/perfume" class="menu__link">ВСЕ АРОМАТЫ</a></li>
                                <li><a href="/products/perfume/detail/angeliques_sous_la_pluie?sku=H47P010000" class="menu__link">ANGELIQUES SOUS LA PLUIE</a></li>
                                <li><a href="/products/perfume/detail/bigarade_concentree?sku=H47X010000" class="menu__link">BIGARADE CONCENTREE</a></li>
                                <li><a href="/products/perfume/detail/carnal_flower?sku=H48H010000" class="menu__link">CARNAL FLOWER</a></li>
                                <li><a href="/products/perfume/detail/cologne_bigarade?sku=H47T010000" class="menu__link">COLOGNE BIGARADE</a></li>
                                <li><a href="/products/perfume/detail/cologne_indelebile?sku=H3WX010000" class="menu__link">COLOGNE INDELEBILE</a></li>
                                <li><a href="/products/perfume/detail/dans_tes_bras?sku=H48T010000" class="menu__link">DANS TES BRAS</a></li>
                                <li><a href="/products/perfume/detail/dawn?sku=H4TA010000" class="menu__link">DAWN</a></li>
                                <li><a href="/products/perfume/detail/dries_van_noten?sku=H3W6010000" class="menu__link">DRIES VAN NOTEN</a></li>
                                <li><a href="/products/perfume/detail/eau_de_magnolia?sku=H3WF010000" class="menu__link">EAU DE MAGNOLIA</a></li>
                                <li><a href="/products/perfume/detail/en_passant?sku=H3NW010000" class="menu__link">EN PASSANT</a></li>
                                <li><a href="/products/perfume/detail/french_lover?sku=H48N010000" class="menu__link">FRENCH LOVER</a></li>
                                <li><a href="/products/perfume/detail/geranium_pour_monsieur?sku=H493010000" class="menu__link">GERANIUM POUR MONSIEUR</a></li>
                                <li><a href="/products/perfume/detail/iris_poudre?sku=H47K010000" class="menu__link">IRIS POUDRE</a></li>
                                <li><a href="/products/perfume/detail/l_eau_d_hiver?sku=H3RH010000" class="menu__link">L'EAU D'HIVER</a></li>
                                <li><a href="/products/perfume/detail/le_parfum_de_therese?sku=H475010000" class="menu__link">LE PARFUM DE THERESE</a></li>
                                <li><a href="/products/perfume/detail/lipstick_rose?sku=H479010000" class="menu__link">LIPSTICK ROSE</a></li>
                                <li><a href="/products/perfume/detail/lys_mediterranee?sku=H47F010000" class="menu__link">LYS MEDITERRANEE</a></li>
                                <li><a href="/products/perfume/detail/monsieur?sku=H3X3010000" class="menu__link">MONSIEUR</a></li>
                                <li><a href="/products/perfume/detail/musc_ravageur?sku=H4W7010000" class="menu__link">MUSC RAVAGEUR</a></li>
                                <li><a href="/products/perfume/detail/music_for_a_while?sku=H4NG010000" class="menu__link">MUSIC FOR A WHILE</a></li>
                                <li><a href="/products/perfume/detail/noir_epices?sku=H3NL010000" class="menu__link">NOIR EPICES</a></li>
                                <li><a href="/products/perfume/detail/outrageous?sku=H44K010000" class="menu__link">OUTRAGEOUS</a></li>
                                <li><a href="/products/perfume/detail/portrait_of_a_lady?sku=H497010000" class="menu__link">PORTRAIT OF A LADY</a></li>
                                <li><a href="/products/perfume/detail/promise?sku=H4CE010000" class="menu__link">PROMISE</a></li>
                                <li><a href="/products/perfume/detail/rose_cuir?sku=H50X010000" class="menu__link">ROSE &amp; CUIR</a></li>
                                <li><a href="/products/perfume/detail/superstitious?sku=H44X010000" class="menu__link">SUPERSTITIOUS</a></li>
                                <li><a href="/products/perfume/detail/the_night?sku=H3WL010000" class="menu__link">THE NIGHT</a></li>
                                <li><a href="/products/perfume/detail/une_fleur_de_cassie?sku=H3NA010000" class="menu__link">UNE FLEUR DE CASSIE</a></li>
                                <li><a href="/products/perfume/detail/une_rose?sku=H48A010000" class="menu__link">UNE ROSE</a></li>
                                <li><a href="/products/perfume/detail/vetiver_extraordinaire?sku=H485010000" class="menu__link">VETIVER EXTRAORDINAIRE</a></li>
                            </ul></li>
                        <li class="menu__item"><a href="#" class="menu__link">УХОД</a>
                            <span class="menu__arrow"></span>
                            <ul class="hidden-menu__list">
                                <li><a href="/products/body/folder/dlya_volos" class="menu__link">ДЛЯ ВОЛОС</a></li>
                                <li><a href="/products/body/folder/cleans" class="menu__link">ОЧИЩЕНИЕ</a></li>
                                <li><a href="/products/body/folder/nourish" class="menu__link">УВЛАЖНЕНИЕ </a></li>
                            </ul></li>
                        <li class="menu__item"><a href="#" class="menu__link">ДЛЯ ДОМА</a>
                            <span class="menu__arrow"></span>
                            <ul class="hidden-menu__list">
                                <li><a href="/products/home/svechi" class="menu__link">СВЕЧИ</a></li>
                                <li><a href="/products/home/aromaticheskie_plastiny" class="menu__link">АРОМАТИЧЕСКИЕ ПЛАСТИНЫ</a></li>
                                <li><a href="/products/home/aromatizatsiya_postelnogo_belya" class="menu__link">АРОМАТИЗАЦИЯ ПОСТЕЛЬНОГО БЕЛЬЯ</a></li>
                            </ul></li>
                        <li class="menu__item"><a href="#" class="menu__link">ПОДАРКИ</a>
                            <span class="menu__arrow"></span>
                            <ul class="hidden-menu__list">
                                <li><a href="/products/gifts/sets" class="menu__link">НАБОРЫ</a></li>
                            </ul></li>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="header__cell">
                <span class="link-language">RU</span>
                <a href="/about/stores/" class="link-maps">
                    <svg id="map-marker" viewBox="0 0 500 500"><title>map-marker icon</title><path fill="#fff" d="M245.791 0C153.799 0 78.957 74.841 78.957 166.833c0 36.967 21.764 93.187 68.493 176.926 31.887 57.138 63.627 105.4 64.966 107.433l22.941 34.773a12.497 12.497 0 0 0 20.868 0l22.94-34.771c1.326-2.01 32.835-49.855 64.967-107.435 46.729-83.735 68.493-139.955 68.493-176.926C412.625 74.841 337.783 0 245.791 0zm76.511 331.576c-31.685 56.775-62.696 103.869-64.003 105.848l-12.508 18.959-12.504-18.954c-1.314-1.995-32.563-49.511-64.007-105.853-43.345-77.676-65.323-133.104-65.323-164.743C103.957 88.626 167.583 25 245.791 25s141.834 63.626 141.834 141.833c0 31.643-21.978 87.069-65.323 164.743z"></path><path fill="#fff" d="M245.791 73.291c-51.005 0-92.5 41.496-92.5 92.5s41.495 92.5 92.5 92.5 92.5-41.496 92.5-92.5-41.495-92.5-92.5-92.5zm0 160c-37.22 0-67.5-30.28-67.5-67.5s30.28-67.5 67.5-67.5c37.221 0 67.5 30.28 67.5 67.5s-30.279 67.5-67.5 67.5z"></path></svg>
                </a>
            </div>
            <div class="header__cell header-search">
                <a href="/search/" class="search">
                    <svg id="search-icon" viewBox="0 0 500 500"><title>search icon</title><path fill="#fff" d="M200.9 392.2c-49.6 0-99.3-18.9-137.1-56.7-75.6-75.6-75.6-198.6 0-274.2s198.6-75.6 274.2 0 75.6 198.6 0 274.2c-37.8 37.8-87.5 56.7-137.1 56.7zM83.6 81c-64.7 64.7-64.7 169.9 0 234.6 64.7 64.7 169.9 64.7 234.6 0 64.7-64.7 64.7-169.9 0-234.6-64.7-64.6-169.9-64.6-234.6 0zm393.3 415.9L308.7 329.2l19.7-19.8L496.7 477l-19.8 19.9z"></path></svg>
                </a>
            </div>
        </div>

        <?if (count($arResult["MENU_STRUCTURE"])):?>
        <div class="header-menu" id="ddmenu">
            <ul class="header-menu__list">
                <?foreach($arResult["MENU_STRUCTURE"] as $itemID => $arColumns):?>
                <li><a href="<?=$arResult["ALL_ITEMS"][$itemID]["LINK"]?>" class="header-menu__link"><?=mb_strtoupper($arResult["ALL_ITEMS"][$itemID]["TEXT"])?></a></li>
                <?endforeach;?>
            </ul>
        </div>
        <?endif?>
    </div>
</header>
<?foreach($arResult["MENU_STRUCTURE"] as $itemID => $arColumns):?>
<div class="header-drop">
    <?if ($arResult["ALL_ITEMS"][$itemID]["PARAMS"]["FIRST"] && false):?>
        <?$APPLICATION->IncludeComponent("ddp:perfum_notes", "", array(
        ),
            false
        );?>
    <?elseif (is_array($arColumns) && count($arColumns) > 0):?>
        <ul class="header-drop__list">
            <?foreach($arColumns as $key=>$arRow):?>
                <?foreach($arRow as $itemIdLevel_2=>$arLevel_3):?>
                    <li><a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_2]["LINK"]?>" class="header-menu__link"><?=mb_strtoupper($arResult["ALL_ITEMS"][$itemIdLevel_2]["TEXT"])?></a></li>
                <?endforeach;?>
            <?endforeach;?>
        </ul>
    <?endif?>
</div>
<?endforeach;?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Легендарные парфюмеры Editions de Parfums Frederic Malle");
$APPLICATION->SetTitle("ПАРФЮМЕРЫ");
?>

<?
$url = explode('?', $_SERVER['REQUEST_URI']);
if($url[0] == "/about/perfumer/"){?>


    <section class="title">
        <h2>Парфюмеры</h2>
        <p>Фредерика Маля и его парфюмеров объединяет стремление к творческой свободе, отсутствию жестких временных ограничений и возможности использовать сырье высочайшего качества. Их совместная работа представляет собой тандем автора и редактора: они постоянно совершенствуют композиции и всегда преследуют цель создавать современные ароматы, которые станут классикой завтрашнего дня.</p>
    </section>

    <section class="story-frederic">
        <h2><?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "page",
                    "AREA_FILE_SUFFIX" => "main_title",
                    "EDIT_TEMPLATE" => ""
                )
            );?></h2>
        <p><?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "page",
                    "AREA_FILE_SUFFIX" => "story_text",
                    "EDIT_TEMPLATE" => ""
                )
            );?></p>
        <div class="collaboration-main">
            <div class="collaboration-item collaboration-item--width">
                <img class="collaboration__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/story/fm2.jpg" alt="#">
            </div>
            <div class="collaboration-item">
                <p><?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "page",
                            "AREA_FILE_SUFFIX" => "born",
                            "EDIT_TEMPLATE" => ""
                        )
                    );?>
                </p>
                <q><?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "page",
                            "AREA_FILE_SUFFIX" => "quote",
                            "EDIT_TEMPLATE" => ""
                        )
                    );?></q>
                <span> - <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "page",
                            "AREA_FILE_SUFFIX" => "author",
                            "EDIT_TEMPLATE" => ""
                        )
                    );?></span>
                <p><?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "page",
                            "AREA_FILE_SUFFIX" => "story_text_2",
                            "EDIT_TEMPLATE" => ""
                        )
                    );?></p>
                <p><?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "page",
                            "AREA_FILE_SUFFIX" => "story_text_3",
                            "EDIT_TEMPLATE" => ""
                        )
                    );?></p>
                <div class="collaboration-item__cell">
                    <a class="collaboration__more collaboration__more_show">Показать больше</a><img class="collaboration__select-arrow" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/arrow1.png" alt="">
                </div>
            </div>
        </div>
    </section>
    <section class="story-list">
    <h2><?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "page",
                "AREA_FILE_SUFFIX" => "perfumers_title",
                "EDIT_TEMPLATE" => ""
            )
        );?></h2>
    <p><?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "page",
                "AREA_FILE_SUFFIX" => "perfumers_text",
                "EDIT_TEMPLATE" => ""
            )
        );?></p>
<?}?>

<?$APPLICATION->IncludeComponent(
    "bitrix:news",
    "perfumer",
    Array(
        "ADD_ELEMENT_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "Y",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "BROWSER_TITLE" => "-",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A",
        "CHECK_DATES" => "N",
        "DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
        "DETAIL_DISPLAY_BOTTOM_PAGER" => "N",
        "DETAIL_DISPLAY_TOP_PAGER" => "N",
        "DETAIL_FIELD_CODE" => array("",""),
        "DETAIL_PAGER_SHOW_ALL" => "N",
        "DETAIL_PAGER_TEMPLATE" => "",
        "DETAIL_PAGER_TITLE" => "Страница",
        "DETAIL_PROPERTY_CODE" => array("SECOND_TITLE",""),
        "DETAIL_SET_CANONICAL_URL" => "Y",
        "DISPLAY_BOTTOM_PAGER" => "N",
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "3",
        "IBLOCK_TYPE" => "info",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
        "LIST_FIELD_CODE" => array("PROPERTY_SLIDER_PHOTO","PROPERTY_QUOTE", "PROPERTY_QUOTE_AUTHOR"),
        "LIST_PROPERTY_CODE" => array("",""),
        "MESSAGE_404" => "",
        "META_DESCRIPTION" => "-",
        "META_KEYWORDS" => "-",
        "NEWS_COUNT" => "20",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Новости",
        "PREVIEW_TRUNCATE_LEN" => "",
        "SEF_FOLDER" => "/about/perfumer/",
        "SEF_MODE" => "Y",
        "SEF_URL_TEMPLATES" => Array("news"=>"index.php", "detail"=>"#ELEMENT_CODE#"),
        "SET_LAST_MODIFIED" => "N",
        "SET_STATUS_404" => "Y",
        "SET_TITLE" => "Y",
        "SHOW_404" => "N",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "NAME",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "ASC",
        "STRICT_SECTION_CHECK" => "N",
        "USE_CATEGORIES" => "N",
        "USE_FILTER" => "N",
        "USE_PERMISSIONS" => "N",
        "USE_RATING" => "N",
        "USE_REVIEW" => "N",
        "USE_RSS" => "N",
        "USE_SEARCH" => "N",
        "USE_SHARE" => "N"
    )
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
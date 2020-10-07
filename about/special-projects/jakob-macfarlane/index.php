<?
define("WITHOUT_MAIN_DIV", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Jakob + MacFarlane");
?>
    <div class="main">

        <section class="content">

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

            <section class="title">
                <h2><?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "sect",
                            "AREA_FILE_SUFFIX" => "title",
                            "EDIT_TEMPLATE" => ""
                        )
                    );?></h2>

                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "sect",
                            "AREA_FILE_SUFFIX" => "description",
                            "EDIT_TEMPLATE" => ""
                        )
                    );?>

            </section>

            <section class="content-box">

                <section class="special-more">

                    <div class="special-more__item">

                        <div class="special-more__cell">
                            <img class="special-more__cell-img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_store1.png" alt="">
                        </div>

                        <div class="special-more__cell order_none">
                            <h2 class="special-more__title">JAKOB + MACFARLANE</h2>
                            <p class="special-more__subtitle">(улица Фран Буржуа, 13)</p>
                            <div class="special-more__text">
                                <p>Мне всегда нравился дизайн, разработанный Jakob + MacFarlane для ресторана Georges в парижском Центре Помпиду. Его интерьер и сегодня остается великолепной фантазией на тему будущего, полной неожиданных деталей.</p>
                            </div>
                        </div>

                    </div>

                    <div class="special-more__item">

                        <div class="special-more__cell">
                            <div class="special-more__text">
                                <p>И когда я посетил книжный магазин Florence Loewy в Маре, который Jakob + MacFarlane искусно обставили фанерными стеллажами, я сразу понял, кто должен спроектировать дизайн моего бутика в этом районе.</p>
                                <p>Вместе с архитекторами мы продумали интерьер, дающий волю фантазии: здесь нарушаются законы природы и используются парящие, словно застывшие во времени, конструкции. В стенах исторического здания был оборудован зеркальный бокс, в стенах которого непрерывной вереницей отражаются подвешенные в невесомости островные стойки. Сложный визуальный эффект бесконечности, создаваемый зеркалами, — это приглашение поразмышлять в одиночестве, пока вы выбираете аромат, соответствующий вашему характеру.</p>
                                <p>Ф. М.</p>
                            </div>
                        </div>

                        <div class="special-more__cell">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:player",
                                "custom",
                                Array(
                                    "ADVANCED_MODE_SETTINGS" => "Y",
                                    "AUTOSTART" => "N",
                                    "AUTOSTART_ON_SCROLL" => "N",
                                    "HEIGHT" => "255",
                                    "MUTE" => "N",
                                    "PATH" => "/upload/video/LeMarais_Vid_version_2.mp4",
                                    "PLAYBACK_RATE" => "1",
                                    "PLAYER_ID" => "fm",
                                    "PLAYER_TYPE" => "videojs",
                                    "PRELOAD" => "Y",
                                    "PREVIEW" => "/upload/photo/video-hero.jpg",
                                    "REPEAT" => "none",
                                    "SHOW_CONTROLS" => "Y",
                                    "SIZE_TYPE" => "absolute",
                                    "SKIN" => "sublime.css",
                                    "SKIN_PATH" => "/bitrix/js/fileman/player/videojs/skins",
                                    "START_TIME" => "0",
                                    "TYPE" => "",
                                    "USE_PLAYLIST" => "N",
                                    "VOLUME" => "90",
                                    "WIDTH" => "480"
                                )
                            );?>

                        </div>

                    </div>

                    <div class="special-more__item">
                        <img class="special-more__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_article_staggered1.jpg" alt="">
                    </div>

                    <div class="special-more__item">

                        <img class="special-more__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_article_staggered2.png" alt="">

                        <img class="special-more__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_article_staggered3.jpg" alt="">

                    </div>

                    <a class="special-more__link" href="/about/special-projects/">Обратно к специальным проектам</a>

                </section><!-- special-more -->


            </section><!-- content -->

    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
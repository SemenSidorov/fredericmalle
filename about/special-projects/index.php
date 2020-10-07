<?
define("WITHOUT_MAIN_DIV", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Специальные проекты и коллаборации Editions de Parfums Frederic Malle");
$APPLICATION->SetTitle("СПЕЦИАЛЬНЫЕ ПРОЕКТЫ");
?>
    <div class="main main--height">

        <div class="content">

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

        <div class="b-center">


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
        </div>
    </div>

    <section class="special-preview">
        <?$APPLICATION->IncludeComponent(
            "bitrix:player",
            "custom",
            Array(
                "ADVANCED_MODE_SETTINGS" => "Y",
                "AUTOSTART" => "N",
                "AUTOSTART_ON_SCROLL" => "N",
                "HEIGHT" => "680",
                "MUTE" => "N",
                "PATH" => "/upload/video/LeMarais_Vid_version_2.mp4",
                "PLAYBACK_RATE" => "1",
                "PLAYER_ID" => "fm",
                "PLAYER_TYPE" => "videojs",
                "PRELOAD" => "Y",
                //"PREVIEW" => "/upload/photo/FM_falabracks_still-1-1.jpg",
                "REPEAT" => "none",
                "SHOW_CONTROLS" => "Y",
                "SIZE_TYPE" => "fluid",
                "SKIN" => "sublime.css",
                "SKIN_PATH" => "/bitrix/js/fileman/player/videojs/skins",
                "START_TIME" => "0",
                "TYPE" => "",
                "USE_PLAYLIST" => "N",
                "VOLUME" => "90",
                "WIDTH" => "1280"
            )
        );?>
    </section>


    <div class="main">

        <section class="content">

            <div class="content-box">

                <h2 class="special__title">Альбер Эльбаз : портрет от Фредерика Маля</h2>

                <div class="slider-special">
                    <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/SuperstitiousPortrait.png" alt=""></div>
                    <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/SuperstitiousLetter.png" alt=""></div>
                    <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/Superstitious_Vid_screenshot.png" alt=""></div>
                    <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/SuperstitiousStillLife.png" alt=""></div>
                </div>

                <div class="special__text">
                    <p>Я познакомился с работами Альбера Эльбаза в 90-х, когда Пьер Берже назначил его главным дизайнером модного дома Yves Saint Laurent. В нем я увидел то, что сегодня вижу в своих парфюмерах — умение интерпретировать классику в контексте современности для новых поколений.</p>
                </div>

                <a href="alber-elbaz/" class="special__more hidden__link">Читать далее</a>
                <a href="alber-elbaz/" class="special__more-new">Читать далее</a>

            </div>


            <section class="special">


                <div class="special-item">
                    <a class="special__link-1" href="alber-elbaz/"><img class="special__img-1"
                                                             src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/SuperstitiousPortrait.png" alt=""></a>
                    <a class="special__link-2" href="alber-elbaz/"><img class="special__img-2"
                                                             src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/SuperstitiousLetter.png" alt=""></a>
                </div>
                <div class="special-item special-item__margin_bottom">
                    <a class="special__link-3" href="alber-elbaz/"><img class="special__img-3"
                                                             src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/Superstitious_Vid_screenshot.png" alt=""></a>
                    <a class="special__link-4" href="alber-elbaz/"><img class="special__img-4"
                                                             src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/SuperstitiousStillLife.png" alt=""></a>
                </div>


            </section>



            <section class="special">

                <div class="special__item">
                    <div class="content-box">

                        <h2 class="special__title">Jakob + MacFarlane (улица Фран Буржуа, 13)</h2>

                        <div class="slider-special">
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_store1.png" alt=""></div>
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_store2.jpg" alt=""></div>
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_store4.jpg" alt=""></div>
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_store3.jpg" alt=""></div>
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_article_staggered3.jpg" alt="">
                            </div>
                        </div>

                        <div class="special__text">
                            <p>Мне всегда нравился дизайн, разработанный Jakob + MacFarlane для ресторана Georges в парижском Центре Помпиду. Его интерьер и сегодня остается великолепной фантазией на тему будущего, полной неожиданных деталей.</p>
                        </div>

                        <a href="jakob-macfarlane/" class="special__more hidden__link">Читать далее</a>
                        <a href="jakob-macfarlane/" class="special__more-new">Читать далее</a>

                    </div>
                </div>

                <div class="special-item">
                    <a class="special__link-5" href="jakob-macfarlane/"><img class="special__img-5"
                                                             src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_store1.png" alt=""></a>
                    <a class="special__link-6" href="jakob-macfarlane/"><img class="special__img-6"
                                                             src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_store2.jpg" alt=""></a>
                </div>

                <div class="special-item">
                    <a class="special__link-7" href="jakob-macfarlane/"><img class="special__img-7"
                                                             src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_store4.jpg" alt=""></a>
                    <a class="special__link-8" href="jakob-macfarlane/"><img class="special__img-8"
                                                             src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_store3.jpg" alt=""></a>
                </div>

                <div class="special-item special-item__margin_bottom">
                    <a class="special__link-9" href="jakob-macfarlane/"><img class="special__img-9"
                                                             src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/new_paris_article_staggered3.jpg" alt=""></a>
                </div>



            </section>

            <section class="special">

                <div class="special__item">

                    <div class="content-box">

                        <h2 class="special__title">Стивен Холл</h2>
                        <h3 class="special__subtitle">Гринвич-авеню, 94</h3>

                        <div class="slider-special">
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll1.jpg" alt=""></div>
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll2.jpg" alt=""></div>
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll4.jpg" alt=""></div>
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll3_optimized.jpg" alt=""></div>
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll5.png" alt=""></div>
                        </div>

                        <div class="special__text">
                            <p>Бутик на Гринвич-авеню, 94 я представлял себе таким же, как и свои ароматы, — как пространство, способное выдержать испытание временем и стать вечной «классикой» будущего. Для реализации этой смелой идеи требовался интерьер, который бы одновременно гармонировал и контрастировал с обликом района, отражал традиции и взгляд в будущее.</p>
                        </div>

                        <a href="steven-holl/" class="special__more hidden__link">Читать далее</a>
                        <a href="steven-holl/" class="special__more-new">Читать далее</a>
                    </div>

                </div>

                <div class="special-item">
                    <a class="special__link-10" href="steven-holl/"><img class="special__img-10"
                                                              src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll1.jpg" alt=""></a>
                    <a class="special__link-11" href="steven-holl/"><img class="special__img-11"
                                                              src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll2.jpg" alt=""></a>
                </div>

                <div class="special-item">
                    <a class="special__link-12" href="steven-holl/"><img class="special__img-12"
                                                              src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll4.jpg" alt=""></a>
                    <a class="special__link-13" href="steven-holl/"><img class="special__img-13"
                                                              src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll3_optimized.jpg" alt=""></a>
                </div>

                <div class="special-item special-item__margin_bottom">
                    <a class="special__link-14" href="steven-holl/"><img class="special__img-14"
                                                              src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll5.png" alt=""></a>
                </div>



            </section>

            <section class="special">

                <div class="special__item">
                    <div class="content-box">

                        <h2 class="special__title">ДРИС ВАН НОТЕН : Портрет Фредерика Мале</h2>
                        <?/*<h3 class="special__subtitle">94 Greenwich Avenue</h3>*/?>

                        <div class="slider-special">
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/dries_van1.png" alt=""></div>
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/dries_van2.png" alt=""></div>
                            <div><img src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/dries_van3.png" alt=""></div>
                        </div>

                        <div class="special__text">
                            <p>Мне всегда казалось, что эстетика Дриса ван Нотена окружает теплом. Наполненная индийскими мотивами, фрагментами с гравюр 18 века и совершенными цветовыми контрастами – она возникает не только из морозных бельгийских зим, но и из истории страны, которая во времена империи была крупнейшиим торговым центром. Плотные фактуры, яркие принты и индийские специи привнесли особый домашний уют, который стал неотъемлемой частью фламандской жизни и дизайнерского стиля Дриса.</p>
                        </div>
                        <a href="dries-van-noten/" class="special__more hidden__link">Читать далее</a>
                        <a href="dries-van-noten/" class="special__more-new">Читать далее</a>

                    </div>
                </div>

                <div class="special-item special-item_justify_end">
                    <a class="special__link-15" href="dries-van-noten/"><img class="special__img-15"
                                                              src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/dries_van1.png" alt=""></a>
                </div>

                <div class="special-item">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:player",
                        "custom",
                        Array(
                            "ADVANCED_MODE_SETTINGS" => "Y",
                            "AUTOSTART" => "N",
                            "AUTOSTART_ON_SCROLL" => "N",
                            "HEIGHT" => "550",
                            "MUTE" => "N",
                            "PATH" => "/upload/video/Dries_Vid.mp4",
                            "PLAYBACK_RATE" => "1",
                            "PLAYER_ID" => "fm_2",
                            "PLAYER_TYPE" => "videojs",
                            "PRELOAD" => "Y",
                            //"PREVIEW" => "/upload/photo/FM_falabracks_still-1-1.jpg",
                            "REPEAT" => "none",
                            "SHOW_CONTROLS" => "Y",
                            "SIZE_TYPE" => "absolute",
                            "SKIN" => "sublime.css",
                            "SKIN_PATH" => "/bitrix/js/fileman/player/videojs/skins",
                            "START_TIME" => "0",
                            "TYPE" => "",
                            "USE_PLAYLIST" => "N",
                            "VOLUME" => "90",
                            "WIDTH" => "980"
                        )
                    );?>
                </div>

                <div class="special-item special-item_justify_start">
                    <a class="special__link-17" href="dries-van-noten/"><img class="special__img-17"
                                                              src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/dries_van3.png" alt=""></a>
                </div>



            </section>
        </section><!-- content -->

    </div><!-- main -->

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
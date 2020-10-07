<?
define("WITHOUT_MAIN_DIV", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("АЛЬБЕР ЭЛЬБАЗ : портрет от Фредерика Маля");
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

            <section class="special-more">

                <div class="special-more__item">

                    <div class="special-more__cell">

                        <h2 class="special-more__title">АЛЬБЕР ЭЛЬБАЗ:</h2>
                        <p class="special-more__subtitle">портрет от Фредерика Маля</p>
                        <div class="special-more__text">
                            <p>Я познакомился с работами Альбера Эльбаза в 90-х, когда Пьер Берже назначил его главным дизайнером модного дома Yves Saint Laurent. В нем я увидел то, что сегодня вижу в своих парфюмерах — умение интерпретировать классику в контексте современности для новых поколений.</p>
                            <p>По воле случая Альбер нашел человека в мире парфюмерии, который полностью разделял его эстетические взгляды. В результате уникальной коллаборации с Домиником Ропьоном, основанной на креативном видении и взаимном восхищении, родился едва уловимый аромат платья — классическая цветочно-альдегидная композиция, которая отражает то, как Альбер видит искусно вытканные ткани, и отвечает самым разным представлениям о красоте.</p>
                            <p>Эльбаз не признает мира, где все делится на категории, и выступает за отказ от них. Он считает интуицию неиссякаемым источником свободы, в противовес чрезмерной организованности, а красота для него заключается в иррациональности и загадочности. И аромат Superstitious будто предлагает нам убедиться, что эта таинственность действительно прекрасна. Поэтому, в знак признания нашей общей веры в загадочное и необъяснимое, мы решили нанести на его черный флакон эскиз в виде «дурного глаза», который Альбер набросал на салфетке в одном из парижских кафе. Мы всегда верили, что зачастую красоту можно увидеть в самых неожиданных вещах.</p>
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
                                "HEIGHT" => "280",
                                "MUTE" => "N",
                                "PATH" => "/upload/video/LeMarais_Vid_version_2.mp4",
                                "PLAYBACK_RATE" => "1",
                                "PLAYER_ID" => "fm",
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
                                "WIDTH" => "640"
                            )
                        );?>

                    </div>

                </div>

                <div class="special-more__item">
                    <img class="special-more__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/3_images.png" alt="">
                </div>

                <div class="special-more__item">
                    <img class="special-more__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/SuperstitiousPortrait.png" alt="">
                </div>


                <a class="special-more__link" href="/about/special-projects/">Обратно к специальным проектам</a>

            </section>
        </section><!-- content -->
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
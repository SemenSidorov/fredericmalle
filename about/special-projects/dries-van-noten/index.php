<?
define("WITHOUT_MAIN_DIV", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ДРИС ВАН НОТЕН : Портрет Фредерика Мале");
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
                        <img class="special-more__cell-img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/dries_van1.png" alt="">
                    </div>

                    <div class="special-more__cell order_none">
                        <h2 class="special-more__title">ДРИС ВАН НОТЕН :</h2>
                        <p class="special-more__subtitle">Портрет Фредерика Мале</p>

                        <div class="special-more__text">
                            <p>Мне всегда казалось, что эстетика Дриса ван Нотена окружает теплом. Наполненная индийскими мотивами, фрагментами с гравюр 18 века и совершенными цветовыми контрастами – она возникает не только из морозных бельгийских зим, но и из истории страны, которая во времена империи была крупнейшиим торговым центром. Плотные фактуры, яркие принты и индийские специи привнесли особый домашний уют, который стал неотъемлемой частью фламандской жизни и дизайнерского стиля Дриса.</p>
                            <p>Я попросил Бруно Йовановича, который обладает уникальным обонянием на теплые запахи, создать духи из вселенной Дриса. Он решил использовать натуральное сандаловое дерево, так как оно сочетало в себе нежность и экзотичность, простоту и уникальность.</p>
                            <p>Соединяя молочные оттенки сандалового дерева с ванилью, шафраном и сакразолом, а затем смягчая аромат нотами жасмина и белого мускуса, Бруно создал долгоиграющий теплый аромат. Парфюм напоминал топленое масло, молочный чай и печенье спекулос и помогал пережить еще одну зиму. Подобно теплому дизайнерскому пальто и множеству других элементов стиля Дриса, парфюм получился вневременным, классическим и очень чувственным.</p>
                        </div>
                    </div>
                </div>

                <div class="special-more__item">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:player",
                        "custom",
                        Array(
                            "ADVANCED_MODE_SETTINGS" => "Y",
                            "AUTOSTART" => "N",
                            "AUTOSTART_ON_SCROLL" => "N",
                            "HEIGHT" => "800",
                            "MUTE" => "N",
                            "PATH" => "/upload/video/Dries_Vid.mp4",
                            "PLAYBACK_RATE" => "1",
                            "PLAYER_ID" => "fm_2",
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
                </div>

                <div class="special-more__item special-more__item_margin_right">
                    <img class="special-more__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/dries_van3.png" alt="">
                </div>

                <a class="special-more__link" href="/about/special-projects/">Обратно к специальным проектам</a>

            </section><!--special-more -->




        </section><!-- content -->
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
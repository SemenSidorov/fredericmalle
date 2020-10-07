<?
define("WITHOUT_MAIN_DIV", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Steven Holl");
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
                        <h2 class="special-more__title">СТИВЕН ХОЛЛ</h2>
                        <p class="special-more__subtitle">Гринвич-авеню, 94</p>
                        <div class="special-more__text">
                            <p>Бутик на Гринвич-авеню, 94 я представлял себе таким же, как и свои ароматы, — как пространство, способное выдержать испытание временем и стать вечной «классикой» будущего. Для реализации этой смелой идеи требовался интерьер, который бы одновременно гармонировал и контрастировал с обликом района, отражал традиции и взгляд в будущее.</p>
                            <p>Учитывая местоположение бутика в Вест-Виллидж и его ограниченную площадь, я сразу же подумал о нью-йоркском архитекторе Стивене Холле. Он известен не только как один из величайших современных проектировщиков, но и как автор потрясающих световых решений для небольших пространств. Одним из ярчайших тому примеров служат офисы D.E. Shaw в Нью-Йорке. Стивен свободно трактует формы, используя их неожиданными способами. Это видно в таких проектах, как Y House в Катскилле, Storefront for Art and Architecture в Нью-Йорке, а также более крупных, например, комплексе Linked Hybrid в Пекине. Ему под силу полностью преобразить пространство любого масштаба, в том числе и небольшое помещение бутика на Гринвич-авеню, 94.</p>
                            <p>Я предложил Стивену построить магазин по аналогии с выдвижным ящиком — выполнить интерьер и фасад в одном стиле и из одного материала, словно его вырезали в горной породе. Стивен поставил единственное условие — пригласить в команду Эрве Дескотта, настоящего волшебника по свету. Я слышал о незаурядном таланте Эрве, и меня не пришлось долго убеждать в том, что именно он привнесет в дизайн Стивена тот самый «недостающий элемент», поэтому я с радостью согласился. Что касается самого дизайна, в нем используется новый материал под названием «алюминиевая пена». Впервые я увидел ее в офисе Стивена — из пены была выполнена потрясающая панельная дверь, — и с тех пор я буквально влюбился в этот материал. Стивен пошел мне навстречу и использовал алюминиевую пену в интерьере бутика, дополнив ее фрагментированными полукруглыми предметами мебели из орехового дерева, выполненными в футуристическом стиле, и создав тем самым интересный контраст.</p>
                            <p>Ф. М.</p>
                        </div>
                    </div>

                    <div class="special-more__cell">
                        <img class="page-img__item" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll1.jpg" alt="">
                    </div>

                </div>

                <div class="special-more__item">
                    <img class="special-more__img special-more__img_max_width" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll2.jpg" alt="">
                    <img class="special-more__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll5.png" alt="">
                </div>

                <div class="special-more__item special-more__item_no_margin">
                    <img class="special-more__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll3_optimized.jpg" alt="">
                    <img class="special-more__img special-more__img_max_width" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/special/StevenHoll4.jpg" alt="">
                </div>

                <a class="special-more__link" href="/about/special-projects/">Обратно к специальным проектам</a>

            </section>



        </section><!-- content -->
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
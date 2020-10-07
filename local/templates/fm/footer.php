<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);
?>
<?if (array_key_exists('is_ajax', $_REQUEST) && $_REQUEST['is_ajax']=='y') {
    die();
}?>
<?if (!defined("WITHOUT_MAIN_DIV")):?>
        <?if (!defined("WITHOUT_CONTENT_DIV")):?></div><?endif?>
    </div><!-- main -->
<?endif?>

<?php
global $APPLICATION;
CModule::IncludeModule("iblock");
$agreement = $APPLICATION->get_cookie("ELC_FM_AGREEMENT_NEW");
if (!$agreement):?>
    <div class="cookie-block">
        <div class="cookie-item">
            <p class="cookie__text">Используя наш сайт, вы даете согласие на обработку файлов сookies и других пользовательских данных, в соответствии с <a href="/privacy/" target="_blank">Политикой конфиденциальности</a>.</p>
        </div>
        <div class="cookie-item">
            <?/*<a href="#" class="cookie__link">Personalize</a>*/?>
            <a href="#" class="cookie__link cookie_yes">Ok</a>
            <div class="cookie-item__circle"><img class="cookie__img" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/close.svg" alt=""></div>
        </div>
    </div>
<?endif?>
<footer class="footer">
    <div class="b-center footer-main">
        <div class="footer-item">
            <?$lm = new CMenu("left");
            $lm->Init("/about/", true);
            if (count($lm->arMenu)):?>
            ?>
            <div class="footer-cell">
                <h3 class="footer__title">ИНФОРМАЦИЯ</h3>
                <ul class="footer__list">
                    <?foreach ($lm->arMenu as $menu):?>
                    <li><a class="footer__link" href="/about/<?=$menu[1]?>"><?=$menu[0]?></a></li>
                    <?endforeach;?>
                </ul>
            </div>
            <?endif?>
<?
$shops = [];
$dbShop = CIblockSection::GetList(["SORT"=>"ASC", "NAME" => "ASC"], ["IBLOCK_ID" => 1, "DEPTH_LEVEL" => 1], false, ["ID","NAME","SECTION_PAGE_URL"]);
while($arShop = $dbShop->GetNext()) {
    $shops[] = $arShop;
}
if (count($shops)):?>
?>
            <div class="footer-cell">
                <h3 class="footer__title">КАТЕГОРИИ</h3>
                <ul class="footer__list">
                    <?foreach ($shops  as $shop):?>
                    <li><a class="footer__link" href="<?=$shop["SECTION_PAGE_URL"]?>"><?=$shop["NAME"]?></a></li>
                    <?endforeach;?>
                </ul>
            </div>
<?endif?>
        </div>
        <div class="footer-item">
            <div class="footer-subscribe">
                <form id="footer-form" class="footer-subscribe__form" action="#" method="post">
                    <div class="footer-subscribe__cell">
                        <div class="footer-subscribe__close"></div>
                        <div class="footer-subscribe__title">Подпишитесь на новости</div>
                        <p class="footer-subscribe__text">Подпишитесь, чтобы получать эксклюзивную информацию о наших продуктах.</p>

                        <label class="mycheckbox-footer">
                            <input class="mycheckbox__default-footer" type="checkbox" name="confirm">
                            <div class="mycheckbox__new-footer"></div>
                            <div class="mycheckbox__descr"><span class="modal__checkbox-text">Да, я хочу получать новости и рекламу сайта ru.fredericmalle.com по email, и я прочитал(а), понял(а) и принимаю разделы <a class="privacy-item__link" href="/terms/">Условия Использования</a> и <a class="privacy-item__link" href="/privacy/">Политика конфиденциальности</a>  этого сайта.</span></div>
                        </label>

                        <p class="footer-subscribe__label">E-mail</p>
                    </div>
                    <div class="footer-subscribe__item">
                        <label class="footer-subscribe__item-label" for="footer-email">Email</label>
                        <input class="footer-subscribe__input" type="email" id="footer-email" name="email" placeholder="E-mail">
                        <a href="#" class="footer-subscribe__submit">Подписаться</a>
                    </div>

                </form>
            </div>

            <div class="footer-social social">
                <a href="https://www.facebook.com/FredericMalle" class="social__link" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
                <a href="https://www.youtube.com/channel/UCoVDINuJm5uQtU5x4Ldy9Bg" class="social__link" target="_blank" rel="noopener"><i class="fa fa-youtube"></i></a>
                <a href="https://www.instagram.com/fredericmalle/" class="social__link" style="width: 16px;" target="_blank" rel="noopener">
                    <svg id="instagram" viewBox="0 0 606 500">
                        <title>instagram</title>
                        <path fill="#FFFFFF" d="M303.2 120.1c-71.1 0-128.6 57.6-128.6 128.6 0 71.1 57.6 128.6 128.6 128.6 71.1 0 128.6-57.6 128.6-128.6 0-71.1-57.5-128.6-128.6-128.6zm0 211.2c-45.5 0-82.6-37-82.6-82.6 0-45.5 37-82.6 82.6-82.6 45.5 0 82.6 37 82.6 82.6 0 45.5-37.1 82.6-82.6 82.6zM436.8 88c16 0 29 13 29 29s-13 29-29 29-29-13-29-29 13-29 29-29z"></path>
                        <path fill="#FFFFFF" d="M512.9 41C486.4 14 449.8 0 406.8 0H199.1C111.5 0 52.9 58.5 52.9 146.1v206.7c0 43.5 14.5 81.1 42 107.6 26.5 26 63.1 39.5 105.1 39.5h205.7c43 0 79.6-13.5 106.1-39.5 27-26.5 41-63.1 41-106.6V146.1c.6-42-13.4-78.6-39.9-105.1zm-5.5 313.3c0 30.5-9.5 56.1-27.5 73.6-17.5 17.5-43.5 26.5-74.1 26.5H200.1c-30 0-55.1-9-73.1-26.5-18.5-18-28-43.5-28-74.6V146.6c0-30.5 9.5-56.1 27-73.6s43-26.5 73.1-26.5h207.7c30 0 55.6 9.5 73.1 27.5 17.5 18 27 43 27 73.1v207.2h.5z"></path>
                    </svg>
                </a>
                <?/*<a href="#" class="social__link"><i class="fa fa-telegram"></i></a>*/?>
            </div>

            <div class="footer-country">
                <div class="footer-country__select">
                    Россия, Русский
                    <img class="footer__arrow" src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/images/select-arrow.png" alt="">
                </div>
                <div class="footer-country__list">
                    <a class="footer-country__link" href="https://www.fredericmalle.eu/fr-e-UF#forcedLocale">France, Français</a>
                    <a class="footer-country__link" href="https://www.fredericmalle.eu/">Rest of Europe, English</a>
                    <a class="footer-country__link" href="https://www.fredericmalle.com/">International, English</a>
                    <a class="footer-country__link" href="https://www.fredericmalle.co.uk/">United Kingdom, English</a>
                    <a class="footer-country__link" href="https://www.fredericmalle.com/jp?LOCALE=en_US&stores=1#forcedLocale">Japan, Japanese</a>
                    <a class="footer-country__link" href="https://www.fredericmalle.com/">UNITED STATES, ENGLISH</a>
                    <a class="footer-country__link footer__link_active" href="#">Россия, Русский</a>
                </div>
            </div>

            <div class="footer-more">
                <a class="footer__link" href="/terms/">Условия использования</a>&nbsp;&nbsp;&nbsp;<a class="footer__link" href="/privacy/">Политика конфиденциальности</a>
            </div>

            <p class="copyright">&copy; НОВОЕ ПАРФЮМЕРНОЕ ИЗДАТЕЛЬСТВО <?=date("Y")?></p>

        </div>

    </div>
</footer>
</div>

<script src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/libs/jquery.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/libs/slick/slick.min.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Content/libs/jquery-validation-1.19.1/dist/jquery.validate.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/_html/Result/Scripts/init.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/catalog.js"></script>
<script src="<?=SITE_TEMPLATE_PATH?>/subscribe.js"></script>

<div id="toTop" class="btn-toTop">Вверх</div>

</body>

</html>
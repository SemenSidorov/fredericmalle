<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<? if(strpos($_SERVER['REQUEST_URI'], '/products/') !== false){
    $APPLICATION->SetPageProperty("OGImage", $arResult["SKU"]["GALLERY"][0]["src"]);
}?>
<?$this ->__template->SetViewTarget('custom-subscribe');?>
<div class="modal-popup subscribe-popup">
    <div class="modal">
        <div class="exit"></div>
        <h3 class="modal__title">Подпишитесь на новости</h3>
        <p class="modal__text">Подпишитесь, чтобы получать письма от Фредерика Маля о последних продуктах, специальных предложениях и советах экспертов.</p>
        <form id="modal-form" class="modal__form subscription-form" action="#" method="post">

            <p class="modal-sex__title">Пол</p>
            <div class="modal__cell">
                <p class="modal-sex__text"><input class="modal__radio" name="sex" type="radio" value="Женский" checked>Женский</p>
                <p class="modal-sex__text"><input class="modal__radio" name="sex" type="radio" value="Мужской">Мужской</p>
            </div>

            <input class="modal__input modal--margin_top" type="text" name="name" placeholder="* Имя">

            <input class="modal__input modal--margin_top" type="email" name="email" placeholder="* E-mail">

            <br>
            <label class="mycheckbox">
                <input class="mycheckbox__default" type="checkbox" name="confirm">
                <div class="mycheckbox__new"></div>
                <div class="mycheckbox__descr"><span class="modal__checkbox-text">Да, я хочу получать новости и рекламу сайта ru.fredericmalle.com по email, и я прочитал(а), понял(а) и принимаю разделы <a class="privacy-item__link" href="/terms/">Условия Использования</a> и <a class="privacy-item__link" href="/privacy/">Политика конфиденциальности</a>  этого сайта.</span></div>
            </label>
            <input class="modal__btn" type="submit" name="" value="Отправить">
        </form>
    </div>
</div>
<?$this ->__template->EndViewTarget();?>

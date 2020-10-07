<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this ->__template->SetViewTarget('custom-subscribe');?>
<div class="modal-popup subscribe-popup">
    <div class="modal">
        <h3 class="modal__title">Подтверждение подписки</h3>
        <p class="modal__text">Подпишитесь, чтобы получать письма от Фредерика Маля о последних продуктах, специальных предложениях и советах экспертов.</p>
        <form id="modal-form-subscribe-crm" class="modal__form subscription-form" action="#" method="post">
            <input type="hidden" name="contact" value="<?=$_REQUEST["contact"]?>">
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

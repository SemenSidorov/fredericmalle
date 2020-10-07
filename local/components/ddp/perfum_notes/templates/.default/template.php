<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<div class="b-center">
    <?if (count($arResult["NOTES"])):?>
    <section class="choice">

        <div class="center_line_bottom">

            <p class="choice__text">Please select up to three filters to guide your discovery:</p>

            <div class="choice-item">
                <div class="choice__cell">
                    <label class="choice__cell-label">
                        <input type="checkbox" name="note[]" value="all"><span class="choice__cell-text">All</span>
                    </label>
                </div>
                <?foreach ($arResult["NOTES"] as $note):?>
                <div class="choice__cell">
                    <label class="choice__cell-label">
                        <input type="checkbox" name="note[]" value="<?=$note["ID"]?>"><span class="choice__cell-text"><?=$note["NAME"]?></span>
                    </label>
                </div>
                <?endforeach;?>
            </div>
            <ul class="header-drop__more">
                <li><a href="<?=$arResult["ALL_ITEMS"][$itemID]["LINK"]?>" class="header-drop__link">BROWSE ALL PERFUMES</a></li>
            </ul>
        </div>
    </section>
    <?endif?>
</div>


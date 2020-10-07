<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$promo = [];
foreach ($arResult["SECTIONS"] as $sect)
    if ($sect["PROMO_IMAGE"])
        $promo[] = $sect;

if (count($promo)):?>
    <section class="discovers">
        <div class="discovers__item">
            <h2 class="discovers__title">ПРИКОСНИТЕСЬ К МИРУ ФРЕДЕРИКА МАЛЯ</h2>
            <div class="slider-three">
                <?foreach ($promo as $banner):
                    $img = ELCPicture($banner["PROMO_IMAGE"],"promo_section", false);
                    ?>
                    <a class="slider-three__link" href="<?=$banner["SECTION_PAGE_URL"]?>"><img class="slider-three__img" src="<?=$img["src"]?>" alt="<?=$banner["NAME"]?>" title="<?=$banner["NAME"]?>"></a>
                <?endforeach;?>
            </div>
        </div>
    </section>
<?endif?>

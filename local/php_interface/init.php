<?
function pre($str) {
    echo "<pre>";
    print_r($str);
    echo "</pre>";
}

if (!function_exists("mb_str_replace"))
{
    function mb_str_replace($needle, $replace_text, $haystack) {
        return implode($replace_text, mb_split($needle, $haystack));
    }
}

function ELCPreview($value, $limit = 150)
{
    $value = stripslashes($value);
    $value = htmlspecialchars_decode($value, ENT_QUOTES);
    $value = str_ireplace(array('<br>', '<br />', '<br/>'), ' ', $value);
    $value = strip_tags($value);
    $value = trim($value);

    if (mb_strlen($value) < $limit) {
        return $value;
    } else {
        $value   = mb_substr($value, 0, $limit);
        $length  = mb_strripos($value, ' ');
        $end     = mb_substr($value, $length - 1, 1);

        if (empty($length)) {
            return $value;
        } elseif (in_array($end, array('.', '!', '?'))) {
            return mb_substr($value, 0, $length);
        } elseif (in_array($end, array(',', ':', ';', '«', '»', '…', '(', ')', '—', '–', '-'))) {
            return trim(mb_substr($value, 0, $length - 1)) . '...';
        } else {
            return trim(mb_substr($value, 0, $length)) . '...';
        }

        return trim();
    }
}

function ELCPicture($pict, $placeholder = "short_detail", $retina = false) {
    $defType = BX_RESIZE_IMAGE_EXACT;
    switch ($placeholder) {
        case "index_collections":
            $width = 580;
            $height = 820;
            break;

        case "perfume_section":
            $width = 430;
            $height = 610;
            break;
        case "search":
            $width = 82;
            $height = 101;
            break;
        case "other_section":
            $width = 256;
            $height = 315;
            break;
        case "section_picture":
            $width = 1440;
            $height = 1440;
            break;
        case "detail":
            $width = 630;
            $height = 630;
            break;
        case "perf_list":
            $width = 260;
            $height = 350;
            break;
        case "perf_slider":
            $width = 629;
            $height = 629;
            break;

        case "perf_detail":
            $width = 400;
            $height = 600;
            break;

        case "promo_section":
            $width = 328;
            $height = 420;
            break;

        case "instagram":
            $width = 520;
            $height = 520;
            break;

        case "search":
            $width = 210;
            $height = 300;
            break;

        case "banner_main_adaptive":
            $width = 720;
            $height = 970;
            break;

    }

    if ($retina) {
        $width *= 2;
        $height *= 2;
    }

    if (intval($pict))
        return CFile::ResizeImageGet(intval($pict), array('width' => $width, 'height' => $height), $defType, true);
    else
        return ["src" => SITE_TEMPLATE_PATH."/img/no_photo.png"];
}

function fmUpdatePrices() {
    CModule::includeModule("ddp.mod");
    $estee = new \DDP\Mod\FM();
    $estee->updatePrices();
    return "fmUpdatePrices();";
}

function fmUpdateCatalog() {
    CModule::includeModule("ddp.mod");
    $estee = new \DDP\Mod\FM();
    //$status = $estee->updateBQCatalog(strtotime('-1 day'));
    $status = $estee->updateBQCatalog();
    return "fmUpdateCatalog();";
}

<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<script>
    function subscribeCRM() {
        $('body').addClass('subscribe-popup');
        $(document).mouseup(function (e) {
            var div = $(".modal");

            if (!div.is(e.target) && div.has(e.target).length === 0) {
                $('body').removeClass('subscribe-popup');
            }
        });
        $('.exit').on('click', function () {
            $('body').removeClass('subscribe-popup');
        });
    }
    window.onload = subscribeCRM;
</script>

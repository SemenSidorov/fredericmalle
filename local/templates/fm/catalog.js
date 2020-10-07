$(function() {
    $("body").on("click", ".product__select-item__link", function(event) {
        event.preventDefault();
        var href = $(this).attr("href");
        if (href.indexOf("?") == -1) {
            var ajaxhref = href + "?is_ajax=y";
        } else {
            var ajaxhref = href + "&is_ajax=y";
        }
        $.get( ajaxhref, function( data ) {
            $("#fm_product").replaceWith(data);
            history.pushState(null, null, href);
            absense();
            showSlider();
            sliderFour();
        });
    });

    var catalogPath = window.location.pathname;

    if (catalogPath.indexOf("search")) {
        catalogPath += "?q=" + $(".search_req").val() + "&is_ajax=y";
    } else {
        catalogPath += "?is_ajax=y";
    }

    $("body").on("click", ".lelabo_filter", function() {
        var type_id = $(this).attr("data-id");

        $.get(catalogPath + "&add="+type_id, function( data ) {
            $("#ajax_section").replaceWith(data);
        });
    });

    $("body").on("click", ".filter-tag", function() {
        var type_id = $(this).attr("data-id");
        $.get(catalogPath+"&del="+type_id, function( data ) {
            $("#ajax_section").replaceWith(data);
        });
    });

    $("body").on("click", ".filter-clear", function() {
        $.get(catalogPath+"&delALL=y", function( data ) {
            $("#ajax_section").replaceWith(data);
        });
    });

    $("body").on("click", ".product__btn", function(event) {
        event.preventDefault();
        if ($(this).attr("data-url")) {
            window.open(
                $(this).attr("data-url"),
                '_blank'
            );
        }
    });

    $("body").on("click", ".product-details-toggle-new", function(event) {
        event.preventDefault();
        if ($(".product-details-toggle-new").html() == "показать больше") {
            $(".product-details-preview").hide();
            $(".product-details-inner").show();
            $(".product-details-toggle-new").html("показать меньше");
        } else {
            $(".product-details-preview").show();
            $(".product-details-inner").hide();
            $(".product-details-toggle-new").html("показать больше");
        }
    });


    if ($(".cookie-block")) {
        $(".cookie-block").on("click", ".cookie_yes", function(event) {
            var query = {
                action: 'ddp:mod.api.fm.cookie',
            };

            var data = {
                confirm: "Y",
                SITE_ID: 's1',
                sessid: BX.message('bitrix_sessid')
            };

            var request = $.ajax({
                url: '/bitrix/services/main/ajax.php?' + $.param(query),
                method: 'POST',
                data: data
            });

            request.done(function (response) {
                if (response.data.result == "ok") {
                    $(".cookie-block").hide();
                };
            });
        });
    }
});

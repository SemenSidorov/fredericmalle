jQuery.validator.addMethod("emailExt", function(value, element, param) {
    return value.match(/^[a-zA-Z0-9_\.%\+\-]+@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,}$/);
},'Введите корректный Email');


$(function() {

    var $form = $('#modal-form');

    $form.validate({
        rules: {
            sex: {
                required: true
            },
            name: {
                required: true,
                minlength: 3,
            },
            email: {
                required: true,
                email: true,
                emailExt: true
            },
            confirm: {
                required: true
            }
        },
        messages: {
            sex: {
                required: "Поле обязательно для выбора",
            },
            name: {
                required: "Поле обязательно для заполнения",
                minlength: "Минимальное кол-во символов 3",
            },
            email: {
                required: "Поле обязательно для заполнения",
                email: "Введите корректный Email"
            },
            confirm: {
                required: "Подтвердите согласие",
            }
        },
        submitHandler: function() {
            post($form);
        }
    });

    function post($form){
        var name = $form.find("input[name=name]").val();
        var email = $form.find("input[name=email]").val();
        var sexInput = $form.find("input[name=sex]");
        var sex;

        sexInput.each(function(index, item){
           if($(item).is(':checked')){
               sex = $(item).val();
           } 
        });

        var confirm = $form.find("input[name=confirm]").is(':checked');

        if (email && name && sex && confirm ) {
            var query = {
                action: 'ddp:mod.api.fm.subscribe',
            };

            var data = {
                name: name,
                email: email,
                sex: sex,
                confirm: confirm,
                SITE_ID: 's1',
                sessid: BX.message('bitrix_sessid')
            };

            var request = $.ajax({
                url: '/bitrix/services/main/ajax.php?' + $.param(query),
                method: 'POST',
                data: data
            });

            var $subscribe = $('.subscribe-popup')[1];

            request.done(function (response) {
                if (response.data.result == "ok") {
                    $($subscribe).html(' <div class="modal modal--success">\n' +
                        '        <div class="exit exit--success"></div>\n' +
                        '        <h3 class="modal__title">Спасибо за подписку</h3>\n' +
                        '        <a href="/products/perfume" class="modal__link">Вернуться к Frederic Malle</a>\n' +
                        '    </div>\n' +
                        '</div>')
                }
                if (response.data.result == "already") {
                    $($subscribe).html(' <div class="modal modal--success">\n' +
                        '        <div class="exit exit--success"></div>\n' +
                        '        <h3 class="modal__title">Вы уже подписаны на нашу рассылку</h3>\n' +
                        '        <a href="/products/perfume" class="modal__link">Вернуться к Frederic Malle</a>\n' +
                        '    </div>\n' +
                        '</div>')
                }


            });
        } else {
            return false;
        }

    }
});

/* Подписка в футере */

$(function() {

    /*  Валидация формы в футере  */

    var $formFooter = $('#footer-form');

    $formFooter.validate({
        rules: {
            email: {
                required: true,
                email: true,
                emailExt: true
            },
            confirm: {
                required: true
            }
        },
        messages: {
            email: {
                required: "Поле обязательно для заполнения",
                email: "Введите корректный Email"
            },
            confirm: {
                required: "Подтвердите согласие",
            }
        },
        submitHandler: function() {
            postFooter($formFooter);
        }
    });

    function postFooter($formFooter){
        var email = $formFooter.find("input[name=email]").val();
        var confirm = $formFooter.find("input[name=confirm]").is(':checked');


        if (email && confirm ) {
            var query = {
                action: 'ddp:mod.api.fm.subscribe',
            };

            var data = {
                email: email,
                confirm: confirm,
                SITE_ID: 's1',
                sessid: BX.message('bitrix_sessid')
            };

            var request = $.ajax({
                url: '/bitrix/services/main/ajax.php?' + $.param(query),
                method: 'POST',
                data: data
            });


            var $subscribe = $('.modal-popup');

            request.done(function (response) {
                if (response.data.result == "ok") {
                    $($subscribe).html(' <div class="modal modal--success">\n' +
                        '        <div class="exit exit--success"></div>\n' +
                        '        <h3 class="modal__title">Спасибо за подписку</h3>\n' +
                        '        <a href="/products/perfume" class="modal__link">Вернуться к Frederic Malle</a>\n' +
                        '    </div>\n' +
                        '</div>')
                    $('body').addClass('subscribe-popup');
                }
                if (response.data.result == "already") {
                    $($subscribe).html(' <div class="modal modal--success">\n' +
                        '        <div class="exit exit--success"></div>\n' +
                        '        <h3 class="modal__title">Вы уже подписаны на нашу рассылку</h3>\n' +
                        '        <a href="/products/perfume" class="modal__link">Вернуться к Frederic Malle</a>\n' +
                        '    </div>\n' +
                        '</div>')
                    $('body').addClass('subscribe-popup');
                }


            });
        } else {
            return false;
        }
    }
})

/* Подписка по ссылке в CRM */
$(function() {
    var $form = $('#modal-form-subscribe-crm');
    if ($form) {
        $form.validate({
            rules: {
                confirm: {
                    required: true
                }
            },
            messages: {
                confirm: {
                    required: "Подтвердите согласие",
                }
            },
            submitHandler: function () {
                post($form);
            }
        });

        function post($form) {
            var confirm = $form.find("input[name=confirm]").is(':checked');
            var contact = $form.find("input[name=contact]").val();

            if (confirm && contact) {
                var query = {
                    action: 'ddp:mod.api.fm.subscribeCRM',
                };

                var data = {
                    confirm: confirm,
                    contact: contact,
                    SITE_ID: 's1',
                    sessid: BX.message('bitrix_sessid')
                };

                var request = $.ajax({
                    url: '/bitrix/services/main/ajax.php?' + $.param(query),
                    method: 'POST',
                    data: data
                });

                var $subscribe = $('.subscribe-popup')[1];

                request.done(function (response) {
                    if (response.data.result == "ok") {
                        $($subscribe).html(' <div class="modal modal--success">\n' +
                            '        <div class="exit exit--success"></div>\n' +
                            '        <h3 class="modal__title">Спасибо за подписку</h3>\n' +
                            '        <a href="/products/perfume" class="modal__link">Вернуться к Frederic Malle</a>\n' +
                            '    </div>\n' +
                            '</div>')
                    }
                    if (response.data.result == "already") {
                        $($subscribe).html(' <div class="modal modal--success">\n' +
                            '        <div class="exit exit--success"></div>\n' +
                            '        <h3 class="modal__title">Вы уже подписаны на нашу рассылку</h3>\n' +
                            '        <a href="/products/perfume" class="modal__link">Вернуться к Frederic Malle</a>\n' +
                            '    </div>\n' +
                            '</div>')
                    }


                });
            } else {
                return false;
            }

        }
    }
});
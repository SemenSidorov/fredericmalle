/* Слайдер на специальных проектах */
$('.slider-special').slick({
    infinite: true,
    dots: true,
    arrows: false,
    autoplay: true,
    autoplaySpeed: 6000,
    slidesToShow: 1,
    slidesToScroll: 1,
    adaptiveHeight: true
});
/*Слайдер города на main.html */

$('.slider-city').slick({
    infinite: true,
    dots: true,
    autoplay: true,
    arrows: false,
    autoplaySpeed: 6000,
    slidesToShow: 1,
    slidesToScroll: 1,
    variableWidth: true,
    adaptiveHeight: true,
    centerMode: true,
});
/* Слайдер на main.html и на странице продукта */

$(document).ready(sliderFour);

function sliderFour() {
    $('.slider-three').slick({
        infinite: true,
        slidesToShow: 3,
        dots: true,
        autoplay: false,
        arrows: false,
        autoplaySpeed: 6000,
        swipeToSlide: true,
        slidesToScroll: 1,
        variableWidth: false,
        adaptiveHeight: true,
        responsive: [{
            breakpoint: 1000,
            settings: {
                variableWidth: false,
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }]
    });
}


/*Слайдер на странице О парфюмерах ( навигация по портретам )*/

$('.slider-list').slick({
    infinite: true,
    slidesToShow: 5,
    dots: false,
    arrows: false,
    variableWidth: false,
    adaptiveHeight: true,
    swipeToSlide: true,
    responsive: [{
        breakpoint: 1300,
        settings: {
            slidesToShow: 2,
            slidesToScroll: 2
        }
    }, {
        breakpoint: 500,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1
        }
    }]
});
/*Появление слайдера при уменьшении экрана*/

$(document).ready(function () {
    if ($(window).width() < 1300) {
        var catalog = $('.show-slider');
        var sliderMain = $('.show-slider__main');
        catalog.slick({
            dots: true,
            arrows: false,
            speed: 300,
            slidesToShow: 2,
            slidesToScroll: 2,
            infinite: false
        });
        sliderMain.slick({
            dots: true,
            arrows: false,
            speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true
        });
    }
});
$(function () {
    /* LOCATION POPUP */
    $('.link-language').on('click', function (e) {
        $('body').toggleClass('location-popup');
        $(document).mouseup(function (e) {
            var div = $(".location-main");

            if (!div.is(e.target) && div.has(e.target).length === 0) {
                $('body').removeClass('location-popup');
            }
        });
    });
    $('.close').on('click', function () {
        $('body').toggleClass('location-popup');
    });

    /* Подписка  */
    function subscribe() {
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

    $('body').on('click', '.product__btn--absence', subscribe);

    /* LOCATION FOOTER */

    $('.footer-country__select').on('click', function () {
        $('body').toggleClass('location-footer');
        $(document).mouseup(function (e) {
            var div = $(".footer-country__list");
            var btn = $('.footer-country__select');
            var arrow = $('.footer__arrow');

            if (!div.is(e.target) && !btn.is(e.target) && !arrow.is(e.target) && div.has(e.target).length === 0) {
                $('body').removeClass('location-footer');
            }
        });
    });
    /* Выпадающий список на странице продукта */

    $('body').on('click', '.product__select', function () {
        $('body').toggleClass('product__item');
        $(this).toggleClass('product__select--active');
    });
    $('body').on('click', '.product__select-item__link', function (e) {
        var choice = e.target;
        var choiceIndex = $(choice).index();
        var choiceText = $(choice).text();
        $('.product__select-text').html(choiceText);
        var $link = $('.product__select-item__link');
        $link.each(function (index, item) {
            $(item).show();
            $(item).removeClass('product__select-item__link--active');

            if (choiceIndex == index) {
                $(item).addClass('product__select-item__link--active');
            }
        });
    });
    /* слайдер на странице продукта   */

    $('body').on('click', '.product-pagination__item', function (event) {
        var $item = $('.product-pagination__item');
        var $img = $('.product-item__img');
        $($img).slideUp('slow');
        var choiceItem = event.target;
        var $index = $item.index(choiceItem);
        $($item).removeClass('product__active');
        $(choiceItem).addClass('product__active');
        $($img[$index]).fadeIn('slow');
    });
    /* Табы-вкладки */

    $('body').on('click', '.product-menu__item', function (event) {
        var $item = $('.product-menu__item');
        var $text = $('.product__text');
        var $choiceItem = event.target;
        var $index = $item.index($choiceItem);
        $($item).removeClass('product__active');
        /*$($text).slideUp('slow');*/
        $($text).css('display', 'none');
        $($choiceItem).addClass('product__active');
        /*$($text[$index]).slideDown('slow');*/
        $($text[$index]).css('display', 'block');
    });
    /* video по клику */

    $('.video__img').on('click', function () {
        $('body').addClass('video');
    });


    /*  переключалка по стрелкам на странице О парфюмерах */

    $('body').on('click', '.arrow_next', function () {
        $('.slider-nav').slick('slickNext');
        $('.slider-for').slick('slickNext');
        nextImg();
    });

    $('body').on('click', '.arrow_prev', function () {
        $('.slider-nav').slick('slickPrev');
        $('.slider-for').slick('slickPrev');
        nextImg();
    });

    $('body').on('click', 'li[role="presentation"]', function () {
        nextImg();
    });

    var desc = $('.story-list__block-desc');
    var target = desc.children();

    $(target).on('click', function () {
        setTimeout(nextImg, 100);
    });

    function nextImg() {
        var img = $('.story-list__block-img');
        img.removeClass('active_img');

        var active = $('.slick-current')[0];
        var activeImg = $(active).children('.story-list__block-img');
        activeImg.addClass('active_img');
    }

    /*NEW SLIDER ABOUT PARFUMER */
    /*Слайдер на странице О парфюмерах ( навигация по портретам )*/

    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav',
        adaptiveHeight: true,
    });
    $('.slider-nav').slick({
        slidesToShow: 7,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: true,
        arrows: false,
        focusOnSelect: true,
        swipeToSlide: true,
        adaptiveHeight: true,
    });

    /*  переключалка по стрелкам на странице Main  */

    $('.arrow_next').on('click', function () {
        var active = $('.active_flex');
        var collaboration = $('.collaboration-block');
        var index = collaboration.index(active);
        index += 1;

        if (index == collaboration.length) {
            index = 0;
        }

        collaboration.removeClass('active_flex');
        $(collaboration[index]).addClass('active_flex');
    });
    $('.arrow_prev').on('click', function () {
        var active = $('.active_flex');
        var collaboration = $('.collaboration-block');
        var index = collaboration.index(active);
        index -= 1;

        if (index == -1) {
            index = collaboration.length - 1;
        }

        collaboration.removeClass('active_flex');
        $(collaboration[index]).addClass('active_flex');
    });

    /*  Выпадающие списки на Листинге */

    $('body').on('click', '.listing-nav__block', function (e) {
        var text = $(this).children('.listing-nav__text');
        var item = $('.listing-more');
        var arrow = $('.listing-nav__arrow');
        var arrowActive = $(text).children('.listing-nav__arrow');

        if ($('.listing-nav__text').hasClass('listing-nav__text--red')) {
            $('.listing-nav__text').removeClass('listing-nav__text--red');
            $(text).addClass('listing-nav__text--red');
        } else {
            $(text).addClass('listing-nav__text--red');
        }

        if ($(this).hasClass('listing-nav__block--open')) {
            $(arrowActive).removeClass('listing-nav__arrow_rotate');
            $(text).removeClass('listing-nav__text--red');
            $(this).removeClass('listing-nav__block--open');
        } else {
            $(arrow).removeClass('listing-nav__arrow_rotate');
            $('.listing-nav__block').removeClass('listing-nav__block--open');
            $(arrowActive).addClass('listing-nav__arrow_rotate');
            $(item).removeClass('active');
            $(this).addClass('listing-nav__block--open');
        }
    });

    /* Закрытие фильтров при клике вне */

    $(document).on("click", function (e) {
        if ($('.listing-nav__block').hasClass('listing-nav__block--open')) {
            if (!$('.listing-nav__block').is(e.target) && $('.listing-nav__block').has(e.target).length === 0) {
                $('.listing-nav__block').removeClass('listing-nav__block--open');
                $('.listing-nav__arrow').removeClass('listing-nav__arrow_rotate');
                return;
            }
        }
    });

    /* Смена фотографий в листинге*/

    $('body').on('mouseenter', '.content', function () {
        var content = $('.listing-collection--slider');
        var $items = content.find('.collection-item__link');
        var setIntervalID = setInterval(function () {
            $items.each(function (index, item) {
                var $img = $(this).find('.collection__img');
                var $imgActive = $(this).find('.collection__img--active');
                var indexActive = $imgActive.index() + 1;
                $img.removeClass('collection__img--active');

                if (indexActive == $img.length) {
                    $img.eq(0).addClass('collection__img--active');
                } else {
                    $img.eq(indexActive).addClass('collection__img--active');
                }
            });
        }, 5000);
        $('.content').on('mouseleave', function () {
            clearInterval(setIntervalID);
        });
    });

    /* Закрытие/открытие меню при клике вне и при клике на кнопку*/

    $(document).on("click", function (e) {
        if ($('.btn-menu').is(e.target) || $('.btn-menu').children('span').is(e.target)) {
            $('.menu-adaptiv').toggleClass('adaptiv-active');
            $('body').toggleClass('menu-active');
            return;
        }
        if ($('.menu-adaptiv').hasClass('adaptiv-active') && $('body').hasClass('menu-active')) {
            if (!$('.menu-adaptiv').is(e.target) && $('.menu-adaptiv').has(e.target).length === 0) {
                $('.menu-adaptiv').removeClass('adaptiv-active');
                $('body').removeClass('menu-active');
                return;
            }
        }
        if ($('body').hasClass('header-main') && $('.header-drop').hasClass('active-drop')) {
            if (!$('.header-drop').is(e.target) && $('.header-drop').has(e.target).length === 0) {
                $('body').removeClass('header-main');
                $('.header-drop').removeClass('active-drop');
                return;
            }
        }


    });

    /* Адаптив меню доп списки при клике */

    $('body').on('click', '.menu__link', btnMenu);

    function btnMenu(e) {
        var link = e.target;
        var $parent = $(link).parent();
        var $list = $parent.find('.hidden-menu__list');
        var $arrow = $parent.children('.menu__arrow');
        var $item = $('.menu__item')[0];
        var $parentItem = $(link).parent()[0];
        if ($item != $parentItem) {
            if ($list.hasClass('block')) {
                $list.slideUp();
                $list.removeClass('block');
                $arrow.removeClass('menu__arrow--rotate');
            } else {
                $list.slideDown();
                $list.addClass('block');
                $arrow.addClass('menu__arrow--rotate');
            }
        }
    }

    /*Выпадающее меню*/

    $('#ddmenu li').hover(inLi);

    function inLi() {
        if ($(window).width() > 1000) {
            $('body').addClass('header-main');
            var menuDropActive = $('div[class|="header-drop active-drop"]');
            menuDropActive.removeClass("active-drop");
            var menuDrop = $('div[class|="header-drop"]');
            var indexLi = $('#ddmenu li').index(this);

            var choiceMenuDrop = menuDrop.eq(indexLi);
            var child = choiceMenuDrop.children();
            if (child.length != 0) {
                choiceMenuDrop.addClass("active-drop");
            }

            menuDropActive.mouseleave(function () {
                menuDropActive.removeClass("active-drop");
                $('body').removeClass('header-main');
            });
        }


    }


    /* Кнопка внизу сайта для скролла наверх*/

    $(window).scroll(function () {
        if ($(this).scrollTop() != 0) {
            $('#toTop').fadeIn();
        } else {
            $('#toTop').fadeOut();
        }
    });
    $('#toTop').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 800);
    });
    /*  Выпадашки на shop (магазины)*/

    var linkStore = $('.page-stores__item').find('a');
    $(linkStore).on('click', function (e) {
        e.preventDefault();
        var cell = $(this).siblings();
        cell.toggleClass('active');
    });
    /* кнопка Read Less Read More*/

    $('.collaboration__more_show').on('click', function (e) {
        e.preventDefault();
        var link = e.target;
        var item = $(link).parent()[0];
        var block = $(item).parent()[0];
        var text = $(block).children('p');
        $(text[text.length - 1]).fadeToggle();
        $('.collaboration__select-arrow').toggleClass('collaboration__select-arrow_rotate');

        if ($('.collaboration__select-arrow').hasClass('collaboration__select-arrow_rotate')) {
            $('.collaboration__more_show').text('Показать меньше');
        } else {
            $('.collaboration__more_show').text('Показать больше');
        }
    });
    /*  SEARCH в хэдере */

    $('.header-search').on('click', function (e) {
        e.preventDefault();
        $('body').addClass('search-header');

        if ($(window).width() < 750) {
            $('#title-search-input').attr('placeholder', 'ПОИСК');
        }

        $('.header-seach__img').on('click', function () {
            $('body').removeClass('search-header');
            $('#search').val('');
        });
    });
    setTimeout(function () {
        $('.cookie-block').addClass('active_flex');
        $('.cookie__img').on('click', function () {
            $('.cookie-block').removeClass('active_flex');
        });
        $('.cookie__link').on('click', function () {
            $('.cookie-block').removeClass('active_flex');
        });
    }, 2000);
});
/*  Функционал на странице SHOP */

$('.tabs-row__btn').on('click', function () {
    $('.tabs-row__btn').removeClass('active');
    var $index = $(this).index();
    $(this).addClass('active');
    var $cityList = $('.tabs-caption');
    $cityList.removeClass('active');
    $cityList.hide('slow');
    var $cityListIndex = $cityList.eq($index);
    $cityListIndex.show('slow');
    var cityChild = $cityListIndex.children()[0];
    $('.tabs-caption__btn').removeClass('active');
    $(cityChild).addClass('active');
    var $cityId = $(cityChild).attr('id');
    content($cityId);
});
$('.tabs-caption__btn').on('click', function () {
    var id = this.id;
    $('.tabs-caption__btn').removeClass('active');
    $(this).addClass('active');
    content(id);
});

function content(id) {
    var $list = $('.address-group');
    $list.removeClass('active_flex');
    $list.each(function (index, item) {
        var $listId = $(item).attr('id');
        var listIdItem = $listId.split(' ');

        if (listIdItem[0] == id) {
            $(item).addClass('active_flex');
        }
    });
}

/* пирамида на странице продукта */

$('body').on('mouseenter', '.spectrum-item', function () {
    var $activeLink = $('.spectrum__link_active');
    $activeLink.removeClass('spectrum__link_active');
    $('body').on('mouseleave', '.spectrum-item', function () {
        $activeLink.addClass('spectrum__link_active');
    });
});

/* Блоки на странице продукта в самом низу - больше 3х - в слайдер */

$(document).ready(showSlider());

function showSlider() {
    var $childCollection = $('.collection-content').children();
    var $childDiscover = $('.discovers-content').children();

    if ($childDiscover.length > 3) {
        $('.discovers-content').addClass('discovers-content--slider');
        $('.discovers-content').slick({
            infinite: true,
            slidesToShow: 3,
            dots: true,
            autoplay: false,
            arrows: false,
            autoplaySpeed: 6000,
            slidesToScroll: 1,
            variableWidth: false,
            adaptiveHeight: true,
            responsive: [{
                breakpoint: 1000,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            }, {
                breakpoint: 750,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }, {
                breakpoint: 500,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
        });
    } else {
        if ($(window).width() < 1000) {
            $('.discovers-content').addClass('discovers-content--slider');
            $('.discovers-content').slick({
                infinite: true,
                slidesToShow: 3,
                dots: true,
                autoplay: false,
                arrows: false,
                autoplaySpeed: 6000,
                slidesToScroll: 1,
                variableWidth: false,
                adaptiveHeight: true,
                responsive: [{
                    breakpoint: 1000,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                }, {
                    breakpoint: 750,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                }, {
                    breakpoint: 500,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
            });
        }
    }
    if ($childCollection.length > 3) {
        $('.collection-content').addClass('collection-content--slider');
        $('.collection-content').slick({
            infinite: true,
            slidesToShow: 3,
            dots: true,
            autoplay: false,
            arrows: false,
            swipeToSlide: true,
            slidesToScroll: 1,
            variableWidth: false,
            adaptiveHeight: true,
            responsive: [{
                breakpoint: 1000,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            }, {
                breakpoint: 750,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }, {
                breakpoint: 500,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
        });
    } else {
        if ($(window).width() < 1000) {
            $('.collection-content').addClass('collection-content--slider');
            $('.collection-content').slick({
                infinite: true,
                slidesToShow: 3,
                dots: true,
                autoplay: false,
                arrows: false,
                swipeToSlide: true,
                slidesToScroll: 1,
                variableWidth: false,
                adaptiveHeight: true,
                responsive: [{
                    breakpoint: 1000,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                }, {
                    breakpoint: 750,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                }, {
                    breakpoint: 500,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
            });
        }
    }
}


/*  расположение товаров на листинге в строке на 3 и 6 */

$('.listing-rows__number-3').on('click', function () {
    $('.listing-rows__number-6').removeClass('active__number');
    $('.listing-collection').removeClass('rows-6');
    $('.listing-rows__number-3').addClass('active__number');
    document.cookie = "show=3";
});
$('.listing-rows__number-6').on('click', function () {
    $('.listing-rows__number-3').removeClass('active__number');
    $('.listing-collection').addClass('rows-6');
    $('.listing-rows__number-6').addClass('active__number');
    document.cookie = "show=6";
});

$(document).ready(itemView);

if ($(window).width() < 1366) {
    $('.listing-collection').removeClass('rows-6');
    document.cookie = "show=3";
}

function itemView() {
    var mode = getCookie("show");
    if (mode == 3) {
        $('.listing-rows__number-6').removeClass('active__number');
        $('.listing-collection').removeClass('rows-6');
        $('.listing-rows__number-3').addClass('active__number');
    } else if (mode == 6) {
        $('.listing-rows__number-3').removeClass('active__number');
        $('.listing-collection').addClass('rows-6');
        $('.listing-rows__number-6').addClass('active__number');
    }
}

function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

/* Проверка на кол-во товара для реализации кнопки вывода по 3 и по 6 */

function checkingProducts() {
    var $block = $('.collection__item').parent('.listing-collection');
    var $child = $block.children('.collection__item');
    if ($child.length < 6) {
        $('.listing-rows').css('display', 'none');
    }
}

$(document).ready(checkingProducts);

/*  Проверка нахождения на странице парфюмера для блока товаров и отброс категорий с товаров на этой странице */

function isParfumer() {
    if ($('section').hasClass('parfumer-promo')) {
        document.cookie = "show=3";
        $('.collection__cell').css('display', 'none');
    }
}

$(document).ready(isParfumer);

/*Кнопка уведомить о поступлении при отсутствии вариантов покупки*/

function absense() {
    if ($('div').hasClass('product-cell')) {
        $('.product-absence').css('display', 'none');
    } else {
        $('.product-absence').css('display', 'block');
    }
}

$(document).ready(absense);


/*Хедэр при прокрутке*/

$(window).scroll(function () {
    if ($(window).width() > 1000) {
        if ($('section').hasClass('transparency')) {
            if ($(window).scrollTop() > 50) {
                $('body').addClass('header-main');
                $('body').removeClass('header-transparency');
            } else if ($(window).scrollTop() < 50) {
                $('body').removeClass('header-main');
                $('body').addClass('header-transparency');
            }
        }
    }
});

/*  Статичный header, кроме главной */

$(document).ready(() => {
    if ($(window).width() > 1000) {
        if ($('section').hasClass('transparency')) {
            $('body').addClass('header-transparency');
        }
    }
})

/* SELECT in SHOP */
$(document).ready(function () {
    if ($('div').hasClass('stores-select')) {
        var $link = $('.stores-select__link');
        $link.each(function (index, item) {
            $(item).attr('id__link', index);
        })
        var $firstLink = $('.stores-select__link');
        $($firstLink[0]).addClass('stores-select__link--active');
    }
})

$('body').on('click', '.stores-select', function (e) {
    e.preventDefault();
    $('.stores-select__list').toggleClass('active');
    $('.stores-select__arrow').toggleClass('stores-select__arrow_rotate');
});
$('body').on('click', '.stores-select__link', function (e) {
    var choice = e.target;
    var choiceAttr = $(choice).attr('id__link');

    var $block = $('.page-stores-list');
    $block.slideUp();
    $block.each(function (index, item) {
        if (choiceAttr == index) {
            $(item).slideDown();
        }
    })

    var choiceText = $(choice).text();
    $('.stores-select__text').html(choiceText);
    var $link = $('.stores-select__link');
    $link.each(function (index, item) {
        $(item).show();
        $(item).removeClass('stores-select__link--active');

        if (choiceAttr == index) {
            $(item).addClass('stores-select__link--active');
        }
    });
});

/* Подписка в футере */

function openFooterSub() {
    $('.footer-subscribe__cell').slideDown();
    labelText();
}

function closeFooterSub() {
    $('.footer-subscribe__cell').slideUp();
    $('.footer-subscribe__select-arrow').removeClass('footer-subscribe__select-arrow--rotate');
    $('.footer-subscribe__select-list').slideUp();
    $('#footer-email').attr('placeholder', 'E-mail');
}

function footerList() {
    $('.footer-subscribe__select-list').slideToggle();
    $('.footer-subscribe__select-arrow').toggleClass('footer-subscribe__select-arrow--rotate');
}

function labelText() {
    $('#footer-email').attr('placeholder', '');
}

function submitFooterForm(e) {
    e.preventDefault();
    $('#footer-form').submit();

    if ($('.mycheckbox__default-footer').is(':checked')) {
        $('.mycheckbox-footer').removeClass('checkbox-error');
    } else {
        $('.mycheckbox-footer').addClass('checkbox-error');
    }
}

function footerListRegion(e) {
    e.preventDefault();
    $('.footer-subscribe__select-link').removeClass('footer-subscribe__select-link--active');
    var region = e.target;
    var regionText = $(region).text();
    $(region).addClass('footer-subscribe__select-link--active');
    var $text = $('.footer-subscribe__select-text');
    $text.text(regionText);
    footerList();
}

$('body').on('click', '.footer-subscribe__select-link', footerListRegion);

$('body').on('focus', '.footer-subscribe__input', function () {
    openFooterSub();
    labelText();
});
$('body').on('click', '.footer-subscribe__close', closeFooterSub);
$('body').on('click', '.footer-subscribe__select-text', footerList);
$('body').on('click', '.footer-subscribe__submit', submitFooterForm);

function oneItemParfumer() {
    var $block = $('.parfumer-list');
    var $item = $block.find('.collection__item');
    if ($item.length == 1) {
        $('.listing-collection').css('justify-content', 'center');
    }
}

$(document).ready(oneItemParfumer);


function noLinkClick() {
    var $block = $('.promo-block');
    var $link = $block.children('a');
    $link.each(function (index, item) {
        var href = $(item).attr('href');
        if (href == 0) {
            $(item).css('cursor', 'default');
            $(item).attr('onclick', 'event.preventDefault()');
        }
    })
}

$(document).ready(noLinkClick);

$('body').on('click', '.mycheckbox', function () {
    if ($('.mycheckbox__default').is(':checked')) {
        $(this).addClass('checkbox-active');
        $(this).removeClass('checkbox-error');
    } else {
        $(this).addClass('checkbox-error');
        $(this).removeClass('checkbox-active');
    }
})

$('body').on('click', '.mycheckbox-footer', function () {
    if ($('.mycheckbox__default-footer').is(':checked')) {
        $(this).addClass('checkbox-active');
        $(this).removeClass('checkbox-error');
    } else {
        $(this).addClass('checkbox-error');
        $(this).removeClass('checkbox-active');
    }
})

$('select').change(function (e) {
    $('#modal-form').validate().element($(e.target));
});

$('body').on('click', '.modal__btn', function () {
    if ($('.mycheckbox__default').is(':checked')) {
        $('.mycheckbox').removeClass('checkbox-error');
    } else {
        $('.mycheckbox').addClass('checkbox-error');
    }
})

$('body').on('click', '.exit', function () {
    $('body').removeClass('subscribe-popup');
});

function oneIteminMenuDrop() {
    var $list = $('.header-drop__list');
    $list.each(function (index, item) {
        if ($(item).children().length == 1) {
            $(item).css('justify-content', 'center');
        }
    })
}

$(document).ready(oneIteminMenuDrop);

function oneItemInCategories() {
    var $item = $('.collection__cell-item');
    $item.each(function (index, item) {
        if ($(item).children().length == 1) {
            $(item).css('width', '40%');
        }
    })
}

$(document).ready(oneItemInCategories);

function firstElemInMenuArrow() {
    var $arrowMenu = $('.menu__arrow');
    var $firstArrow = $($arrowMenu)[0];
    $($firstArrow).css('display', 'none');
}

$(document).ready(firstElemInMenuArrow);

function zoomAdaptiveMenu() {
    var $adaptiveItem = $('.menu__item');
    var $menuLink = $adaptiveItem.children('.menu__link');
    $menuLink.each(function (index, item) {
        if (index != 0) {
            $(item).on('click', function () {
                $('body').toggleClass('adaptiv-zoom');
            });
        }
    })
}

$(document).ready(zoomAdaptiveMenu);

function firstBlockStores() {
    if ($(window).width() < 1000) {
        var $block = $('.page-stores-list')[0];
        $($block).css('display', 'block');
    }
}

$(document).ready(firstBlockStores);

try {

    const link = document.querySelector('a[href="#video"]');
    if (link) {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const block = document.querySelector('.story-video');
            let height = block.querySelector('p').offsetHeight;
            if (window.innerWidth > 1900) {
                height = height * 2.5
            } else if (window.innerWidth > 1400) {
                height = height * 2
            }
            $('html,body').animate({scrollTop: $(block).offset().top + height + "px"}, {duration: 1E3});
        });
    }


} catch (e) {
    console.log(e)
}

try {

    const mainPromoLink = document.querySelectorAll('.main-promo--link');

    const setLinkToPromoBlock = (arr) => {
        arr = [...arr];
        arr.forEach(item => {
            item.addEventListener('click', () => {
                const href = item.querySelector('a').getAttribute('href');
                location.assign(`${href}`);
            })
        })
    }

    mainPromoLink && setLinkToPromoBlock(mainPromoLink);

}catch (e) {
    console.log(e+ 'link in main promo')
}
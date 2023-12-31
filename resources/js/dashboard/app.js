/*****
 * CONFIGURATION
 */
//Main navigation
$.navigation = $('nav > ul.nav');

$.panelIconOpened = 'icon-arrow-up';
$.panelIconClosed = 'icon-arrow-down';

//Default colours
$.brandPrimary = '#20a8d8';
$.brandSuccess = '#4dbd74';
$.brandInfo = '#63c2de';
$.brandWarning = '#f8cb00';
$.brandDanger = '#f86c6b';

$.grayDark = '#2a2c36';
$.gray = '#55595c';
$.grayLight = '#818a91';
$.grayLighter = '#d1d4d7';
$.grayLightest = '#f8f9fa';
$.ajaxLoadingIcon = 'fa fa-refresh fa-spin';
$.ajaxLoadingFontawesomeResizeFactor = '0.50';

'use strict';

/****
 * MAIN NAVIGATION
 */

$(document).ready(function ($) {

    // Add class .active to current link
    $.navigation.find('a').each(function () {

        var cUrl = String(window.location).split('?')[0];

        if (cUrl.substr(cUrl.length - 1) == '#') {
            cUrl = cUrl.slice(0, -1);
        }

        if ($($(this))[0].href == cUrl) {
            $(this).addClass('active');

            $(this).parents('ul').add(this).each(function () {
                $(this).parent().addClass('open');
            });
        }
    });

    // Dropdown Menu
    $.navigation.on('click', 'a', function (e) {

        if ($.ajaxLoad) {
            e.preventDefault();
        }

        if ($(this).hasClass('nav-dropdown-toggle')) {
            $(this).parent().toggleClass('open');
            resizeBroadcast();
        }

    });

    function resizeBroadcast()
    {

        var timesRun = 0;
        var interval = setInterval(function () {
            timesRun += 1;
            if (timesRun === 5) {
                clearInterval(interval);
            }
            window.dispatchEvent(new Event('resize'));
        }, 62.5);
    }

    /* ---------- Main Menu Open/Close, Min/Full ---------- */
    $('.navbar-toggler').click(function () {

        if ($(this).hasClass('sidebar-toggler')) {
            $('body').toggleClass('sidebar-hidden');
            resizeBroadcast();
        }

        if ($(this).hasClass('sidebar-minimizer')) {
            $('body').toggleClass('sidebar-minimized');
            resizeBroadcast();
        }

        if ($(this).hasClass('aside-menu-toggler')) {
            $('body').toggleClass('aside-menu-hidden');
            resizeBroadcast();
        }

        if ($(this).hasClass('mobile-sidebar-toggler')) {
            $('body').toggleClass('sidebar-mobile-show');
            resizeBroadcast();
        }

    });

    $('.sidebar-close').click(function () {
        $('body').toggleClass('sidebar-opened').parent().toggleClass('sidebar-opened');
    });

    /* ---------- Disable moving to top ---------- */
    $('a[href="#"][data-top!=true]').click(function (e) {
        e.preventDefault();
    });

});

/****
 * CARDS ACTIONS
 */

$(document).on('click', '.card-actions a', function (e) {
    e.preventDefault();

    if ($(this).hasClass('btn-close')) {
        $(this).parent().parent().parent().fadeOut();
    } else if ($(this).hasClass('btn-minimize')) {
        var $target = $(this).parent().parent().next('.card-block');
        if (!$(this).hasClass('collapsed')) {
            $('i', $(this)).removeClass($.panelIconOpened).addClass($.panelIconClosed);
        } else {
            $('i', $(this)).removeClass($.panelIconClosed).addClass($.panelIconOpened);
        }
    } else if ($(this).hasClass('btn-setting')) {
        $('#myModal').modal('show');
    }

});

function capitalizeFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function init(url)
{

    /* ---------- Tooltip ---------- */
    $('[rel="tooltip"],[data-rel="tooltip"]').tooltip({"placement": "bottom", delay: {show: 400, hide: 200}});

    /* ---------- Popover ---------- */
    $('[rel="popover"],[data-rel="popover"],[data-toggle="popover"]').popover();

}

(function ($) {
    $.fn.ForceNumericOnly =
        function () {
            return this.each(function () {
                $(this).keydown(function (e) {
                    var key = e.charCode || e.keyCode || 0;
                    // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
                    // home, end, period, and numpad decimal
                    return (
                        key === 110 || key === 190 ||
                        key === 8 ||
                        key === 9 ||
                        key === 13 ||
                        key === 46 ||
                        key === 110 ||
                        key === 190 ||
                        key === 44 ||
                        (key >= 35 && key <= 40) ||
                        (key >= 48 && key <= 57) ||
                        (key >= 96 && key <= 105));
                });
            });
        };

    $.fn.parseArabic = function () {
        return this.each(function () {
            $(this).keyup(function (e) {
                var str = $(this).val().replace(/,/g, '');
                var data = Number(str.replace(/[٠١٢٣٤٥٦٧٨٩]/g, function (d) {
                    return d.charCodeAt(0) - 1632; // Convert Arabic numbers
                }).replace(/[۰۱۲۳۴۵۶۷۸۹]/g, function (d) {
                    return d.charCodeAt(0) - 1776; // Convert Persian numbers
                }));
                if (data !== null) {
                    $(this).val(data);
                }
            });
        });
    }

    $.fn.addCommas = function () {
        return this.each(function () {
            $(this).keyup(function (e) {
                var nStr = $(this).val();
                nStr += '';
                nStr = nStr.replace(/,/g, '');
                var x = nStr.split('.');
                var x1 = x[0];
                var x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                if ($.trim(nStr) !== null) {
                    $(this).val($.trim(x1 + x2));
                }
            });
        });
    }

    $.fn.onlyEnglish = function () {
        return this.each(function () {
            $(this).keypress(function (event) {
                var ew = event.which;
                if (ew === 32) {
                    return true;
                }
                if (48 <= ew && ew <= 57) {
                    return true;
                }
                if (65 <= ew && ew <= 90) {
                    return true;
                }
                if (97 <= ew && ew <= 122) {
                    return true;
                }
                alert('فقط حروف انگلیسی قابل قبول است.');
                return false;
            });
        });
    }

})(jQuery);

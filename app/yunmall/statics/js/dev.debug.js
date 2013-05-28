/* global.js */
/*globals jQuery, SE, window */
(function (SE, $) {
    String.prototype.trim = function () {
        return this.replace(/(^\s*)|(\s*$)/g, "");
    };
    Array.prototype.clone = function () {
        var a = [];
        for (var i = 0, l = this.length; i < l; i++)
            a.push(this[i]);
        return a;
    }

    $.fn.Validator = function(opts){
        var settings = {
        };
        var o = $.extend(settings, opts);
        return $(this).each(function(){
            var d = $(this);
            d.focusin(function(e){
                if(e.target.type == 'text' && e.target.attributes["data-tip"] != undefined){
                    var wrapper = $(e.target).parents('.text-wrap'),
                        cxt = $(e.target).attr('data-tip');
                    wrapper.children('.msgbox').removeClass('pass').removeClass('err').addClass('tip').children('span').text(cxt);
                }
            }).focusout(function(e){
                if(e.target.type == 'text' && e.target.attributes["data-tip"] != undefined){
                    var wrapper = $(e.target).parents('.text-wrap'),
                        cxt = $(e.target).attr('data-error').length ? $(e.target).attr('data-error') : $(e.target).attr('data-tip');
                    wrapper.children('.msgbox').removeClass('tip');
                    if(e.target.value.trim().length)
                        wrapper.children('.msgbox').children('span').text('');
                    else
                        wrapper.children('.msgbox').removeClass('tip').removeClass('pass').addClass('err').children('span').text(cxt);
                }
            }).click(function(e){
                if(e.target.type == 'submit'){
                    e.preventDefault();
                    var prevent = true;
                    $("[data-tip]").each(function(){
                        if(!$(this).val().trim().length){
                            prevent = false;
                            var wrapper = $(this).parents('.text-wrap'),
                                cxt = $(this).attr('data-error').length ? $(this).attr('data-error') : $(this).attr('data-tip');
                            wrapper.children('.msgbox').removeClass('tip').removeClass('pass').addClass('err').children('span').text(cxt);
                        }
                    });
                    if(prevent)
                        d.submit();
                }
            });
        });
    }


    $(function ($) {
        SE.common = {
            init: function () {
                //put code here when page is onload.
                if ($('body').length > 0) SE.common.global();
            },
            global: function () {
                if($.browser.msie){
                    switch(parseInt($.browser.version)){
                        case 6: $("body").addClass('ie6');break;
                        case 7: $("body").addClass('ie7');break;
                        case 8: $("body").addClass('ie8');break;
                        default:break;
                    }

                    if(parseInt($.browser.version) == 6){
                        if($(".wlist .item a").length){
                            var item = $(".wlist .item a");
                            item.hover(function(){
                                $(this).find('span').css('display', 'block')
                            }, function(){
                                $(this).find('span').css('display', 'none')
                            })

                        }
                    }
                }

                if($(".enable-shop form").length){
                    var df = $(".enable-shop form");
                    df.Validator();
                }


            }
        };


        $(document).ready(SE.common.init());

    });
} (window.SE || {}, jQuery));


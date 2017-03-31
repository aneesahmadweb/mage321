/*
 * Copyright © 2016 PlazaThemes.com. All rights reserved.
 *
 * @author PlazaThemes Team <contact@plazathemes.com>
 */

//<![CDATA[
var pt_ajax_layer = {
    'ajaxFilter' : function (url) {
        require(['jquery'], function($){
            var data = "layer_action=1";
            $.ajax({
                url         : url,
                method      : "post",
                dataType    : 'json',
                data        : data,
                beforeSend  : function() {
                    pt_ajax_layer.showBackGround();
                },
                success     : function(data) {
                    console.log(data);
                    if (data.productlist) {
                        $('.category-products').html(data.productlist).trigger('contentUpdated');
                    }

                    if (data.leftLayer) {
                        $('.sidebar-main1').html(data.leftLayer).trigger('contentUpdated');
                    }

                    if(url.search('price') == -1) {
                        $('#price-from').val("0");
                        $('#price-to').val($('#current-max-price').val());
                    }
                    $("#filter-url").val(url);
                },
                complete    : function() {
                    pt_ajax_layer.hideBackGround();
                }
            });
        });
    },

    'showBackGround' : function() {
        require(['jquery'], function($){
            $('.loading-image').show();
            $('.loading-background').show();
        });
    },

    'hideBackGround' : function() {
        require(['jquery'], function($){
            $('.loading-image').hide();
            $('.loading-background').hide();
        });
    }
};
//]]>
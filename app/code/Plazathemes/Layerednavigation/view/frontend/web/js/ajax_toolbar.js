/*
 * Copyright © 2016 PlazaThemes.com. All rights reserved.
 *
 * @author PlazaThemes Team <contact@plazathemes.com>
 */

//<![CDATA[
require(['jquery'], function($){
    var paramString = '',
        paramValue = '',
        element,
        filter_url = '',
        filterType = '',
        filterParam = '',
        finalUrl = '';
    $(document).ready(function () {
        $('[data-filter]').each(function () {
            filter_url = $("#filter-url").val();
            var urlPaths = filter_url.split('?'),
                baseUrl = urlPaths[0],
                urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
                paramData = {},
                parameters;

            for (var i = 0; i < urlParams.length; i++) {
                parameters = urlParams[i].split('=');
                paramData[parameters[0]] = parameters[1] !== undefined
                    ? window.decodeURIComponent(parameters[1].replace(/\+/g, '%20'))
                    : '';
            }

            element = $(this);
            filterType = element.data("filter");
            filterParam = "product_list_" + filterType;

            if(element.is("select")) {

                element.on('change', function() {
                    element = $(this);
                    filterType = element.data("filter");
                    filterParam = "product_list_" + filterType;
                    paramValue = element.val();
                    paramData[filterParam] = paramValue;
                    paramString = $.param(paramData);

                    finalUrl = baseUrl + (paramString.length ? '?' + paramString : '');

                    pt_ajax_layer.ajaxFilter(finalUrl);
                });
            } else {
                paramValue = element.data('value');
                paramData[filterParam] = paramValue;
                paramString = $.param(paramData);
                finalUrl = baseUrl + (paramString.length ? '?' + paramString : '');

                element.attr('onclick', "pt_ajax_layer.ajaxFilter('" + finalUrl + "')");
            }

            delete paramData[filterParam];
        });
    });

    $(document).ajaxComplete(function () {
        $('[data-filter]').each(function () {
            filter_url = $("#filter-url").val();
            var urlPaths = filter_url.split('?'),
                baseUrl = urlPaths[0],
                urlParams = urlPaths[1] ? urlPaths[1].split('&') : [],
                paramData = {},
                parameters;

            for (var i = 0; i < urlParams.length; i++) {
                parameters = urlParams[i].split('=');
                paramData[parameters[0]] = parameters[1] !== undefined
                    ? window.decodeURIComponent(parameters[1].replace(/\+/g, '%20'))
                    : '';
            }

            element = $(this);
            filterType = element.data("filter");
            filterParam = "product_list_" + filterType;

            if(element.is("select")) {
                element.on('change', function() {
                    element = $(this);
                    filterType = element.data("filter");
                    filterParam = "product_list_" + filterType;
                    paramValue = element.val();
                    paramData[filterParam] = paramValue;

                    paramString = $.param(paramData);

                    finalUrl = baseUrl + (paramString.length ? '?' + paramString : '');

                    pt_ajax_layer.ajaxFilter(finalUrl);
                });
            } else {
                paramValue = element.data('value');

                paramData[filterParam] = paramValue;
                paramString = $.param(paramData);

                finalUrl = baseUrl + (paramString.length ? '?' + paramString : '');

                element.attr('onclick', "pt_ajax_layer.ajaxFilter('" + finalUrl + "')");
            }
            delete paramData[filterParam];
        });
    });
});
//]]>
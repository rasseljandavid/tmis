{*
 * Copyright (c) 2004-2013 OIC Group, Inc.
 *
 * This file is part of Tienda
 *
 * Tienda is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 *}

<div class="module ecommerce ecom-search yui3-skin-sam yui-skin-sam">
    <div id="search-autocomplete" class="control" style="z-index: 999;">

        {*<input id="ac-input" type="text" class="text">*}
        {control name="ac-input" type="search" class="text autosearch-txt" prepend="search"}
    </div>
</div>

{script unique="ecom-autocomplete" yui3mods=1}
{literal}
YUI(EXPONENT.YUI3_CONFIG).use("datasource-io","datasource-jsonschema","autocomplete", "autocomplete-highlighters", "datasource-get", function (Y) {
    
    var formatResults = function (query, results) {
        return Y.Array.map(results, function (result) {
            var result = result.raw;

            var template = (result.fileid != '') ? '<img width="30" height="30" class="srch-img" src="'+EXPONENT.PATH_RELATIVE+'thumb.php?id='+result.fileid+'&w=30&h=30&zc=1" />' : '';
     
            template += ' <span class="title">'+result.title+'</span>';

            return template;
        });
     }
    
    var autocomplete = Y.one('#ac-input');
    
    autocomplete.plug(Y.Plugin.AutoComplete, {
        width:"250px",
        maxResults: 10,
		minQueryLength: 3,
        resultListLocator: 'data',
        resultTextLocator: 'title', // the field to place in the input after selection
        resultFormatter: formatResults,
        source: EXPONENT.PATH_RELATIVE+'index.php?controller=store&action=search&json=1&ajax_action=1',
        requestTemplate: '&query={query}'
    });
    
    autocomplete.ac.on('select', function (e) {
        window.location = EXPONENT.PATH_RELATIVE+"product/"+e.result.raw.sef_url;
        return e.result.raw.title;
    });
});

{/literal}
{/script}

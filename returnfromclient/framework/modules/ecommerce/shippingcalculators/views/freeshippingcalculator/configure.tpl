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

<div id="freeshippingcfg">
    <div id="freeship-tabs" class="yui-navset exp-skin-tabview hide">
        <ul class="yui-nav">
	        <li class="selected"><a href="#tab1"><em>{'Free Shipping Settings'|gettext}</em></a></li>
        </ul>            
        <div class="yui-content">
            <div id="tab1">
                {control type="text" name="free_shipping_method_default_name" label="Default Name for this Shipping Method"|gettext value=$calculator->configdata.free_shipping_method_default_name}
                {control type="text" name="free_shipping_option_default_name" label="Default Name for the Selectable Shipping Option"|gettext value=$calculator->configdata.free_shipping_option_default_name}
            </div>        
        </div>
    </div>
	<div class="loadingdiv">{'Loading'|gettext}</div>
</div>

{script unique="editform" yui3mods=1}
{literal}
    EXPONENT.YUI3_CONFIG.modules.exptabs = {
        fullpath: EXPONENT.JS_RELATIVE+'exp-tabs.js',
        requires: ['history','tabview','event-custom']
    };

	YUI(EXPONENT.YUI3_CONFIG).use('exptabs', function(Y) {
        Y.expTabs({srcNode: '#freeship-tabs'});
		Y.one('#freeship-tabs').removeClass('hide');
		Y.one('.loadingdiv').remove();
    });
{/literal}
{/script}

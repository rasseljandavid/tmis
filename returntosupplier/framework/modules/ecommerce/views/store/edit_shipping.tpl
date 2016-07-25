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

{control type="hidden" name="tab_loaded[shipping]" value=1}
{control type="text" name="shipping[weight]" label="Item Weight"|gettext size=4 filter=decimal value=$record->weight}
{control type="text" name="shipping[width]" label="Width (in inches)"|gettext size=4 filter=decimal value=$record->width}
{control type="text" name="shipping[height]" label="Height (in inches)"|gettext size=4 filter=decimal value=$record->height}

{*FIXME convert to yui3*}
{script unique="prodedit" yui3mods=1}
{literal}
YUI(EXPONENT.YUI3_CONFIG).use('yui2-yahoo-dom-event', function(Y) {
    switchMethods = function() {
        var dd = YAHOO.util.Dom.get('required_shipping_calculator_id');
        var methdd = YAHOO.util.Dom.get('dd-'+dd.value);

        var otherdds = YAHOO.util.Dom.getElementsByClassName('methods', 'div');
        
        for(i=0; i<otherdds.length; i++) {
            if (otherdds[i].id == 'dd-'+dd.value) {
                YAHOO.util.Dom.setStyle(otherdds[i].id, 'display', 'block');
            } else {
                YAHOO.util.Dom.setStyle(otherdds[i].id, 'display', 'none');
            }
            
        }
        YAHOO.util.Dom.setStyle(methdd, 'display', 'block');
        //Y.log(methdd);
        //Y.log(dd.value);
    }
    YAHOO.util.Event.onDOMReady(switchMethods);
});

{/literal}
{/script}

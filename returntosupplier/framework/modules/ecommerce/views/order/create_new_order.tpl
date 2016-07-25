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

{css unique="invoice" link="`$smarty.const.PATH_RELATIVE`framework/modules/ecommerce/assets/css/invoice.css"}

{/css}
    
<div>
    <h1>{'Create A New Order'|gettext}</h1>
    <div id="create_new_order">
        {form id=order_item_form name=order_item_form action=save_new_order}
            {'Select the order type, order status, and customer.'|gettext}{br}{br}
            {control type="dropdown" name="order_type_id" label="Order Type:"|gettext frommodel='order_type'}
            {control type="dropdown" name="order_status_id" label="Order Status:"|gettext frommodel='order_status' orderby='rank'}
            {control type="hidden" id="addresses_id" name="addresses_id"}
            {br}
            <input type="radio" id="customer_type1" name="customer_type" value="1" checked=""> {'New Customer'|gettext}{br}
            <input type="radio" id="customer_type2" name="customer_type"  value="2"> {'Existing Customer'|gettext}{br}
            {capture assign="callbacks"}
                {literal}

                // the text box for the title
                var tagInput = Y.one('#search_internal');
                var theAddressesId = Y.one('#addresses_id');
                var existingRadio = Y.one('#customer_type2');

                var onRequestData = function( oSelf , sQuery , oRequest) {
                    existingRadio.set('checked',true);
                    tagInput.setStyles({'border':'1px solid green','background':'#fff url('+EXPONENT.PATH_RELATIVE+'framework/core/forms/controls/assets/autocomplete/loader.gif) no-repeat 100% 50%'});
                }

                var onRGetDataBack = function( oSelf , sQuery , oRequest) {
                    tagInput.setStyles({'border':'1px solid #000','backgroundImage':'none'});
                }

                var setProduct = function(e,args) {
                    theAddressesId.set('value',args[2].id);
                    tagInput.set('value',args[2].firstname + ' ' + args[2].middlename + ' ' + args[2].lastname + ' - ' + args[2].email);
                    return true;
                }

                // makes formatResult work mo betta
                oAC.resultTypeList = false;

                oAC.maxResultsDisplayed  = 12;

                // when we start typing...?
                oAC.dataRequestEvent.subscribe(onRequestData);
                oAC.dataReturnEvent.subscribe(onRGetDataBack);

                // format the results coming back in from the query
                oAC.formatResult = function(oResultData, sQuery, sResultMatch) {
                    return oResultData.firstname + ' ' + oResultData.middlename + ' ' + oResultData.lastname + ' - ' + oResultData.email;
                }

                // what should happen when the user selects an item?
                oAC.itemSelectEvent.subscribe(setProduct);

                {/literal}
            {/capture}
            {control type="autocomplete" controller="order" action="search" name="search_internal" value="Search customer name or email" schema="id,firstname,middlename,lastname,organization,email" searchmodel="addresses" searchoncol="firstname,lastnamename,organization,email" jsinject=$callbacks}

             <div id="submit_order_item_formControl" class="control buttongroup">
                <input id="submit_order_item_form" class="submit button awesome {$smarty.const.BTN_SIZE} orange" type="submit" value="Save New Order" />
                <input class="cancel button awesome {$smarty.const.BTN_SIZE} orange" type="button" value="Cancel" onclick="history.back(1);" />
            </div>
        {/form}
    </div>
</div>
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

{if $printerfriendly==1}
    {$pf=1}
    {*{if $include_css == true}*}
        {*{css unique="invoice" link="`$smarty.const.PATH_RELATIVE`framework/modules/ecommerce/assets/css/print-invoice.css"}*}
        {*{/css}*}
    {*{/if}*}
    <style type="text/css">
        {$css}
    </style>
{else}
    {css unique="invoice" link="`$smarty.const.PATH_RELATIVE`framework/modules/ecommerce/assets/css/invoice.css"}
    {/css}
{/if}

<div id="invoice">
    <div id="store-header">
        {*<h1>{$storeConfig.storename} {'Packing List'|gettext}</h1>*}
        {*{$storeConfig.ecomheader}*}
        <h1>{ecomconfig var=storename} {'Packing List'|gettext}</h1>
        {ecomconfig var=ecomheader}
    </div>
    {*{if $pf && $storeConfig.enable_barcode}*}
    {if $pf && ecomconfig::getConfig('enable_barcode')}
    <div id="barcode">
        <img style="margin:10px" src="{$smarty.const.PATH_RELATIVE}external/barcode.php?barcode={$order->invoice_id}&amp;width=400&amp;height=50" alt="">
    </div>
    {/if}
    <div id="invoice-data">
        <table class="order-info" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>
                        {"Source Site"|gettext}
                    </th>
                    <th>
                        {"Order #"|gettext}
                    </th>
                    <th>
                        {"Order Date"|gettext}
                    </th>
                    <th>
                        {"Order Type"|gettext}
                    </th>
                    <th>
                        {"Date Shipped"|gettext}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {ecomconfig var=storename}
                    </td>
                    <td>
                        {$order->invoice_id}
                        {permissions}
                            <div class="item-permissions">
                                {if $permissions.edit_invoice_id == 1 && !$pf}
                                    {br}
                                    <a class="edit" href="{link action=edit_invoice_id id=$order->id}" title="{'Edit Invoice Number'|gettext}">{'Edit'|gettext}</a>
                                {/if}
                            </div>
                         {/permissions}
                    </td>
                    <td>
                        {$order->purchased|format_date:"%A, %B %e, %Y"}
                    </td>
                    <td>
                        {$order->order_type->title}
                    </td>
                    <td>
                        {if $order->shipped}
                            {if $order->shipped}
                                {*{if $order->shipped == -1}*}
                                {if !$order->shipping_required}
                                    {'No Shipping Required'|gettext}
                                {else}
                                    {$order->shipped|format_date:"%A, %B %e, %Y":"Not Shipped Yet"}
                                {/if}
                            {else}
                                {"Not Shipped Yet"|gettext}
                            {/if}
                        {/if}
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="payment-info" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="billing-header" style="width:27%;">
                        {"Bill To"|gettext}
                    </th>
                    <th class="shipping-header" style="width:27%;">
                        {"Ship To"|gettext}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:27%;">
                        {$order->billingmethod[0]->addresses_id|address}
                        {permissions}
                            <div class="item-permissions">
                                {if $permissions.edit_address == 1 && !$pf}
                                {br}
                                <a class="edit" href="{link action=edit_address id=$order->id type='b'}" title="{'Edit Billing Address'|gettext}">{'Edit'|gettext}</a>
                                {/if}
                            </div>
                        {/permissions}
                    </td>
                    <td style="width:27%;">
                        {$shipping->shippingmethod->addresses_id|address}
                        {permissions}
                            <div class="item-permissions">
                                {if $permissions.edit_address == 1 && !$pf}
                                    {br}
                                    <a class="edit" href="{link action=edit_address id=$order->id type='s'}" title="{'Edit Shipping Address'|gettext}">{'Edit'|gettext}</a>
                                    {br}
                                {/if}
                            </div>
                        {/permissions}
                        {br}
                        <table style="width: 100%; border: 0px; text-align: left; padding: 0px; margin:0px;">
                            <tr style="border: 0px; padding: 0px; margin:0px;">
                                <td style="border: 0px; text-align: left; padding: 0px; margin:0px;">
                                    <strong>{"Shipping Method"|gettext}:</strong>{br}
                                    {$shipping->shippingmethod->option_title}
                                    {permissions}
                                        <div class="item-permissions">
                                            {if $permissions.edit_shipping_method == 1 && !$pf}
                                                {br}
                                                <a class="edit" href="{link action=edit_shipping_method id=$order->id}" title="{'Edit Shipping Method'|gettext}">{'Edit'|gettext}</a>
                                            {/if}
                                        </div>
                                    {/permissions}
                                </td>
                                <td style="border: 0px; text-align: left; padding: 0px; padding-right: 5px; margin:0px;">
                                    {if $shipping->shippingmethod->carrier != ''}
                                    <strong>{"Carrier"|gettext}:</strong>{br}
                                    {$shipping->shippingmethod->carrier}
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        {$sm=$order->orderitem[0]->shippingmethod}
        {if $sm->to != "" || $sm->from != "" || $sm->message != ""}
        <table class="gift-message" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>
                    {"Gift Message"|gettext}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{'To:'|gettext} </strong>{$sm->to}{br}
                        <strong>{'From'|gettext}: </strong>{$sm->from}{br}
                        <strong>{'Message'|gettext}: </strong>{$sm->message}{br}
                    </td>
                </tr>
            </tbody>
        </table>
        {/if}

        <table class="order-items" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>
                        {"QTY"|gettext}
                    </th>
                    <th>
                        {"SKU"|gettext}
                    </th>
                    <th>
                        {"Description"|gettext}
                    </th>
                    <th>
                        {"Location"|gettext}
                    </th>
                    <th>
                        {"Status"|gettext}
                    </th>
                    {permissions}
                        <div class="item-permissions">
                            {if $permissions.edit_order_item == 1 && !$pf}
                                <th style="text-align:right;"></th>
                            {/if}
                        </div>
                   {/permissions}
                </tr>
            </thead>
            <tbody>
            {foreach from=$order->orderitem item=oi}
                <tr class="{cycle values="odd, even"}">
                    <td>
                        {$oi->quantity}
                    </td>
                    <td>
						{if $oi->products_model != ""}{$oi->products_model}{else}N/A{/if}
                    </td>
                    <td>
                        {$oi->getProductsName()}
                        {if $oi->opts[0]}
                            {br}
                            {foreach from=$oi->opts item=options}
                                {$oi->getOption($options)}{br}
                            {/foreach}
                        {/if}
                        {$oi->getUserInputFields('br')}
						{*{if $oi->product_type == "product" || $oi->product_type == "childProduct"}*}
							{$oi->getExtraData()}
						{*{else}*}
							{*{$oi->getFormattedExtraData('list')}*}
						{*{/if}*}
                    </td>
                    <td>
                        {$oi->products_warehouse_location}
                    </td>
                    <td>
                        {$oi->products_status}
                    </td>
                    {permissions}
                        <div class="item-permissions">
                            {if $permissions.edit_order_item == 1 && !$pf}
                                <td style="text-align:right;"><a class="edit" href="{link action=edit_order_item id=$oi->id orderid=$order->id}" title="{'Edit Invoice Item'|gettext}">{'Edit'|gettext}</a>&#160;<a class="delete" href="{link action=delete_order_item id=$oi->id orderid=$order->id}" onclick="return confirm('Are you sure you want to delete this item from this order?')" title="{'Delete Invoice Item'|gettext}">{'Delete'|gettext}</a></td>
                            {/if}
                        </div>
                    {/permissions}
                </tr>
            {/foreach}
             {permissions}
                <div class="item-permissions">
                {if $permissions.add_order_item == 1 && !$pf}
                    <tr>
                        {*<td colspan="8" style='text-align: right;'><!--a href="{link action=add_order_item id=$order->id}">[+]</a-->*}
                        <td colspan="8"><!--a href="{link action=add_order_item id=$order->id}">[+]</a-->
                        {capture assign="callbacks"}
                        {literal}

                        // the text box for the title
                        var tagInput = Y.one('#add_new_item');

                        // the UL to append to
                        var tagUL = Y.one('#new_items');

                        // the Add Link
                        var tagAddToList = Y.one('#addToRelProdList');


                        var onRequestData = function( oSelf , sQuery , oRequest) {
                            tagInput.setStyles({'border':'1px solid green','background':'#fff url('+EXPONENT.PATH_RELATIVE+'framework/core/subsystems/forms/controls/assets/autocomplete/loader.gif) no-repeat 100% 50%'});
                        }

                        var onRGetDataBack = function( oSelf , sQuery , oRequest) {
                            tagInput.setStyles({'border':'1px solid #000','backgroundImage':'none'});
                        }

                        var appendToList = function(e,args) {
                            tagUL.appendChild(createHTML(args[2]));
                            return true;
                        }

                        var removeLI = function(e) {
                            var t = e.target;
                            if (t.test('a')) tagUL.removeChild(t.get('parentNode'));
                        }

                        var createHTML = function(val) {
                            var f = '<form id=addItem method=post>';
                                f += '<input type=hidden name=orderid id=orderid value={/literal}{$order->id}{literal}>';
                                f += '<input type=hidden name=module id=module value=order>';
                                f += '<input type=hidden name=action id=action value=add_order_item>';
                                f += '<input type=hidden name=product_id id=product_id value=' + val.id + '>';
                                f += '<input type=submit name=submit value="Add This Item">';
                                f += '</form>';
                            var newLI = Y.Node.create(f);
                            return newLI;
                        }

                        //tagAddToList.on('click',appendToList);
                        tagUL.on('click',removeLI);

                        // makes formatResult work mo betta
                        oAC.resultTypeList = false;

                        //AC.useShadow = true;
                        //oAC.autoHighlight  = true;
                        //oAC.typeAhead = true;

                        oAC.maxResultsDisplayed   = 30;

                        // when we start typing...?
                        oAC.dataRequestEvent.subscribe(onRequestData);
                        oAC.dataReturnEvent.subscribe(onRGetDataBack);

                        // format the results coming back in from the query
                        oAC.formatResult = function(oResultData, sQuery, sResultMatch) {
                            return '(' + oResultData.model + ') ' + oResultData.title;
                        }

                        // what should happen when the user selects an item?
                        oAC.itemSelectEvent.subscribe(appendToList);

                        {/literal}
                        {/capture}
                        {control type="autocomplete" controller="store" action="search" name="add_new_item" label="Add a new item"|gettext value="Search title or SKU to add an item" schema="title,id,sef_url,expFile,model" searchmodel="product" searchoncol="title,model" jsinject=$callbacks}
                        <div id="new_items">
                        </div>
                        </td>
                    </tr>
                {/if}
                </div>
             {/permissions}
            </tbody>
        </table>
    </div>
    <div id="store-footer">
        {ecomconfig var=ecomfooter}
    </div>
</div>

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
 
{css unique="cart" link="`$asset_path`css/cart.css" corecss="tables,panels,button"}

{/css}

<div id="myCart" class="module cart show hide">
	<h2>Your Secure Shopping Cart</h2>
    <div style="padding:8px; 0">
        <a class="awesome {$smarty.const.BTN_SIZE}  red" href="{backlink}">Continue Shopping</a>
        {if $items|@count gt 0}
            <a class="awesome {$smarty.const.BTN_SIZE}  red" style="margin-left: 18px;" href="{securelink controller=cart action=checkout}">Checkout Now</a>
            <a class="awesome small red" style="float:right; margin-left: 18px;" href="{link action=empty_cart}"  onclick="return confirm('Are you sure you want to empty all items from your shopping cart?');">Empty Cart</a>
        {/if}
    </div>
	<div id="cartbox">        
		<div id="cart-top" width="100%" cellpadding="0" cellspacing="0">
			<div class="cart-total-label">
                {if $order->total_discounts > 0} 
			        <span class="total-label">{"Cart Items Total With Discounts"|gettext}:</span>
                {else}
                    <span class="total-label">{"Cart Items Total"|gettext}:</span>
                {/if}
                <span id="cart-total" class="carttotal">{$order->total|currency}</span>
			</div>
		</div>
        
		{include file="show_cart_only.tpl"}
        
        {if $items|@count gt 0}
            <table width="100%" id="cart-totals" class="table">
                <thead>
                    <tr>
                        <th colspan=3 align="left">
                            {"Totals"|gettext}
                        </th>
                   </tr>
                </thead>
                <tbody>
                    <tr class="{cycle values="odd, even"}">
                        <td class="cart-totals-title">
                            {"Subtotal"|gettext}:
                        </td>
                        <td>
                            {currency_symbol}
                        </td>
                        <td style="text-align:right;">{$order->subtotal|number_format:2}
                        </td>
                    </tr>
                     {if isset($discounts[0])}                        
                        {if $discounts[0]->isCartDiscount()} 
                             <tr class="{cycle values="odd, even"}">
                                <td class="cart-totals-title">
                                    <a style="font-weight: normal;" href="{link action=removeDiscountFromCart id=$discounts[0]->id}"  alt="Remove discount from cart.">[remove coupon code]</a>&#160;(<span style="background-color:#33CC00;">{$discounts[0]->coupon_code}</span>)&#160;{"Total Discounts"|gettext}:
                                </td>
                                <td>
                                    {currency_symbol}
                                </td>
                                <td style="text-align:right;">-{$order->total_discounts|number_format:2}
                                </td>
                            </tr>
                            <tr class="{cycle values="odd, even"}">
                                <td class="cart-totals-title">
                                    {"Cart Total"|gettext}:
                                </td>
                                <td>
                                    {currency_symbol}
                                </td>
                                <td style="text-align:right;">{$order->total|number_format:2}
                                </td>
                            </tr>   
                        {/if}
                      {/if}     
                      <tr class="{cycle values="odd, even"}">
                        <td width="90%" class="cart-totals-title">
                            {"Tax"|gettext}
                        </td>
                        <td>
                            {currency_symbol}
                        </td>
                        <td style="text-align:center;">-
                        </td>
                    </tr>   
                    <tr class="{cycle values="odd, even"}">
                        <td class="cart-totals-title">
                            {if isset($discounts[0])}
                                {if $discounts[0]->isShippingDiscount()}
                                    <a style="font-weight: normal;" href="{link action=removeDiscountFromCart id=$discounts[0]->id}"  alt="Remove discount from cart.">[{'remove coupon code'|gettext}]</a>&#160;(<span style="background-color:#33CC00;">{$discounts[0]->coupon_code}</span>)&#160;
                                {/if}
                            {/if}
                            {* else *}
                            {"Shipping & Handling"|gettext}:
                            {* /if *}
                        </td>
                        <td>
                            {currency_symbol}
                        </td>
                        {if is_string($order->shipping_total)}
                            <td style="text-align:center;">
                                {$order->shipping_total}
                        {else}
                            <td style="text-align:right;">
                                {$order->shipping_total|number_format:2}
                        {/if}
                        </td>
                    </tr>
                    {if $order->surcharge_total != 0}
                        <tr class="{cycle values="odd, even"}">
                            <td class="cart-totals-title">
                                {"Freight Surcharge"|gettext}
                            </td>
                            <td>
                                {currency_symbol}
                            </td>
                            <td style="text-align:right;">{$order->surcharge_total|number_format:2}
                            </td>
                        </tr>
                    {/if}
                    <tr class="{cycle values="odd, even"}">
                        <td class="cart-totals-title">
                            {"Order Total"|gettext}:
                        </td>
                        <td>
                            {currency_symbol}
                        </td>
                        <td style="text-align:right;">{$order->grand_total|number_format:2}
                        </td>
                    </tr>
                    {if !isset($noactivediscounts)}                                                
                        <tr class="{cycle values="odd, even"}">
                            <td colspan="3">
                                <div class="input-code">
                                    {form action="addDiscountToCart"}
                                        {control type="text" name="coupon_code" label="Enter a Discount Code"|gettext}
                                        {control type="buttongroup" submit="Apply Code"|gettext}
                                    {/form}
                                </div>
                                {clear}
                            </td>
                        </tr>
                   {/if}
                </tbody>
            </table>       
        {/if}
	</div>
    <div style="padding:8px; 0">
        <a class="awesome {$smarty.const.BTN_SIZE} red" href="{backlink}">{"Continue Shopping"|gettext}</a>
        {if $items|@count gt 0}
            <a class="awesome {$smarty.const.BTN_SIZE} red" style="margin-left: 18px;" href="{securelink controller=cart action=checkout}">{"Checkout Now"|gettext}</a>
        {/if}
    </div>
</div>
<div class="loadingdiv">{"Loading Cart"|gettext}</div>

{script unique="editform" yui3mods=1}
{literal}
	YUI(EXPONENT.YUI3_CONFIG).use('node', function(Y) {
		Y.one('#myCart').removeClass('hide');
		Y.one('.loadingdiv').remove();
    });
{/literal}
{/script}

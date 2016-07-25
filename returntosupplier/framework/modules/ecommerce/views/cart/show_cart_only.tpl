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

{css unique="showcartonly" corecss="tables"}

{/css}

{if $items|@count > 0}
    <table id="cart" width="100%" class="table table-striped table-hover">
        <thead>
            <tr>
                <th>{'Item'|gettext}</th>
                <th>{'Price'|gettext}</th>
				    <th>{'Qty'|gettext}</th>
                <th>{'Amount'|gettext}</th>
            
                <th>{'Action'|gettext}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$items item=item}
                <tr>
                    <td class="prodrow">
                         {get_cart_summary item=$item}
                    </td>
                    <td class="prodrow price" id="price-{$item->id}">{$item->products_price|currency}</td>
					 <td class="prodrow quantity">

	                            {$item->quantity}

	                    </td>
                    <td class="prodrow price" id="amount-{$item->id}">{$item->getTotal()|currency}</td>
                   
                    <td class="prodrow">
	
                        <a class="icon-remove cart-remove" href="{link action=removeItem id=$item->id}" title="{'Remove'|gettext} {$item->product->title} {'from cart'|gettext}" onclick="return confirm('{'Are you sure you want to remove this item?'|gettext}');">
                        </a>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
{else}
    <div class="no-items">
        {'You currently have no items in your cart'|gettext}
    </div>
{/if}

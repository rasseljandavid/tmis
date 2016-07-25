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

<div class="columnize">
    <table class="table table-striped table_summary">
        <thead>
			<tr>
				<th colspan="4">You're purchasing</th>
			</tr>
     
        </thead>
        <tbody>
			       <tr>
		                <td>{'Item'|gettext}</td>
		                <td>{'Qty'|gettext}</td>
		                <td>{'Item Price'|gettext}</td>
		                <td>{'Total Price'|gettext}</td>
		            </tr>
            {foreach from=$items item=oi}
                <tr class={cycle values="even,odd"}>
                    <td>
                       	<a href='{link action="show" controller="store" title="`$oi->product->getSEFURL()`"}'>
                       		{$oi->products_name} {$oi->product->capacity}
					   	</a>
                    </td>
                 
                    <td>{$oi->quantity}</td>
                    <td>{$oi->products_price|currency}</td>
                    <td>{$oi->getTotal()|currency}</td>
                </tr>
            {/foreach}
            {if $show_totals == 1}
                <tr>
                    <td colspan="4" class="totals top-brdr">{'Subtotal'|gettext}</td>
                    <td class="top-brdr">{$order->subtotal|currency}</td>
                </tr>
                {if $order->total_discounts > 0}
                    <tr>
                        <td colspan="4" class="totals">{'Discounts'|gettext}</td>
                        <td align="right">-{$order->total_discounts|currency}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="totals">{'Total'|gettext}</td>
                        <td align="right">{$order->total|currency}</td>
                    </tr>
                {/if}
                <tr>
                    <td colspan="4" class="totals">
                        Tax:
                        {foreach from=$order->taxzones item=zone}
                            {br}{$zone->name} ({$zone->rate}%)
                        {foreachelse}
                            ({'Not Required'|gettext})
                        {/foreach}
                    </td>
                    <td>{$order->tax|currency}</td>
                </tr>
                <tr>
                    <td colspan="4" class="totals">{'Shipping'|gettext}</td>
                    <td>{$order->shipping_total|currency}</td>
                </tr>
                <tr>
                    <td colspan="4" class="totals">{'Order Total'|gettext}</td>
                    <td>{$order->grand_total|currency}</td>
                </tr>
                </tr>
            {/if}
        </tbody>
    </table>
</div>
 
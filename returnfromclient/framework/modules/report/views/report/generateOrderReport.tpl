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

{css unique="ecom-report1" link="`$smarty.const.PATH_RELATIVE`framework/modules/ecommerce/assets/css/ecom.css" corecss="button,tables"}

{/css} 
{css unique="ecom-report2" link="`$asset_path`css/generate-report.css"}

{/css}

<div class="module report generate-report">
    {$page->links}
    {form id="batch" controller=report}
<!--
        <div class="actions-to-apply">
            {control type="dropdown" name="action" label="Select Action"|gettext items=$action_items}
            {control type="checkbox" name="applytoall" label="Apply to all pages"|gettext class="applytoall" value=1}
            <button type="submit" class="awesome {$smarty.const.BTN_SIZE} red">{"Apply Batch Action"|gettext}</button>
        </div>
-->
     
		<table class="table">
			<thead>
				<tr>
					{$page->header_columns}
<th>Profit</th>
					<th>Profit</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$page->records item=item}
					<tr>	
						<td><input type="checkbox" name="act-upon[]" value="{$item->id}"></td>
					  	<td>{$item->id}</td>
						<td>{$item->purchased_date}</td>
						<td>{$item->bfirst}</td>
						<td>{$item->blast}</td>
						<td>{$item->status_title}</td>
						<td>{$item->grand_total}</td>
<td>{($item->total - $od->computeProfit($item->id))|currency}</td>
						<td>{$od->computeProfit($item->id)|currency}</td>
					</tr>
				{/foreach}
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
<td></td>
					<td>{$od->computeTotalOrder()|currency}</td>
					<td>{$od->computeCogs()|currency}</td>
					<td>{$od->computeTotalProfit()|currency}</td>
				</tr>
			</tfoot>
		</table>

    {/form}
	{$page->links}
</div>
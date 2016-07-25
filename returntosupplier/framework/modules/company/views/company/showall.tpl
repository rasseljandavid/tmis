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

{css unique="showallcompany" corecss="tables"}

{/css}

<div class="company showall">
	<h1>{$moduletitle|default:'Company Listings'|gettext}</h1>
	{icon class=add controller=$controller action=edit text="Add a new"|gettext|cat:" `$modelname`"}
    <table class="exp-skin-table">
    <thead>
        {$page->header_columns}
        <th></th>
        <th>{'Actions'|gettext}</th>
    </thead>
    <tbody>
        {foreach from=$page->records item=company name=companies}
		<tr class="{cycle values="odd,even"}">
            <td><a href="{link action="show" id=$company->id}">{$company->title}</a></td>
            <td>{$company->website}</td>
            <td width=64px>
                {if $company->expFile.logo[0]->id}
                    {img file_id=$company->expFile.logo[0]->id w=64 alt="Image of `$company->title`" title="`$company->title`" id="enlarged-image"}
                {/if}
            </td>
            <td>
                {permissions}
					<div class="item-actions">
						{if $permissions.edit == 1}
							{icon action=edit record=$company title="Edit"|gettext|cat:" `$company->title`"}
						{/if}
						{if $permissions.delete == 1}
							{icon action=delete record=$company title="Delete"|gettext|cat:" `$company->title`"}
						{/if}
					</div>
                {/permissions}
            </td>
        </tr>
        {/foreach}
    </tbody>
    </table>
</div>

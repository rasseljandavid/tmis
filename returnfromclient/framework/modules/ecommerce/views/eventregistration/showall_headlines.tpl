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

{css unique="event-listings" link="`$asset_path`css/storefront.css" corecss="common"}

{/css}

{css unique="event-listings1" link="`$asset_path`css/eventregistration.css"}

{/css}

<div class="module store upcoming-events">
    {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}<h2>{$moduletitle}</h2>{/if}
    {permissions}
    <div class="module-actions">
        {if $permissions.create == true || $permissions.edit == true}
            {icon class="add" controller=store action=edit product_type=eventregistration text="Add an event"|gettext}
        {/if}
        {if $permissions.manage == 1}
             {icon controller=eventregistration action=manage text="Manage Events"|gettext}
        {/if}
    </div>
    {/permissions}
    {if $config.moduledescription != ""}
   		{$config.moduledescription}
   	{/if}
    <ul>
        {foreach name=uce from=$page->records item=item}
            {if $smarty.foreach.uce.iteration<=$config.headcount || !$config.headcount}
                <li>
                    <a {if $item->eventdate < time()}class="date past" {/if}href="{link controller=eventregistration action=show title=$item->sef_url}" title="{$item->body|summarize:"html":"para"}">{$item->eventdate|format_date:"%A, %B %e, %Y"}</a>
                    {*<p>{$item->summary|truncate:75:"..."}</p>*}
                    {permissions}
                        <div class="item-actions">
                            {if $permissions.edit == true}
                                {icon controller="store" action=edit record=$item}
                                {icon controller="store" action=copyProduct class="copy" record=$item text="Copy" title="Copy `$item->title` "}
                            {/if}
                            {if $permissions.delete == true}
                                {icon controller="store" action=delete record=$item}
                            {/if}
                        </div>
                    {/permissions}
                    <p>
                        {$item->title}
                        {if $item->getBasePrice()}- {'Cost'|gettext}: {$item->getBasePrice()|currency}{/if}
                    </p>
                </li>
            {/if}
        {/foreach}
    </ul>
</div>

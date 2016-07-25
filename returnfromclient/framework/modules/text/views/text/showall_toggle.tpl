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

<div class="module text showall showall-toggle">
    {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}<h1>{$moduletitle}</h1>{/if}
    {permissions}
        <div class="module-actions">
            {if $permissions.create == 1}
                {icon class=add action=edit rank=1 text="Add text at the top"|gettext}
            {/if}
            {if $permissions.manage == 1}
                {ddrerank items=$items model="text" label="Text Items"|gettext}
            {/if}
        </div>
    {/permissions}
    {if $config.moduledescription != ""}
        {$config.moduledescription}
    {/if}
    {$myloc=serialize($__loc)}
    {foreach from=$items item=text name=items}
        {permissions}
            <div class="item-actions">
                {if $permissions.edit == 1}
                    {if $myloc != $text->location_data}
                        {if $permissions.manage == 1}
                            {icon action=merge id=$text->id title="Merge Aggregated Content"|gettext}
                        {else}
                            {icon img='arrow_merge.png' title="Merged Content"|gettext}
                        {/if}
                    {/if}
                    {icon action=edit record=$text}
                {/if}
                {if $permissions.delete == 1}
                    {icon action=delete record=$text}
                {/if}
            </div>
        {/permissions}
        {if $config.show_summary}
            {$summary = $text->body|summarize:"html":"parahtml"}
        {else}
            {$summary = ''}
        {/if}
        {*{toggle unique="text`$text->id`" title=$text->title|default:'Click to Hide/View'|gettext collapsed=$config.show_collapsed summary=$config.summary_height}*}
        {toggle unique="text`$text->id`" title=$text->title|default:'Click to Hide/View'|gettext collapsed=$config.show_collapsed summary=$summary}
            <div class="bodycopy">
                {if $config.ffloat != "Below"}
                    {filedisplayer view="`$config.filedisplay`" files=$text->expFile record=$text}
                {/if}
                {$text->body}
                {if $config.ffloat == "Below"}
                    {filedisplayer view="`$config.filedisplay`" files=$text->expFile record=$text}
                {/if}
            </div>
            {clear}
        {/toggle}
        {permissions}
			<div class="module-actions">
				{if $permissions.create == 1}
					{icon class=add action=edit rank=$text->rank+1 text="Add more text here"|gettext}
				{/if}
			</div>
        {/permissions}
    {/foreach}
</div>

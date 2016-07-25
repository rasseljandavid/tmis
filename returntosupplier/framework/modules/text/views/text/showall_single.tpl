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

<div class="module text single">
    {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}<h2>{$moduletitle}</h2>{/if}
    {$myloc=serialize($__loc)}
    {if $items[0]->title}<h2>{$items[0]->title}</h2>{/if}
    {permissions}
       <div class="item-actions">
            {if $permissions.edit == 1}
                {if $myloc != $items[0]->location_data}
                    {if $permissions.manage == 1}
                        {icon action=merge id=$items[0]->id title="Merge Aggregated Content"|gettext}
                    {else}
                        {icon img='arrow_merge.png' title="Merged Content"|gettext}
                    {/if}
                {/if}
                {icon action=edit record=$items[0]}
            {/if}
            {if $permissions.delete == 1}
                {icon action=delete record=$items[0]}
            {/if}
        </div>
    {/permissions}
    <div class="bodycopy">
        {if $config.ffloat != "Below"}
            {filedisplayer view="`$config.filedisplay`" files=$items[0]->expFile record=$items[0]}
        {/if}
        {$items[0]->body}
        {if $config.ffloat == "Below"}
            {filedisplayer view="`$config.filedisplay`" files=$items[0]->expFile record=$items[0]}
        {/if}
    </div>
    {clear}
</div>

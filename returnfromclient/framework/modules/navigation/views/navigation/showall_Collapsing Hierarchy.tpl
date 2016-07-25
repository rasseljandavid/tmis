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

{css unique="collapsing-hierarchy" link="`$asset_path`css/depth.css"}

{/css}

<div class="module navigation collapsing collapsing-hierarchy">
    {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}<h1>{$moduletitle}</h1>{/if}
    {if $config.moduledescription != ""}
        {$config.moduledescription}
    {/if}
    <ul>
        {foreach from=$sections item=section}
            {$inPath=0}
            {foreach from=$current->parents item=parentId}
                {if $parentId == $section->id}
                    {$inPath=1}
                {/if}
            {/foreach}
            {if $section->numParents == 0 || $inPath || $section->id == $current->id ||  $section->parent == $current->id}
                <li class="depth{$section->depth} {if $section->id == $current->id}current{/if}">
                    {if $section->active == 1}
                        <a href="{$section->link}" class="navlink"{if $section->new_window} target="_blank"{/if}>{$section->name}</a>&#160;
                    {else}
                        <span class="navlink">{$section->name}</span>&#160;
                    {/if}
                </li>
            {/if}
        {/foreach}
    </ul>
</div>

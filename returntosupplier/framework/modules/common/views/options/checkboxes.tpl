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

<div class="options checkboxes">
    <span class="label">{if $group->required == true}* {/if}{$group->title}</span>
    {$gid=$group->id}
    {foreach from=$options item=option key=id}
        {if is_array($selected.$gid) && in_array($id, $selected.$gid)}  
            {control type="checkbox" name="options[`$group->id`][]" label=$option value=$id checked=true}
        {else}
            {control type="checkbox" name="options[`$group->id`][]" label=$option value=$id}
        {/if}
    {/foreach}
</div>
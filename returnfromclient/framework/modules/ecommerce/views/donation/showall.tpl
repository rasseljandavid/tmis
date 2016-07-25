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

<div class="module donation showall">
    {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}<h1>{$moduletitle}</h1>{/if}
    {permissions}
        {if $permissions.edit == 1 or $permissions.manage == 1}
            <div id="prod-admin">
                {icon class="add" controller=store action=edit id=0 product_type=donation text="Add a new donation cause"|gettext}
            </div>
        {/if}
    {/permissions}
    {if $config.moduledescription != ""}
   		{$config.moduledescription}
   	{/if}
    {if $config.quickadd}
        {$quickadd = '1'}
    {/if}
    <table>
        {foreach from=$causes item=cause}
            <tr>
                <td style="padding: 5px;">{img file_id=$cause->expFile.mainimage[0]->id square=120}</td>
                <td style="padding: 5px;">
                    <h3>{$cause->title}</h3>
                    {permissions}
                        <div class="item-actions">
                            {if $permissions.edit == 1}
                                {icon controller=store action=edit record=$cause title="Edit Donation"|gettext}
                            {/if}
                            {if $permissions.delete == 1}
                                {icon controller=store action=delete record=$cause title="Remove Donation"|gettext}
                            {/if}
                        </div>
                    {/permissions}
                    {$cause->body}
                </td>
                <td style="padding: 5px;">
                    <a class="add-to-cart-btn awesome {$smarty.const.BTN_SIZE} orange" href={link controller=cart action=addItem product_type=$cause->product_type product_id=$cause->id quick=$quickadd}>{'Donate'|gettext} {if $config.quickadd}{$cause->base_price|currency}{/if}</a>
                </td>
             </tr>
        {foreachelse}
            {if $permissions.create == 1}
                <h2>{"No causes have been setup for donations."|gettext}</h2>
            {/if}
        {/foreach}
    </table>
</div>

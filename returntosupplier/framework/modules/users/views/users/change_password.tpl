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

<div class="module users change-password">
    <div class="info-header">
        <div class="related-actions">
            {help text="Get Help with"|gettext|cat:" "|cat:("Changing User Passwords"|gettext) module="change-my-password"}
        </div>
        <h1>{'Change'|gettext} {if $isuser}{'your'|gettext}{else}{$u->username}'s{/if} {'password'|gettext}</h1>
    </div>
    {if $isuser}
        <blockquote>{'To change your password enter your current password and then enter what you would like your new password to be'|gettext}.</blockquote>
    {/if}
    {form action=save_change_password}
        {control type="hidden" name="uid" value=$u->id}
        {if $isuser}
            {control type="password" name="password" label="Current Password"|gettext}
        {/if}
        {control type="password" name="new_password1" label="Enter your new password"|gettext}
        {control type="password" name="new_password2" label="Confirm your new password"|gettext}
        {control type="buttongroup" submit="Change My Password"|gettext cancel="Cancel"|gettext}
    {/form}
    
</div>

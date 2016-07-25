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

{css unique="edit_user" corecss="tables"}

{/css}

<div id='edituser' class="module users edit">
    {form action=update}

			{if $edit_user->id == ""}
				<h2>{'Create a New User Account'|gettext}</h2>
			{else}
				<h2>{'Edit User'|gettext} - '{$edit_user->username}'</h2> ( {'Date of last login'|gettext} {$edit_user->last_login|format_date})
			{/if}

	  
                    {if empty($edit_user->id)}
                        {if $smarty.const.USER_REGISTRATION_USE_EMAIL == 0}
                            {control type=text name=username label="Username"|gettext value=$edit_user->username required=1}
                        {else}
                            {*{control type=text name=email label="Email Address"|gettext value=$edit_user->email required=1}*}
                            {control type=email name=email label="Email Address"|gettext value=$edit_user->email required=1}
                        {/if}
                        {control type=password name=pass1 label="Password"|gettext required=1}
                        {control type=password name=pass2 label="Confirm Password"|gettext required=1}
                    {else}
                        {control type="hidden" name="id" value=$edit_user->id}
	                {/if}
                    {control type="hidden" name="userkey" value=$userkey}
	                {if $smarty.const.USER_REGISTRATION_USE_EMAIL == 0}
                        {*{control type=text name=email label="Email Address"|gettext value=$edit_user->email}*}
                        {control type=email name=email label="Email Address"|gettext value=$edit_user->email}
                    {/if}
	                {control type=text name=firstname label="First Name"|gettext value=$edit_user->firstname}
	                {control type=text name=lastname label="Last Name"|gettext value=$edit_user->lastname}
	                {*control type=checkbox name="recv_html" label="I prefer HTML Email" value=1 checked=$edit_user->recv_html*}
	                {if $user->isAdmin()}
                        {if $smarty.const.USE_LDAP}
                            {control type=checkbox name=is_ldap value=1 label="Use LDAP Authentication?"|gettext checked=$edit_user->is_ldap}
                        {/if}
                        {if $user->isSuperAdmin()} {* only super admins can create/change admins *}
                            {control type=checkbox name=is_acting_admin value=1 label="Make this user an Administrator?"|gettext checked=$edit_user->is_acting_admin}
                        {else}
                            {control type=checkbox readonly="readonly" name=is_acting_admin value=1 label="This user is an Administrator?"|gettext checked=$edit_user->is_acting_admin}
                            {if $edit_user->is_acting_admin}{control type=hidden name=is_acting_admin value=1}{/if}
                        {/if}
                        {if $user->isSuperAdmin()}
                            {if $user->is_system_user}  {* only the real super admin can create/change other super admins *}
                                {control type=checkbox name=is_admin value=1 label="Make this user a Super Administrator?"|gettext checked=$edit_user->is_admin}
                            {else}
                                {control type=checkbox readonly="readonly" name=is_admin value=1 label="This user is a Super Administrator?"|gettext checked=$edit_user->is_admin}
                                {if $edit_user->is_admin}{control type=hidden name=is_admin value=1}{/if}
                            {/if}
                        {/if}
                    {/if}
	            

	    {control type="buttongroup" submit="Submit"|gettext cancel="Cancel"|gettext}
	{/form}
</div>

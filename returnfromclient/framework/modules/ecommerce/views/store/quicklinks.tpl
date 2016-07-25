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
 
{css unique="store-quicklinks" link="`$asset_path`css/cart.css"}

{/css}

<div class="module store quick-links">

    {if $user->id != '' && $user->id != 0}
        <h4>{'Welcome'|gettext} {attribution user=$user display=first}!</h4>
	{else}
		<h4>Welcome!</h4>
    {/if}
    <ul class="nav">
        <li><a class="viewcart" href="{link controller=cart action=show}" rel="nofollow">{'My Cart'|gettext} ( {if $grand_total}P<span class="cart_total">{$grand_total|number_format:2}</span>{else}P<span class="cart_total">{'0.00'|gettext}{/if}</span> )</a></li>
        {if $grand_total > 0}
            <li>
                <a class="checkoutnow" href="{securelink controller=cart action=checkout}" rel="nofollow">{'Checkout Now'|gettext}</a>
            </li>
        {/if}
        {if $user->id != '' && $user->id != 0}
            <li><a class="profile" href="{link module=users action=viewuser}">{'View My Account'|gettext}</a></li>
            {if !($smarty.const.USER_NO_PASSWORD_CHANGE || $user->is_ldap || !$user->isAdmin())}
                <li><a class="password" href="{link controller=users action=change_password}">{'Change My Password'|gettext}</a></li>
            {/if}
            <li><a class="logout" href="{link controller=login action=logout}">{'Log Out'|gettext}</a></li>
        {else}
            <li><a class="login" href="{link controller=login action=loginredirect}" rel="nofollow">{'Login'|gettext}</a></li>
        {/if}
    </ul>
</div>

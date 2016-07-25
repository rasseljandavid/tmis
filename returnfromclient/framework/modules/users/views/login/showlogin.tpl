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

{css unique="showlogin" link="`$asset_path`css/login.css" corecss="button"}

{/css}

{messagequeue}
<div class="login default">
    {if $loggedin == false || $smarty.const.PREVIEW_READONLY == 1}
        <div{if $smarty.const.SITE_ALLOW_REGISTRATION || $smarty.const.ECOM} class="box login-form one"{/if}>
            {if $smarty.const.USER_REGISTRATION_USE_EMAIL || $smarty.const.ECOM}
                {$usertype="Customers"|gettext}
                {$label="Email Address"|gettext|cat:":"}
            {else}
                {$usertype="Users"|gettext}
                {$label="Username"|gettext|cat:":"}
            {/if}
            <h4>{"Existing"|gettext} {$usertype}</h4>
            <!--p>If you are an existing customer please log-in below to continue in the checkout process.</p-->
            {form action=login}
                {control type="text" name="username" label=$label size=25 required=1 prepend="user"}
                {control type="password" name="password" label="Password"|gettext|cat:":" size=25 required=1 prepend="key"}
                {control type="buttongroup" submit="Log In"|gettext}
            {/form}
            {br}<a href="{link controller=users action=reset_password}">{'Forgot Your Password?'|gettext}</a>
            {br}
        </div>
        {if $smarty.const.SITE_ALLOW_REGISTRATION || $smarty.const.ECOM}
            <div class="box new-user two">
                <h4>{"New"|gettext} {$usertype}</h4>
                <p>
                    {if $smarty.const.ECOM}
                        {if $oicount>0}
                            {"If you are a new customer, select this option to continue with the checkout process."|gettext}{br}{br}
                            {"We will gather billing and shipping information, and you will have the option to create an account so can track your order status."|gettext}{br}{br}
                            <a class="awesome {$smarty.const.BTN_SIZE} red"
                               href="{link module=cart action=customerSignup}">{"Continue Checking Out"|gettext}</a>
                        {else}
                            {"If you are a new customer, add an item to your cart to continue with the checkout process."|gettext}{br}{br}
                            <a class="awesome {$smarty.const.BTN_SIZE} red"
                               href="{backlink}">{"Keep Shopping"|gettext}</a>
                        {/if}
                    {else}
                        {"Create a new account here."|gettext}{br}{br}
                        <a class="awesome red {$smarty.const.BTN_SIZE}"
                           href="{link controller=users action=create}">{"Create an Account"|gettext}</a>
                    {/if}
                </p>
            </div>
        {/if}
    {else}
        {if !$smarty.const.ECOM}
            <div class=" logout">
                <a class="awesome red {$smarty.const.BTN_SIZE}"
                   href="{link action=logout}">{'Logout'|gettext}</a>
            </div>
        {/if}
    {/if}
</div>

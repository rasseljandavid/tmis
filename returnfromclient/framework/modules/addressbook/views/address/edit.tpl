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

{css unique="address-edit" link="`$asset_path`css/address.css"}

{/css}


{script unique="hidePasswordFields" yui3mods=1}
{literal}
YUI(EXPONENT.YUI3_CONFIG).use('node', function(Y) {
     // start coding
     var checkbox = Y.one('#remember_me'); //the checkbox
     if (checkbox){
         checkbox.on('click',function(e){
             var psswrd = Y.one("#passwordDiv .passwords");//div wrapping the password boxs
             psswrd.toggleClass('hide');
         });
     }
})
{/literal}
{/script}
<div class="module address edit address-form">
    {if $record->id != ""}
        <h2>{'Editing address for'|gettext} {$record->firstname} {$record->lastname}</h2>
    {else}
        <h2>{'New'|gettext} {$modelname}</h2>
    {/if}
    {form action=update}
        {control type=hidden name=id value=$record->id}
        {control type=hidden name=is_default value=$record->is_default}
        {control type=hidden name=is_shipping value=$record->is_shipping}
        {control type=hidden name=is_billing value=$record->is_billing}
        {control type=text name=firstname label="First Name"|gettext required=true value=$record->firstname}
        {control type=text name=middlename label="Middle Name"|gettext value=$record->middlename}
        {control type=text name=lastname label="Last Name"|gettext required=true value=$record->lastname}
        {control type=tel name="phone" label="Contact Number"|gettext required=true value=$record->phone}
      	{control type=email name="email" label="Email Address"|gettext required=true value=$record->email}
        {control type=text name=address1 label="Street Address"|gettext required=true value=$record->address1}
		{control type=text name=address2 label="Barangay"|gettext required=true value=$record->address2}
        {control type="dropdown" name="city" label="City/Municipality"|gettext required=true items="Angeles City,Mabalacat City,Bamban"|gettxtlist includeblank="-- Choose a City --"|gettext values="Angeles City,Mabalacat City,Bamban"|gettxtlist default=$record->city}
      	{control type=hidden name=country value=1}
        {if !$user->isLoggedIn()}
               <div id="passwordDiv">
	                {control type="checkbox" flip=1 id="remember_me" name="remember_me" label="Remember Me"|gettext|cat:"?" value=1 checked=true}
	                <p>
	                    {"If you would like us to remember you, simply supply a password here and you may login to this site anytime to track your orders and view your order history."|gettext}&#160;&#160;
	                    {'Otherwise uncheck \'Remember Me?\' and continue anonymously.'|gettext}
	                </p>
	                <div class="passwords">
	                    {control type="password" name="password" label="Password"|gettext}
	                    {control type="password" name="password2" label="Confirm Password"|gettext}
	                </div>
	            </div>
            
            <!--The following field is an anti-spam measure to prevent fraudulent account creation. -->
            {* control type="antispam" *}
        {/if}
        {control type=buttongroup submit="Save Address and Continue"|gettext cancel="Cancel"|gettext}
    {/form}
</div>

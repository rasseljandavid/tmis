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

<div class="module order address edit address-form">
    <h1>{'Editing address'|gettext}</h1>
    <blockquote>
        <em>{'Fields marked with an * are required.'|gettext}</em>
    </blockquote>
    {form action=save_address}
        {control type=hidden name=orderid value=$orderid}
        {control type=hidden name=addyid value=$record->id}
        {control type=hidden name=type value=$type}
        {control type=hidden name=same value=$same}
        {if $same==true}
            {'This address is the same for both shipping and billing on this order.'|gettext}&#160;&#160;
            {'If you update the existing address, it will update both the shipping and billing address for this order.'|gettext}&#160;&#160;
            {'If you save this as a new address, it will leave the existing address as is and only update the'|gettext} {$type} {'address'|gettext}.{br}
        {else}
            {'If you update the existing address, it will change this saved address permanently.'|gettext}
            {'If you save this as a new address, it will leave the existing address as-is to be used later by the customer and create a new'|gettext} {$type} {'address for this order.'|gettext}{br}
        {/if}
        {control type=radiogroup label='' items='Update existing address, Save as new address'|gettxtlist values='0,1' name=save_option default='0'}
            
        {control type=checkbox label='Default address for this customer?'|gettext flip=true name='address[is_default]' value=1 checked=$record->is_default}
        {control type=hidden name='address[is_shipping]' value=$record->is_shipping}
        {control type=hidden name='address[is_billing]' value=$record->is_billing}
        {control type=text name='address[firstname]' label="*First Name"|gettext value=$record->firstname}
        {control type=text name='address[middlename]' label="Middle Name"|gettext value=$record->middlename}
        {control type=text name='address[lastname]' label="*Last Name"|gettext value=$record->lastname}
		{control type=tel name="address[phone]" label="*"|cat:"Contact Number"|gettext|cat:" (xxx-xxx-xxxx)" value=$record->phone}
        {control type=email name="address[email]" label="*"|cat:"Email Address"|gettext value=$record->email}
        {control type=text name='address[address1]' label="*Street Address"|gettext value=$record->address1}
        {control type=text name='address[address2]' label="Barangay"|gettext value=$record->address2}
        {control type=text name='address[city]' label="*"|cat:"City"|gettext value=$record->city}
		 {control type=text name='address[zip]' label="*"|cat:"Zip Code"|gettext value=$record->zip}
        {control type=hidden name='address[state]' value="1"}  
        {control type=hidden name='address[country]' value="1"}
        {control type=buttongroup submit="Save Address Change"|gettext cancel="Cancel"|gettext}
        
    {/form}
</div>

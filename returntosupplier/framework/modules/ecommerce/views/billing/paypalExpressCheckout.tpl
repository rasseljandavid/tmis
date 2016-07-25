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

{css unique="general-ecom" link="`$asset_path`css/creditcard-form.css"}

{/css}
<div class="billing-method">
	<h4>PayPal or Credit Card</h4>
    {if $order->total}
        {form controller=cart action=preprocess}
            {control type="hidden" name="billingcalculator_id" value=$calcid}
			<input type="hidden" name="delivery_date" class="delivery_date" />
			<input type="hidden" name="delivery_slot" class="delivery_slot" />
            <input id="continue-checkout" type="image" name="submit" value="1" src="http://tienda.ph/files/paypalbutton.png">
        {/form}
    {else}
        <h4>{'PayPal Express Checkout is unavailable for this transaction'|gettext}</h4>
    {/if}
</div>

<div style="margin-bottom: 5px; padding-bottom: 5px; margin-left: 60px;">-- Or --</div>
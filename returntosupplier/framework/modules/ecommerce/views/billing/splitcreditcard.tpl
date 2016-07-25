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

<div class="billing-method splitcreditcard creditcard-form">
    {form name="ccinfoform" id="ccinfoform" controller=cart action=preprocess}
        {control type="hidden" name="billingcalculator_id" value=$calcid}
        {$billing->form.$calcid}
        <button id="continue-checkout" class="add-to-cart-btn awesome {$smarty.const.BTN_SIZE} orange">{'Continue Checkout'|gettext}</button>
    {/form}
</div>

{*script unique="continue-checkout"}
{literal}
    YUI(EXPONENT.YUI3_CONFIG).use('node', function(Y) {
        //Y.one('#cont-checkout').setStyle('display','none');
        Y.one('#continue-checkout').on('click',function(e){
            e.halt();
            Y.one('#ccinfoform').submit();
        });
    });
{/literal}
{/script*}
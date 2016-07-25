{css unique="confirmation1" link="`$asset_path`css/ecom.css"}

{/css}

{css unique="confirmation2" link="`$asset_path`css/confirmation.css" corecss="button"}

{/css}

{css unique="cart" link="`$asset_path`css/cart.css"}

{/css}

<div class="module cart confirm">
    <h2>{ecomconfig var='checkout_title_top' default="Confirm Your Secure Order"|gettext}</h2>

   
    <div class="shippinginfo">
    {if $order->shipping_required == true}
       
       
            <table class="table" style="margin-bottom: 10px;">
        		<thead>
					<tr>
						<th>{"Shipping Address"|gettext}</th>
					</tr>
				</thead>
                <tbody>
                    <tr class="even">
                        <td>
                            {$shipping->shippingmethod->addresses_id|address}
                        </td>
                    </tr>
                </tbody>
            </table>
     
    {/if}

	  <table class="table">
    		<thead>
				<tr>
					<th>{"Delivery Date and Time"|gettext}</th>
				</tr>
			</thead>
            <tbody>
                <tr class="even">
                    <td>
                        {$order->delivery_date}{br}
                        {$order->special_note}
                    </td>
                </tr>
            </tbody>
        </table>
      
        {include file="../order/partial_summary.tpl" items=$order->orderitem}
        <div class=" order-total">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>
                            {'Total'|gettext}
                        </th>
						<th style="text-align: right; padding-right: 75px;">
							{$order->grand_total|currency}
						</th>
                    </tr>
                </tbody>
              
            </table>
        </div>
  
    </div>
{clear}
	
    <div class="confirmationlinks">
		{form action="process"}
		    <button type="submit" id="Submit" class="awesome medium red next" value="Looks good, submit my order!"><i class="icon-ok-circle icon-medium"></i> Looks good, submit my order! </button>
		{/form}
        <a href="{securelink controller=cart action=checkout}" class="awesome medium red back">
            &laquo; {"Let me edit something"|gettext}
        </a>
    </div>
    <p align="center">
        <div style="width:100%; margin: auto;">
            {ecomconfig var='ssl_seal' default="" unescape="true"}
        </div>
    </p>

    {ecomconfig var='checkout_message_bottom' default=""}
</div>
{script unique="confirm_order"}
{literal}
$(function() {
	$("form").submit(function() {
	    $(this).submit(function() {
	        return false;
	    });
	    return true;
	});
});
{/literal}
{/script}
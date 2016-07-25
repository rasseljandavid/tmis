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

<div class="product item">
	
	<div class="product_cart_ico {if !$current_cart[$listing->id]}hide{/if}"><span class="qty_cart">{$current_cart[$listing->id]}</span> <i class="icon-shopping-cart"></i></div>

	<div class="product_add_minus">
		<a href="javascript:;" title="{$listing->id}" class="icon-minus remove_product"></a> 
			<span class="product_quantity">
				{if $current_cart[$listing->id]}
					{$current_cart[$listing->id]}
				{else}
					0
				{/if}
			</span> <a href="javascript:;" title="{$listing->id}" class="icon-plus add_product"></a>
	</div>
		{if $listing->use_special_price}
			<input type="hidden" class="productprice" value="{$listing->special_price}" />
		{else}
	<input type="hidden" class="productprice" value="{$listing->base_price}" />
	{/if}
    {permissions}
        <div class="item-actions">
            {if $permissions.edit == 1}
                {icon action=edit record=$listing title="Edit `$listing->title`"}
                {icon action=copyProduct class="copy" record=$listing text="Copy" title="Copy `$listing->title` "}
            {/if}
            {if $permissions.delete == 1}
                {icon action=delete record=$listing title="Delete `$listing->title`" onclick="return confirm('"|cat:("Are you sure you want to delete this product?"|gettext)|cat:"');"}
            {/if}
        </div>
    {/permissions}

    <a href="{link controller=store action=show title=$listing->sef_url}" class="prod-img">
        {if $listing->expFile.mainthumbnail[0]->id != ""}
            {img file_id=$listing->expFile.mainthumbnail[0]->id constraint=1 w=150 h=150 alt=$listing->title}
        {else}
            {img src="`$asset_path`images/no-image.jpg" constraint=1 w=$config.listingwidth|default:150 h=$config.listingheight|default:150 alt="'No Image Available'|gettext"}
        {/if}
    </a>

    <h3 class="product-info">
        <a href="{link controller=store action=show title=$listing->sef_url}">{$listing->title}</a>
    </h3>   
	<div class="row-fluid capacity-prices">
		<div class="span6 capacity muted">{$listing->quantity} 
                 {if $listing->quantity > 1} pcs
                 {else} pc
                  {/if}

                </div>
		<div class="span6 prices muted">
                      	{if $listing->use_special_price}
			<span class="sale-price">{$listing->special_price|currency}&#160;<sup>{"SALE!"|gettext}</sup></span> 
			<span class="regular-price on-sale">{$listing->base_price|currency}</span>
			{else}
			<span class="regular-price">{$listing->base_price|currency}</span>
			{/if}
                </div>
	</div>
</div>

{script unique='product-my-list'}
    {literal}
		function ReplaceNumberWithCommas(yourNumber) {
		    //Seperates the components of the number
		    var n= yourNumber.toString().split(".");
		    //Comma-fies the first part
		    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		    //Combines the two sections
		    return n.join(".");
		}
		$(function() {
			$("body").on("click", 'a.add_product', function () {
					
					var plus_sign = $(this);
					var quantity = parseInt($(this).siblings(".product_quantity").text()) + 1;
					
					if(quantity == 1) {
							
						var url = EXPONENT.URL_FULL + 'index.php?controller=cart&action=addItemListing&product_id=' + $(this).attr("title") + '&ajax_action=1';
					} else {  	  
						var url = EXPONENT.URL_FULL + 'index.php?controller=cart&action=increaseItemListing&product_id=' + $(this).attr("title") + '&ajax_action=1&quantity=' + quantity;
					}
					
					plus_sign.siblings(".product_quantity").text(quantity);
					$(".cart_total").text(ReplaceNumberWithCommas(parseFloat(parseFloat(plus_sign.parent().siblings(".productprice").val()) + parseFloat( $(".cart_total").text().replace(/,/g, ''))).toFixed(2)));
					$.ajax({
				     	type: "GET",
				 	 	url: url,
				     	success:  function( msg ) {
						  if(msg == 1) {
				
							//update the cart
							
							plus_sign.parent().siblings(".product_cart_ico").removeClass("hide")
							plus_sign.parent().siblings(".product_cart_ico").find(".qty_cart").text(quantity);
						  }
				     	}
					})
				
			});
			
			$("body").on("click", 'a.remove_product', function () {
				var minus_sign = $(this);
				var quantity = parseInt(minus_sign.siblings(".product_quantity").text()) - 1;
				
		
				if(quantity >= 0) {
					
					
					if(quantity < 0) {
						quantity = 0;
					}
					
					if(quantity == 0) {
								 
						var url = EXPONENT.URL_FULL + 'index.php?controller=cart&action=removeItemListing&product_id=' + $(this).attr("title") + '&ajax_action=1';
					} else {
						var url = EXPONENT.URL_FULL + 'index.php?controller=cart&action=decreaseItemListing&product_id=' + $(this).attr("title") + '&ajax_action=1&quantity=-1';
					}
					
					minus_sign.siblings(".product_quantity").text(quantity);
					$(".cart_total").text(ReplaceNumberWithCommas(parseFloat( parseFloat($(".cart_total").text().replace(/,/g, '')) - parseFloat(minus_sign.parent().siblings(".productprice").val()) ).toFixed(2)));
					
					$.ajax({
				     	type: "GET",
				 	 	url: url,
				   
				     	success:  function( msg ) {
						  if(msg == 1) {
							
							//update the cart
							minus_sign.parent().siblings(".product_cart_ico").find(".qty_cart").text(quantity);
							if(quantity == 0) {
								minus_sign.parent().siblings(".product_cart_ico").addClass("hide")
							}
						  }
				     	}
					})
				}	
			});
		
		});
	{/literal}
{/script}

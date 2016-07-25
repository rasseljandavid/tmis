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
 
{css unique="product-show" link="`$asset_path`css/storefront.css" corecss="button,tables"}

{/css}

{css unique="product-show" link="`$asset_path`css/ecom.css"}

{/css}

{*{if $config.enable_lightbox}*}
{*{css unique="files-gallery" link="`$smarty.const.PATH_RELATIVE`framework/modules/common/assets/css/gallery-lightbox.css"}*}

{*{/css}    *}
{*{/if}*}

{if $product->user_message != ''}
    <div id="msg-queue" class="msg-queue notice">
        <div class="msg">{$product->user_message}</div>
    </div>
{/if}

<div class="module store show product">
    
	<div class="product_contents row-fluid">
		<div class="product_left_contents span4">
    		<div class="large-ecom-image">
		    	<a href="{$smarty.const.PATH_RELATIVE}thumb.php?id={$product->expFile.mainimage[0]->id}&w={$config.enlrg_w|default:500}" title="{$product->expFile.mainimage[0]->title|default:$product->title}" rel="lightbox[g{$product->id}]" id="enlarged-image-link">
	         		{img file_id=$product->expFile.mainimage[0]->id w=280 alt=$product->image_alt_tag|default:"Image of `$product->title`" title="`$product->title`"  class="large-img" id="enlarged-image"}
	            </a>
	
	            {$mainimg=$product->expFile.mainimage.0}
		   
		        {if $product->expFile.images[0]->id}
		            <div class="additional thumbnails">
		                <h3>{"Additional Images"|gettext}</h3>
		                <ul>
		                    <li>
		                        {if $config.enable_lightbox}
		                            <a href="{$smarty.const.PATH_RELATIVE}thumb.php?id={$product->expFile.mainimage[0]->id}&w={$config.enlrg_w|default:500}" title="{$mainimg->title|default:$product->title}" rel="lightbox[g{$product->id}]">
		                        {/if}
		                        {img file_id=$product->expFile.mainthumbnail[0]->id|default:$mainimg->id w=50 h=50 zc=1 class="thumbnail" id="thumb-`$mainimg->id`"}
		                        {if $config.enable_lightbox}
		                            </a>
		                        {/if}
		                    </li>
		                    {foreach from=$product->expFile.images item=thmb}
		                        <li>
		                            {if $config.enable_lightbox}
		                                <a href="{$smarty.const.PATH_RELATIVE}thumb.php?id={$thmb->id}&w={$config.enlrg_w|default:500}" title="{$thmb->title|default:$product->title}" rel="lightbox[g{$product->id}]">
		                            {/if}
		                            {img file_id=$thmb->id w=50 h=50 zc=1 class="thumbnail" id="thumb-`$thmb->id`"}
		                            {if $config.enable_lightbox}
		                                </a>
		                            {/if}
		                        </li>
		                    {/foreach}
		                </ul>
		            </div>
		        {/if}
        
		        {if $config.enable_lightbox}
		            {script unique="thumbswap-shadowbox" yui3mods=1}
		            {literal}
		                EXPONENT.YUI3_CONFIG.modules = {
		                    'gallery-lightbox' : {
		                        fullpath: EXPONENT.PATH_RELATIVE+'framework/modules/common/assets/js/gallery-lightbox.js',
		                        requires : ['base','node','anim','selector-css3','lightbox-css']
		                    },
		                    'lightbox-css': {
		                        fullpath: EXPONENT.PATH_RELATIVE+'framework/modules/common/assets/css/gallery-lightbox.css',
		                        type: 'css'
		                    }
		                }

		                YUI(EXPONENT.YUI3_CONFIG).use('node-event-simulate','gallery-lightbox', function(Y) {
		                    Y.Lightbox.init();

		                    if (Y.one('#enlarged-image-link') != null) {
		                        Y.one('#enlarged-image-link').on('click',function(e){
		                           if(!Y.Lang.isNull(Y.one('.thumbnails'))) {
		                              e.halt();
		                              e.currentTarget.removeAttribute('rel');
		                              Y.Lightbox.init();
		                              Y.one('.thumbnails ul li a').simulate('click');
		                           }
		                        });
		                    }
		                    Y.one('#enlarged-image-link').on('click',function(e){
		                       if(!Y.Lang.isNull(Y.one('.thumbnails'))) {
		                          e.halt();
		                          e.currentTarget.removeAttribute('rel');
		                          Y.Lightbox.init();
		                          Y.one('.thumbnails ul li a').simulate('click');
		                       }
		                    });
		                //}

		                });
		
						$(function() {
							$(".product .icon-star-empty.logged").click(function (event) {
								event.preventDefault();
								var container = $(this).parent('.my-list-icons');
								$.ajax({
								     type: "GET",
								     url: $(this).attr("href"),
								     beforeSend: function() {
								          container.html('<div class="loader"><img src="' + EXPONENT.THEME_RELATIVE + 'images/loader.gif"/></div>');
								     },
								     success:  function( msg ) {

										  if(msg == 1) {
								          	container.html('<i class="btn icons icon-star"> Added in My List</i>');
										  }
								     }
								});	
							});
						});
		            {/literal}
		            {/script}
		        {/if}
		    </div>
			
			<div class="my-list-icons">
			{if $user_id}
			
				{if $product->id|in_array:$mylists}
				<i class="btn icons icon-star"> Added to My List</i>
				{else}
				<a title="Add to my list" class="btn icons icon-star-empty logged" href="{link controller=store action=addtomylist product_id=$product->id ajax_action=1}"> Add to My List</a> 
				{/if}
			
			{else}
				<a title="Add to my list" class="btn icons icon-star-empty" href="{link controller=login action=loginredirect}"> Add to My List</a> 
			{/if}
			</div>
			
			<div class="social-product">
			<div class="fb-like" data-href="{$smarty.const.URL_FULL}store/show/title/{$product->sef_url}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
			</div>
		</div>
 		<div class="product_right_contents span8">
			<h1 class="product_title">{$product->title} <span class="muted">{$product->capacity}</span></h1>

		    {permissions}
		    <div class="item-actions">
		        {if $permissions.edit == 1}
		            {icon action=edit record=$product title="Edit `$product->title`"}
		            {icon action=copyProduct class="copy" text="Copy Product"|gettext title="Copy `$product->title` " record=$product}
		            {icon class="add" action=edit parent_id=$product->id product_type='childProduct' text='Add Child Product'|gettext}
		        {/if}
		        {if $permissions.delete == 1}
		            {icon action=delete record=$product title="Delete `$product->title`" onclick="return confirm('Are you sure you want to delete this product?');"}
		        {/if}
		    </div>
		    {/permissions}
			
   		 	<div class="prod-price"> 
		       {if $product->use_special_price}                     
		       		
		            <span class="sale-price">{$product->special_price|currency}&#160;<sup>{"SALE!"|gettext}</sup></span>
					<span class="regular-price on-sale">{$product->base_price|currency}</span>
		       {else}
		            <span class="regular-price">{$product->base_price|currency}</span>
		       {/if}
		    </div>     
		
			{if $user->is_admin}  
				<p>Manufacturing Price: <strong>{$product->manufacturing_price|currency}</strong>{br}
				   Stock: <strong>{$product->quantity}</strong>
				</p>
			{/if}
		   
		    <div class="addtocart">
            {form id="addtocart`$product->id`" controller=cart action=addItem}
                {control type="hidden" name="product_id" value="`$product->id`"}
                {control type="hidden" name="product_type" value="`$product->product_type`"}

                <div class="add-to-cart-btn">
                    <input type="text" class="text " size="5" value="{$product->minimum_order_quantity|default:1}" name="quantity">
                       <button type="submit" class="add-to-cart-btn btn btn-primary btn-medium" rel="nofollow">
                           {"Add to Cart"|gettext}
                       </button>
                </div>
            {/form}
         	</div>
		
			{if $product->body}
			 <div class="product_description">
				<h2>Product Description</h2>
			    {$product->body}    
			 </div>
			{/if}
			
			<div class="product_specification">
				<h2>Product Specification</h2>
			    <ul>
					<li>Height: {$product->height}"</li>
					<li>Width: {$product->width}"</li>
					<li>Capacity: {$product->capacity}</li>
				</ul>    
			 </div>

		    {clear}
		</div>		
	</div>		
	{if $product->crosssellItem|@count >= 1}
	<div class="product_related">
		<h2>Related Products</h2>
        <div class="products ipr{$config.images_per_row} related-products">

            {counter assign="ipr" name="ipr" start=1}

            {foreach name=listings from=$product->crosssellItem item=listing}

                {if $smarty.foreach.listings.first || $open_row}
                    <div class="product-row">
                    {$open_row=0}
                {/if}

                {include file=$listing->getForm('storeListing')}

                {if $smarty.foreach.listings.last || $ipr%$config.images_per_row==0}
                    </div>
                    {$open_row=1}
                {/if}
                {counter name="ipr"}

            {/foreach}
        	</div>
	</div>
    {/if}
</div>



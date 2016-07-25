{css unique="storeListing" link="`$asset_path`css/storefront.css" corecss="button,clearfix"}

{/css}

<div class="module store showall">
    <div class="products ipr{$config.images_per_row|default:3} listing-row">
          {counter assign="ipr" name="ipr" start=1}
          {foreach from=$page->records item=listing name=listings}
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
		{if $page->page < $page->total_pages}
     <a class="load_next_products" href="{link controller=store action=load_next_products page=$page->page+1  ajax_action=1 category=$current_category->id}">Loading...</a>
	
	
	{script unique="autoloadproducts" jquery=1}
	{literal}
		$(function () {
			$('.module.store.showall').jscroll({
					padding: 20,
					nextSelector: 'a.load_next_products',
					loadingHtml: '<img src="/ajax-loader.gif" alt="Loading" style="margin:auto;display:block; text-align: center;" />',
			});
		});
	{/literal}
	{/script}
	{/if}
</div>
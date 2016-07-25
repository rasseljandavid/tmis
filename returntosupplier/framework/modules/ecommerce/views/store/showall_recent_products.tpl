{css unique="storeListing" link="`$asset_path`css/storefront.css" corecss="button,clearfix"}

{/css}
<div class="module store showall best">
    <h2>{$moduletitle}</h2>

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

</div>
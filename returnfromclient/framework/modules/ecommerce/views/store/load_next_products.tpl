<div class="products ipr{$config.images_per_row|default:3} listing-row">
    <div class="products ipr{$config.images_per_row|default:3} listing-row">
          {counter assign="ipr" name="ipr" start=1}
          {foreach from=$page->records item=listing name=listings}
              {if $smarty.foreach.listings.first || $open_row}
                  <div class="product-row">
                  {$open_row=0}
              {/if}
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
              {if $smarty.foreach.listings.last || $ipr%$config.images_per_row==0}
                  </div>
                  {$open_row=1}
              {/if}
              {counter name="ipr"}
          {/foreach}
     </div>
</div>
{if $page->page < $page->total_pages}
 <a class="load_next_products" href="{link controller=store action=load_next_products page=$page->page+1  ajax_action=1 category=$current_category->id}">Loading...</a>
{/if}
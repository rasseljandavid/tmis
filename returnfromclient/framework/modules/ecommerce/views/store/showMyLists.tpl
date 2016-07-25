<div class="module store showMyLists">
    {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}
		<h2>{$moduletitle}</h2>
	{/if}

	{if !empty($mylists)}
		{form action=addmylist}
		<table class="table table-hover mylist_products">
			<tbody>
				{foreach from=$mylists item=list}
					<tr>
						<td>
							{control type="text" name="qty[`$list->product_id`]" label="" value="0" size=3 maxlength=5 class="mylist-qty"}
						</td>
						<td class="product_image_column">
							<a href="{link controller=store action=show title=$list->sef_url}" class="prod-img">
						        {if $list->product_image != ""}
						            {img file_id=$list->product_image constraint=1 w=50 h=50 alt=$listing->product_title}
						        {else}
						            {img src="`$asset_path`images/no-image.jpg" constraint=1 w=150 h=150 alt="'No Image Available'|gettext"}
						        {/if}
						    </a>
						</td>
						<td class="product_title_column">
							<a href="{link controller=store action=show title=$list->sef_url}">
								{$list->product_title} 
							</a>
							<span class="muted">{$list->capacity}</span>
						</td>
						<td>
							<a href="{link controller=store action=removefrommylist list_id=$list->id}" class="icon-remove" onclick="return confirm('Are you sure you want to remove this product in your list?');">
								
							</a>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
		{control type="buttongroup" submit="Add to Cart"|gettext}
		{/form}
		
		
	{else}
		<p>You don't have any products in your list yet.</p> <p>Click <a href="{link controller=store action=showall}">here</a> to add some products.</p>
	{/if}

</div>
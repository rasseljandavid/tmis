<div class="module store showallfeaturedcategory">
	<div class="cats">
    	<h2>Featured Categories</h2>
			{counter assign="ipcr" name="ipcr" start=1}
            {$open_c_row=1}

            {foreach name="cats" from=$categories item="cat"}
                {if $cat->is_active==1 || $user->isAdmin()}

                    {if $smarty.foreach.cats.first || $open_c_row}
                        <div class="category-row row-fluid">
                        {$open_c_row=0}
                    {/if}

                    <div class="cat{if $cat->is_active!=1} inactive{/if} span6 category">
	
						<a href="{link controller=store action=showall title=$cat->sef_url}" class="cat-img-link">
	                    	{if $cat->expFile[0]->id}
	                        	{img file_id=$cat->expFile[0]->id w=$config.category_thumbnail|default:150 class="cat-image"}
	                        {elseif $page->records[0]->expFile.mainimage[0]->id}
	                            {img file_id=$page->records[0]->expFile.mainimage[0]->id w=$config.category_thumbnail|default:150 class="cat-image"}
	                        {else}
	                             {img src="`$asset_path`images/no-image.jpg" w=$config.category_thumbnail|default:150 class="cat-image" alt="'No Image Available'|gettext"}
	                       {/if}
	                    </a>

						<div class="cat-info">
							<h3>
                            	<a href="{link controller=store action=showall title=$cat->sef_url}">
                                	{$cat->title}
                            	</a>
                        	</h3>
							<p>
								{foreach name="childcatName" from=$cat->childCat item="childcat"}
									<a href="{link controller=store action=showall title=$childcat->sef_url}">{$childcat->title}</a>{if !$smarty.foreach.childcatName.last}, {/if}
								{/foreach}
							</p>
						</div>
						
                    </div>

                    {if $smarty.foreach.cats.last || $ipcr%2==0}
                        </div>
                        {$open_c_row=1}
                    {/if}
                    {counter name="ipcr"}

                {/if}
            {/foreach}

            {* close the row if left open. might happen for non-admins *}
            {if $open_c_row==0}
                </div>
                {$open_c_row=1}
            {/if}
        </div>
</div>


{uniqueid assign=id prepend="sub`$curcat->title`"}
<div class="module store show-top-level">
    {$depth=0}
        {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}<h1>{$moduletitle}</h1>{/if}
 
        {$myloc=serialize($__loc)}

		<h4>Tienda Categories</h4>

        {if $curcat->id}
            {permissions}
                {if $permissions.edit == 1}
                    {icon class="edit" action=edit module=storeCategory id=$curcat->id title="Edit `$curcat->title`" text="Edit this Store Category"}{br}
                {/if}
                {*{if $permissions.manage == 1}*}
                    {*{icon class="configure" action=configure module=storeCategory id=$curcat->id title="Configure `$curcat->title`" text="Configure this Store Category"}{br}*}
                {*{/if}*}
                {*{if $permissions.manage == 1}*}
                    {*{icon class="configure" action=configure module=ecomconfig hash="#tab2" title="Configure Categories Globally" text="Configure Categories Globally"}{br}*}
                {*{/if}*}
                {if $permissions.edit == 1 && $config.orderby=="rank"}
                    {ddrerank label="Products"|gettext sql=$rerankSQL model="product" controller="storeCategory" id=$curcat->id}
                {/if}
            {/permissions}
        {/if}
	<div id="catnav" class="cat-navigation has-child">
		<a href="#" class="open-close">Tienda Categories<i class="icon-chevron-down"></i></a>
		{$liopening=0}
		{$prev_cat=0}
		<ul class="nav nav-tabs nav-stacked">	
			{foreach from=$categories item=category}
		
    			{if $category->is_active==1 || $user->is_acting_admin}
				
					<!-- If it is a child category than the previous category -->
					{if $category->depth > $depth}
				
						<ul class="nav">
						{$depth = $category->depth}	
					<!-- If it is a parent category than the previous category -->				
					{elseif $category->depth < $depth}	
			
						{while $category->depth < $depth}
							</li>
							</ul>
							{$liopening = $liopening-1}
							{$depth = $depth-1}		
						{/while}
					<!-- If it is a sibling category of the previous category -->		
					{else}	
						</li>
						{$liopening = $liopening-1}
					{/if}
				
                    <li class="{if $curcat->id==$category->id}active{/if}{if $category->is_active!=1} inactive{/if} {if $parentcategory->id == $category->id || $subparentcategory->id == $category->id} actives{/if}">
						<a href="{link controller=store action=showall title=$category->sef_url}">{if $category->parent_id != 0} - {/if}{$category->title}</a>
					{$liopening = $liopening+1}
					{$prev_cat = $category->id}	
				{/if}
			{/foreach}
			{if $liopening > 0}</li>{/if}
		</ul>
	</div>
</div>
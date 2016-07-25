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

{css unique="storeListing" link="`$asset_path`css/storefront.css" corecss="button,clearfix"}

{/css}

<div class="module store showall-featured-products ipr{$config.images_per_row|default:3} listing-row">
    {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}<h2>{$moduletitle}</h2>{/if}
    {permissions}
    <div class="module-actions">
        {if $permissions.create == true || $permissions.edit == true}
            {icon class="add" action=create text="Add a Product"|gettext}
        {/if}
        {if $permissions.manage == 1}
            {icon action=manage text="Manage Products"|gettext}
            {icon controller=storeCategory action=manage text="Manage Store Categories"|gettext}
        {/if}
    </div>
    {/permissions}
    {if $config.moduledescription != ""}
        {$config.moduledescription}
    {/if}
    {$myloc=serialize($__loc)}

    <div class="products ipr{$config.images_per_row|default:3} listing-row">
    {counter assign="ipr" name="ipr" start=1}
    {foreach from=$page->records item=listing name=listings}
        {if $listing->is_featured}
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
        {/if}
    {foreachelse}
       {'No Products were found!'|gettext}
    {/foreach}
    </div>
</div>

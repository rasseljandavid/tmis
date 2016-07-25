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


        <a style="margin: 3px; padding:0px; display: inline-block;" href="{link action=show controller=store title=$item->product->getSEFURL()}">{img file_id=$item->product->expFile.mainimage[0]->id h=50 w=50 zc=1 class="border"}</a>
	<div style="display: inline-block;">
            <span class="itemname"><strong><a style="padding-left: 10px;" href="{link action=show controller=store title=$item->product->getSEFURL()}">{$item->products_name}</a></strong></span>
        	<span class="muted" style="padding-left: 10px;">{$item->product->capacity}</span>
      </div>
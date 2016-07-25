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

<div class="store showall">
    <h2>{'Product Model Search results for'|gettext} '{$terms}'</h2>
    {pagelinks paginate=$page top=1}
    <div class="products">
        {foreach from=$page->records item=listing name=listings}
            {include file=$listing->getForm('storeListing')}
        {/foreach}
    </div>
    {pagelinks paginate=$page bottom=1}
</div>

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
 
{css unique="z-pagination-bootstrap" link="`$smarty.const.PATH_RELATIVE`framework/modules/common/assets/css/pagination-bootstrap.css"}

{/css}

{if $page->total_records > 0}
    <div class="pagination-links">
        <div class="pagination pagination-small">
            <ul>
            {if $page->previous_page != ''}<li><a class="pager" href="{$page->previous_page}" rel="{$page->previous_pagenum}">&laquo;</a></li> {/if}
            {if $page->firstpage != ''}<li><a class="pager" href="{$page->firstpage}" rel="1">1</a></li> {/if}
            {if $page->previous_shift != ''}<li><a class="pager" href="{$page->previous_shift}" rel="{$page->previous_shiftnum}">...</a></li> {/if}
            {if $page->total_pages > 1}
                {foreach from=$page->pages item=link key=curpage}
                        {if $curpage == $page->page}
                            <li class="active disabled"><a href="#">{$curpage}</a></li>
                        {else}
                            <li><a class="pager" href="{$link}" rel="{$curpage}">{$curpage}</a></li>
                        {/if}
                {/foreach}
            {/if}
            {if $page->next_shift != ''}<li><a class="pager" href="{$page->next_shift}" rel="{$page->next_shiftnum}">...</a></li> {/if}
            {if $page->lastpage != ''}<li><a class="pager" href="{$page->lastpage}" rel="{$page->total_pages}">{$page->total_pages}</a></li> {/if}
            {if $page->next_page != ''} <li><a class="pager" href="{$page->next_page}" rel="{$page->next_pagenum}">&raquo;</a></li>{/if}
            </ul>
        </div>
    </div>
{/if}

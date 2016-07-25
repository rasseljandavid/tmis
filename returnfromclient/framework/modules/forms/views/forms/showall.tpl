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

{if !$error}
    {css unique="data-view" corecss="button, tables"}

    {/css}
    <div class="module forms showall">
        <h2>{$title}</h2>
        {if $description != ""}
            {$description}
        {/if}
        {permissions}
            <div class="module-actions">
                {if $permissions.edit == 1}
                    {icon class=add action=enterdata forms_id=$f->id text='Add record'|gettext}
                    &#160;&#160;|&#160;&#160;
                {/if}
                {icon class="downloadfile" action=export_csv id=$f->id text="Export as CSV"|gettext}
                {export_pdf_link landscapepdf=1 limit=999 prepend='&#160;&#160;|&#160;&#160;'}
                {if $permissions.manage}
                    &#160;&#160;|&#160;&#160;
                    {icon class=configure action=design_form id=$f->id text="Design Form"|gettext}
                    &#160;&#160;|&#160;&#160;
                    {icon action=manage text="Manage Forms"|gettext}
                {/if}
            </div>
        {/permissions}
        {$page->links}
        <div style="overflow: auto; overflow-y: hidden;">
            <table border="0" cellspacing="0" cellpadding="0" class="exp-skin-table">
                <thead>
                    <tr>
                        {$page->header_columns}
                        <div class="item-actions">
                            <th>{'Actions'|gettext}</th>
                        </div>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$page->records item=fields key=ukey name=fields}
                        <tr class="{cycle values="even,odd"}">
                            {foreach from=$page->columns item=column name=column}
                                <td>
                                    {if $smarty.foreach.column.iteration == 1}
                                        <a href={link action=show forms_id=$f->id id=$fields.id}>{$fields.$column}</a>
                                    {else}
                                        {$fields.$column}
                                    {/if}
                                </td>
                            {/foreach}
                            <div class="item-actions">
                                <td>
                                    {icon img="view.png" action=show forms_id=$f->id id=$fields.id title='View all data fields for this record'|gettext}
                                    {if $permissions.edit == 1}
                                        {icon img="edit.png" action=enterdata forms_id=$f->id id=$fields.id title='Edit this record'|gettext}
                                    {/if}
                                    {if $permissions.delete == 1}
                                        {icon img="delete.png" action=delete forms_id=$f->id id=$fields.id title='Delete this record'|gettext}
                                    {/if}
                                </td>
                            </div>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        {$page->links}
        {*<a class="awesome {$smarty.const.BTN_SIZE} orange" href="{$backlink}">{'Back'|gettext}</a>*}
    </div>
{/if}

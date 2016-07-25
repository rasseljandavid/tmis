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

{control type="hidden" name="tab_loaded[general]" value=1}
{if $record->parent_id == 0}
	{control type="hidden" name="general[parent_id]" value=$record->parent_id}   
	{control type="text" name="general[model]" label="Barcode"|gettext value=$record->model}
	{control type="text" name="general[rank]" label="Rank"|gettext value=$record->rank}
	{control type="text" class="title" name="general[title]" label="Product Name"|gettext value=$record->title}
	{control type="text" class="title" name="general[capacity]" label="Capacity"|gettext value=$record->capacity}
	{control type="text" name="general[manufacturer]" label="Manufacturer"|gettext value=$record->manufacturer}
	{control type="dropdown" name="general[companies_id]" label="Vendor"|gettext includeblank=true frommodel=company value=$record->companies_id}
    {icon class="manage" controller="company" action="showall" text="Manage Vendors"|gettext}
	{*{control type="textarea" name="general[summary]" label="Product Summary"|gettext rows=5 cols=85 value=$record->summary}*}
	{control type="editor" name="general[body]" label="Product Description"|gettext height=450 value=$record->body}

{else}
	{'Parent Product:'|gettext} <a href="{link controller='store' action='edit' id=$record->parent_id}">{$parent->title}</a>
	{control type="text" name="general[child_rank]" label="Rank"|gettext value=$record->child_rank}
	{control type="hidden" name="general[parent_id]" value=$record->parent_id}  
	{control type="hidden" name="general[product_type]" value='childProduct'}  
	{control type="text" name="general[model]" label="Model # / SKU"|gettext value=$record->model}
	{control type="text" class="title" name="general[title]" label="Product Name"|gettext value=$record->title}
	{control type="dropdown" name="general[companies_id]" label="Manufacturer"|gettext includeblank=true frommodel=company value=$record->companies_id}
    {icon class="manage" controller="company" action="showall" text="Manage Manufacturers"|gettext}
	{*{control type="textarea" name="general[summary]" label="Product Summary"|gettext rows=3 cols=45 value=$record->summary}*}
	{control type="editor" name="general[body]" label="Product Description"|gettext height=250 value=$record->body}
{/if}

{script unique="general" yui3mods=1}
{literal}
    Y.Global.fire('lazyload:cke');
{/literal}
{/script}

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

{if $record->id != ""}
	<h1>{'Edit Information for'|gettext} {$modelname}</h1>
{else}
	<h1>{'New'|gettext} {$modelname}</h1>
{/if}

{form action=update}
	{control name=id type=hidden value=$record->id}
    {control type="text" name="title" label="Title"|gettext value=$record->title}
    {*{control type="text" name="url" label="URL"|gettext value=$record->url}*}
    {control type=url name="url" label="URL"|gettext value=$record->url}
    {control type="files" name="image" label="Image"|gettext accept="image/*" value=$record->expFile}
    {control type="editor" name="body" label="URL Description"|gettext value=$record->body}
    {control type="buttongroup" submit="Save"|gettext cancel="Uh.. Nevermind..."|gettext}
{/form}

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

{include file="show.tpl"}

{*<h1>{'Showing'|gettext} {$model_name}, id: {$object->id}</h1>*}

{*<div id="scaffold-object">*}
	{*{list_object object=$record}*}
    {*<a href="{link controller=$model_name action=showall}">{'Go back to Show All'|gettext} {$model_name}</a> or*}
    {*<a href="{link controller=$model_name action=edit id=$record->id}"> {'Edit this'|gettext} {$model_name}</a>*}
{*</div>*}

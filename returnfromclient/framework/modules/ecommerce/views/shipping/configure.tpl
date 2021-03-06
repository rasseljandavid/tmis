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

<div class="module shipping configure">
    <h1>{'Configure'|gettext} {$calculator->title}</h1>
    <blockquote>{'Use this form to configure the'|gettext} {$calculator->title}</blockquote>
    
    {form action=saveconfig}
        {control type="hidden" name="id" value=$calculator->id}
        {include file=$calculator->configForm()}
        {control type="buttongroup" submit="Save Config"|gettext cancel="Cancel"|gettext}
    {/form}
</div>

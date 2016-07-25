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

{form action=update_zone}
    {control type="hidden" name="id" value=$zone->id}
    {control type="text" name="name" label="Zone Name"|gettext value=$zone->name}
    {control type="buttongroup" submit="Submit"|gettext cancel="Cancel"|gettext}
{/form}

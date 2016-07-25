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

<div id="migrationconfig" class="module migration configure">
 	<a class="awesome {$smarty.const.BTN_SIZE} red" href="{link module=migration action=manage_users}"><strong>{'Skip to Next Step -> Migrate Users & Groups'|gettext}</strong></a>
    {br}{br}<hr />
    <div class="info-header">
        <div class="related-actions">
			{help text="Get Help with"|gettext|cat:" "|cat:("Migration Database Settings"|gettext) module="configure-migration-settings"}
        </div>
		<h1>{"Database Settings to Migrate Your Old Site"|gettext}</h1>	    
    </div>

    <blockquote>
		{'This is where you enter the database connection information for your old Tienda v1 site you want to migrate data from.'|gettext}
    </blockquote>
    {form action=saveconfig}
		{control type=text name=server label="Server Name"|gettext value=$config.server|default:'localhost'}
		{control type="text" name="database" label="Database Name"|gettext value=$config.database}
		{control type="text" name="username" label="Username"|gettext value=$config.username}
		{control type="password" name="password" label="Password"|gettext value=$config.password}
		{control type="text" name="port" label="Port"|gettext value=$config.port|default:3306}
		{control type="text" name="prefix" label="Tienda Table Prefix"|gettext value=$config.prefix|default:'exponent'}
	    {control type="checkbox" name="fix_database" label="Attempt to fix tables in old database? (may cause a timeout on slow connection)"|gettext value=1 checked=false}
        {control type=buttongroup submit="Save Config"|gettext cancel="Cancel"|gettext}
    {/form}
</div>
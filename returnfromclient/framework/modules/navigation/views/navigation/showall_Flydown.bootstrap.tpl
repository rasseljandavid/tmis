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

{css unique="z-dropdown-bootstrap" link="`$asset_path`css/dropdown-bootstrap.css"}

{/css}

<ul class="nav{if $smarty.const.MENU_ALIGN == 'right'} pull-right{/if}">
    {getnav type='hierarchy' assign=hierarchy}
    {bootstrap_navbar menu=$hierarchy}
    	<li>
		<a class="icon-envelope topmenuicon" href="mailto:hello@tienda.ph?subject=Customer%20Service"> Email Us</a>
	</li>
	<li>
		<a class="icon-phone topmenuicon" href="callto://+0063453085345"> (045) 308-5345</a>
	</li>
</ul>


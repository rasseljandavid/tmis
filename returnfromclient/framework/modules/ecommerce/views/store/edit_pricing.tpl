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

{control type="hidden" name="tab_loaded[pricing]" value=1}
{group label="General Pricing"|gettext}
    <table>
		<tr>
			<td>{control type="text" name="pricing[manufacturing_price]" label="Manufacturing Price"|gettext value=$record->manufacturing_price filter=decimal size=15}</td>
			<td></td>
		</tr>
        <tr>
            <td>{control type="text" name="pricing[base_price]" label="Base Price"|gettext value=$record->base_price filter=decimal size=15}</td>
            <td>{control type="text" name="pricing[special_price]" label="Special Price"|gettext value=$record->special_price filter=decimal size=15}</td>
        </tr>
        <tr>
            <td colspan="2">{control type="checkbox" name="pricing[use_special_price]" label="Use Special Price"|gettext value=1 checked=$record->use_special_price postfalse=1}</td>
        </tr>
    </table>
{/group}

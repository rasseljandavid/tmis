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

{if $smarty.const.SITE_WYSIWYG_EDITOR=="ckeditor"}
    {include file="picker_cke.tpl"}
{else}
    {"Uh... yeah, we\'re not supporting that editor. Feel free to integrate it yourself though."|gettext}
{/if}
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

{css unique="msg-queue" corecss="msgq"}

{/css}

{if $queues|@count!=0} 
{foreach from=$queues item=queue key=qname}
    <div class="msg-queue {$qname}">
        <a class="close" href="#">{'Close'|gettext}</a>
        {foreach from=$queue item=msg}
            <div class="msg">{$msg}</div>
        {/foreach}
    </div>
{/foreach}

{script unique="closequeue" yui3mods="node"}
{literal}
    YUI(EXPONENT.YUI3_CONFIG).use('node', function(Y) {
        Y.all('.msg-queue .close').on('click',function(e){
            e.halt();
            e.target.get('parentNode').remove();
        });
    });
{/literal}
{/script}
{/if}
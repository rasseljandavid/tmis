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

<div class="exp-comment edit">
	{if $formtitle}<h3>{$formtitle}</h3>{/if}
    {$config.commentinfo}
    {*{if ($smarty.const.COMMENTS_REQUIRE_LOGIN == 1 && $user->id != 0) || $smarty.const.COMMENTS_REQUIRE_LOGIN == 0}*}
    {if ($require_login == 1 && $user->id != 0) || $require_login == 0}
    	{form action=update}
    		{control type=hidden name=id value=$comment->id}
            {control type=hidden name=parent_id value=$comment->parent_id}
    		{control type=hidden name=content_id value=$content_id}
    		{control type=hidden name=content_type value=$content_type}
            <div id="commentinput"></div>
    		{if $user->id == 0 || $comment->id }
    	        {control type=text name=name label="Name"|gettext required=true value=$comment->name required=1}
    		    {*{control type=text name=email label="Email"|gettext required=true value=$comment->email required=1}*}
                {control type=email name=email label="Email"|gettext required=true value=$comment->email required=1}
    		{else}
                {control type=text name=name disabled=1 label="Name"|gettext value="`$user->firstname` `$user->lastname`"}
        	    {*{control type=text name=email disabled=1 label="Email"|gettext value=$user->email}*}
                {control type=email name=email disabled=1 label="Email"|gettext value=$user->email}
    		{/if}
    		{*control type=text name=website label="Website" value=$comment->website*}
    		{*{control type=textarea name=body label="Your Comment"|gettext rows=6 cols=35 value=$comment->body}*}
            {control type="editor" name=body label="Your Comment"|gettext value=$comment->body toolbar='basic'}
    		{control type="antispam"}
            {permissions}
                {if $permissions.approve}
                    {control type="checkbox" name="approved" label="Approve Comment"|gettext value=1 checked=$comment->approved}
                {/if}
            {/permissions}
    		{control type=buttongroup submit="Submit Comment"|gettext}
    	{/form}
	{else}
		<p>
			<a href="{link controller=login action=loginredirect}">{"Log In to leave a comment"|gettext}</a>
		</p> 
	{/if}
</div>

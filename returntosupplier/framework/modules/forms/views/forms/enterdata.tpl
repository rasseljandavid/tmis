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

{uniqueid prepend=$form->sef_url assign="name"}
{if !$error}
    {if $config.style}
        {css unique="formmod2" corecss="forms2col"}

        {/css}
    {/if}
    <div class="module forms edit enter-data">
        {messagequeue name='notice'}
        {permissions}
            <div class="module-actions">
                {if $permissions.viewdata && $form->is_saved}
                    {icon class="view" action=showall id=$form->id text='View Data'|gettext|cat:" (`$count`)"}
                    &#160;&#160;|&#160;&#160;
                    {icon class="downloadfile" action=export_csv id=$form->id text="Export CSV"|gettext}
                    {if $permissions.manage}
                        &#160;&#160;|&#160;&#160;
                    {/if}
                {/if}
                {if $permissions.manage}
                    {if !empty($form->id)}
                        {icon class=configure action=design_form id=$form->id text="Design Form"|gettext}
                        &#160;&#160;|&#160;&#160;
                    {/if}
                    {icon action=manage text="Manage Forms"|gettext}
                {/if}
            </div>
        {/permissions}
        {if $edit_mode}
            <h2>{'Edit Form Record'|gettext}</h2>
        {else}
            {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}
                <h2>{$moduletitle}</h2>{/if}
            {if $config.moduledescription != ""}
                {$config.moduledescription}
            {/if}
        {/if}
        <div class="bodycopy">
            {if empty($form) && $permissions.configure}
                {permissions}
                    <div class="module-actions">
                        <div class="msg-queue notice" style="text-align:center">
                            <p>{'You MUST assign a form to use this module!'|gettext} {icon action="manage"}</p></div>
                    </div>
                {/permissions}
            {else}
                {if $description != ""}
                    {$description}
                {/if}
				{if $form->id == 1}
					<div class="row-fluid">
						<div class="span6 contact-us" style="border-right: 1px solid #ccc">
							{$form_html}
						</div>
						<div class="span6">
							<p><strong>Phone:</strong> (045) 436-0734</p>
							<p><strong>Email:</strong> hello@tienda.ph</p>
							<p><strong>HQ:</strong> L2 B20 18th Street Mauaque Mabalacat City, 2010</p>
							<div id="map_canvas"></div>
						</div>
					</div>
				{else}
                	{$form_html}
				{/if}
            {/if}
        </div>
    </div>
{/if}
{if $form->id == 1}
{script unique="contact_form" src="https://maps.googleapis.com/maps/api/js?sensor=false"}
	$(function() {
    function initialize() {
        var map_canvas = document.getElementById('map_canvas');
        var map_options = {
          center: new google.maps.LatLng(15.203709, 120.604219),
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(map_canvas, map_options)
      }
      google.maps.event.addDomListener(window, 'load', initialize);
	});
{/script}
{/if}
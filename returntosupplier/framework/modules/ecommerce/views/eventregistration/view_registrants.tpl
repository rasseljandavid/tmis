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

{css unique="viewregistrants" corecss="tables"}

{/css}

{css unique="event-show1" link="`$asset_path`css/eventregistration.css"}

{/css}

<div class="store  store show event-registration">
    <div class="form_header">
        {permissions}
            <div class="module-actions">
                {if $permissions.create == 1 || $permissions.edit == 1}
                    {icon class="add" controller=store action=edit product_type=eventregistration text="Add an event"|gettext}
                {/if}
                {if $permissions.manage == 1}
                     {icon action=manage text="Manage Events"|gettext}
                {/if}
            </div>
        {/permissions}
        <h1>{'Event Information'|gettext}</h1>
        <h2>{$event->title}</h2>
        {permissions}
            <div class="item-actions">
                {if $permissions.edit == 1}
                    {icon controller="store" action=edit record=$event}
                    {icon controller="store" action=copyProduct class="copy" record=$event text="Copy" title="Copy `$event->title` "}
                {/if}
                {if $permissions.delete == 1}
                    {icon controller="store" action=delete record=$event}
                {/if}
            </div>
        {/permissions}

        <div id="eventregform">
            <span class="label">{'Event Date'|gettext}: </span>
            <span class="value">{$event->eventdate|format_date:"%A, %B %e, %Y"}
                {if (!empty($event->eventenddate) && $event->eventdate != $event->eventenddate)} {'to'|gettext} {$event->eventenddate|format_date:"%A, %B %e, %Y"}{/if}
            </span>{br}
            <span class="label">{'Start Time'|gettext}: </span>
            <span class="value">{($event->eventdate+$event->event_starttime)|format_date:"%l:%M %p"}</span>{br}
            <span class="label">{'End Time'|gettext}: </span>
            <span class="value">{($event->eventdate+$event->event_endtime)|format_date:"%l:%M %p"}</span>{br}
            {if !empty($event->location)}
                <span class="label">{'Location:'|gettext} </span>
                <span class="value">{$event->location}</span>{br}
            {/if}
            <span class="label">{'Price per person:'|gettext} </span>
            <span class="value">{if $event->base_price}{$event->base_price|currency}{else}{'No Cost'|gettext}{/if}</span>{br}
            <span class="label">{'Seats Registered:'|gettext} </span>
            <span class="value">{$count} {'of'|gettext} {$event->quantity}</span>{br}
            <span class="label">{'Registration Closes:'|gettext} </span>
            <span class="value">{$event->signup_cutoff|format_date:"%A, %B %e, %Y"}</span>
        </div>
    </div>
    {br}
    {form action="emailRegistrants"}
        <div class="events">
            {permissions}
                {if $registrants|count < $event->quantity}
                    <div class="module-actions">
                        {if $permissions.create == 1}
                            {icon class="add" action=edit_registrant event_id=$event->id text="Manually Add a Registrant"|gettext}
                        {/if}
                    </div>
                {/if}
            {/permissions}
            {$controls = $event->getAllControls()}
            <div style="overflow: auto; overflow-y: hidden;">
            <table class="exp-skin-table">
                <thead>
                    <tr>
                        {*<th>{'Registrant Name'|gettext}</th>*}
                        {*<th>{'Registrant Email'|gettext}</th>*}
                        {*<th>{'Registrant Phone'|gettext}</th>*}
                        {foreach $controls as $control}
                            <th>
                                <span>{$control->caption}</span>
                            </th>
                        {foreachelse}
                            <th>{'Name'|gettext}</th>
                            <th>{'Quantity'|gettext}</th>
                        {/foreach}
                        <th>{'Paid?'|gettext}</th>
                        <th>{'Actions'|gettext}</th>
                    </tr>
                </thead>
                <tbody>
                    {if $registrants|count > 0}
                        {foreach from=$registrants item=registrant key=id}
                            {*{get_user user=$user assign=registrant}*}
                            <tr class="{cycle values="odd,even"}">
                                {*<td>{$registrant->name}</td>*}
                                {*<td>*}
                                    {*{if !empty($registrant->email)}{control type="hidden" name="email_addresses[]" value={$registrant->email}}{/if}*}
                                    {*<a href="mailto:{$registrant->email}">{$registrant->email}</a>*}
                                {*</td>*}
                                {*<td>{$registrant->phone} </td>*}
                                {$is_email = false}
                                {foreach $controls as $control}
                                    {$ctlname = $control->name}
                                    <td>
                                       {if $ctlname == 'email'}
                                           {$is_email = true}
                                           {control type="hidden" name="email_addresses[]" value={$registrant->$ctlname}}
                                           <a href="mailto:{$registrant->$ctlname}" title="{'Send them an email'|gettext}">{$registrant->$ctlname}</a>
                                       {else}
                                           {$registrant->$ctlname}
                                       {/if}
                                    </td>
                                {foreachelse}
                                    <th>{$registrant->user}</th>
                                    <th>{$registrant->qty}</th>
                                {/foreach}
                                <td>
                                    {if $registrant->order_id}
                                        <a href="{link controller="order" action="show" id=$registrant->order_id}" title="{'Edit this order'|gettext}">{$registrant->payment}</a>
                                    {else}
                                        {$registrant->payment}
                                    {/if}
                                </td>
                                <td>
                                    {permissions}
                                        <div class="item-actions">
                                            {if $permissions.edit == 1}
                                                {icon class=edit action=edit_registrant event_id=$event->id id=$registrant->id title='Edit this Registrant'|gettext}
                                            {/if}
                                            {if $permissions.delete == 1}
                                                 {icon class="delete" action=delete_registrant event_id=$event->id id=$registrant->id title='Delete this Registrant'|gettext onclick="return confirm('"|cat:("Are you sure you want to delete this registrant from the roster?"|gettext)|cat:"');"}
                                            {/if}
                                        </div>
                                    {/permissions}
                                </td>
                            </tr>
                        {/foreach}
                    {else}
                        <tr class="{cycle values="odd,even"}">
                            <td colspan="4">{'There is currently no one registered'|gettext}</td>
                        </tr>
                    {/if}
                </tbody>
            </table>
            </div>
        </div>
        {icon class=downloadfile controller=eventregistration action=export id=$event->id text='Export this Event Roster'|gettext}
        {if $registrants|count > 0 && $is_email}
            {group label='Send an Email to All Registrants'|gettext}
                {control type="text" name="email_subject" label="Subject"|gettext}
                {control type="editor" name="email_message" label="Message"|gettext}
                {control type="uploader" name="attach" label="Attachment"|gettext description='Optionally send a file attachment'|gettext}
                {control type="buttongroup" submit="Send Email"|gettext}
            {/group}
        {/if}
    {/form}
</div>
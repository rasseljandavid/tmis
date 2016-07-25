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

{uniqueid assign="config"}

<div id="siteconfig" class="module administration configure-site">
	<div class="form_header">
		<div class="info-header">
	
			<h1>{'Configure Website'|gettext}</h1>
		</div>
	</div>
    {form controller="administration" action=update_siteconfig}
        <div id="{$config}" class="yui-navset exp-skin-tabview hide">
            <ul class="yui-nav">
	            <li class="selected"><a href="#tab1"><em>{"General"|gettext}</em></a></li>
	            <li><a href="#tab2"><em>{"Anti-Spam"|gettext}</em></a></li>
	            <li><a href="#tab3"><em>{"User Registration"|gettext}</em></a></li>
	            <li><a href="#tab4"><em>{"Comment Policies"|gettext}</em></a></li>

             
	            {if $user->isAdmin()}
					<li><a href="#tab7"><em>{"Mail Server"|gettext}</em></a></li>
		            <li><a href="#tab8"><em>{"Maintenance"|gettext}</em></a></li>
		            <li><a href="#tab9"><em>{"Security"|gettext}</em></a></li>
					

		            <li><a href="#tab12"><em>{"Error Messages"|gettext}</em></a></li>
	
					<li><a href="#tab15"><em>{"Search Report"|gettext}</em></a></li>
       
	            {/if}
            </ul>            
            <div class="yui-content">
                <div id="tab1">
	                <div class="info-header">
                   
		                <h2>{"General Site Configuration"|gettext}</h2>
                    </div>
                    {control type="text" name="sc[ORGANIZATION_NAME]" label="Site/Organization Name"|gettext value=$smarty.const.ORGANIZATION_NAME}
                    {control type="text" name="sc[SITE_TITLE]" label="Site Title"|gettext value=$smarty.const.SITE_TITLE}
					{control type="text" name="sc[SITE_HEADER]" label="Site Header"|gettext value=$smarty.const.SITE_HEADER}
                    {control type="checkbox" postfalse=1 name="sc[SEF_URLS]" label="Search Engine Friendly URLs?"|gettext checked=$smarty.const.SEF_URLS value=1}
					{control type="checkbox" postfalse=1 name="sc[ADVERTISE_RSS]" label="Advertise RSS Feeds to Web Browsers?"|gettext checked=$smarty.const.ADVERTISE_RSS value=1}
                    {control type="checkbox" postfalse=1 name="sc[SKIP_VERSION_CHECK]" label="Skip Automatic Online Version Update Check?"|gettext checked=$smarty.const.SKIP_VERSION_CHECK value=1 description='You can still check for an updated version using the Tienda, Super-Admin Tools menu'|gettext}
                    {control type="dropdown" name="sc[SITE_DEFAULT_SECTION]" label="Default Section (Home Page)"|gettext items=$section_dropdown default=$smarty.const.SITE_DEFAULT_SECTION}
                    {control type="textarea" name="sc[SITE_KEYWORDS]" label='('|cat:('Meta'|gettext)|cat:') '|cat:('Keywords'|gettext) value=$smarty.const.SITE_KEYWORDS description='Comma separated phrases'|gettext}
	                {control type="textarea" name="sc[SITE_DESCRIPTION]" label='('|cat:('Meta'|gettext)|cat:') '|cat:('Description'|gettext) value=$smarty.const.SITE_DESCRIPTION}
                </div>
                <div id="tab2">
	                <div class="info-header">
                
		                <h2>{"Anti-Spam Measures"|gettext}</h2>
                    </div>
                    {control type="checkbox" postfalse=1 name="sc[SITE_USE_ANTI_SPAM]" label="Use Anti-Spam measures?"|gettext checked=$smarty.const.SITE_USE_ANTI_SPAM value=1}
                    {control type="checkbox" postfalse=1 name="sc[ANTI_SPAM_USERS_SKIP]" label="Skip using Anti-Spam measures for Logged-In Users?"|gettext checked=$smarty.const.ANTI_SPAM_USERS_SKIP value=1}
                    {control type="dropdown" name="sc[ANTI_SPAM_CONTROL]" label="Anti-Spam Method"|gettext items=$as_types default=$smarty.const.ANTI_SPAM_CONTROL}
                    <blockquote>
	                {'To obtain the reCAPTCHA \'keys\', you\'ll need to first have a'|gettext} <a href="http://www.google.com/" target="_blank">{"Google account"|gettext}</a> {"to log in, then setup up a reCAPTCHA account for your domain(s)"|gettext} <a href="http://www.google.com/recaptcha/whyrecaptcha" target="_blank">{"here"|gettext}</a>
                    </blockquote>
                    {control type="dropdown" name="sc[RECAPTCHA_THEME]" label="re-Captcha Theme"|gettext items=$as_themes default=$smarty.const.RECAPTCHA_THEME}
                    {control type="text" name="sc[RECAPTCHA_PUB_KEY]" label="reCAPTCHA Public Key"|gettext value=$smarty.const.RECAPTCHA_PUB_KEY}
                    {control type="text" name="sc[RECAPTCHA_PRIVATE_KEY]" label="reCAPTCHA Private Key"|gettext value=$smarty.const.RECAPTCHA_PRIVATE_KEY}
                </div>
                <div id="tab3">
	                <div class="info-header">
               
		                <h2>{"User Registration"|gettext}</h2>
                    </div>
                    {control type="checkbox" postfalse=1 name="sc[SITE_ALLOW_REGISTRATION]" label="Allow users to create accounts for themselves"|gettext checked=$smarty.const.SITE_ALLOW_REGISTRATION value=1}
                    {control type="checkbox" postfalse=1 name="sc[USER_REGISTRATION_USE_EMAIL]" label="Use an email address instead of a username"|gettext checked=$smarty.const.USER_REGISTRATION_USE_EMAIL value=1}
                    {control type="checkbox" postfalse=1 name="sc[USER_NO_PASSWORD_CHANGE]" label="Disable User Request Password Change Feature"|gettext checked=$smarty.const.USER_NO_PASSWORD_CHANGE value=1}
                    {group label="New User Notification Email"|gettext}
                        {control type="checkbox" postfalse=1 name="sc[USER_REGISTRATION_SEND_NOTIF]" label="Notify a site administrator when a new user registers on your website"|gettext checked=$smarty.const.USER_REGISTRATION_SEND_NOTIF value=1}
                        {control type="text" name="sc[USER_REGISTRATION_NOTIF_SUBJECT]" label='Subject of the administrator\'s new user notification'|gettext value=$smarty.const.USER_REGISTRATION_NOTIF_SUBJECT}
                        {control type=email name="sc[USER_REGISTRATION_ADMIN_EMAIL]" label="Email address of administrator that should be notified when a user signs up"|gettext value=$smarty.const.USER_REGISTRATION_ADMIN_EMAIL}
                    {/group}
                    {group label="New User Welcome Message"|gettext}
                        {control type="checkbox" postfalse=1 name="sc[USER_REGISTRATION_SEND_WELCOME]" label="Send an email to the user after registering?"|gettext checked=$smarty.const.USER_REGISTRATION_SEND_WELCOME value=1}
                        {control type="text" name="sc[USER_REGISTRATION_WELCOME_SUBJECT]" label="Welcome Email Subject"|gettext value=$smarty.const.USER_REGISTRATION_WELCOME_SUBJECT}
                        {control type="textarea" name="sc[USER_REGISTRATION_WELCOME_MSG]" label="Welcome Email Content"|gettext value=$smarty.const.USER_REGISTRATION_WELCOME_MSG}
                    {/group}
                    
                </div>
                <div id="tab4">
	                <div class="info-header">
                   
		                <h2>{"User Comment Policies"|gettext}</h2>
                    </div>
                    {control type="checkbox" postfalse=1 name="sc[COMMENTS_REQUIRE_LOGIN]" label="Require User Login to Post Comments?"|gettext checked=$smarty.const.COMMENTS_REQUIRE_LOGIN value=1}
                    {control type="checkbox" postfalse=1 name="sc[COMMENTS_REQUIRE_APPROVAL]" label="All Comments Must be Approved?"|gettext checked=$smarty.const.COMMENTS_REQUIRE_APPROVAL value=1}
                    {group label="New Comment Notification Email"|gettext}
                        {control type="checkbox" postfalse=1 name="sc[COMMENTS_REQUIRE_NOTIFICATION]" label="Notify a site administrator of New Comments?"|gettext checked=$smarty.const.COMMENTS_REQUIRE_NOTIFICATION value=1}
                        {control type=email multiple="1" name="sc[COMMENTS_NOTIFICATION_EMAIL]" label="Email address(es) that should be notified of New Comments"|gettext description="Enter multiple addresses by using a comma to separate them"|gettext value=$smarty.const.COMMENTS_NOTIFICATION_EMAIL}
                    {/group}
                </div>

                {if $user->is_admin==1}
                <div id="tab7">
	                <div class="info-header">
                
		                <h2>{"Mail Server Settings"|gettext}</h2>
                    </div>
                    {control type=email name="sc[SMTP_FROMADDRESS]" label="From Address"|gettext value=$smarty.const.SMTP_FROMADDRESS description='This MUST be in a valid email address format or sending mail may fail!'|gettext}
                    {control type="checkbox" postfalse=1 name="sc[SMTP_USE_PHP_MAIL]" label='Use simplified php mail() function instead of SMTP?'|gettext checked=$smarty.const.SMTP_USE_PHP_MAIL value=1}
	                ({"or"|gettext})
                    {group label="SMTP Server Settings"|gettext}
                        {control type="text" name="sc[SMTP_SERVER]" label="SMTP Server"|gettext value=$smarty.const.SMTP_SERVER}
                        {control type="text" name="sc[SMTP_PORT]" label="SMTP Port"|gettext value=$smarty.const.SMTP_PORT}
                        {control type="dropdown" name="sc[SMTP_PROTOCOL]" label="Type of Encrypted Connection"|gettext items=$protocol default=$smarty.const.SMTP_PROTOCOL includeblank="None"}
                        {control type="text" name="sc[SMTP_USERNAME]" label="SMTP Username"|gettext value=$smarty.const.SMTP_USERNAME}
                        {control type="password" name="sc[SMTP_PASSWORD]" label="SMTP Password"|gettext value=$smarty.const.SMTP_PASSWORD}
                        {control type="checkbox" postfalse=1 name="sc[SMTP_DEBUGGING]" label="Turn On SMTP Debugging?"|gettext checked=$smarty.const.SMTP_DEBUGGING value=1}
                    {/group}
                </div>
                <div id="tab8">
	                <div class="info-header">
            
		                <h2>{"Site Maintenance Mode Settings"|gettext}</h2>
                    </div>
                    {control type="checkbox" postfalse=1 name="sc[MAINTENANCE_MODE]" label="Place Site in Maintenance Mode?"|gettext checked=$smarty.const.MAINTENANCE_MODE value=1}
                    {control type="html" name="sc[MAINTENANCE_MSG_HTML]" label="Maintenance Mode Message"|gettext value=$smarty.const.MAINTENANCE_MSG_HTML}
                </div>
                <div id="tab9">
	                <div class="info-header">
        
		                <h2>{"Security Settings"|gettext}</h2>
                    </div>
                    {control type="checkbox" postfalse=1 name="sc[SESSION_TIMEOUT_ENABLE]" label="Enable Session Timeout?"|gettext checked=$smarty.const.SESSION_TIMEOUT_ENABLE value=1}
                    {control type="text" name="sc[SESSION_TIMEOUT]" label="Session Timeout in seconds"|gettext value=$smarty.const.SESSION_TIMEOUT}
                    {control type="dropdown" name="sc[FILE_DEFAULT_MODE_STR]" label="Default File Permissions"|gettext items=$file_permisions default=$smarty.const.FILE_DEFAULT_MODE_STR}
                    {control type="dropdown" name="sc[DIR_DEFAULT_MODE_STR]" label="Default Directory Permissions"|gettext items=$dir_permissions default=$smarty.const.DIR_DEFAULT_MODE_STR}
                    {control type="checkbox" postfalse=1 name="sc[ENABLE_SSL]" label="Enable SSL (https://) Support?"|gettext checked=$smarty.const.ENABLE_SSL value=1}
                    {*{control type="text" name="sc[NONSSL_URL]" label="Non-SSL URL Base"|gettext value=$smarty.const.NONSSL_URL}*}
                    {*{control type="text" name="sc[SSL_URL]" label="SSL URL Base"|gettext value=$smarty.const.SSL_URL}*}
                </div>
            
        
                <div id="tab12">
	                <div class="info-header">
                   
		                <h2>{"Error Messages"|gettext}</h2>
                    </div>
                    {control type="text" name="sc[SITE_404_TITLE]" label='Page Title For \'Not Found\' (404) Error'|gettext value=$smarty.const.SITE_404_TITLE}
                    {control type="html" name="sc[SITE_404_HTML]" label='\'Not Found\' (404) Error Message'|gettext value=$smarty.const.SITE_404_HTML}
                    {control type="html" name="sc[SITE_403_REAL_HTML]" label='\'Access Denied\' (403) Error Message'|gettext value=$smarty.const.SITE_403_REAL_HTML}
                    {control type="html" name="sc[SESSION_TIMEOUT_HTML]" label='\'Session Expired\' Error  Message'|gettext value=$smarty.const.SESSION_TIMEOUT_HTML}
                </div>
        
			
				<div id="tab15">
                    <div class="info-header">
                        <div class="related-actions">
                            {help text="Get Help with"|gettext|cat:" "|cat:("search report settings"|gettext) module="search-report-settings"}
                        </div>
                        <h2>{"Search Report Configuration"|gettext}</h2>
                    </div>
                    {control type="checkbox" postfalse=1 name="sc[SAVE_SEARCH_QUERIES]" label="Save Search Queries?"|gettext checked=$smarty.const.SAVE_SEARCH_QUERIES value=1}
					{control type="text" name="sc[TOP_SEARCH]" label="Number of Top Search Queries to Return"|gettext value=$smarty.const.TOP_SEARCH}
					{control type="checkbox" postfalse=1 name="sc[INCLUDE_AJAX_SEARCH]" label="Include ajax search in reports?"|gettext checked=$smarty.const.INCLUDE_AJAX_SEARCH value=1}
					{control type="checkbox" postfalse=1 name="sc[INCLUDE_ANONYMOUS_SEARCH]" label="Include unregistered users search?"|gettext checked=$smarty.const.INCLUDE_ANONYMOUS_SEARCH value=1}
				</div>
              
                {/if}
            </div>
        </div>
	    <div class="loadingdiv">{"Loading Site Configuration"|gettext}</div>
        {control type="buttongroup" submit="Save Website Configuration"|gettext cancel="Cancel"|gettext returntype="viewable"}
    {/form}
</div>

{script unique="`$config`" yui3mods=1}
{literal}
    EXPONENT.YUI3_CONFIG.modules.exptabs = {
        fullpath: EXPONENT.JS_RELATIVE+'exp-tabs.js',
        requires: ['history','tabview','event-custom']
    };

	YUI(EXPONENT.YUI3_CONFIG).use('exptabs', function(Y) {
        Y.expTabs({srcNode: '#{/literal}{$config}{literal}'});
        Y.one('#{/literal}{$config}{literal}').removeClass('hide');
        Y.one('.loadingdiv').remove();
	});

    function changeProfile(val) {
        if (confirm('{/literal}{'Are you sure you want to load a new profile?'|gettext}{literal}')) {
            window.location = EXPONENT.PATH_RELATIVE+"administration/change_profile/profile/" + val;
        } else {
            document.getElementById("profiles").value = '';
        }
    }

    function saveProfile() {
        if (document.getElementById("profile_name").value != '') {
            if (confirm('{/literal}{'Are you sure you want to save this configuration profile?'|gettext}{literal}')) {
                window.location = EXPONENT.PATH_RELATIVE+"administration/save_profile/profile/" + document.getElementById("profile_name").value;
            }
        }
    }
{/literal}
{/script}

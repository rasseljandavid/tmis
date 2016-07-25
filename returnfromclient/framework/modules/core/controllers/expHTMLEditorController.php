<?php
##################################################
#
# Copyright (c) 2004-2013 OIC Group, Inc.
#
# This file is part of Tienda
#
# Tienda is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################

/**
 * This is the class expHTMLEditorController
 *
 * @package Core
 * @subpackage Controllers
 */

class expHTMLEditorController extends expController {
    static function displayname() { return gt("Editors"); }
    static function description() { return gt("Mostly for CKEditor"); }
    static function author() { return "Phillip Ball"; }
    static function hasSources() { return false; }
    static function hasContent() { return false; }
	protected $add_permissions = array(
        'activate'=>"Activate",
        'preview'=>"Preview CKEditor Toolbars"
    );
    
    function manage () {
        global $db;

        if (SITE_WYSIWYG_EDITOR=="FCKeditor") {
	        flash('error',gt('FCKeditor is deprecated!'));
	        redirect_to(array("module"=>"administration","action"=>"configure_site"));
        }

        // otherwise, on to cke
        $configs = $db->selectObjects('htmleditor_ckeditor',1);
        
        assign_to_template(array(
            'configs'=>$configs
        ));
    }

    function update () {
        global $db;

//        $obj = $db->selectObject('htmleditor_ckeditor',"id=".$this->params['id']);
        $obj = self::getEditorSettings($this->params['id']);
        $obj->name = $this->params['name'];
        $obj->data = stripSlashes($this->params['data']);
        $obj->skin = $this->params['skin'];
        $obj->scayt_on = $this->params['scayt_on'];
        $obj->paste_word = $this->params['paste_word'];
        $obj->plugins = stripSlashes($this->params['plugins']);
        $obj->stylesset = stripSlashes($this->params['stylesset']);
        $obj->formattags = stripSlashes($this->params['formattags']);
        $obj->fontnames = stripSlashes($this->params['fontnames']);
        if (empty($this->params['id'])) {
            $this->params['id'] = $db->insertObject($obj,'htmleditor_ckeditor');
        } else {
            $db->updateObject($obj,'htmleditor_ckeditor',null,'id');
        }
		if ($this->params['active']) {
			$this->activate();
		}
	    expHistory::returnTo('manageable');
    }

    function edit() {
        global $db;

        expHistory::set('editable', $this->params);
//        $tool = @$db->selectObject('htmleditor_ckeditor',"id=".$this->params['id']);
        $tool = self::getEditorSettings($this->params['id']);
        $tool->data = !empty($tool->data) ? @stripSlashes($tool->data) : '';
        $tool->plugins = !empty($tool->plugins) ? @stripSlashes($tool->plugins) : '';
        $tool->stylesset = !empty($tool->stylesset) ? @stripSlashes($tool->stylesset) : '';
        $tool->formattags = !empty($tool->formattags) ? @stripSlashes($tool->formattags) : '';
        $tool->fontnames = !empty($tool->fontnames) ? @stripSlashes($tool->fontnames) : '';
        $skins_dir = opendir(BASE.'external/editors/ckeditor/skins');
        while (($skin = readdir($skins_dir)) !== false) {
            if ($skin != '.' && $skin != '..')
                $skins[] = $skin;
        }
        assign_to_template(array(
            'record'=>$tool,
            'skins'=>$skins
        ));
    }
    
	function delete() {
	    global $db;

	    expHistory::set('editable', $this->params);
	    @$db->delete('htmleditor_ckeditor',"id=".$this->params['id']);
		expHistory::returnTo('manageable');
	}

    function activate () {
        global $db;
        
        $db->toggle('htmleditor_ckeditor',"active",'active=1');
        if ($this->params['id']!="default") {
//            $active = $db->selectObject('htmleditor_ckeditor',"id=".$this->params['id']);
            $active = self::getEditorSettings($this->params['id']);
            $active->active = 1;
            $db->updateObject($active,'htmleditor_ckeditor',null,'id');
        }
	    expHistory::returnTo('manageable');
    }

    function preview () {
        global $db;

        if ($this->params['id']==0) {  // we want the default editor
            $demo = new stdClass();
            $demo->id=0;
            $demo->name="Default";
			$demo->skin='kama';
        } else {
//            $demo = $db->selectObject('htmleditor_ckeditor',"id=".$this->params['id']);
            $demo = self::getEditorSettings($this->params['id']);
        }
        assign_to_template(array(
            'demo'=>$demo
        ));
    }

    public static function getEditorSettings($settings_id) {
        global  $db;

        return @$db->selectObject('htmleditor_ckeditor',"id=".$settings_id);
    }

    public static function getActiveEditorSettings() {
        global  $db;

        return $db->selectObject('htmleditor_ckeditor', 'active=1');
    }

}

?>

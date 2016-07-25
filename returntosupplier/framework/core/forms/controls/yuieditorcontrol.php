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
/** @define "BASE" "../../../../.." */

if (!defined('EXPONENT')) exit('');

/**
 * YUI Text Editor Control
 *
 * @package Subsystems-Forms
 * @subpackage Control
 */
class yuieditorcontrol extends formcontrol {

	var $cols = 60;
	var $rows = 20;
	
	static function name() { return "YUI HTML Editor"; }
	static function getFieldDefinition() {
		return array(
			DB_FIELD_TYPE=>DB_DEF_STRING,
			DB_FIELD_LEN=>10000);
	}
	
	function __construct($default="",$rows = 20,$cols = 60) {
		$this->default = $default;
		$this->rows = $rows;
		$this->cols = $cols;
		$this->required = false;
		$this->maxchars = 0;
	}

	function controlToHTML($name,$label) {
//		$html  = '<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.5.2/build/editor/assets/skins/sam/simpleeditor.css" />';
		$html  = '<link rel="stylesheet" type="text/css" href="'.YUI2_RELATIVE.'assets/skins/sam/simpleeditor.css" />';
		$html .= '<div class="yui-skin-sam"><textarea name="'.$name.'" id="'.$name.'"';
		$html .= " rows=\"" . $this->rows . "\" cols=\"" . $this->cols . "\"";
		if ($this->accesskey != "") $html .= " accesskey=\"" . $this->accesskey . "\"";
		if (!empty($this->class)) $html .= " class=\"" . $this->class . "\"";
		if ($this->tabindex >= 0) $html .= " tabindex=\"" . $this->tabindex . "\"";
		if (@$this->required) {
			$html .= ' required="'.rawurlencode($this->default).'" caption="'.rawurlencode($this->caption).'" ';
		}
		$html .= ">";
		$html .= $this->default;
		$html .= "</textarea></div>";
//FIXME convert to yui3
		$script = "
		(function() {
    			var Dom = YAHOO.util.Dom,
        		Event = YAHOO.util.Event;
    
    			var myConfig = {
        			height: '95%',
        			width: '530px',
        			dompath: true,
				handleSubmit: true,
				autoHeight: true
    			};

    			YAHOO.log('Create the Editor..', 'info', 'example');
    			var myEditor = new YAHOO.widget.SimpleEditor('".$name."', myConfig);
    			myEditor.render();

		})();";
		
		expJavascript::pushToFoot(array(
		    "unique"=>'editor-'.$name,
		    "yui2mods"=>'editor',
//		    "yui3mods"=>null,
		    "content"=>$script,
		    "src"=>""
		 ));
		return $html;
	}
	
	static function form($object) {

		$form = new form();
		
		if (!isset($object->identifier)) {
			$object->identifier = "";
			$object->caption = "";
			$object->default = "";
			$object->rows = 20;
			$object->cols = 60;
			$object->maxchars = 0;
		} 
		$form->register("identifier",gt('Identifier/Field'),new textcontrol($object->identifier));
		$form->register("caption",gt('Caption'), new textcontrol($object->caption));
		$form->register("default",gt('Default'),  new texteditorcontrol($object->default));
		$form->register("rows",gt('Rows'), new textcontrol($object->rows,4,false,3,"integer"));
		$form->register("cols",gt('Columns'), new textcontrol($object->cols,4, false,3,"integer"));
		$form->register("submit","",new buttongroupcontrol(gt('Save'),'',gt('Cancel'),"",'editable'));
		return $form;
	}
	
	static function update($values, $object) {
		if ($object == null) $object = new texteditorcontrol();
		if ($values['identifier'] == "") {
			$post = $_POST;
			$post['_formError'] = gt('Identifier is required.');
			expSession::set("last_POST",$post);
			return null;
		}
		$object->identifier = $values['identifier'];
		$object->caption = $values['caption'];
		$object->default = $values['default'];
		$object->rows = intval($values['rows']);
		$object->cols = intval($values['cols']);
		$object->maxchars = intval($values['maxchars']);
		$object->required = isset($values['required']);
		
		return $object;
	
	}
	
	static function parseData($original_name,$formvalues,$for_db = false) {
		return str_replace(array("\r\n","\n","\r"),'<br />', htmlspecialchars($formvalues[$original_name])); 
	}
	
}

?>

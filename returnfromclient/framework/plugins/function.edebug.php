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
 * Smarty plugin
 * @package Smarty-Plugins
 * @subpackage Function
 */

/**
 * Smarty {edebug} function plugin
 *
 * Type:     function<br>
 * Name:     edebug<br>
 * Purpose:  dump a variable
 *
 * @param         $params
 * @param \Smarty $smarty
 */
function smarty_function_edebug($params,&$smarty) {
    eDebug($params['var']);
}

?>

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
 * @subpackage Modifier
 */

/**
 * Smarty {relative_date} modifier plugin
 * Type:     modifier<br>
 * Name:     relative_date<br>
 * Purpose:  convert a date in a relative format
 *
 * @param        array
 *
 * @return array
 */
function smarty_modifier_relative_date($timestamp) {
    return expDateTime::relativeDate($timestamp);
}

?>
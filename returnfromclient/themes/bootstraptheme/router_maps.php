<?php
##################################################
#
# Copyright (c) 2004-2008 OIC Group, Inc.
# Written and Designed by Adam Kessler
#
# This file is part of Exponent
#
# Exponent is free software; you can redistribute
# it and/or modify it under the terms of the GNU
# General Public License as published by the Free
# Software Foundation; either version 2 of the
# License, or (at your option) any later version.
#
# GPL: http://www.gnu.org/licenses/gpl.txt
#
##################################################
$maps = array();



$maps[] = array('controller'=>'store',
        'action'=>'showall',
        'url_parts'=>array(     
	           'controller'=>'grocery',
                'title'=>'(.*)'),
);

$maps[] = array('controller'=>'store',
        'action'=>'show',
        'url_parts'=>array(    
	            'controller'=>'product',
                'title'=>'(.*)'),
);

?>
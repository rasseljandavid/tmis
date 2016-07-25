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
 * @subpackage Models
 * @package Core
 */
class promocodes extends expRecord {
    public $table = 'promocodes';
    public $validates = array(
        'presence_of'=>array(
            'title'=>array('message'=>'You must give this code a title.'),
            'promo_code'=>array('message'=>'You must create a promo code.'),
            'discount_id'=>array('message'=>'You must select a discount')
        ),
        'uniqueness_of'=>array(
            'promo_code'=>array('message'=>'Promo code must be unique.'),
        )
   );
}

?>
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
class billingcalculator extends expRecord {
    public $table = 'billingcalculator';
    public function captureEnabled() {return false; }
    public function isRestricted() { return false; }
    
    public function __construct($params=null, $get_assoc=true, $get_attached=true) {        
        parent::__construct($params, $get_assoc, $get_attached);
        
        // set the calculator
        if (!empty($this->calculator_name)) $this->calculator = new $this->calculator_name();
        
        // grab the config data for this calculator
        $this->configdata = empty($this->config) ? array() : unserialize($this->config);
    }

    function createBillingTransaction($method,$amount,$result,$trax_state)
    {
        global $order, $db, $user;
        
        $bt = new billingtransaction();
        $bt->billingmethods_id = $method->id;
        $bt->billingcalculator_id = $method->billingcalculator_id;
        $bt->billing_cost = $amount;
        $bt->billing_options  = serialize($result);
        $bt->extra_data = '';
        $bt->transaction_state = $trax_state;
        //$bt->result = $result;    
        $bt->save();
    }
    
    function postProcess($order,$params)
    {
         return true;
    }

    /**
     * Return default billing calculator
     *
     */
    public static function getDefault() {
        global $db;

        $calc = $db->selectObject('billingcalculator','is_default=1');
        if (empty($calc)) $calc = $db->selectObject('billingcalculator','enabled=1');
        if ($calc->id) return $calc->id;
        else return false;
    }

}

?>
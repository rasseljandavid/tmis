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
class shipping extends expRecord {
	public $table = 'shipping';
	public $available_calculators = array();
	public $calculator = null;
	public $shippingmethod = null;
	public $options = null;
	public $available_options = null;
	public $address = null;
	public $splitshipping = false;
	public $forced_shipping = false;
	
	public function __construct() {
        global $order, $user;

        if (empty($order->id)) return false;
        
        $existing_shippingmethods = $order->getShippingMethods();        
        $this->available_calculators = $this->listAvailableCalculators();
        $this->selectable_calculators = $this->selectableCalculators();
        
        if (count($existing_shippingmethods) == 1) {
            if ($order->forcedShipping()) {
                $this->shippingmethod = $order->getForcedShippingMethod();
                $this->forced_shipping = true;
            } else {
                $this->shippingmethod = $order->getCurrentShippingMethod();
            }        
            
            // if this shippingmethod doesn't have an address assigned to it, lets check and see if this
            // user has set one up yet and default to that if so
            //if (empty($this->shippingmethod->addresses_id) && $user->isLoggedIn()) {            
            if ($user->id !=0) {            
                $address = new address();
                $addy = $address->find('first', 'user_id='.$user->id.'  AND is_shipping=1');
                if (empty($addy->id)) $addy = $address->find('first', 'user_id='.$user->id);
                if (!empty($addy->id)) $this->shippingmethod->setAddress($addy);                
            }                                                                     
            $this->address = new address($this->shippingmethod->addresses_id);
            
            $number_of_calculators = count($this->available_calculators);
            if ($number_of_calculators == 1 || empty($this->shippingmethod->shippingcalculator_id)) {
                $calcid = key($this->available_calculators);
                if ($this->shippingmethod->shippingcalculator_id != $calcid) {
                    $this->shippingmethod->update(array('shippingcalculator_id'=>$calcid));
                }
            } 
                                                      
            if (!empty($this->available_calculators) && !empty($this->shippingmethod->shippingcalculator_id)) {
                if(isset($this->available_calculators[$this->shippingmethod->shippingcalculator_id]))
                {
                    $calcname = $this->available_calculators[$this->shippingmethod->shippingcalculator_id];                
                }
                else
                {
                    //recently reconfigured/disabled shipping calc that was already set in the object, so default to the first one available
                    $key = @array_shift(array_keys($this->available_calculators));
                    $calcname = $this->available_calculators[$key];      
                    $this->shippingmethod->shippingcalculator_id = $key;                             
                }                              
                $this->calculator = new $calcname($this->shippingmethod->shippingcalculator_id);
            } else {
                $this->calculator = null;                
            }                                
//            $this->getRates();  //FIXME,  we don't really need to call it each time the shipping model is created! slows entire system down!
        } else {
            eDebug($this);
            eDebug($order);
            eDebug("Error in shipping constuctor.", true) ;
            //NO split shipping for now
            /*$this->splitshipping = true;
            $this->splitmethods = array();
            foreach ($existing_shippingmethods as $smid) {
                $method = new shippingmethod($smid);
                if ($method->requiresShipping()) {
                    $this->splitmethods[$method->id] = $method;
                    $this->splitmethods[$method->id]->orderitem = $order->getOrderitemsByShippingmethod($method->id);
                }
                
            } */
        }
    }
	
	public function getRates() {
	    global $order;
        
	    if (!empty($this->calculator->id) && (!empty($this->shippingmethod->addresses_id) || !$this->calculator->addressRequired())) {	
		    $this->pricelist = $this->calculator->getRates($order);
		} else {
		    $this->pricelist = array();
		}

		// if the user hasn't selected a shipping option yet we will default one for him now.
		if ((!empty($this->shippingmethod->id) && (is_array($this->pricelist) && (count($this->pricelist) > 0)))) { 
		    if(empty($this->shippingmethod->option)) {
		        $opt = current($this->pricelist);
		        $this->shippingmethod->update(array('option'=>$opt['id'],'option_title'=>$opt['title'],'shipping_cost'=>$opt['cost'])); //updates SECOND created shipping method w/ rates, as that was the one set to $this->shippingmethod
		    } else {                       
		        if ($this->shippingmethod->shipping_cost != $this->pricelist[$this->shippingmethod->option]['cost']) {                    
		            $opt = !empty($this->pricelist[$this->shippingmethod->option]) ? $this->pricelist[$this->shippingmethod->option] : '';
		            $this->shippingmethod->update(array('option'=>$opt['id'],'option_title'=>$opt['title'],'shipping_cost'=>$opt['cost']));
		        }
		    }
		}		
		//return $pricelist;
	}
	
	public function listAvailableCalculators() {
	    global $db;
	    $calcs = array();
	    foreach ($db->selectObjects('shippingcalculator', 'enabled=1') as $calc) {
	        $calcs[$calc->id] = $calc->calculator_name;
	    }
	    
		return $calcs;
    }
    
    public static function listAllCalculators() {
	    global $db;
	    $calcs = array();
	    foreach ($db->selectObjects('shippingcalculator') as $calc) {
	        $calcs[$calc->id] = $calc->calculator_name;
	    }
	    
		return $calcs;
    }
    
    public function selectableCalculators() {
	    global $db;
	    $calcs = array();
	    foreach ($db->selectObjects('shippingcalculator', 'enabled=1') as $calc) {
	        $calcs[$calc->id] = $calc->title;
	    }
	    
		return $calcs;
    }
    
    static function estimateShipping($order)
    {
        $c = new shippingcalculator();
        $calc = $c->find('first',"enabled=1 AND is_default=1");
        if (!empty($calculator)) {
            $calcName = $calc->calculator_name;
            $calculator = new $calcName();
            if($calculator->addressRequired()) {
                global $user;
                //FIXME we need to get current address here
                if (!empty($order->shippingmethod->addresses_id)) {
                    $rates = $calculator->getRates($order);
                    return $rates['01']['cost'];
                }
    //            return 0;
                return '-';
            } else {
                $rates = $calculator->getRates($order);
                return $rates['01']['cost'];
            }
        } else {
            return '-';
        }
    }
}

?>
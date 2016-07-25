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
 * @subpackage Calculators
 * @package    Modules
 */
/** @define "BASE" "../../../.." */

class paylater extends billingcalculator {
    function name() {
        return gt("Cash on Delivery");
    }

    function description() {
        return gt("Enabling this payment option will allow your customers to pay when the purchase was been delivered.");
    }

    public $title = 'Cash on Delivery';
    public $payment_type = 'Billed';

    function hasConfig() {
        return false;
    }

    function hasUserForm() {
        return false;
    }

    function isOffsite() {
        return false;
    }

    function isSelectable() {
        return true;
    }

    //Called for billing method selection screen, return true if it's a valid billing method.
    function preprocess($method, $opts, $params, $order) {
        if ($opts->cash_amount < $order->grand_total) $opts->payment_due = $order->grand_total - $opts->cash_amount;
        //just save the opts
        $method->update(array('billing_options' => serialize($opts)));
    }

//    function process($method, $opts, $params, $invoice_number) {
    function process($method, $opts, $params, $order) {
//        global $order, $db, $user;

        $object = new stdClass();
        $object->errorCode = $opts->result->errorCode = 0;
//        $opts->result = $object;
        $opts->result->payment_status = gt("complete");
        if ($opts->cash_amount < $order->grand_total) $opts->result->payment_status = gt("payment due");
//        $method->update(array('billing_options' => serialize($opts),'transaction_state'=>$opts->result, $opts->result->payment_status));
        $method->update(array('billing_options' => serialize($opts), 'transaction_state' => $opts->result->payment_status));
        $this->createBillingTransaction($method, number_format($order->grand_total, 2, '.', ''), $opts->result, $opts->result->payment_status);
        return $opts;
    }

    function userForm($config_object = null, $user_data = null) {
        $form = '<h4>' . gt('Cash on Delivery.') . '</h4>';

        $cash_amount = new hiddenfieldcontrol(0, 20, false, 20, "money", true);
        $cash_amount->filter = 'money';
        $cash_amount->id = "cash_amount";
		$form .= $cash_amount->toHTML(gt("Cash Amount"), "cash_amount");

		$delivery_slot = new hiddenfieldcontrol();
        $delivery_slot->class = "delivery_slot";
		$form .= $delivery_slot->toHTML(gt("Delivery Slot"), "delivery_slot");
	$delivery_date = new hiddenfieldcontrol();
	        $delivery_date->class = "delivery_date";
			$form .= $delivery_date->toHTML(gt("Delivery Date"), "delivery_date");
        return $form;
    }

    //Should return html to display user data.
    function userView($opts) {
        if (empty($opts)) return false;
        $cash = !empty($opts->cash_amount) ? $opts->cash_amount : 0;
        if (!empty($opts->payment_due)) {
            $billinginfo = '<br>' . gt('Total') . ': ' . expCore::getCurrencySymbol() . number_format($opts->payment_due, 2, ".", ",");
        }
        return $billinginfo;
    }

    function userFormUpdate($params) {
//        global $order;

        if (substr($params['cash_amount'], 0, strlen(expCore::getCurrencySymbol())) == expCore::getCurrencySymbol()) {
            $params['cash_amount'] = substr($params['cash_amount'], strlen(expCore::getCurrencySymbol()));
        }
        // force full payment prior to checkout
//        if (expUtil::isNumberGreaterThan($order->grand_total, floatval($params["cash_amount"]), 2)) {
//            expValidator::failAndReturnToForm(gt("The total amount of your order is greater than the amount you have input.") . "<br />" . gt("Please enter exact or greater amount of your total."));
//        }
        $this->opts = new stdClass();
        $this->opts->cash_amount = $params["cash_amount"];
        return $this->opts;
    }

    function getPaymentAuthorizationNumber($billingmethod) {
        $ret = expUnserialize($billingmethod->billing_options);
        return $ret->result->token;
    }

    function getPaymentReferenceNumber($opts) {
        $ret = expUnserialize($opts);

        if (isset($ret->result)) {
            return $ret->result->transId;
        } else {
            return $ret->transId;
        }
    }

    function getPaymentStatus($billingmethod) {
        $ret = expUnserialize($billingmethod->billing_options);
        return $ret->result->payment_status;
    }

    function getPaymentMethod($billingmethod) {
        return $this->title;
    }

    function showOptions() {
        return;
    }

    function getAVSAddressVerified($billingmethod) {
        return '';
    }

    function getAVSZipVerified($billingmethod) {
        return '';
    }

    function getCVVMatched($billingmethod) {
        return '';
    }
}

?>
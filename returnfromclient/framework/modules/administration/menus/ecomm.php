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

if (!defined('EXPONENT')) exit('');

global $user, $db;

$active = ECOM;
if (!$user->isAdmin() || empty($active)) return false;

//$new_orders = $db->countObjects('orders', 'purchased !=0 AND order_status_id = 1');  //FIXME order_status_id of 1 isn't always true
$new_status = $db->selectValue('order_status', 'id', 'is_default = 1');
$new_orders = $db->countObjects('orders', 'purchased !=0 AND order_status_id = ' . $new_status);
if ($new_orders > 0) {
    $newo = '<em class="newalert">' . $new_orders . ' ' . gt('new') . '</em>';
} else {
    $newo = '';
};

$ecom = array(
    'text'      => gt('e-Commerce') . $newo . '<form id="orderQuickfinder" method="POST" action="' . PATH_RELATIVE . 'index.php" enctype="multipart/form-data"><input type="hidden" name="controller" value="order"><input type="hidden" name="action" value="quickfinder"><input style="padding-top: 3px;" type="text" name="ordernum" id="ordernum" size="25" placeholder="' . gt("Order Quickfinder") . '"></form>',
    'classname' => 'ecom',
    'submenu'   => array(
        'id'       => 'ecomm',
        'itemdata' => array(
            array(
                'text' => gt("Dashboard"),
                'classname' => 'dashboard',
                'url'  => makeLink(array('controller' => 'report', 'action' => 'dashboard')),
            ),
            array(
                'text'    => gt("Orders"),
                'classname' => 'orders',
                'submenu' => array(
                    'id'       => 'ordermenu',
                    'itemdata' => array(
                        array(
                            'text' => gt("View Orders") . " <em>(" . $new_orders . "  " . gt("New Orders") . ")",
                            'classname' => 'search',
                            'url'  => makeLink(array('controller' => 'order', 'action' => 'showall')),
                        ),
                        array(
                            'text' => gt("Create Order"),
                            'classname' => 'add',
                            'url'  => makeLink(array('controller' => 'order', 'action' => 'create_new_order')),
                        ),
/*
                        array(
                            'text' => gt("Batch Process Orders"),
                            'classname' => 'config',
                            'url'  => makeLink(array('controller' => 'store', 'action' => 'batch_process')),
                        ),
*/
                        array(
                            'text'      => gt("Manage Order Status"),
                            'classname' => 'manage',
                            'url'       => makeLink(array('controller' => 'order_status', 'action' => 'manage')),
                        ),
                      
                        array(
                            'text'      => gt("Manage Order Types"),
                            'classname' => 'manage',
                            'url'       => makeLink(array('controller' => 'order_type', 'action' => 'manage')),
                        ),
                    ),
                ),
            ),
            array(
                'text'    => gt("Products"),
                'classname' => 'products',
                'submenu' => array(
                    'id'       => 'prodscats',
                    'itemdata' => array(
                        array(
                            'text'      => gt("Add a Product"),
                            'classname' => 'add',
                            'url'       => makeLink(array('controller' => 'store', 'action' => 'create')),
                        ),
                        array(
                            'text'      => gt("Manage Products"),
                            'classname' => 'manage',
                            'url'       => makeLink(array('controller' => 'store', 'action' => 'manage')),
                        ),
                   
                        array(
                            'text'      => gt("Manage Tienda Categories"),
                            'classname' => 'manage',
                            'url'       => makeLink(array('controller' => 'storeCategory', 'action' => 'manage')),
                        ),
                        array(
                            'text'      => gt("Manage Distributors"),
                            'classname' => 'manage',
//                            'url'=>makeLink(array('controller'=>'company','action'=>'manage')),
                            'url'       => makeLink(array('controller' => 'company', 'action' => 'showall')),
                        ),
                      
                    ),
                ),
            ),
            array(
                'text'    => gt("Reports"),
                'classname' => 'reports',
                'submenu' => array(
                    'id'       => 'reports',
                    'itemdata' => array(
                        array(
                            'text' => gt("View Uncategorized Products"),
                            'url'  => makeLink(array('controller' => 'store', 'action' => 'showallUncategorized')),
                        ),
                      
                        array(
                            'text' => gt("Build an Order Report"),
                            'classname' => 'development',
                            'url'  => makeLink(array('controller' => 'report', 'action' => 'order_report')),
                        ),
                        array(
                            'text' => gt("Build a Product Report"),
                            'classname' => 'development',
                            'url'  => makeLink(array('controller' => 'report', 'action' => 'product_report')),
                        ),
                    ),
                ),
            ),
//            array(
//                'text'    => gt("Purchase Orders"),
//                'classname' => 'purchase',
//                'submenu' => array(
//                    'id'       => 'purchase-order',
//                    'itemdata' => array(
//                        array(
//                            'text' => gt("Create Purchase Order"),
//                            'classname' => 'add',
//                            'url'  => makeLink(array('controller' => 'purchaseOrder', 'action' => 'edit')),
//                        ),
//                        array(
//                            'text'      => gt("Manage Purchase Orders"),
//                            'classname' => 'manage',
//                            'url'       => makeLink(array('controller' => 'purchaseOrder', 'action' => 'manage')),
//                        ),
//                        array(
//                            'text'      => gt("Manage Vendors"),
//                            'classname' => 'manage',
//                            'url'       => makeLink(array('controller' => 'purchaseOrder', 'action' => 'manage_vendors')),
//                        ),
//                    ),
//                ),
//            ),
            array(
                'text'    => gt("Store Setup"),
                'classname' => 'configure',
                'submenu' => array(
                    'id'       => 'store',
                    'itemdata' => array(
                        array(
                            'text' => gt("General Store Settings"),
                            'classname' => 'configure',
                            'url'  => makeLink(array('controller' => 'ecomconfig', 'action' => 'configure')),
                        ),
//                        array(
//                            'text' => gt("General Cart Settings"),
//                            'classname' => 'configure',
//                            'url'  => makeLink(array('controller' => 'cart', 'action' => 'configure')),
//                        ),
                       
                        array(
                            'text'      => gt("Manage Discounts"),
                            'classname' => 'manage',
                            'url'       => makeLink(array('controller' => 'ecomconfig', 'action' => 'manage_discounts')),
                        ),
                        array(
                            'text'      => gt("Import Products"),
                            'classname' => 'import',
                            'url'       => makeLink(array('controller' => 'importexport', 'action' => 'manage')),
                        ),
                        
                    ),
                ),
            ),
        ),
    )
);
// $ecom[] = array(
//     'text'=>'<form id="orderQuickfinder" method="POST" action="/index.php" enctype="multipart/form-data"><input type="hidden" name="controller" value="order"><input type="hidden" name="action" value="quickfinder"><input style="padding-top: 3px;" type="text" name="ordernum" id="ordernum" size="25" value="Order Quickfinder" onclick="this.value=\'\';"></form>',
//     'classname'=>'order',    
// );
return $ecom;
?>

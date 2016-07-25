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
 * @subpackage Controllers
 * @package    Modules
 */
/** @define "BASE" "../../../.." */

class storeController extends expController {
    public $basemodel_name = 'product';

    public $useractions = array(
        'showall'                         => 'All Products and Categories',
		'showall_best_sellers'    		  => 'Best Sellers Product',
		'showall_recent_products'         => "Show all recent products",
        'showallFeaturedProducts'         => 'Products - Only show Featured',
        'showTopLevel'                    => 'Categories - Show Top Level',
		'showallFeaturedCategory'            => 'Categories - Show Top Level Featured',
        'showFullTree'                    => 'Categories - Show Full Tree',  //FIXME image variant needs separate method
        'showallSubcategories'            => 'Categories - Subcategories of current category',
        'ecomSearch'                      => 'Search - Autocomplete',
        'searchByModelForm'               => 'Search - By Model',  //FIXME broken? doesn't work as initial view
        'quicklinks'                      => 'Links - Users Links',
        'showallCategoryFeaturedProducts' => 'Show Featured Products under the current category',  //FIXME broken? doesn't work as initial view
        'showGiftCards'                   => 'Gift Cards UI',
		'showMyLists'                     => 'Show list of products of the current user'
    );

    // hide the configs we don't need
    public $remove_configs = array(
        'aggregation',
        'categories',
        'comments',
        'ealerts',
        'facebook',
        'files',
        'rss',
        'tags',
        'twitter',
    );  // all options: ('aggregation','categories','comments','ealerts','facebook','files','module_title','pagination','rss','tags','twitter',)

    //protected $permissions = array_merge(array("test"=>'Test'), array('copyProduct'=>"Copy Product"));
    protected $add_permissions = array(
        'copyProduct'                 => "Copy Product",
        'delete_children'             => "Delete Children",
        'import'                      => 'Import Products',
        'reimport'                    => 'ReImport Products',
        'export'                      => 'Export Products',
        'findDupes'                   => 'Fix Duplicate SEF Names',
        'manage_sales_reps'           => 'Manage Sales Reps',
        'batch_process'               => 'Batch capture order transactions',
        'process_orders'              => 'Batch capture order transactions',
        'import_external_addresses'   => 'Import addresses from other sources',
        'showallImpropercategorized'  => 'View products in top level categories that should not be',
        'showallUncategorized'        => 'View all uncategorized products',
        'nonUnicodeProducts'          => 'View all non-unicode charset products',
        'cleanNonUnicodeProducts'     => 'Clean all non-unicode charset products',
        'uploadModelAliases'          => 'Upload model aliases',
        'processModelAliases'         => 'Process uploaded model aliases',
        'saveModelAliases'            => 'Save uploaded model aliases',
        'deleteProcessedModelAliases' => 'Delete processed uploaded model aliases',
        'delete_model_alias'          => 'Process model aliases',
        'update_model_alias'          => 'Save model aliases',
        'edit_model_alias'            => 'Delete model aliases'
    );

    static function displayname() {
        return gt("e-Commerce Store Front");
    }

    static function description() {
        return gt("Displays products and categories from your e-Commerce store");
    }

    static function author() {
        return "OIC Group, Inc";
    }

    static function isSearchable() {
        return true;
    }

    static function canImportData() {
        return true;
    }

    static function canExportData() {
        return true;
    }

	function showall_best_sellers() {
		global $db, $user, $order;
		$best_sellers_id = $db->selectObjectsBySql("SELECT COUNT(product_id) numberofsold, product_id FROM exponent_orderitems WHERE product_id in (SELECT id FROM exponent_product) AND orders_id in (SELECT id FROM exponent_orders WHERE purchased > 0) GROUP BY product_id ORDER BY numberofsold");
	
		foreach( $best_sellers_id as $item ) {
			$best_sellers[] = new product($item->product_id, false, true);
			
		}
		
		foreach($order->orderitem as $item) {
			$myorder[$item->product_id] = $item->quantity;
		}
		
		$page = new expPaginator(array(
			'records'  => $best_sellers,
			'controller' => 'store',
			'action' => 'showall_best_sellers',
			'limit'       => 12,
            'dontsort'       => true,
		    'page'        => (isset($this->params['page']) ? $this->params['page'] : 1)
		));
		
		assign_to_template(array(
            'page'     => $page,
			'mylists'		   => @$mylists,
			'current_cart' => @$myorder
        ));
	}

    function __construct($src = null, $params = array()) {
        global $db, $router, $section, $user;
//        parent::__construct($src = null, $params);
        if (empty($params)) {
            $params = $router->params;
        }
        parent::__construct($src, $params);

        // we're setting the config here globally
        $this->grabConfig();

//        if (expTheme::inAction() && !empty($router->url_parts[1]) && ($router->url_parts[0] == "store" && $router->url_parts[1] == "showall")) {
        if (!empty($params['action']) && ($params['controller'] == "store" && $params['action'] == "showall")) {
//            if (isset($router->url_parts[array_search('title', $router->url_parts) + 1]) && is_string($router->url_parts[array_search('title', $router->url_parts) + 1])) {
            if (isset($params['title']) && is_string($params['title'])) {
//                $default_id = $db->selectValue('storeCategories', 'id', "sef_url='" . $router->url_parts[array_search('title', $router->url_parts) + 1] . "'");
//                $active     = $db->selectValue('storeCategories', 'is_active', "sef_url='" . $router->url_parts[array_search('title', $router->url_parts) + 1] . "'");
                $default_id = $db->selectValue('storeCategories', 'id', "sef_url='" . $params['title'] . "'");
                $active = $db->selectValue('storeCategories', 'is_active', "sef_url='" . $params['title'] . "'");
                if (empty($active) && !$user->isAdmin()) {
                    redirect_to(array("section" => SITE_DEFAULT_SECTION)); // selected category is NOT active
                }
            } elseif (isset($this->config['category'])) { // the module category to display
                $default_id = $this->config['category'];
            }
//        } elseif (expTheme::inAction() && !empty($router->url_parts[1]) && ($router->url_parts[0] == "store" && ($router->url_parts[1] == "show" || $router->url_parts[1] == "showByTitle"))) {
        } elseif (!empty($params['action']) && ($params['controller'] == "store" && ($params['action'] == "show" || $params['action'] == "showByTitle"))) {
//            if (isset($router->url_parts[array_search('id', $router->url_parts) + 1]) && ($router->url_parts[array_search('id', $router->url_parts) + 1] != 0)) {
            if (!empty($params['id'])) {
//                $default_id = $db->selectValue('product_storeCategories', 'storecategories_id', "product_id='" . $router->url_parts[array_search('id', $router->url_parts) + 1] . "'");
                $default_id = $db->selectValue('product_storeCategories', 'storecategories_id', "product_id='" . $params['id'] . "'");
            } elseif (!empty($params['title'])) {
//                $prod_id    = $db->selectValue('product', 'id', "sef_url='" . $router->url_parts[array_search('title', $router->url_parts) + 1] . "'");
                $prod_id = $db->selectValue('product', 'id', "sef_url='" . $params['title'] . "'");
                $default_id = $db->selectValue('product_storeCategories', 'storecategories_id', "product_id='" . $prod_id . "'");
            }
        } elseif (isset($this->config['show_first_category']) || (!expTheme::inAction() && $section == SITE_DEFAULT_SECTION)) {
            if (!empty($this->config['show_first_category'])) {
                $default_id = $db->selectValue('storeCategories', 'id', 'lft=1');
            } else {
                $default_id = 0;
            }
        } elseif (!isset($this->config['show_first_category']) && !expTheme::inAction()) {
            $default_id = 0;
        } else {
            $default_id = 0;
        }
        if (empty($default_id)) $default_id = 0;
        expSession::set('catid', $default_id);

        // figure out if we need to show all categories and products or default to showing the first category.
        // elseif (!empty($this->config['category'])) {
        //     $default_id = $this->config['category'];
        // } elseif (ecomconfig::getConfig('show_first_category')) {
        //     $default_id = $db->selectValue('storeCategories', 'id', 'lft=1');
        // } else {
        //     $default_id = 0;
        // }

        $this->parent = expSession::get('catid');
        $this->category = new storeCategory($this->parent);
        if ($this->parent) { // we're setting the config here for the category
            $this->grabConfig($this->category); //FIXME we don't currently create category configuration since we can display them as modules?
        }
    }
function load_next_products() {
		global $db, $user, $order;

        expHistory::set('viewable', $this->params);
		$this->category = new storeCategory($this->params['category']);
	
        $sql_start = 'SELECT DISTINCT p.*, IF(base_price > special_price AND use_special_price=1,special_price, base_price) as price FROM ' . DB_TABLE_PREFIX . '_product p ';
        $sql = 'JOIN ' . DB_TABLE_PREFIX . '_product_storeCategories sc ON p.id = sc.product_id ';
        $sql .= 'WHERE 1=1 ';
        if (!($user->is_admin || $user->is_acting_admin)) $sql .= 'AND (p.active_type=0 OR p.active_type=1) ';
       
		if(!empty($this->category->id)) {
        $sql .= 'AND sc.storecategories_id IN (';
        $sql .= 'SELECT id FROM ' . DB_TABLE_PREFIX . "_storeCategories WHERE sef_url ='{$this->category->sef_url}')";
		}

        $sql = $sql_start . $sql;
	
          $page = new expPaginator(array(
               'model_field' => 'product_type',
               'sql'         => $sql,
               'limit'       => 12,
               'order'       => 'rank',
               'dir'         => 'ASC',
               'page'        => (isset($this->params['page']) ? $this->params['page'] : 1),
               'controller'  => $this->params['controller'],
               'action'      => $this->params['action'],
               'columns'     => array(
                   gt('Model #')      => 'model',
                   gt('Product Name') => 'title',
                   gt('Price')        => 'price'
               ),
           ));
       

		foreach($order->orderitem as $item) {
			$myorder[$item->product_id] = $item->quantity;
		}
		
        assign_to_template(array(
            'page'             => $page,
            'current_category' => $this->category,
			'current_cart' => @$myorder
        ));
	   
	}
    function showall() {
        global $db, $user, $order;

        expHistory::set('viewable', $this->params);

        $sql_start = 'SELECT DISTINCT p.*, IF(base_price > special_price AND use_special_price=1,special_price, base_price) as price FROM ' . DB_TABLE_PREFIX . '_product p ';
        $sql = 'JOIN ' . DB_TABLE_PREFIX . '_product_storeCategories sc ON p.id = sc.product_id ';
        $sql .= 'WHERE 1=1 ';
        if (!($user->is_admin || $user->is_acting_admin)) $sql .= 'AND (p.active_type=0 OR p.active_type=1)';
		
		if(!empty($this->category->id)) {
        $sql .= 'AND sc.storecategories_id IN (';
        $sql .= 'SELECT id FROM ' . DB_TABLE_PREFIX . "_storeCategories WHERE sef_url ='{$this->category->sef_url}')";
		}
        $sql = $sql_start . $sql;
		
          $page = new expPaginator(array(
               'model_field' => 'product_type',
               'sql'         => $sql,
               'limit'       => 12,
               'order'       => 'rank',
               'dir'         => 'ASC',
               'page'        => (isset($this->params['page']) ? $this->params['page'] : 1),
               'controller'  => $this->params['controller'],
               'action'      => $this->params['action'],
               'columns'     => array(
                   gt('Model #')      => 'model',
                   gt('Product Name') => 'title',
                   gt('Price')        => 'price'
               ),
           ));
       

		foreach($order->orderitem as $item) {
			$myorder[$item->product_id] = $item->quantity;
		}
		
        assign_to_template(array(
            'page'             => $page,
            'current_category' => $this->category,
			'current_cart' => @$myorder
        ));
    }

	function showMyLists() {
		global $db, $user;
		if($user->id) {
			$mylists = $db->selectObjects("mylists", "user_id = {$user->id}");
		} else {
			flash('message',gt('You need to login to use this feature.'));
			redirect_to(array('controller' => 'login', 'action' => 'loginredirect'));
		}
		
		for($i=0; $i < count($mylists); $i++) {
			$mylists[$i]->product_title = $db->selectValue("product", "title", "id = {$mylists[$i]->product_id}");
                        $mylists[$i]->capacity      = $db->selectValue("product", "capacity", "id = {$mylists[$i]->product_id}");
			
			$mylists[$i]->sef_url       = $db->selectValue("product", "sef_url", "id = {$mylists[$i]->product_id}");
			$mylists[$i]->product_image = $db->selectValue("content_expFiles", "expfiles_id", "content_id = {$mylists[$i]->product_id} && subtype='mainthumbnail'");
		}
	
		assign_to_template(array(
			'mylists' => @$mylists,
			'user_id' => @$user->id
        ));
	}

	function showall_recent_products() {
        global $db, $user, $router, $order;

        expHistory::set('viewable', $this->params);

      
            $page = new expPaginator(array(
                'model_field' => 'product_type',
                'sql'         => 'SELECT * FROM ' . DB_TABLE_PREFIX . '_product WHERE 1',
                'limit'       => 12,
                'order'       => "created_at",
                'dir'         => "desc",
                'page'        => (isset($this->params['page']) ? $this->params['page'] : 1),
                'controller'  => $this->params['controller'],
                'action'      => $this->params['action'],
                'columns'     => array(
                    gt('Model #')      => 'model',
                    gt('Product Name') => 'title',
                    gt('Price')        => 'price'
                ),
            ));
		
		foreach($order->orderitem as $item) {
			$myorder[$item->product_id] = $item->quantity;
		}
		
        assign_to_template(array(
            'page'             => $page,
			'current_cart' => @$myorder
        ));
    }
	

    function grabConfig($category = null) {

        // grab the configs for the category
        if (is_object($category)) {
            $catConfig = new expConfig(expCore::makeLocation("storeCategory","@store-" . $category->id,""));
        }

        // since router maps strip off src and we need that to pull configs, we won't get the configs
        // of the page is router mapped. We'll ensure we do here:
        $config = new expConfig(expCore::makeLocation("ecomconfig","@globalstoresettings",""));

        $this->config = @array_merge((empty($catConfig->config) || @$catConfig->config['use_global'] == 1) ? $config->config : $catConfig->config, $this->config);

        //This is needed since in the first installation of ecom the value for this will be empty and we are doing % operation for this value
        //So we need to ensure if the value is = 0, then we can as well make it to 1
        if (empty($this->config['images_per_row'])) {
            $this->config['images_per_row'] = 3;
        }
    }


	function getProductCategory($title = '') {
		global $db; 

		$product_id = $db->selectValue("product", "id", "sef_url = '{$_GET['title']}'");
		if($product_id) {
			$cat_id = $db->selectValue("product_storeCategories", "storecategories_id", "product_id = {$product_id}");
			if($cat_id) {
				return $cat_id;
			}
		}
	
	}

    function categoryBreadcrumb() {
        global $db, $router;

     
			
		
		$active_id = @$db->selectValue("storeCategories", "id", "sef_url = '{$_GET['title']}'");
	
		if(empty($active_id) && isset($_GET['title'])) {
			$active_id = $this->getProductCategory($_GET['title']);
		}

		if($active_id) {
			$aciive_cat = new storeCategory($active_id , false, false);
			 $active_ancestor =  $aciive_cat->pathToNode();
		} else {
			$active_id = $db->selectValue("storeCategories", "id", "sef_url = '" . substr($router->sefPath, 1) . "'");
			if($active_id) {
				$aciive_cat = new storeCategory($active_id , false, false);
				 $active_ancestor =  $aciive_cat->pathToNode();
			}
		}
			
        assign_to_template(array(
            'ancestors' => @$active_ancestor
        ));
    }

    function showallUncategorized() {
        expHistory::set('viewable', $this->params);

//        $sql = 'SELECT p.* FROM ' . DB_TABLE_PREFIX . '_product p JOIN ' . DB_TABLE_PREFIX . '_product_storeCategories ';
//        $sql .= 'sc ON p.id = sc.product_id WHERE sc.storecategories_id = 0 AND parent_id=0';
        $sql = 'SELECT * FROM exponent_product WHERE id NOT IN (SELECT product_id FROM exponent_product_storeCategories) OR id IN (SELECT product_id FROM exponent_product_storeCategories WHERE   storecategories_id = 0)';

        expSession::set('product_export_query', $sql);

        $limit = !empty($this->config['limit']) ? $this->config['limit'] : 10;
        $page = new expPaginator(array(
            'model_field' => 'product_type',
            'sql'         => $sql,
            'limit'       => !empty($this->config['pagination_default']) ? $this->config['pagination_default'] : $limit,
            'page'        => (isset($this->params['page']) ? $this->params['page'] : 1),
            'controller'  => $this->params['controller'],
            'action'      => $this->params['action'],
            'columns'     => array(
                gt('Model #')      => 'model',
                gt('Product Name') => 'title',
                gt('Price')        => 'base_price'
            ),
        ));

        assign_to_template(array(
            'page'        => $page,
            'moduletitle' => 'Uncategorized Products'
        ));
    }

    function manage() {
        expHistory::set('manageable', $this->params);
        $limit = !empty($this->config['limit']) ? $this->config['limit'] : 10;
        $page = new expPaginator(array(
            'model'      => 'product',
            'where'      => 'parent_id=0',
            'limit'      => !empty($this->config['pagination_default']) ? $this->config['pagination_default'] : $limit,
            'order'      => 'title',
            'page'       => (isset($this->params['page']) ? $this->params['page'] : 1),
            'controller' => $this->params['controller'],
            'action'     => $this->params['action'],
            'columns'    => array(
                gt('Type')         => 'product_type',
                gt('Product Name') => 'title',
                gt('Model #')      => 'model',
                gt('Price')        => 'base_price'
            )
        ));
        assign_to_template(array(
            'page' => $page
        ));
    }

    function showallImpropercategorized() {
        expHistory::set('viewable', $this->params);

        //FIXME not sure this is the correct sql, not sure what we are trying to pull out
        $sql = 'SELECT DISTINCT(p.id),p.product_type FROM ' . DB_TABLE_PREFIX . '_product p JOIN ' . DB_TABLE_PREFIX . '_product_storeCategories psc ON p.id = psc.product_id ';
        $sql .= 'JOIN '.DB_TABLE_PREFIX.'_storeCategories sc ON psc.storecategories_id = sc.parent_id WHERE ';
        $sql .= 'p.parent_id=0 AND sc.parent_id != 0';

        expSession::set('product_export_query', $sql);

        $limit = !empty($this->config['limit']) ? $this->config['limit'] : 10;
        $page = new expPaginator(array(
            'model_field' => 'product_type',
            'sql'         => $sql,
            'limit'       => !empty($this->config['pagination_default']) ? $this->config['pagination_default'] : $limit,
            'page'        => (isset($this->params['page']) ? $this->params['page'] : 1),
            'controller'  => $this->params['controller'],
            'action'      => $this->params['action'],
            'columns'     => array(
                gt('Model #')      => 'model',
                gt('Product Name') => 'title',
                gt('Price')        => 'base_price'
            ),
        ));

        assign_to_template(array(
            'page'        => $page,
            'moduletitle' => 'Improperly Categorized Products'
        ));
    }

    function exportMe() {
        redirect_to(array('controller' => 'report', 'action' => 'batch_export', 'applytoall' => true));
    }

    function show() {
        global $order, $template, $user, $db;
        //need to add a check here for child product and redirect to parent if hit directly by ID
        expHistory::set('viewable', $this->params);

        $product = new product(addslashes($this->params['title']));
        $product_type = new $product->product_type($product->id);
        $product_type->title = expString::parseAndTrim($product_type->title, true);
        $product_type->image_alt_tag = expString::parseAndTrim($product_type->image_alt_tag, true);

        //if we're trying to view a child product directly, then we redirect to it's parent show view
        //bunk URL, no product found
        if (empty($product->id)) {
            redirect_to(array('controller' => 'notfound', 'action' => 'page_not_found', 'title' => $this->params['title']));
        }
        if (!empty($product->parent_id)) {
            $product = new product($product->parent_id);
            redirect_to(array('controller' => 'store', 'action' => 'show', 'title' => $product->sef_url));
        }
        if ($product->active_type == 1) {
            $product_type->user_message = "This product is temporarily unavailable for purchase.";
        } elseif ($product->active_type == 2 && !($user->is_admin || $user->is_acting_admin)) {
            flash("error", $product->title . " " . gt("is currently unavailable."));
            expHistory::back();
        } elseif ($product->active_type == 2 && ($user->is_admin || $user->is_acting_admin)) {
            $product_type->user_message = $product->title . " is currently marked as unavailable for purchase or display.  Normal users will not see this product.";
        }
        if (!empty($product_type->crosssellItem)) foreach ($product_type->crosssellItem as &$csi) {
            $csi->getAttachableItems();
        }
        //eDebug($product->crosssellItem);

        $tpl = $product_type->getForm('show');
        //eDebug($product);
        if (!empty($tpl)) $template = new controllertemplate($this, $tpl);
        $this->grabConfig(); // grab the global config

		if($user->id) {
			$mylists = $db->selectColumn("mylists", "product_id", "user_id = {$user->id}","created_at DESC");
		}
        assign_to_template(array(
            'config'        => $this->config,
            'product'       => $product_type,
			'mylists' => @$mylists,
			'user_id' => @$user->id,
			'user' => @$user,
            'last_category' => !empty($order->lastcat) ? $order->lastcat : null,
        ));
    }

    function showallSubcategories() {
        global $db;

        expHistory::set('viewable', $this->params);
        $parent = isset($_REQUEST['cat']) ? $_REQUEST['cat'] : expSession::get('last_ecomm_category');
        $category = new storeCategory($parent);
        $categories = $category->getEcomSubcategories();  //FIXME returns a product count of 0
        $ancestors = $category->pathToNode();
        assign_to_template(array(
            'categories' => $categories,
            'ancestors'  => $ancestors,
            'category'   => $category
        ));
    }

    function showallFeaturedProducts() {
		global $user, $db, $order;
	
        $order_rec = 'rank';
        $dir = 'ASC';

        $page = new expPaginator(array(
            'model_field' => 'product_type',
            'sql'         => 'SELECT * FROM ' . DB_TABLE_PREFIX . '_product WHERE is_featured=1',
            'limit'       => ecomconfig::getConfig('pagination_default'),
            'order'       => $order_rec,
            'dir'         => $dir,
            'page'        => (isset($this->params['page']) ? $this->params['page'] : 1),
            'controller'  => $this->params['controller'],
            'action'      => $this->params['action'],
            'columns'     => array(
                gt('Model #')      => 'model',
                gt('Product Name') => 'title',
                gt('Price')        => 'base_price'
            ),
        ));
		
		foreach($order->orderitem as $item) {
			$myorder[$item->product_id] = $item->quantity;
		}
		
        assign_to_template(array(
            'page' => $page,
			'current_cart' => @$myorder
        ));
    }

	function addtomylist() {
		global $db, $user;
		
		if($user->id && $this->params['product_id']) {
			$title = $db->selectValue("product", "title", "id = {$this->params['product_id']}");
			$db->delete("mylists", "user_id = {$user->id} && product_id = {$this->params['product_id']}");
			$obj->product_id = $this->params['product_id'];
			$obj->user_id = $user->id;
			$obj->created_at = time();
			$id = $db->insertObject($obj, "mylists");
		}
		if($id) {
			echo true;
		}
		exit();
	}
	
	function removefrommylist() {
		global $user, $db;
		
		$list_id = $this->params['list_id'];
		
		$id = $db->delete("mylists", "id = {$list_id} && user_id = {$user->id}");
		
		if($id) {
		 	flash('message', gt('The product was been removed in your list successfully.'));
		} else {
			flash('error', gt('Error occurred, Please try again.'));
		}
	    expHistory::back();
	}
	
	function addmylist() {
		global $router;
		//forloop all the products selected
		//check if the quantity is > 0
		//initialize the product
		//build the product
		//add to cart
		//end for loop
		
		foreach ($this->params['qty'] as $product_id => $quantity) {
			if($quantity > 0) {
				$params['quantity'] = $quantity;
				$product = new product($product_id);
				$product->addToCart($params);
			}
		}
		redirect_to(array('controller' => 'cart', 'action' => 'show'));
	}
	
    function showallCategoryFeaturedProducts() {

        $curcat = $this->category;

        $order = 'title';
        $dir = 'ASC';
        //FIXME bad sql statement needs to be a JOIN
        $sql = 'SELECT * FROM ' . DB_TABLE_PREFIX . '_product,' . DB_TABLE_PREFIX . '_product_storeCategories WHERE product_id = id and is_featured=1 and storecategories_id =' . $curcat->id;
        $page = new expPaginator(array(
            'model_field' => 'product_type',
            'sql'         => $sql,
            'limit'       => ecomconfig::getConfig('pagination_default'),
            'order'       => $order,
            'dir'         => $dir,
            'page'        => (isset($this->params['page']) ? $this->params['page'] : 1),
            'controller'  => $this->params['controller'],
            'action'      => $this->params['action'],
            'columns'     => array(
                gt('Model #')      => 'model',
                gt('Product Name') => 'title',
                gt('Price')        => 'base_price'
            ),
        ));

        assign_to_template(array(
            'page' => $page
        ));
    }

    function showTopLevel() {
        $category = new storeCategory(null, false, false);
        //$categories = $category->getEcomSubcategories();
        $categories = $category->getTopLevel(null, false, true);
        $ancestors = $this->category->pathToNode();
        $curcat = $this->category;

        assign_to_template(array(
            'categories' => $categories,
            'curcat'     => $curcat,
            'topcat'     => @$ancestors[0]
        ));
    }

	function showallFeaturedCategory() {
		global $db;
        $category = new storeCategory(null, false, false);
    
        $featured_top_categories = $category->find("all", "parent_id = 0 AND is_featured = 1");
		for($i = 0; $i < count($featured_top_categories); $i++) {
			$featured_top_categories[$i]->childCat = $db->selectObjects("storeCategories", "parent_id = {$featured_top_categories[$i]->id}");
		}
        assign_to_template(array(
            'categories' => $featured_top_categories
        ));
    }
  

    function showTopLevel_images() {
        global $user;
        $count_sql_start = 'SELECT COUNT(DISTINCT p.id) as c FROM ' . DB_TABLE_PREFIX . '_product p ';
        $sql_start = 'SELECT DISTINCT p.* FROM ' . DB_TABLE_PREFIX . '_product p ';
        $sql = 'JOIN ' . DB_TABLE_PREFIX . '_product_storeCategories sc ON p.id = sc.product_id ';
        $sql .= 'WHERE ';
        if (!($user->is_admin || $user->is_acting_admin)) $sql .= '(p.active_type=0 OR p.active_type=1)'; //' AND ' ;
        //$sql .= 'sc.storecategories_id IN (';
        //$sql .= 'SELECT id FROM '.DB_TABLE_PREFIX.'_storeCategories WHERE rgt BETWEEN '.$this->category->lft.' AND '.$this->category->rgt.')';         

        $count_sql = $count_sql_start . $sql;
        $sql = $sql_start . $sql;

        $order = 'sc.rank'; //$this->config['orderby'];
        $dir = 'ASC'; //$this->config['orderby_dir'];

        $limit = !empty($this->config['limit']) ? $this->config['limit'] : 10;
        $page = new expPaginator(array(
            'model_field' => 'product_type',
            'sql'         => $sql,
            'count_sql'   => $count_sql,
            'limit'       => !empty($this->config['pagination_default']) ? $this->config['pagination_default'] : $limit,
            'order'       => $order,
            'dir'         => $dir,
            'page'        => (isset($this->params['page']) ? $this->params['page'] : 1),
            'controller'  => $this->params['controller'],
            'action'      => $this->params['action'],
            'columns'     => array(
                gt('Model #')      => 'model',
                gt('Product Name') => 'title',
                gt('Price')        => 'base_price'
            ),
        ));

        $category = new storeCategory(null, false, false);
        //$categories = $category->getEcomSubcategories();
        $categories = $category->getTopLevel(null, false, true);
        $ancestors = $this->category->pathToNode();
        $curcat = $this->category;

        assign_to_template(array(
            'page'       => $page,
            'categories' => $categories
        ));
    }

    function showFullTree() {  //FIXME we also need a showFullTree_images method like above
		global $db, $router;

		$active_id = @$db->selectValue("storeCategories", "id", "sef_url = '{$_GET['title']}'");
		
		
		if($active_id) {
			$aciive_cat = new storeCategory($active_id , false, false);
			 $active_ancestor =  $aciive_cat->pathToNode();
		} else {
			$active_id = $db->selectValue("storeCategories", "id", "sef_url = '" . substr($router->sefPath, 1) . "'");
			if(empty($active_id) && isset($_GET['title'])) {
				$active_id = $this->getProductCategory($_GET['title']);
			}
			if($active_id) {
				$aciive_cat = new storeCategory($active_id , false, false);
				 $active_ancestor =  $aciive_cat->pathToNode();
			}
		}


        $category = new storeCategory(null, false, false);
        //$categories = $category->getEcomSubcategories();
        $categories = $category->getFullTree();
        $ancestors = $this->category->pathToNode();
        $curcat = new storeCategory($active_id);
	
        assign_to_template(array(
            'categories' => $categories,
            'curcat'     => $curcat,
            'topcat'     => @$ancestors[0],
			'parentcategory' => @$active_ancestor[0],
			'subparentcategory' => @$active_ancestor[1]
        ));
    }

    function ecomSearch() {

    }

    function billing_config() {

    }

    function addContentToSearch() {
        global $db, $router;

        $model = new $this->basemodel_name();

        $total = $db->countObjects($model->table);

        $count = 0;
        for ($i = 0; $i < $total; $i += 100) {
            $orderby = 'id LIMIT ' . ($i) . ', 100';
            $content = $db->selectArrays($model->table, 'parent_id=0', $orderby);

            foreach ($content as $cnt) {
                $origid = $cnt['id'];
                $prod = new product($cnt['id']);
                unset($cnt['id']);
                //$cnt['title'] = $cnt['title'].' - SKU# '.$cnt['model'];
                $cnt['title'] = (isset($prod->expFile['mainimage'][0]) ? '<img src="' . PATH_RELATIVE . 'thumb.php?id=' . $prod->expFile['mainimage'][0]->id . '&w=40&h=40&zc=1" style="float:left;margin-right:5px;" />' : '') . $cnt['title'] . (!empty($cnt['model']) ? ' - SKU#: ' . $cnt['model'] : '');

//                $search_record = new search($cnt, false, false);
               //build the search record and save it.
                $sql = "original_id=" . $origid . " AND ref_module='" . $this->baseclassname . "'";
                $oldindex = $db->selectObject('search', $sql);
                if (!empty($oldindex)) {
                    $search_record = new search($oldindex->id, false, false);
                    $search_record->update($cnt);
                } else {
                    $search_record = new search($cnt, false, false);
                }

                $search_record->posted = empty($cnt['created_at']) ? null : $cnt['created_at'];
                $search_record->view_link = str_replace(URL_FULL, '', $router->makeLink(array('controller' => $this->baseclassname, 'action' => 'show', 'title' => $cnt['sef_url'])));
//                $search_record->ref_module = 'store';
                $search_record->ref_module  = $this->baseclassname;
//                $search_record->ref_type = $this->basemodel_name;
                $search_record->ref_type = $cnt['product_type'];
//                $search_record->category = 'Products';
                $prod = new $search_record->ref_type();
                $search_record->category = $prod->product_name;

                $search_record->original_id = $origid;
                //$search_record->location_data = serialize($this->loc);
                $search_record->save();
                $count += 1;
            }
        }
        return $count;
    }

    function searchByModel() {
        //do nothing...just show the view.
    }

    function edit() {
        global $db;

//        $expDefinableField = new expDefinableField();
//        $definablefields = $expDefinableField->find('all','1','rank');
        $f = new forms();
        $forms_list = array();
        $forms_list[0] = '- '.gt('No User Input Required').' -';
        $forms = $f->find('all', 'is_saved=1');
        if (!empty($forms)) foreach ($forms as $frm) {
            $forms_list[$frm->id] = $frm->title;
        }

        //Make sure that the view is the edit.tpl and not any ajax views
        if (isset($this->params['view']) && $this->params['view'] == 'edit') {
            expHistory::set('editable', $this->params);
        }

        // first we need to figure out what type of ecomm product we are dealing with
        if (!empty($this->params['id'])) {
            // if we have an id lets pull the product type from the products table.
            $product_type = $db->selectValue('product', 'product_type', 'id=' . $this->params['id']);
        } else {
            if (empty($this->params['product_type'])) redirect_to(array('controller' => 'store', 'action' => 'picktype'));
            $product_type = $this->params['product_type'];
        }

        if (!empty($this->params['id'])) {
            $record = new $product_type($this->params['id']);
            if (!empty($this->user_input_fields) && !is_array($record->user_input_fields)) $record->user_input_fields = expUnserialize($record->user_input_fields);
        } else {
            $record = new $product_type();
            $record->user_input_fields = array();
        }

//        if (!empty($this->params['parent_id']))

        // get the product options and send them to the form
        $editable_options = array();
        //$og = new optiongroup();
        $mastergroups = $db->selectExpObjects('optiongroup_master', null, 'optiongroup_master');
        //eDebug($mastergroups,true);
        foreach ($mastergroups as $mastergroup) {
            // if this optiongroup_master has already been made into an option group for this product
            // then we will grab that record now..if not, we will make a new one.
            $grouprec = $db->selectArray('optiongroup', 'optiongroup_master_id=' . $mastergroup->id . ' AND product_id=' . $record->id);
            //if ($mastergroup->id == 9) eDebug($grouprec,true);
            //eDebug($grouprec);
            if (empty($grouprec)) {
                $grouprec['optiongroup_master_id'] = $mastergroup->id;
                $grouprec['title'] = $mastergroup->title;
                $group = new optiongroup($grouprec);
            } else {
                $group = new optiongroup($grouprec['id']);
            }

            $editable_options[$group->title] = $group;

            if (empty($group->option)) {
                foreach ($mastergroup->option_master as $optionmaster) {
                    $opt = new option(array('title' => $optionmaster->title, 'option_master_id' => $optionmaster->id), false, false);
                    $editable_options[$group->title]->options[] = $opt;
                }

            } else {
                if (count($group->option) == count($mastergroup->option_master)) {
                    $editable_options[$group->title]->options = $group->option;
                } else {
                    // check for any new options or deleted since the last time we edited this product
                    foreach ($mastergroup->option_master as $optionmaster) {
                        $opt_id = $db->selectValue('option', 'id', 'option_master_id=' . $optionmaster->id . " AND product_id=" . $record->id);
                        if (empty($opt_id)) {
                            $opt = new option(array('title' => $optionmaster->title, 'option_master_id' => $optionmaster->id), false, false);
                        } else {
                            $opt = new option($opt_id);
                        }

                        $editable_options[$group->title]->options[] = $opt;
                    }
                }
            }
            //eDebug($editable_options[$group->title]);        
        }
        //die();

        uasort($editable_options, array("optiongroup", "sortOptiongroups"));

        // get the shipping options and their methods
        $shipping = new shipping();
        foreach ($shipping->available_calculators as $calcid => $name) {
            $calc = new $name($calcid);
            $shipping_services[$calcid] = $calc->title;
            $shipping_methods[$calcid] = $calc->availableMethods();
        }

#        eDebug($shipping_services);
#        eDebug($shipping_methods);

        if (!empty($this->params['product_type']) && ($this->params['product_type'] == "product" || $this->params['product_type'] == "childProduct")) {
            //if new record and it's a child, then well set the child rank to be at the end
            if (empty($record->id) && $record->isChild()) {
                $record->child_rank = $db->max('product', 'child_rank', null, 'parent_id=' . $record->parent_id) + 1;
            }
            //eDebug($record,true);
        }
        $view = '';
        $parent = null;
        if ((isset($this->params['parent_id']) && empty($record->id))) {
            //NEW child product
            $view = 'edit';
            $parent = new $product_type($this->params['parent_id'], false, true);
            $record->parent_id = $this->params['parent_id'];
        } elseif ((!empty($record->id) && $record->parent_id != 0)) {
            //EDIT child product
            $view = 'edit';
            $parent = new $product_type($record->parent_id, false, true);
        } else {
            $view = 'edit';
        }

        assign_to_template(array(
            'record'            => $record,
            'parent'            => $parent,
            'form'              => $record->getForm($view),
            'optiongroups'      => $editable_options,
//            'definablefields'   => isset($definablefields) ? $definablefields : '',
            'forms'=> $forms_list,
            'shipping_services' => isset($shipping_services) ? $shipping_services : '', // Added implication since the shipping_services default value is a null
            'shipping_methods'  => isset($shipping_methods) ? $shipping_methods : '', // Added implication since the shipping_methods default value is a null
            'product_types'     => isset($this->config['product_types']) ? $this->config['product_types'] : ''
            //'status_display'=>$status_display->getStatusArray()
        ));
    }

    function copyProduct() {
        global $db;

        //expHistory::set('editable', $this->params);
        $f = new forms();
        $forms_list = array();
        $forms_list[0] = '- '.gt('No User Input Required').' -';
        $forms = $f->find('all', 'is_saved=1');
        if (!empty($forms)) foreach ($forms as $frm) {
            $forms_list[$frm->id] = $frm->title;
        }

        // first we need to figure out what type of ecomm product we are dealing with
        if (!empty($this->params['id'])) {
            // if we have an id lets pull the product type from the products table.
            $product_type = $db->selectValue('product', 'product_type', 'id=' . $this->params['id']);
        } else {
            if (empty($this->params['product_type'])) redirect_to(array('controller' => 'store', 'action' => 'picktype'));
            $product_type = $this->params['product_type'];
        }

        $record = new $product_type($this->params['id']);
        // get the product options and send them to the form
        $editable_options = array();

        $mastergroups = $db->selectExpObjects('optiongroup_master', null, 'optiongroup_master');
        foreach ($mastergroups as $mastergroup) {
            // if this optiongroup_master has already been made into an option group for this product
            // then we will grab that record now..if not, we will make a new one.
            $grouprec = $db->selectArray('optiongroup', 'optiongroup_master_id=' . $mastergroup->id . ' AND product_id=' . $record->id);
            //eDebug($grouprec);
            if (empty($grouprec)) {
                $grouprec['optiongroup_master_id'] = $mastergroup->id;
                $grouprec['title'] = $mastergroup->title;
                $group = new optiongroup($grouprec);
            } else {
                $group = new optiongroup($grouprec['id']);
            }

            $editable_options[$group->title] = $group;

            if (empty($group->option)) {
                foreach ($mastergroup->option_master as $optionmaster) {
                    $opt = new option(array('title' => $optionmaster->title, 'option_master_id' => $optionmaster->id), false, false);
                    $editable_options[$group->title]->options[] = $opt;
                }
            } else {
                if (count($group->option) == count($mastergroup->option_master)) {
                    $editable_options[$group->title]->options = $group->option;
                } else {
                    // check for any new options or deleted since the last time we edited this product
                    foreach ($mastergroup->option_master as $optionmaster) {
                        $opt_id = $db->selectValue('option', 'id', 'option_master_id=' . $optionmaster->id . " AND product_id=" . $record->id);
                        if (empty($opt_id)) {
                            $opt = new option(array('title' => $optionmaster->title, 'option_master_id' => $optionmaster->id), false, false);
                        } else {
                            $opt = new option($opt_id);
                        }

                        $editable_options[$group->title]->options[] = $opt;
                    }
                }
            }
        }

        // get the shipping options and their methods
        $shipping = new shipping();
        foreach ($shipping->available_calculators as $calcid => $name) {
            $calc = new $name($calcid);
            $shipping_services[$calcid] = $calc->title;
            $shipping_methods[$calcid] = $calc->availableMethods();
        }

        $record->original_id = $record->id;
        $record->original_model = $record->model;
        $record->sef_url = NULL;
        $record->previous_id = NULL;
        $record->editor = NULL;

        if ($record->isChild()) {
            $record->child_rank = $db->max('product', 'child_rank', null, 'parent_id=' . $record->parent_id) + 1;
        }

        assign_to_template(array(
            'copy'              => true,
            'record'            => $record,
            'parent'            => new $product_type($record->parent_id, false, true),
            'form'              => $record->getForm($record->parent_id == 0 ? 'edit' : 'child_edit'),
            'optiongroups'      => $editable_options,
            'forms'=> $forms_list,
            'shipping_services' => $shipping_services,
            'shipping_methods'  => $shipping_methods
        ));
    }

    function picktype() {
        $prodfiles = storeController::getProductTypes();
        $products = array();
        foreach ($prodfiles as $filepath => $classname) {
            $prodObj = new $classname();
            $products[$classname] = $prodObj->product_name;
        }
        assign_to_template(array(
            'product_types' => $products
        ));
    }

    function update() {
        global $db;
        //Get the product type
        $product_type = isset($this->params['product_type']) ? $this->params['product_type'] : 'product';

        $record = new $product_type();

        $record->update($this->params);

        if ($product_type == "childProduct" || $product_type == "product") {
            $record->addContentToSearch();
            //Create a flash message and redirect to the page accordingly
            if ($record->parent_id != 0) {
                $parent = new $product_type($record->parent_id, false, false);
                if (isset($this->params['original_id'])) {
                    flash("message", gt("Child product saved."));
                } else {
                    flash("message", gt("Child product copied and saved."));
                }
                redirect_to(array('controller' => 'store', 'action' => 'show', 'title' => $parent->sef_url));
            } elseif (isset($this->params['original_id'])) {
                flash("message", gt("Product copied and saved. You are now viewing your new product."));
            } else {
                flash("message", gt("Product saved."));
            }
            redirect_to(array('controller' => 'store', 'action' => 'show', 'title' => $record->sef_url));
        } elseif ($product_type == "giftcard") {
            flash("message", gt("Giftcard saved."));
            redirect_to(array('controller' => 'store', 'action' => 'manage'));
        } elseif ($product_type == "eventregistration") {
            //FIXME shouldn't event registrations be added to search index?
//            $record->addContentToSearch();  //FIXME there is NO eventregistration::addContentToSearch() method
            flash("message", gt("Event saved."));
            redirect_to(array('controller' => 'store', 'action' => 'manage'));
        } elseif ($product_type == "donation") {
            flash("message", gt("Donation saved."));
            redirect_to(array('controller' => 'store', 'action' => 'manage'));
        }
    }

    function delete() {
        global $db;

        if (empty($this->params['id'])) return false;
        $product_type = $db->selectValue('product', 'product_type', 'id=' . $this->params['id']);
        $product = new $product_type($this->params['id'], true, false);
        //eDebug($product_type);  
        //eDebug($product, true);
        //if (!empty($product->product_type_id)) {
        //$db->delete($product_type, 'id='.$product->product_id);
        //}

        $db->delete('option', 'product_id=' . $product->id . " AND optiongroup_id IN (SELECT id from " . DB_TABLE_PREFIX . "_optiongroup WHERE product_id=" . $product->id . ")");
        $db->delete('optiongroup', 'product_id=' . $product->id);
        //die();
        $db->delete('product_storeCategories', 'product_id=' . $product->id . ' AND product_type="' . $product_type . '"');

        if ($product->product_type == "product") {
            if ($product->hasChildren()) {
                $this->deleteChildren();
            }
        }

        $product->delete();

        flash('message', gt('Product deleted successfully.'));
        expHistory::back();
    }

    function quicklinks() {
        global $order;

        assign_to_template(array(
            "grand_total" => $order->total,
        ));
    }

    static public function getProductTypes() {
        $paths = array(
            BASE . 'framework/modules/ecommerce/products/models',
        );

        $products = array();
        foreach ($paths as $path) {
            if (is_readable($path)) {
                $dh = opendir($path);
                while (($file = readdir($dh)) !== false) {
                    if (is_readable($path . '/' . $file) && substr($file, -4) == '.php' && $file != 'childProduct.php') {
                        $classname = substr($file, 0, -4);
                        $products[$path . '/' . $file] = $classname;
                    }
                }
            }
        }

        return $products;
    }

    function metainfo() {
        global $router;

        if (empty($router->params['action'])) return false;

        // figure out what metadata to pass back based on the action we are in.
        $action = $_REQUEST['action'];
        $metainfo = array('title'=>'', 'keywords'=>'', 'description'=>'', 'canonical'=> '');
        switch ($action) {
            case 'showall': //category page
                //$cat = new storeCategory(isset($_REQUEST['title']) ? $_REQUEST['title']: $_REQUEST['id']);
                $cat = $this->category;
                if (!empty($cat)) {
                    $metainfo['title'] = empty($cat->meta_title) ? $cat->title : $cat->meta_title;
                    $metainfo['keywords'] = empty($cat->meta_keywords) ? $cat->title : strip_tags($cat->meta_keywords);
                    $metainfo['description'] = empty($cat->meta_description) ? strip_tags($cat->body) : strip_tags($cat->meta_description);
                    $metainfo['canonical'] = empty($cat->canonical) ? '' : strip_tags($cat->canonical);
                }
                break;
            case 'show':
            case 'showByTitle':
                $prod = new product(isset($_REQUEST['title']) ? expString::sanitize($_REQUEST['title']) : intval($_REQUEST['id']));
                if (!empty($prod)) {
                    $metainfo['title'] = empty($prod->meta_title) ? $prod->title : $prod->meta_title;
                    $metainfo['keywords'] = empty($prod->meta_keywords) ? $prod->title : strip_tags($prod->meta_keywords);
                    $metainfo['description'] = empty($prod->meta_description) ? strip_tags($prod->body) : strip_tags($prod->meta_description);
                    $metainfo['canonical'] = empty($prod->canonical) ? '' : strip_tags($prod->canonical);
                    break;
                }
            default:
                $metainfo = array('title' => $this->displayname() . " - " . SITE_TITLE, 'keywords' => SITE_KEYWORDS, 'description' => SITE_DESCRIPTION, 'canonical'=> '');
        }

        // Remove any quotes if there are any.
        $metainfo['title'] = expString::parseAndTrim($metainfo['title'], true);
        $metainfo['description'] = expString::parseAndTrim($metainfo['description'], true);
        $metainfo['keywords'] = expString::parseAndTrim($metainfo['keywords'], true);
        $metainfo['canonical'] = expString::parseAndTrim($metainfo['canonical'], true);

        return $metainfo;
    }

    public function deleteChildren() {
        //eDebug($data[0],true);
        //if($id!=null) $this->params['id'] = $id;
        //eDebug($this->params,true);        
        $product = new product($this->params['id']);
        //$product = $product->find("first", "previous_id =" . $previous_id);
        //eDebug($product, true);
        if (empty($product->id)) // || empty($product->previous_id)) 
        {
            flash('error', gt('There was an error deleting the child products.'));
            expHistory::back();
        }
        $childrenToDelete = $product->find('all', 'parent_id=' . $product->id);
        foreach ($childrenToDelete as $ctd) {
            //fwrite($lfh, "Deleting:" . $ctd->id . "\n");                             
            $ctd->delete();
        }
    }

    function searchByModelForm() {
        // get the search terms
        $terms = $this->params['search_string'];

        $sql = "model like '%" . $terms . "%'";

        $limit = !empty($this->config['limit']) ? $this->config['limit'] : 10;
        $page = new expPaginator(array(
            'model'      => 'product',
            'where'      => $sql,
            'limit'      => !empty($this->config['pagination_default']) ? $this->config['pagination_default'] : $limit,
            'order'      => 'title',
            'dir'        => 'DESC',
            'page'       => (isset($this->params['page']) ? $this->params['page'] : 1),
            'controller' => $this->params['controller'],
            'action'     => $this->params['action'],
            'columns'    => array(
                gt('Model #')      => 'model',
                gt('Product Name') => 'title',
                gt('Price')        => 'base_price'
            ),
        ));

        assign_to_template(array(
            'page'  => $page,
            'terms' => $terms
        ));
    }

    function search_by_model() {
        global $db, $user;

        $sql = "select DISTINCT(p.id) as id, p.title, model from " . $db->prefix . "product as p WHERE ";
        if (!($user->is_admin || $user->is_acting_admin)) $sql .= '(p.active_type=0 OR p.active_type=1) AND ';

        //if first character of search is a -, then we do a wild card, else from beginning
        if ($this->params['query'][0] == '-') {
            $sql .= " p.model LIKE '%" . $this->params['query'];
        } else {
            $sql .= " p.model LIKE '" . $this->params['query'];
        }

        $sql .= "%' AND p.parent_id=0 GROUP BY p.id ";
        $sql .= "order by p.model ASC LIMIT 30";
        $res = $db->selectObjectsBySql($sql);
        //eDebug($sql);
        $ar = new expAjaxReply(200, gt('Here\'s the items you wanted'), $res);
        $ar->send();
    }

    public function search() {
        global $db, $user;

        //$this->params['query'] = str_ireplace('-','\-',$this->params['query']);
        $terms = explode(" ", $this->params['query']);
        $sql = "select DISTINCT(p.id) as id, p.title, capacity, sef_url, f.id as fileid, match (p.title,p.body) against ('" . $this->params['query'] . "*' IN BOOLEAN MODE) as score ";
        $sql .= "  from " . $db->prefix . "product as p INNER JOIN " .
            $db->prefix . "content_expFiles as cef ON p.id=cef.content_id INNER JOIN " . $db->prefix .
            "expFiles as f ON cef.expFiles_id = f.id WHERE ";
        if (!($user->is_admin || $user->is_acting_admin)) $sql .= '(p.active_type=0 OR p.active_type=1) AND ';
        $sql .= " match (p.title,p.body) against ('" . $this->params['query'] . "*' IN BOOLEAN MODE) AND p.parent_id=0  GROUP BY p.id ";
        $sql .= "order by score desc LIMIT 10";

        $res = $db->selectObjectsBySql($sql);

		for($i=0; $i < count($res); $i++) {
			if (preg_match('/^.{1,30}\b/s', $res[$i]->title, $compact_title)) {
			    $res[$i]->title= str_replace(" &", "", $compact_title[0]) . ' ' . $res[$i]->capacity;
			}
		}
		
        $ar = new expAjaxReply(200, gt('Here\'s the items you wanted'), $res);
        $ar->send();
    }

    public function searchNew() {
        global $db, $user;
        //$this->params['query'] = str_ireplace('-','\-',$this->params['query']);
        $sql = "select DISTINCT(p.id) as id, p.title, model, sef_url, f.id as fileid, ";
        $sql .= "match (p.title,p.model,p.body) against ('" . $this->params['query'] . "*' IN BOOLEAN MODE) as relevance, ";
        $sql .= "CASE when p.model like '" . $this->params['query'] . "%' then 1 else 0 END as modelmatch, ";
        $sql .= "CASE when p.title like '%" . $this->params['query'] . "%' then 1 else 0 END as titlematch ";
        $sql .= "from " . $db->prefix . "product as p INNER JOIN " .
            $db->prefix . "content_expFiles as cef ON p.id=cef.content_id INNER JOIN " . $db->prefix .
            "expFiles as f ON cef.expFiles_id = f.id WHERE ";
        if (!($user->is_admin || $user->is_acting_admin)) $sql .= '(p.active_type=0 OR p.active_type=1) AND ';
        $sql .= " match (p.title,p.model,p.body) against ('" . $this->params['query'] . "*' IN BOOLEAN MODE) AND p.parent_id=0 ";
        $sql .= " HAVING relevance > 0 ";
        //$sql .= "GROUP BY p.id "; 
        $sql .= "order by modelmatch,titlematch,relevance desc LIMIT 10";

        eDebug($sql);
        $res = $db->selectObjectsBySql($sql);
        eDebug($res, true);
        $ar = new expAjaxReply(200, gt('Here\'s the items you wanted'), $res);
        $ar->send();
    }

    function batch_process() {
        $os = new order_status();
        $oss = $os->find('all');
        $order_status = array();
        $order_status[-1] = '';
        foreach ($oss as $status) {
            $order_status[$status->id] = $status->title;
        }
        assign_to_template(array(
            'order_status' => $order_status
        ));
    }

    function process_orders() {
        /*
          Testing
        */
        /*echo "Here?";
        $inv = 30234;
        $req = 'a29f9shsgh32hsf80s7';        
        $amt = 101.00;
        for($count=1;$count<=25;$count+=2)
        {   
            $data[2] = $inv + $count;
            $amt += $count*$count;
            $successSet[$count]['message'] = "Sucessfully imported row " . $count . ", order: " . $data[2] . "<br/>";                
            $successSet[$count]['order_id'] = $data[2];
            $successSet[$count]['amount'] = $amt;
            $successSet[$count]['request_id'] = $req;
            $successSet[$count]['reference_id'] = $req;
            $successSet[$count]['authorization_code'] = $req;
            $successSet[$count]['shipping_tracking_number'] = '1ZNF453937547';    
            $successSet[$count]['carrier'] = 'UPS';
        }
        for($count=2;$count<=25;$count+=2)
        {   
            $data[2] = $inv + $count;                
            $amt += $count*$count;        
            $errorSet[$count]['error_code'] = '42';
            $errorSet[$count]['message'] = "No go for some odd reason. Try again.";
            $errorSet[$count]['order_id'] = $data[2];
            $errorSet[$count]['amount'] = $amt;
        }
        
        assign_to_template(array('errorSet'=>$errorSet, 'successSet'=>$successSet));     
        return;*/

        ###########

        global $db;
        $template = get_template_for_action(new orderController(), 'setStatus', $this->loc);

        //eDebug($_FILES);
        //eDebug($this->params,true); 
        set_time_limit(0);
        //$file = new expFile($this->params['expFile']['batch_process_upload'][0]);
        if (!empty($_FILES['batch_upload_file']['error'])) {
            flash('error', gt('There was an error uploading your file.  Please try again.'));
            redirect_to(array('controller' => 'store', 'action' => 'batch_process'));
//            $this->batch_process();
        }

        $file = new stdClass();
        $file->path = $_FILES['batch_upload_file']['tmp_name'];
        echo "Validating file...<br/>";

        $checkhandle = fopen($file->path, "r");
        $checkdata = fgetcsv($checkhandle, 10000, ",");
        $fieldCount = count($checkdata);
        $count = 1;
        while (($checkdata = fgetcsv($checkhandle, 10000, ",")) !== FALSE) {
            $count++;
            if (count($checkdata) != $fieldCount) {
                echo "Line " . $count . " of your CSV import file does not contain the correct number of columns.<br/>";
                echo "Found " . $fieldCount . " header fields, but only " . count($checkdata) . " field in row " . $count . " Please check your file and try again.";
                exit();
            }
        }
        fclose($checkhandle);

        echo "<br/>CSV File passed validation...<br/><br/>Detecting carrier type....<br/>";
        //exit();
        $handle = fopen($file->path, "r");
        $data = fgetcsv($handle, 10000, ",");
        //eDebug($data);      
        $dataset = array();
        $carrier = '';
        if (trim($data[0]) == 'ShipmentInformationShipmentID') {
            echo "Detected UPS file...<br/>";
            $carrier = "UPS";
            $carrierTrackingLink = "http://wwwapps.ups.com/etracking/tracking.cgi?TypeOfInquiryNumber=T&InquiryNumber1=";
        } elseif (trim($data[0]) == 'PIC') {
            echo "Detected United States Post Service file...<br/>";
            $carrier = "USPS";
            $carrierTrackingLink = "http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=";
        }

        //eDebug($carrier);
        $count = 1;
        $errorSet = array();
        $successSet = array();

        $oo = new order();

        while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
            $count++;
            $originalOrderId = $data[2];
            $data[2] = intval($data[2]);
            $order = new stdClass();
            $bm = new stdClass();
            $transactionState = null;

            //check for valid order number - if not present or not order, fail and continue with next record
            if (isset($data[2]) && !empty($data[2])) {
                $order = $oo->findBy('invoice_id', $data[2]);
                if (empty($order->id)) {
                    $errorSet[$count]['message'] = $originalOrderId . " is not a valid order in this system.";
                    $errorSet[$count]['order_id'] = $originalOrderId;
                    continue;
                }
            } else {
                $errorSet[$count]['message'] = "Row " . $count . " has no order number.";
                $errorSet[$count]['order_id'] = "N/A";
                continue;
            }

            /*we have a valid order, so let's see what we can do: */

            //set status of order to var
            $currentStat = $order->order_status;
            //eDebug($currentStat,true);

            //-- check the order for a closed status - if so, do NOT process or set shipping
            if ($currentStat->treat_as_closed == true) {
                $errorSet[$count]['message'] = "This is currently a closed order. Not processing.";
                $errorSet[$count]['order_id'] = $data[2];
                continue;
            }

            //ok, if we made it here we have a valid order that is "open"
            //we'll try to capture the transaction if it's in an authorized state, but set shipping regardless
            if (isset($order->billingmethod[0])) {
                $bm = $order->billingmethod[0];
                $transactionState = $bm->transaction_state;
            } else {
                $bm = null;
                $transactionState = '';
            }

            if ($transactionState == 'authorized') {
                //eDebug($order,true);
                $calc = $bm->billingcalculator->calculator;
                $calc->config = $bm->billingcalculator->config;
                if (method_exists($calc, 'delayed_capture')) {
                    //$result = $calc->delayed_capture($bm,$bm->billing_cost);
                    $result = $calc->delayed_capture($bm, $order->grand_total, $order);
                    if ($result->errorCode == 0) {
                        //we've succeeded.  transaction already created and billing info updated.
                        //just need to set the order shipping info, check and see if we send user an email, and set statuses.  
                        //shipping info:                                      
                        $successSet[$count]['order_id'] = $data[2];
                        $successSet[$count]['message'] = "Sucessfully captured order " . $data[2] . " and set shipping information.";
                        $successSet[$count]['amount'] = $order->grand_total;
                        $successSet[$count]['request_id'] = $result->request_id;
                        $successSet[$count]['reference_id'] = $result->PNREF;
                        $successSet[$count]['authorization_code'] = $result->AUTHCODE;
                        $successSet[$count]['shipping_tracking_number'] = $data[0];
                        $successSet[$count]['carrier'] = $carrier;
                    } else {
                        //failed capture, so we report the error but still set the shipping information
                        //because it's already out the door
                        //$failMessage = "Attempted to delay capture order " . $data[2] . " and it failed with the following error: " . $result->errorCode . " - " .$result->message;   
                        //if the user seelected to set a different status for failed orders, set it here.
                        /*if(isset($this->params['order_status_fail'][0]) && $this->params['order_status_fail'][0] > -1)
                        {
                            $change = new order_status_changes();
                            // save the changes
                            $change->from_status_id = $order->order_status_id;
                            //$change->comment = $this->params['comment'];
                            $change->to_status_id = $this->params['order_status_fail'][0];
                            $change->orders_id = $order->id;
                            $change->save();
                            
                            // update the status of the order
                            $order->order_status_id = $this->params['order_status_fail'][0];
                            $order->save();                             
                        }*/
                        $errorSet[$count]['error_code'] = $result->errorCode;
                        $errorSet[$count]['message'] = "Capture failed: " . $result->message . "<br/>Setting shipping information.";
                        $errorSet[$count]['order_id'] = $data[2];
                        $errorSet[$count]['amount'] = $order->grand_total;
                        $errorSet[$count]['shipping_tracking_number'] = $data[0];
                        $errorSet[$count]['carrier'] = $carrier;
                        //continue;   
                    }
                } else {
                    //dont suppose we do anything here, as it may be set to approved manually 
                    //$errorSet[$count] = "Order " . $data[2] . " does not use a billing method with delayed capture ability.";  
                    $successSet[$count]['message'] = 'No capture processing available for order:' . $data[2] . '. Setting shipping information.';
                    $successSet[$count]['order_id'] = $data[2];
                    $successSet[$count]['amount'] = $order->grand_total;
                    $successSet[$count]['shipping_tracking_number'] = $data[0];
                    $successSet[$count]['carrier'] = $carrier;
                }
            } //if we hit this else, it means we have an order that is not in an authorized state
            //so we do not try to process it = still set shipping though.
            else {
                $successSet[$count]['message'] = 'No processing necessary for order:' . $data[2] . '. Setting shipping information.';
                $successSet[$count]['order_id'] = $data[2];
                $successSet[$count]['amount'] = $order->grand_total;
                $successSet[$count]['shipping_tracking_number'] = $data[0];
                $successSet[$count]['carrier'] = $carrier;
            }

            $order->shipped = time();
            $order->shipping_tracking_number = $data[0];
            $order->save();

            $s = array_pop($order->shippingmethods);
            $sm = new shippingmethod($s->id);
            $sm->carrier = $carrier;
            $sm->save();

            //statuses and email
            if (isset($this->params['order_status_success'][0]) && $this->params['order_status_success'][0] > -1) {
                $change = new order_status_changes();
                // save the changes
                $change->from_status_id = $order->order_status_id;
                //$change->comment = $this->params['comment'];
                $change->to_status_id = $this->params['order_status_success'][0];
                $change->orders_id = $order->id;
                $change->save();

                // update the status of the order
                $order->order_status_id = $this->params['order_status_success'][0];
                $order->save();

                // email the user if we need to
                if (!empty($this->params['email_customer'])) {
                    $email_addy = $order->billingmethod[0]->email;
                    if (!empty($email_addy)) {
                        $from_status = $db->selectValue('order_status', 'title', 'id=' . $change->from_status_id);
                        $to_status = $db->selectValue('order_status', 'title', 'id=' . $change->to_status_id);
//                        $template->assign(
                        assign_to_template(
                            array(
                                'comment'          => $change->comment,
                                'to_status'        => $to_status,
                                'from_status'      => $from_status,
                                'order'            => $order,
                                'date'             => date("F j, Y, g:i a"),
                                'storename'        => ecomconfig::getConfig('storename'),
                                'include_shipping' => true,
                                'tracking_link'    => $carrierTrackingLink . $order->shipping_tracking_number,
                                'carrier'          => $carrier
                            )
                        );

                        $html = $template->render();
                        $html .= ecomconfig::getConfig('ecomfooter');

                        $from = array(ecomconfig::getConfig('from_address')=> ecomconfig::getConfig('from_name'));
                        if (empty($from[0])) $from = SMTP_FROMADDRESS;
                        try {
                            $mail = new expMail();
                            $mail->quickSend(array(
                                'html_message' => $html,
                                'text_message' => str_replace("<br>", "\r\n", $template->render()),
                                'to'           => $email_addy,
                                'from'         => $from,
                                'subject'      => 'Your Order Has Been Shipped (#' . $order->invoice_id . ') - ' . ecomconfig::getConfig('storename')
                            ));
                        } catch (Exception $e) {
                            //do nothing for now
                            eDebug("Email error:");
                            eDebug($e);
                        }
                    }
                    //else {
                    //    $errorSet[$count]['message'] .= "<br/>Order " . $data[2] . " was captured successfully, however the email notification was not successful.";
                    //}
                }
            }

            //eDebug($product);        
        }

        assign_to_template(array(
            'errorSet'   => $errorSet,
            'successSet' => $successSet
        ));
    }

    function manage_sales_reps() {

    }

    function showHistory() {
        $h = new expHistory();
//        echo "<xmp>";
        echo "<pre>";
        print_r($h);
//        echo "</xmp>";
        echo "</pre>";
    }

    function import_external_addresses() {
        $sources = array('mc' => 'MilitaryClothing.com', 'nt' => 'NameTapes.com', 'am' => 'Amazon');
        assign_to_template(array(
            'sources' => $sources
        ));
    }

    function process_external_addresses() {
        global $db;
        set_time_limit(0);
        //$file = new expFile($this->params['expFile']['batch_process_upload'][0]);
        eDebug($this->params);
//        eDebug($_FILES,true);
        if (!empty($_FILES['address_csv']['error'])) {
            flash('error', gt('There was an error uploading your file.  Please try again.'));
            redirect_to(array('controller' => 'store', 'action' => 'import_external_addresses'));
//            $this->import_external_addresses();
        }

        $file = new stdClass();
        $file->path = $_FILES['address_csv']['tmp_name'];
        echo "Validating file...<br/>";

        //replace tabs with commas
        /*if($this->params['type_of_address'][0] == 'am')
        {
            $checkhandle = fopen($file->path, "w");
            $oldFile = file_get_contents($file->path);
            $newFile = str_ireplace(chr(9),',',$oldFile);
            fwrite($checkhandle,$newFile);
            fclose($checkhandle);
        }*/

        $checkhandle = fopen($file->path, "r");
        if ($this->params['type_of_address'][0] == 'am') {
            $checkdata = fgetcsv($checkhandle, 10000, "\t");
            $fieldCount = count($checkdata);
        } else {
            $checkdata = fgetcsv($checkhandle, 10000, ",");
            $fieldCount = count($checkdata);
        }

        $count = 1;
        if ($this->params['type_of_address'][0] == 'am') {
            while (($checkdata = fgetcsv($checkhandle, 10000, "\t")) !== FALSE) {
                $count++;
                //eDebug($checkdata);
                if (count($checkdata) != $fieldCount) {
                    echo "Line " . $count . " of your CSV import file does not contain the correct number of columns.<br/>";
                    echo "Found " . $fieldCount . " header fields, but only " . count($checkdata) . " field in row " . $count . " Please check your file and try again.";
                    exit();
                }
            }
        } else {
            while (($checkdata = fgetcsv($checkhandle, 10000, ",")) !== FALSE) {
                $count++;
                if (count($checkdata) != $fieldCount) {
                    echo "Line " . $count . " of your CSV import file does not contain the correct number of columns.<br/>";
                    echo "Found " . $fieldCount . " header fields, but only " . count($checkdata) . " field in row " . $count . " Please check your file and try again.";
                    exit();
                }
            }
        }

        fclose($checkhandle);

        echo "<br/>CSV File passed validation...<br/><br/>Importing....<br/><br/>";
        //exit();
        $handle = fopen($file->path, "r");
        $data = fgetcsv($handle, 10000, ",");
        //eDebug($data);      
        $dataset = array();

        //mc=1, nt=2, amm=3

        if ($this->params['type_of_address'][0] == 'mc') {
            //militaryclothing
            $db->delete('external_addresses', 'source=1');

        } else if ($this->params['type_of_address'][0] == 'nt') {
            //nametapes
            $db->delete('external_addresses', 'source=2');
        } else if ($this->params['type_of_address'][0] == 'am') {
            //amazon
            $db->delete('external_addresses', 'source=3');
        }

        if ($this->params['type_of_address'][0] == 'am') {
            while (($data = fgetcsv($handle, 10000, "\t")) !== FALSE) {
                //eDebug($data,true);
                $extAddy = new external_address();

                //eDebug($data);
                $extAddy->source = 3;
                $extAddy->user_id = 0;
                $name = explode(' ', $data[15]);
                $extAddy->firstname = $name[0];
                if (isset($name[3])) {
                    $extAddy->firstname .= ' ' . $name[1];
                    $extAddy->middlename = $name[2];
                    $extAddy->lastname = $name[3];
                } else if (isset($name[2])) {
                    $extAddy->middlename = $name[1];
                    $extAddy->lastname = $name[2];
                } else {
                    $extAddy->lastname = $name[1];
                }
                $extAddy->organization = $data[15];
                $extAddy->address1 = $data[16];
                $extAddy->address2 = $data[17];
                $extAddy->city = $data[19];
                $state = new geoRegion();
                $state = $state->findBy('code', trim($data[20]));
                if (empty($state->id)) {
                    $state = new geoRegion();
                    $state = $state->findBy('name', trim($data[20]));
                }
                $extAddy->state = $state->id;
                $extAddy->zip = str_ireplace("'", '', $data[21]);
                $extAddy->phone = $data[6];
                $extAddy->email = $data[4];
                //eDebug($extAddy);
                $extAddy->save();
            }
        } else {
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                eDebug($data);
                $extAddy = new external_address();
                if ($this->params['type_of_address'][0] == 'mc') {
                    $extAddy->source = 1;
                    $extAddy->user_id = 0;
                    $name = explode(' ', $data[3]);
                    $extAddy->firstname = $name[0];
                    if (isset($name[2])) {
                        $extAddy->middlename = $name[1];
                        $extAddy->lastname = $name[2];
                    } else {
                        $extAddy->lastname = $name[1];
                    }
                    $extAddy->organization = $data[4];
                    $extAddy->address1 = $data[5];
                    $extAddy->address2 = $data[6];
                    $extAddy->city = $data[7];
                    $state = new geoRegion();
                    $state = $state->findBy('code', $data[8]);
                    $extAddy->state = $state->id;
                    $extAddy->zip = str_ireplace("'", '', $data[9]);
                    $extAddy->phone = $data[20];
                    $extAddy->email = $data[21];
                    //eDebug($extAddy);
                    $extAddy->save();

                    //Check if the shipping add is same as the billing add
                    if ($data[5] != $data[14]) {
                        $extAddy = new external_address();
                        $extAddy->source = 1;
                        $extAddy->user_id = 0;
                        $name = explode(' ', $data[12]);
                        $extAddy->firstname = $name[0];
                        if (isset($name[2])) {
                            $extAddy->middlename = $name[1];
                            $extAddy->lastname = $name[2];
                        } else {
                            $extAddy->lastname = $name[1];
                        }
                        $extAddy->organization = $data[13];
                        $extAddy->address1 = $data[14];
                        $extAddy->address2 = $data[15];
                        $extAddy->city = $data[16];
                        $state = new geoRegion();
                        $state = $state->findBy('code', $data[17]);
                        $extAddy->state = $state->id;
                        $extAddy->zip = str_ireplace("'", '', $data[18]);
                        $extAddy->phone = $data[20];
                        $extAddy->email = $data[21];
                        // eDebug($extAddy, true);
                        $extAddy->save();
                    }
                }
                if ($this->params['type_of_address'][0] == 'nt') {
                    //eDebug($data,true);
                    $extAddy->source = 2;
                    $extAddy->user_id = 0;
                    $extAddy->firstname = $data[16];
                    $extAddy->lastname = $data[17];
                    $extAddy->organization = $data[15];
                    $extAddy->address1 = $data[18];
                    $extAddy->address2 = $data[19];
                    $extAddy->city = $data[20];
                    $state = new geoRegion();
                    $state = $state->findBy('code', $data[21]);
                    $extAddy->state = $state->id;
                    $extAddy->zip = str_ireplace("'", '', $data[22]);
                    $extAddy->phone = $data[23];
                    $extAddy->email = $data[13];
                    //eDebug($extAddy);
                    $extAddy->save();
                }
            }
        }
        echo "Done!";
    }

    function nonUnicodeProducts() {
        global $db, $user;

        $products = $db->selectObjectsIndexedArray('product');
        $affected_fields = array();
        $listings = array();
        $listedProducts = array();
        $count = 0;
        //Get all the columns of the product table
        $columns = $db->getTextColumns('product');
        foreach ($products as $item) {

            foreach ($columns as $column) {
                if ($column != 'body' && $column != 'summary' && $column != 'featured_body') {
                    if (!expString::validUTF($item->$column) || strrpos($item->$column, '?')) {
                        $affected_fields[] = $column;
                    }
                } else {
                    if (!expString::validUTF($item->$column)) {
                        $affected_fields[] = $column;
                    }
                }
            }

            if (isset($affected_fields)) {
                if (count($affected_fields) > 0) {
                    //Hard coded fields since this is only for displaying
                    $listedProducts[$count]['id'] = $item->id;
                    $listedProducts[$count]['title'] = $item->title;
                    $listedProducts[$count]['model'] = $item->model;
                    $listedProducts[$count]['sef_url'] = $item->sef_url;
                    $listedProducts[$count]['nonunicode'] = implode(', ', $affected_fields);
                    $count++;
                }
            }
            unset($affected_fields);
        }

        assign_to_template(array(
            'products' => $listedProducts,
            'count'    => $count
        ));
    }

    function cleanNonUnicodeProducts() {
        global $db, $user;

        $products = $db->selectObjectsIndexedArray('product');
        //Get all the columns of the product table
        $columns = $db->getTextColumns('product');
        foreach ($products as $item) {
            //Since body, summary, featured_body can have a ? intentionally such as a link with get parameter.
            //TO Improved
            foreach ($columns as $column) {
                if ($column != 'body' && $column != 'summary' && $column != 'featured_body') {
                    if (!expString::validUTF($item->$column) || strrpos($item->$column, '?')) {
                        $item->$column = expString::convertUTF($item->$column);
                    }
                } else {
                    if (!expString::validUTF($item->$column)) {
                        $item->$column = expString::convertUTF($item->$column);
                    }
                }
            }

            $db->updateObject($item, 'product');
        }

        redirect_to(array('controller' => 'store', 'action' => 'nonUnicodeProducts'));
//        $this->nonUnicodeProducts();
    }

    //This function is being used in the uploadModelaliases page for showing the form upload
    function uploadModelAliases() {
        global $db;
        set_time_limit(0);

        if (isset($_FILES['modelaliases']['tmp_name'])) {
            if (!empty($_FILES['modelaliases']['error'])) {
                flash('error', gt('There was an error uploading your file.  Please try again.'));
//				redirect_to(array('controller'=>'store','action'=>'uploadModelAliases'));
                $this->uploadModelAliases();
            }

            $file = new stdClass();
            $file->path = $_FILES['modelaliases']['tmp_name'];
            echo "Validating file...<br/>";

            $checkhandle = fopen($file->path, "r");
            $checkdata = fgetcsv($checkhandle, 10000, ",");
            $fieldCount = count($checkdata);
            $count = 1;

            while (($checkdata = fgetcsv($checkhandle, 10000, ",")) !== FALSE) {
                $count++;
                if (count($checkdata) != $fieldCount) {
                    echo "Line " . $count . " of your CSV import file does not contain the correct number of columns.<br/>";
                    echo "Found " . $fieldCount . " header fields, but only " . count($checkdata) . " field in row " . $count . " Please check your file and try again.";
                    exit();
                }
            }

            fclose($checkhandle);

            echo "<br/>CSV File passed validation...<br/><br/>Importing....<br/><br/>";
            $handle = fopen($file->path, "r");
            $data = fgetcsv($handle, 10000, ",");

            //clear the db
            $db->delete('model_aliases_tmp');
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {

                $tmp = new stdClass();
                $tmp->field1 = expString::onlyReadables($data[0]);
                $tmp->field2 = expString::onlyReadables($data[1]);
                $db->insertObject($tmp, 'model_aliases_tmp');
            }
            redirect_to(array('controller' => 'store', 'action' => 'processModelAliases'));
            echo "Done!";
        }

        //check if there are interrupted model alias in the db
        $res = $db->selectObjectsBySql("SELECT * FROM ".DB_TABLE_PREFIX."_model_aliases_tmp WHERE is_processed = 0");
        if (!empty($res)) {
            assign_to_template(array(
                'continue' => '1'
            ));
        }
    }

    // This function process the uploading of the model aliases in the uploadModelAliases page
    function processModelAliases($index = 0, $error = '') {
        global $db;

        //Going next and delete the previous one
        if (isset($this->params['index'])) {
            $index = $this->params['index'];

            //if go to the next processs
            if (isset($this->params['next'])) {
                $res = $db->selectObjectBySql("SELECT * FROM ".DB_TABLE_PREFIX."_model_aliases_tmp LIMIT " . ($index - 1) . ", 1");
                //Update the record in the tmp table to mark it as process
                $res->is_processed = 1;
                $db->updateObject($res, 'model_aliases_tmp');
            }
        }

        $product_id = '';
        $autocomplete = '';

        do {
            $count = $db->countObjects('model_aliases_tmp', 'is_processed=0');
            $res = $db->selectObjectBySql("SELECT * FROM ".DB_TABLE_PREFIX."_model_aliases_tmp LIMIT {$index}, 1");
            //Validation
            //Check the field one
            if (!empty($res)) {
                $product_field1 = $db->selectObject("product", "model='{$res->field1}'");
                $product_field2 = $db->selectObject("product", "model='{$res->field2}'");
            }
            if (!empty($product_field1)) {
                $product_id = $product_field1->id;
                //check the other field if it also being used by another product
                if (!empty($product_field2) && $product_field1->id != $product_field2->id) {
                    $error = "Both {$res->field1} and {$res->field2} are models of a product. <br />";
                } else {
                    //Check the field2 if it is already in the model alias
                    $model_alias = $db->selectObject("model_aliases", "model='{$res->field2}'");
                    if (empty($model_alias) && @$model_alias->product_id != $product_field1->id) {
                        //Add the first field
                        $tmp = new  stdClass();
                        $tmp->model = $res->field1;
                        $tmp->product_id = $product_field1->id;
                        $db->insertObject($tmp, 'model_aliases');
                        //Add the second field
                        $tmp->model = $res->field2;
                        $tmp->product_id = $product_field1->id;
                        $db->insertObject($tmp, 'model_aliases');
                        //Update the record in the tmp table to mark it as process
                        $res->is_processed = 1;
                        $db->updateObject($res, 'model_aliases_tmp');

                    } else {
                        $error = "{$res->field2} has already a product alias. <br />";
                    }
                }
            } elseif (!empty($product_field2)) {
                $product_id = $product_field2->id;
                $model_alias = $db->selectObject("model_aliases", "model='{$res->field1}'");
                if (empty($model_alias) && @$model_alias->product_id != $product_field2->id) {
                    //Add the first field
                    $tmp = new stdClass();
                    $tmp->model = $res->field1;
                    $tmp->product_id = $product_field2->id;
                    $db->insertObject($tmp, 'model_aliases');
                    //Add the second field
                    $tmp->model = $res->field2;
                    $tmp->product_id = $product_field2->id;
                    $db->insertObject($tmp, 'model_aliases');
                    //Update the record in the tmp table to mark it as process
                    $res->is_processed = 1;
                    $db->updateObject($res, 'model_aliases_tmp');
                } else {
                    $error = "{$res->field1} has already a product alias. <br />";
                }
            } else {
                $model_alias1 = $db->selectObject("model_aliases", "model='{$res->field1}'");
                $model_alias2 = $db->selectObject("model_aliases", "model='{$res->field2}'");

                if (!empty($model_alias1) || !empty($model_alias2)) {
                    $error = "The {$res->field1} and {$res->field2} are already being used by another product.<br />";
                } else {
                    $error = "No product match found, please choose a product to be alias in the following models below:<br />";
                    $error .= $res->field1 . "<br />";
                    $error .= $res->field2 . "<br />";
                    $autocomplete = 1;
                }
            }
            $index++;
        } while (empty($error));
        assign_to_template(array(
            'count'        => $count,
            'alias'        => $res,
            'index'        => $index,
            'product_id'   => $product_id,
            'autocomplete' => $autocomplete,
            'error'        => $error
        ));
    }

    // This function save the uploaded processed model aliases in the uploadModelAliases page
    function saveModelAliases() {
        global $db;

        $index = $this->params['index'];
        $title = mysql_real_escape_string($this->params['product_title']);
        $product = $db->selectObject("product", "title='{$title}'");

        if (!empty($product->id)) {
            $res = $db->selectObjectBySql("SELECT * FROM ".DB_TABLE_PREFIX."_model_aliases_tmp LIMIT " . ($index - 1) . ", 1");
            //Add the first field
            $tmp = new stdClass();
            $tmp->model = $res->field1;
            $tmp->product_id = $product->id;
            $db->insertObject($tmp, 'model_aliases');
            //Add the second field
            $tmp->model = $res->field2;
            $tmp->product_id = $product->id;
            $db->insertObject($tmp, 'model_aliases');

            //if the model is empty, update the product table so that it will used the field 1 as its primary model
            if (empty($product->model)) {
                $product->model = $res->field1;
                $db->updateObject($product, 'product');
            }

            //Update the record in the tmp table to mark it as process
            $res->is_processed = 1;
            $db->updateObject($res, 'model_aliases_tmp');
            flash("message", gt("Product successfully Saved."));
            redirect_to(array('controller' => 'store', 'action' => 'processModelAliases', 'index' => $index));
        } else {
            flash("error", gt("Product title is invalid."));
            redirect_to(array('controller' => 'store', 'action' => 'processModelAliases', 'index' => $index - 1, 'error' => 'Product title is invalid.'));
        }
    }

    // This function delete all the already processed model aliases in the uploadModelAliases page
    function deleteProcessedModelAliases() {
        global $db;

        $db->delete('model_aliases_tmp', 'is_processed=1');
        redirect_to(array('controller' => 'store', 'action' => 'processModelAliases'));
    }

    // This function show the form of model alias to be edit or add in the product edit page
    function edit_model_alias() {
        global $db;

        if (isset($this->params['id'])) {
            $model_alias = $db->selectObject('model_aliases', 'id =' . $this->params['id']);
            assign_to_template(array(
                'model_alias' => $model_alias
            ));
        } else {
            assign_to_template(array(
                'product_id' => $this->params['product_id']
            ));
        }
    }

    // This function update or add the model alias in the product edit page
    function update_model_alias() {
        global $db;

        if (empty($this->params['id'])) {
            $obj = new stdClass();
            $obj->model = $this->params['model'];
            $obj->product_id = $this->params['product_id'];
            $db->insertObject($obj, 'model_aliases');

        } else {
            $model_alias = $db->selectObject('model_aliases', 'id =' . $this->params['id']);
            $model_alias->model = $this->params['model'];
            $db->updateObject($model_alias, 'model_aliases');
        }

        expHistory::back();
    }

    // This function delete the model alias in the product edit page
    function delete_model_alias() {
        global $db;

        if (empty($this->params['id'])) return false;
        $db->delete('model_aliases', 'id =' . $this->params['id']);

        expHistory::back();
    }

    function setup_wizard() {

    }
}

?>
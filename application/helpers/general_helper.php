<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * function slug take a title|String and return URL|String
 *
 * @param {title|String}
 * @return {url|String}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function slug($title){

    $ci = &get_instance();

    $title = strtolower(trim($title));
    $title = preg_replace('/\s+/', ' ',  $title);
    $title = preg_replace("/[^A-Za-z0-9 ]/", '', $title );
    $title = str_replace(" ", '-', $title );
    $countter = 0;
    foreach($GLOBALS['PAGES_TABLES'] as $table){
        $result = $ci->db->query('
            SELECT *
            FROM '.$table.'
            WHERE slug LIKE "'.$title.'%"
          ');
        if($result->num_rows()>0)
            $countter = ($countter+$result->num_rows());
    }

    if($countter > 0)
        $slug = $title.'-'.($countter+1);
    else
        $slug = $title;

    return $slug;

}
/**
 * function auth authorizes the admin or redirects to login page.
 *
 * 
 * @return {authorized|Boolean}
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function auth(){
	
	$ci = &get_instance();

	
	
	$is_login = $ci->session->userdata('is_Login');
	
	if($is_login === true){
		
		$admin_id = $ci->session->userdata('admin_id');
		
		if($ci->session->userdata("type_vendor")==1){
			$admin_data = get_company_by_id($admin_id);
		}
		else{
		$admin_data = get_admin_by_id($admin_id);

		}

		
		if($admin_data->num_rows() == 0){
			
			$ci->session->sess_destroy();
			
			redirect(base_url()."admin/login");
			
		}else{
			
			return false;
			
		}
		
	}else{



		
		redirect(base_url()."admin/login");
		
		
	}
}

/**
 * function auth checks if admin is from a region or not.
 *
 * 
 * @return {Region Admin or Not|Boolean}
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function is_region(){
	
	$ci = &get_instance();

	
	
	$is_login = $ci->session->userdata('is_Login');
	
	if($is_login === true){
		
		$admin_id = $ci->session->userdata('admin_id');
		
		$admin_data = get_admin_by_id($admin_id);

		
		if($admin_data->num_rows() == 0){
			
			$ci->session->sess_destroy();
			
			redirect(base_url()."admin/login");
			
		}else{
			$admin_data=$admin_data->result_object()[0];
			return $admin_data->region_id!=0;
		}
		
	}else{
		redirect(base_url()."admin/login");
	}
}
/**
 * function redirects user to previous page if he is from a region and action is not allowed
 *
 * 
 * @return {Redirects|302}
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function redirect_region(){
	
	$ci = &get_instance();

	
	
	$is_login = $ci->session->userdata('is_Login');
	
	if($is_login === true){
		
		$admin_id = $ci->session->userdata('admin_id');
		
		$admin_data = get_admin_by_id($admin_id);

		
		if($admin_data->num_rows() == 0){
			
			$ci->session->sess_destroy();
			
			redirect(base_url()."admin/login");
			
		}else{
			$admin_data=$admin_data->result_object()[0];
			if($admin_data->region_id!=0)
			{
				redirect($_SERVER["HTTP_REFERER"]);
			}
		}
		
	}else{
		redirect(base_url()."admin/login");
	}
}

/**
 * function returns region ID of current logged in admin.
 *
 * 
 * @return {Region ID|Integer}
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function region_id(){
	
	$ci = &get_instance();

	
	
	$is_login = $ci->session->userdata('is_Login');
	
	if($is_login === true){
		
		$admin_id = $ci->session->userdata('admin_id');
		
		$admin_data = get_admin_by_id($admin_id);

		
		if($admin_data->num_rows() == 0){
			
			$ci->session->sess_destroy();
			
			redirect(base_url()."admin/login");
			
		}else{
			$admin_data=$admin_data->result_object()[0];
			return $admin_data->region_id;
		}
		
	}else{
		redirect(base_url()."admin/login");
	}
}


function vendor_id()
{
	$ci = &get_instance();

	
	
	$is_login = $ci->session->userdata('is_Login');
	
	if($is_login === true){
		
		$admin_id = $ci->session->userdata('admin_id');
		
		if($ci->session->userdata("type_vendor")==1){
			return $admin_id;
		}
		return 0;
	}
	return 0;
}
/**
 * function is_session checks if user is logged in and went to login page,
 * this function redirects user to dashboard itself.
 *
 * 
 * @return {authorized|Boolean}
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function is_session(){
	
	$ci = &get_instance();
	
	$is_login = $ci->session->userdata('is_Login');
	
	if($is_login === true){
		
		$admin_id = $ci->session->userdata('admin_id');
		// echo $admin_id."admin"; exit;

		if($ci->session->userdata("type_vendor")==1){
			$admin_data = get_company_by_id($admin_id);
		}
		else{
		$admin_data = get_admin_by_id($admin_id);

		}



		
		
		if($admin_data->num_rows() == 0){
			
			$ci->session->sess_destroy();
			
			redirect(base_url()."admin/login");
			
		}else{
			
			redirect('admin/dashboard');
			
		}
		
	}else{
		
		return true;
		
		
	}
}

function is_vendor()
{
	$ci = &get_instance();

	return $ci->session->userdata("type_vendor")==1;
}

/**
 * function get_admin_by_id provides admin object by ID
 *
 * @param {$id|int}
 * @return {authorized|Object}
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function get_admin_by_id($id){
	
	$ci = &get_instance();
	
	$result = $ci->db->query('
		SELECT *
		FROM admin
		WHERE id = '.$id.'
	');
	
	return $result;
	
}
/**
 * function get_admin_by_id provides admin object by ID
 *
 * @param {$id|int}
 * @return {authorized|Object}
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function get_company_by_id($id){
	
	$ci = &get_instance();
	
	$result = $ci->db->query('
		SELECT *
		FROM vendors
		WHERE id = '.$id.'
	');
	
	return $result;
	
}

/**
 * function settings provides global settings from settings table
 *
 * @return {settings|Object}
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function settings(){
	return array();
	// $ci = &get_instance();
	
	// $result = $ci->db->query('
	// 	SELECT *
	// 	FROM settings
	// 	WHERE id = 1
	// ');
	
	// return $result->row();
}
/**
 * function get_field_value_by_id provides an object containing value for specific
 * field in specific table against specific id.
 *
 * @param {$field|String} name of field
 * @param {$table|String} name of table
 * @param {$id|int} id of row
 * 
 * @return {$value|Object}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function get_field_value_by_id($field,$table,$id){
	
	$ci = &get_instance();
	
	$result = $ci->db->query('
		SELECT '.$field.'
		FROM '.$table.'
		WHERE id = '.$id.'
	');
	
	$result = $result->row();
	
	return $result->$field;
}

/**
 * function get_dot_extension_comma_splited gets all extensions allowed in db and 
 * returns them as comma splited. This function also puts dot (.) as prefix.
 *
 * @return {$extensions|array}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function get_dot_extension_comma_splited(){

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT extension
		FROM attachment_type
		WHERE status = 1
		AND is_deleted = 0
	');
    $arr = array();
    foreach($result->result() as $ext){
        $arr[] = '.'.strtolower($ext->extension);
    }

    return implode(',',$arr);
}
/**
 * function get_extension_comma_splitted gets all extensions allowed in db and 
 * returns them as comma splited.
 *
 * @return {$extensions|array}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function get_extension_comma_splited(){

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT extension
		FROM attachment_type
		WHERE status = 1
		AND is_deleted = 0
	');
    $arr = array();
    foreach($result->result() as $ext){
        $arr[] = strtolower($ext->extension);
    }

    return implode(',',$arr);


}

/**
 * function get_extension_piped_splited gets all extensions allowed in db and 
 * returns them as pipe splited.
 *
 * @return {$extensions|array}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function get_extension_piped_splited(){

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT extension
		FROM attachment_type
		WHERE status = 1
		AND is_deleted = 0
	');
    $arr = array();
    foreach($result->result() as $ext){
        $arr[] = strtolower($ext->extension);
    }

    return implode('|',$arr);


}
/**
 * function get_extensions_by_extend takes a string name of an extension, checks
 * if this extension is allowed in db or not, returns the object got from db.
 *
 * @param {$ext|String}
 * @return {$result|Object}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function get_extensions_by_extend($ext){

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT *
		FROM attachment_type
		WHERE status = 1
		AND is_deleted = 0
		AND extension = "'.$ext.'"
	');
    return $result;
}
/**
 * function get_attachment_by_id takes id as int and returns the attachement from 
 * db as object
 *
 * @param {$id|int}
 * @return {$result|Object}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function get_attachment_by_id($id){

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT *
		FROM attachments
		WHERE is_deleted = 0
		AND id = "'.$id.'"
	');


    return $result;

}
/**
 * function message_status_upadte updates status of messages. `is_read` = 1
 * this function takes array of ids of messages in conversation_messages table
 *
 * @param {$ids|array}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function message_status_upadte($ids){
	
	$ci = &get_instance();
	$ci->db->query('UPDATE `conversation_messages` SET `is_read` = 1 WHERE id IN ('.implode(',',$ids).')');
}

/**
 * function get_message_attachments takes ids of messages and returns objects 
 * of message attachements
 *
 * @param {$ids|array}
 * @return {$attachments|Object}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function get_message_attachments($ids){
    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT i.*,t.icon
		FROM attachments i
		LEFT JOIN attachment_type t
		ON i.attachment_type_id = t.id
		WHERE i.is_deleted = 0
		AND i.raw_file = 0
		AND i.id IN ('.$ids.')
	');


    return $result;
}

/**
 * function time_elapsed_string_header takes date time in Y-m-d H:i:s format and 
 * return the string containing information of AGO format.
 *
 * @example time_elapsed_string_header("2019-01-16 00:00:00", false)
 * => "12 hours ago"
 *
 * @param {$datetime|string}
 * @param {$full|boolean}
 * @return {$friendly_format|String}
 * 
 * @since 1.0
 * @author Unknown
 */
function time_elapsed_string_header($datetime, $full = false) {


    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
/**
 * function check_role take the role in integer and checks if role is in user's
 * sessions or user is super admin
 *
 * @param {$role|number}
 * @return {$result|boolean}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function check_role($role)
{
    $ti = &get_instance();
    // print_r($ti->session->userdata('admin_roles'));exit;
    
    if(in_array($role,$ti->session->userdata('admin_roles')))
        return true;

    if(is_region())
    {
    	if(in_array(-1,$ti->session->userdata('admin_roles'))) return true;
    }
    else
    {
    	if(in_array(-1,$ti->session->userdata('admin_roles'))) return true;
    }
    return false;
}

function is_designer()
{
	 $ti = &get_instance();
    // print_r($ti->session->userdata('admin_roles'));exit;
    
    if(in_array(-2,$ti->session->userdata('admin_roles')))
        return true;
    return false;
}

function is_purchaser()
{
	 $ti = &get_instance();
    // print_r($ti->session->userdata('admin_roles'));exit;
    
    if(in_array(-3,$ti->session->userdata('admin_roles')))
        return true;
    return false;
}

function is_accountant()
{
	 $ti = &get_instance();
    // print_r($ti->session->userdata('admin_roles'));exit;
    
    if(in_array(-4,$ti->session->userdata('admin_roles')))
        return true;
    return false;
}

function is_stock()
{
	 $ti = &get_instance();
    // print_r($ti->session->userdata('admin_roles'));exit;
    
    if(in_array(-5,$ti->session->userdata('admin_roles')))
        return true;
    return false;
}
/**
 * function get_role_name returns the role name of give role number
 *
 * @param {$role|number}
 * @return {$result|string}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function get_role_name($role)
{
    $roles = get_all_roles();
    foreach ($roles as $key => $value) {
        # code...
        if($key==$role)
            return $value;
    }
}
/**
 * function get_all_roles returns array of all available roles in the system
 *
 * @return {$result|array}
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function get_all_roles($region_only=false)
{

    return $arr = array(
        '-1'=>'Super Admin',
        '1'=>'Categories',
        '150'=>'Promo Code',
        '5'=> 'Reports',
        '2'=>'Settings',
        '14'=>'Orders/offers',
        '19'=>'Service Providers',
        '13'=>'Users',
        '100'=>'Withdrawal Requests',
        '3'=>'Admins',
        '12'=>'Static Pages',
    );
}
/**
 * view_products_section_helper takes id of category, returns view with 
 * dropdown to select a product from. This function is basically developed
 * for adding an offer and editing an offer page. Whenever user change the category 
 * selection, this function is being hit by AJAX call and thus returns the updated
 * products dropdown under that category
 *
 * 
 * @param  number  $id      id of selected category
 * @param  integer $prodcut already selected product if it is otherwise 0
 * @return view           	the view of product selection HTML
 *
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function view_products_section_helper($id,$prodcut=0){
	$ti = &get_instance();
	$ti->data['prodcut']=$product;
	// $ti->data
	$categories = $ti->db->where("parent",$id)->get('categories')->result_object();
	$cat_ids = array($id);
	foreach($categories as $cat_id)
	{
		$cat_ids[] = $cat_id->id;
	}


	$ti->data['products']=$ti->db->where_in('category',$cat_ids)->where('is_deleted',0)->get('products');
        if(!empty($ti->data['products']->result_object()))

        echo $ti->load->view('backend/offers/products_dropdown_view',$ti->data,true);

        else 
            echo "<div class='easy' style='padding:10px; font-size:19px; color:red;'>No products found under this category, please <a href='".base_url()."admin/add-product'>add</a> few products under this category to create an offer"."</div>";
}
/**
 * total_customers returns total active customers in the system
 * @return integer number of customers
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function total_customers()
{
	$ti = &get_instance();

	return $ti->db->where('is_deleted',0)->count_all_results('users');
}
/**
 * total_brands returns total active brands in the system
 * @return integer number of brands
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function total_brands()
{
	$ti = &get_instance();
	if(is_vendor())
		return $ti->db->where('is_deleted',0)->where("s",1)->count_all_results('brands');
	return $ti->db->where('is_deleted',0)->count_all_results('brands');
}
/**
 * total_products returns total active products in the system
 * @return integer number of products
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function total_products()
{
	$ti = &get_instance();
	if(is_vendor())
	return $ti->db->where('vendor_id',vendor_id())
	->where('is_deleted',0)
	->where('lparent',0)
	->count_all_results('products');


	if(is_region())
	{
		$wehere = where_region_id();

	   
		
		$ti->db->where("products.lparent",0);
		$ti->db->where("products.is_deleted",0);
		$ti->db->select("products.*");
		$ti->db->order_by("products.id","DESC");

		$ti->db->from("products");

		$ti->db->join("product_units","products.id=product_units.product_id","LEFT");
		$ti->db->where_in("product_units.region_id",explode(",",$wehere));
		$ti->db->select("product_units.id as no_use");

		return $ti->db->count_all_results();

	}
	return $ti->db->where('is_deleted',0)->where('lparent',0)->count_all_results('products');
}
/**
 * total_orders returns total active orders in the system
 * @return integer number of orders
 * 
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
function total_orders()
{
	$ti = &get_instance();
	if(is_vendor())
	{
		$return = 0;
		$all_orders = $ti->db->where("is_deleted",0)->get("orders")->result_object();

		foreach($all_orders as $one_order)
		{
			$products = $ti->db->where("order_id",$one_order->id)->get("order_products")->result_object();

			$one_found = 0;

			foreach($products as $product)
			{
				$i_own_this = $ti->db->where("id",$product->product_id)->where("vendor_id",vendor_id())->get("products")->result_object()[0];

				if($i_own_this)
				{
					$one_found = 1;
					break;
				}
			}

			if($one_found==1) $return++;
		}

		return $return;
	}
	return $ti->db->where('is_deleted',0)->count_all_results('orders');
}
/**
 * takaes table name and returns total  count of rows based on is_deleted
 * or not as second perimeter (boolean)
 * @param  string  $table      name of table
 * @param  boolean $trash_only is getting for trash
 * @return integer              count of data
 */
function count_listing($table,$trash_only=false)
{
	$ti = &get_instance();
	if($trash_only)
	{
		$ti->db->where('is_deleted',1);
	}
	else
	{
		$ti->db->where('lparent', 0);
		$ti->db->where('is_deleted',0);
		
	}

	return $ti->db->count_all_results($table);

}
/**
 * takaes table name and returns total  count of rows based on is_deleted
 * or not as second perimeter (boolean)
 * @param  string  $table      name of table
 * @param  boolean $trash_only is getting for trash
 * @return integer              count of data
 */
function count_vlisting($table,$trash_only=false)
{
	$ti = &get_instance();
	if($trash_only)
	{
		$ti->db->where('is_deleted',1);
		$ti->db->where('vendor_id',vendor_id());
		$ti->db->where('lparent',0);
	}
	else
	{
		$ti->db->where('is_deleted',0);
		$ti->db->where('lparent',0);
		$ti->db->where('vendor_id',vendor_id());
		
		
	}

	return $ti->db->count_all_results($table);

}
function lang_select()
{
	$ti = &get_instance();
	return $ti->load->view("backend/common/lang_select");
}
function dlang()
{
	$ti = &get_instance();
	return $ti->db->where('is_default',1)->get('languages')->result_object()[0];
}
function langs()
{
	$ti = &get_instance();
	return $ti->db->where("status",1)
            ->where("is_deleted",0)
            ->order_by("is_default","DESC")
            ->get('languages')->result_object();
}

function guid()
{
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function push_notif($push_id,$notif)
{
	$ti = &get_instance();

	// echo $push_id;exit;
	if($push_id=="") return false;


	require './vendor/autoload.php';
	 $interestDetails = [$notif["tag"], $push_id];

	  // You can quickly bootup an expo instance
	  $expo = \ExponentPhpSDK\Expo::normalSetup();
	  
	  // Subscribe the recipient to the server
	  $expo->subscribe($interestDetails[0], $interestDetails[1]);
	  
	  // Build the notification data
	  $notification = ['title'=>$notif["title"],'body' =>$notif["msg"],"data"=>json_encode($notif["data"])];
 
 
	  
	  
	  
	  // Notify an interest with a notification
	  $x = $expo->notify($interestDetails, $notification);
	  
	  $expo->unsubscribe($interestDetails[0], $interestDetails[1]);
	  
	  return $x;
}
function most_selling_products()
{
	$ci = &get_instance();


	$date = date("Y-m-d",strtotime("-29 Days"));

	$ci->db->where("DATE(created_at) >=",$date);
	$order_limits = $ci->db->get('orders')->result_object();

	foreach($order_limits as $order_limit) $order_ids[] = $order_limit->id;

	if(empty($order_ids)) $order_ids[] = -1;



	$order_products = $ci->db->where_in("order_id",$order_ids)->get('order_products')->result_object();


	$the_arr = array();

	foreach ($order_products as $key => $value) {


		if(is_vendor())
		{
			$my_product = $ci->db->where("id",$value->product_id)->where("vendor_id",vendor_id())->get("products")->result_object()[0];

			if(!$my_product) continue;
		}

		$found = false;
		foreach($the_arr as $kkey=>$the_ar)
		{
			if($the_ar["id"]==$value->product_id)
			{
				$the_arr[$kkey] = array("id"=>$value->product_id,"qty"=> ($the_ar["qty"]+$value->qty),
				"order_id"=>$value->order_id


				 );

				$found = true;
				break;
			}
		}

		if(!$found)
		{
			$the_arr[] = array(
				"id"=>$value->product_id,
				"qty"=> ($value->qty),
				"order_id"=>$value->order_id
				 );
		}
		# code...
	}

	usort($the_arr,'my_sort');

	$titles = array();
	if(empty($the_arr))
	$the_arr[] = array("id"=>-1,"qty"=>0);

	foreach($the_arr as $the_ar)
	{
		$only_ids[] = $the_ar["id"];
	}

	$final_products = $ci->db->where_in("id",$only_ids)->get("products")->result_object();

	$titles = "['Day',";

	foreach($final_products as $final_product)
	{
		$titles .= '"'.$final_product->title.'",';
	}

	if(empty($final_products))
	{
		$titles .= '"No Products",';
	}

	$titles = rtrim($titles,',');

	$titles .= "]";

	return array("titles"=>$titles,"ids"=>$the_arr);

}
function my_sort($a,$b)
{
	if ($a["qty"]==$b["qty"]) return 0;
	return ($a["qty"]>$b["qty"])?-1:1;
}
function new_unit($region_id=0,$price=0,$cost_price=0,$vat=0,$sticky=false)
{
	$data["region_id"]=$region_id;
	$data["price"]=$price;
	$data["cost_price"]=$cost_price;
	$data["vat"]=$vat;
	$data["sticky"]=$sticky;

	$ci = &get_instance();

	return $ci->load->view("backend/products/new_unit",$data,true);
}
function more_optionv2($t,$value_en="",$value_ar="",$price=0,$primary=0)
{
    $data["value_en"]=$value_en;
    $data["value_ar"]=$value_ar;
    $data["primary"]=$primary;
    $data["price"]=$price;
    $data["t"]=$t;

    $ci = &get_instance();

    return $ci->load->view("backend/categories/option",$data,true);
}

function more_option($t,$value_en="",$value_ar="",$value_price=0,$primary=0)
{
	$data["value_en"]=$value_en;
	$data["value_ar"]=$value_ar;
	$data["value_price"]=$value_price;
	$data["primary"]=$primary;
	$data["t"]=$t;
	
	$ci = &get_instance();

	return $ci->load->view("backend/products/option",$data,true);
}



function c_variationv2($arr)
{
    $data["title_en"] = $arr->title;
    $data["title_ar"] = $arr->title_ar;
    $data["price"] = $arr->price;
    $data["disabled"] = $arr->disabled;
//    $data["disabled"] = $arr->disabled;
    $data["options"] = json_decode($arr->options);

    $ci = &get_instance();

    echo $ci->load->view("backend/categories/cvariation_section",$data,true);
}
function c_variation($arr)
{
	$data["c_var_size_en"] = $arr->title_en;
	$data["c_var_size_ar"] = $arr->title_ar;
	$data["c_var_price_en"] = ($arr->options);

	$ci = &get_instance();

	echo $ci->load->view("backend/products/cvariation_section",$data,true);
}
function region_selector($region_id=0)
{
	$data["region_id"] = $region_id;

	$ci = &get_instance();

	echo $ci->load->view("backend/region_selector",$data,true);
}
function s_role_to_text($role)
{
	if($role==-2) return "designer";
	if($role==-3) return "purchaser";
	if($role==-4) return "accountant";
	if($role==-5) return "stock";
	if($role==-6) return "production";
}
function s_role_to_int($role)
{
	if($role=="designer") return -2;
	if($role=="purchaser") return -3;
	if($role=="accountant") return -4;
	if($role=="stock") return -5;
	if($role=="production") return -6;
}
function newRow($id=0,$table_id,$is_first=false)
{
	$ci = &get_instance();
	$table = $ci->db->where("id",$table_id)->get("tmp_table")->result_object()[0];

	if($id == 0 )
	{

		$dummy_print = array();

		for($qq = 0; $qq<=$table->faces-1; $qq++)
		{
			$dummy_print[] = "0";
		}
		$last_row = $ci->db->where("table_id",$table_id)->get("tmp_rows")->result_object()[0];
		$before_prints = count(json_decode($last_row->prints));
		if($before_prints==0)
		{
			$prints[] = $dummy_print;
			$prints[] = $dummy_print;
			$prints[] = $dummy_print;
		}
		else
		{

			for($pp = 0; $pp<=$before_prints-1; $pp++)
			{
				$prints[] = $dummy_print;
			}
		}
		$ci->db->insert("tmp_rows",array(
			"created_at"=>date("Y-m-d"),
			"table_id"=>$table_id,
			"prints"=>json_encode($prints)
		));


		$id = $ci->db->insert_id();
	}

	$row = $ci->db->where("id",$id)->get("tmp_rows")->result_object()[0];


	$data["is_first"]=$is_first;
	$data["row"]=$row;
	$data["table"]=$table;
	return $ci->load->view("backend/categories/new_row",$data,true);
}
function newTableV2($id=0,$only=false)
{
	$ci = &get_instance();
	$data["is_new"]=0;
	if($id == 0 )
	{
		$ci->db->insert("tmp_table",array(
			"created_at"=>date("Y-m-d"),
			"faces"=>2,
			"qty"=>5000
		));
		$id = $ci->db->insert_id();
		$data["is_new"]=1;
	}

	$table = $ci->db->where("id",$id)->get("tmp_table")->result_object()[0];

	$data["table"]=$table;
	if(!$only)
	return $ci->load->view("backend/categories/db_logic",$data,true);
	return $ci->load->view("backend/categories/db_logic_body",$data,true);
}
function new_row_cost($row)
{

	$ci = &get_instance();
	
	$data["row"] = (Object) $row;



	echo $ci->load->view("backend/costs/row",$data,true);
}
function staff_by_id($id)
{
	$ci = &get_instance();
	



	return $ci->db->where("id",$id)->get("admin")->result_object()[0];
}
function langd($val)
{
	return $val=="en"?2:1;
}
// function newTableV2($new=false)
// {
// 	$ci = &get_instance();

// 	if($new)
// 	{
// 		$ci->db->insert("tmp_table",array(
// 			"created_at"=>date("Y-m-d")
// 		));

// 		$id = $ci->db->insert_id();


// 	}

// 	$table = $ci->db->where("id",$id)->get("tmp_table")->result_object()[0];

// 	$data["table"]=$table;
// 	return $ci->load->view("backend/categories/db_logic",$data,true);
// }

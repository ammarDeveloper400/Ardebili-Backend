<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ADMIN_Controller extends CI_Controller {

	/**
	 * Core Class for everthing controller of admin.
	 *
	 * this class is for running pre-codes before actual class.
	 *
	 * @$data is variable of this class and is inherited with childs
	 * this is a semi-global container of multiple objects|array|any
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	*/
	// private $data = array();
	function __construct()
	{
		parent::__construct();
		error_reporting(1);
		$this->load->library('form_validation');
		$this->load->library('session');
        $this->load->helper('url');
        $this->load->database();
        $this->db->reconnect();

        $this->data['url'] = base_url();
		$this->data['assets'] = base_url()."resources/backend/";
		$this->data['root'] = base_url();
		$this->data['js'] = '';
		$this->data['jsfile'] = '';
		$this->data['sub'] = '';
		// $this->load->model('app_model','app');
		// $this->data['notifications'] = $this->app->get_all_notification();
		// $this->data['settings'] =settings();

        // refreshing roles
        // $this->refresh_roles();
	}
	/**
	 * function image_required takes name as String and takes file from global 
	 * $_FILES array, and a 2nd perimeter $required as array of 3 consective
	 * elements, first input field name from HTML, 2nd is allowed width, and 3rd 
	 * is allowed height, thus this function returns true or false on behalf of
	 * given data. This function returns false if no images is submitted
	 *
	 * @param {$val|string}
	 * @param {$requird|array}
	 * @return {$fine|boolean}
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
	public function image_required($val,$requird){
        $requird = explode(',',$requird);
        $field_name = $requird[0];
        $required_width = $requird[1];
        $required_height = $requird[2];
	    if(empty($_FILES[$field_name]['name'])){

	        $this->form_validation->set_message('image_required','This field is required.');
	        return false;
        }else{
            $file_parts = pathinfo($_FILES[$field_name]['name']);
            $allowed_ext = array('jpg','jpeg','png','gif');
            if(in_array(strtolower($file_parts['extension']),$allowed_ext)){
                $image_info = getimagesize($_FILES[$field_name]["tmp_name"]);
                $width = $image_info[0];
                $height = $image_info[1];
                if(($required_height < $width) && ($required_height < $height)){
                    return true;
                }else{
                    $this->form_validation->set_message('image_required','Minimum image dimension is '.$required_width.' X '.$required_height.' required.');
                    return false;
                }
            }else{
                $this->form_validation->set_message('image_required','Only jpg,jpeg,png and gif image are allowed.');

                return false;
            }
        }

    }
    /**
	 * function image_not_required takes name as String and takes file from global 
	 * $_FILES array, and a 2nd perimeter $required as array of 3 consective
	 * elements, first input field name from HTML, 2nd is allowed width, and 3rd 
	 * is allowed height, thus this function returns true or false on behalf of
	 * given data. This function returns true if no images is submitted
	 *
	 * @param {$val|string}
	 * @param {$requird|array}
	 * @return {$fine|boolean}
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
    public function image_not_required($val,$requird){
        $requird = explode(',',$requird);
        $field_name = $requird[0];
        $required_width = $requird[1];
        $required_height = $requird[2];
        if(empty($_FILES[$field_name]['name'])){

            return true;
        }else{
            $file_parts = pathinfo($_FILES[$field_name]['name']);
            $allowed_ext = array('jpg','jpeg','png','gif');
            // print_r($_FILES[$field_name]['name']);
            if(in_array(strtolower($file_parts['extension']),$allowed_ext)){
                $image_info = getimagesize($_FILES[$field_name]["tmp_name"]);
                $width = $image_info[0];
                $height = $image_info[1];
                if(($required_height < $width) && ($required_height < $height)){
                    return true;
                }else{
                    $this->form_validation->set_message('image_not_required','Minimum image dimension is '.$required_width.' X '.$required_height.' required.');
                    return false;
                }
            }else{
                $this->form_validation->set_message('image_not_required','Only jpg,jpeg,png and gif image are allowed.');
                // echo 1;exit;
                return false;
            }
        }

    }
    /**
	 * function image_upload takes field name from HTML, path to upload to, and 
	 * array of allowed extensions, then this function uploads image to given path
	 * 
	 *
	 * @param {$field|string}
	 * @param {$path|string}
	 * @param {$extensions|array}
	 * @return {$result|array}
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
    public function image_upload($field,$path,$extensions){
        $config['upload_path']          = $path;
        $config['allowed_types']        = $extensions;
        $config['file_ext_tolower']     = true;
        $config['encrypt_name']         = true;
        $config['remove_spaces']        = true;

        $this->load->library('upload', $config);

        $return['upload'] = true;
        $return['data'] = '';

        if ( ! $this->upload->do_upload($field))
        {

            $return['upload'] = false;
            $return['data'] = $this->upload->display_errors();

        }
        else
        {
            $return['data'] = $this->upload->data();

        }

        return $return;

    }

    public function file_upload_func($field,$path,$extensions){
        $config['upload_path']          = $path;
        $config['allowed_types']        = $extensions;
        $config['file_ext_tolower']     = true;
        $config['encrypt_name']         = true;
        $config['remove_spaces']        = true;

        $this->load->library('upload', $config);

        $return['upload'] = true;
        $return['data'] = '';
        if ( ! $this->upload->do_upload($field))
        {

            $return['upload'] = false;
            $return['data'] = $this->upload->display_errors();

        }
        else
        {
            $return['data'] = $this->upload->data();

        }


        return $return;

    }
    /**
	 * function image_upload_width_height field as string and path to upload file
	 * to and width and height to strech image to.
	 *
	 * @param {$field|string}
	 * @param {$path|string}
	 * @param {$extensions|array}
	 * @param {$width|string}
	 * @param {$height|string}
	 * @return {$result|array}
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
    public function image_upload_width_height($field,$path,$extensions,$width,$height)
    {
        $config['upload_path']          = $path;
        $config['allowed_types']        = $extensions;
        $config['file_ext_tolower']     = true;
        $config['encrypt_name']         = true;
        $config['remove_spaces']        = true;
        $config['min_width']            = $width;
        $config['min_height']           = $height;

        $this->load->library('upload', $config);

        $return['upload'] = true;
        $return['data'] = '';

        if ( ! $this->upload->do_upload($field))
        {
            $return['upload'] = false;
            $return['data'] = $this->upload->display_errors();

        }
        else
        {
            $return['data'] = $this->upload->data();

        }
        return $return;
    }
    /**
	 * function image_thumb takes $resource as source image and makes a thumbnail
	 * of given $width and $height, also stores it on specified $path.
	 *
	 * @param {$resource|string}
	 * @param {$path|string}
	 * @param {$width|string}
	 * @param {$height|string}
	 *
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
    public function image_thumb($resource,$path,$width,$height)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $resource;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = $width;
        $config['height']       = $height;
        $config['quality']       = '100%';
        $config['new_image']       = $path;
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
    }
    /**
	 * function reply_email takes $to to send email to, take $subject as subject 
	 * (string) and a $message (string) and $attached_file (string|path_to_file)
	 * and sends and email from dedevelopers@gmail.com 
	 *
	 * @param {$to|string}
	 * @param {$subject|string}
	 * @param {$message|string}
	 * @param {$attached_file|string}
	 *
	 * 
	 * @since 1.0
	 * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
	 */
    public function reply_email($to , $subject ,$message ,$attached_file)
    {
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $this->load->library('email',$config);
        $this->email->from('dedevelopers@gmail.com', 'Dedevelopers');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);
        if($attached_file !='')
        {
          $this->email->attach($attached_file); 
        }
        $this->email->set_mailtype("html");
        $this->email->send();
    }

    /**
     * [redirect_role redirects the user to logout page if user is trying
     * to access the class if the premission is missing... the class is where
     * from this function is called at the moment, with given role]
     * @param  [number] $role [role required to access this class]
     * @return [redirect|voic]       [redirects if user is missing the required
     * permissions, otherwise doesn't do anything]
     * 
     * @since 1.0
     * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function redirect_role($role)
    {
        if(is_designer() && in_array($role, $this->designer_permitted())) return true;
        if(is_accountant() && in_array($role, $this->accountant_permitted())) return true;
        if(is_stock() && in_array($role, $this->stock_permitted())) return true;
        if(!check_role($role))
        {
            redirect(base_url().'admin/logout');
            exit;
        }
    }
    private function designer_permitted()
    {
        return array(3,1);
    }

    private function accountant_permitted()
    {
        return array(3,1);
    }

    private function stock_permitted()
    {
        return array(3,1);
    }
    /**
     * [refresh_roles when this function is called all the user roles sessions
     * are refreshed by using the update/current database values]
     * @return [void] []
     *
     * 
     * @since 1.0
     * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
     */
    public function refresh_roles()
    {
        $roles = $this->db->where('admin_id',$this->session->userdata('admin_id'))->get('admin_roles')->result_object();
            foreach($roles as $role)
                $final_roles[]=$role->role;
        $this->session->set_userdata('admin_roles',$final_roles);
    }
    
    /**
    * this method is used inside un-available functions
    *
     * @since 1.0
     * @author DeDevelopers https://dedevelopers.com
     * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
    */
    public function shutdown_function()
    {
        echo "Function is shutdown for unavailability reasons around this feature";
        exit;
    }
    /**
    * this method is used to store data according to the selected language
    *
    * @since 1.0
    * @author DeDevelopers https://dedevelopers.com
    * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
    */
    public function insert_lang_wise($table,$data,$clang)
    {
        
    }
    public function redirect_back()
    {
        redirect($_SERVER["HTTP_REFERER"]);
    }

}

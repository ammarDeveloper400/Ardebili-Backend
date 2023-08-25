<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');
use Twilio\Rest\Client;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * handles the admins
 * @since 1.0
 * @author DeDevelopers
 * @copyright Copyright (c) 2019, DeDevelopers, https://dedevelopers.com
 */
class Api extends ADMIN_Controller {
    private $guest_id;
    function __construct()
    {
        parent::__construct();
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
    }

    private function generateRandomString($length = 10) 
    {
        $characters = '023456789abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function generateRandomStringCode($length = 10) 
    {
        $characters = '023456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function settings() {
        $ret = $this->db->query("SELECT * FROM site_settings")->result_object()[0];
        return $ret;
    }

    public function send_sms_to_users($phone=0,$desc="")
    {
        $phone_first_letter = substr($phone,0,1);
        if($phone_first_letter == "+"){
            $phone = $phone;
        } else {
            $phone = "+".$phone;
        }
        
        // $twillio_db = $this->db->where("id",1)->get("settings")->result_object()[0];
        // $sid = $twillio_db->twillio_pub;
        // $token = $twillio_db->twillio_sec;
        // $phone_number = '+12564641014';
        $sid    = "AC7d39f9489bd4c783cf7bc6e299a51a1c";
        $token = "83369ab8eb290d83f72914974e43c6d0";
        $phone_number = '+12765008397';
        
        $twilio = new Client($sid, $token);
        try{
            // echo "success";
            $message = $twilio->messages
                          ->create($phone, // to
                                   ["body" => $desc, "from" => $phone_number, "locale" => "en"]
                          );
        }catch(Exception $e) { 
            // echo "here";
            $exp = $e->getMessage();
            // print_r($exp);
            $epx = explode(":",$exp);
            $_SESSION['invalid'] = $epx[1];
            return "error";
            // die;
        }
    }

    public function check_login()
    {
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;

        $user = $this->db->where('token',$post->token)
                ->where('is_active',1)
                ->get('users')
                ->result_object();

        if(empty($user) || $post->token=="")
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid login credentials"
            ));
            exit;
        }
        $user=$user[0];
        $this->print_user_data($user->id);
    }

    private function print_user_data($id)
    {
        $user = $this->db->where('id',$id)->get('users')->result_object();


        if(empty($user))
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid login credentials"
            ));
            return;
        }
        $user = $user[0];
        // $dep = array();
        // $user_department = $this->db->query("SELECT * FROM user_departments WHERE user_id = ".$user->id)->result_object();

        // foreach($user_department as $k=>$row){
        //     $dep[]= $row->departement;
        // }
        // $dep = substr($dep, 0, -1);

        $roles = $this->get_user_role_info($user->id);
        if (in_array("Admin", $roles) || in_array("Account Manager", $roles) || in_array("Management", $roles)){
            $dep = 1;
        }
        else {
            $dep = 0;
        }

        echo json_encode(array(
            "action"=>"success",
            "data"=>array(
                        "id"=>$user->id,
                        "token"=>$user->token,
                        "email"=>$user->email,
                        "first_name"=>$user->fname?$user->fname:"",
                        "last_name"=>$user->lname?$user->lname:"",
                        "user_type"=>$user->user_type,
                        "profile_pic"=>$user->img_path?$user->img_path:"",
                        "departement" => $dep
                    )
                )
            );
    }

    private function do_auth($post)
    {
        $user = 
        $this->db->where('token',$post->token)
        ->get('users')
        ->result_object();
        if(empty($user) || $post->token=="")
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid login credentials"
            ));
            exit;
        }
        return $user[0];
    }


    public function do_login()
    {
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;

        $user = 
        $this->db->group_start()
        ->where('LOWER(email)',strtolower($post->email))
        ->group_end()
        ->group_start()
        ->where('password',md5($post->password))
        ->group_end()
        ->get('users')
        ->result_object();

        if(empty($user))
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid Login Credentials"
            ));
            exit;
        }
        if($user[0]->is_active == 0){
            echo json_encode(array(
                    "action"=>"failed",
                    "error"=>"Your account is locked from admin."
                ));
                exit;
        }

        $this->do_sure_login($user[0]->id);
    }

    private function do_sure_login($id)
    {
        $user = $this->db->where('id',$id)->get('users')->result_object();
        if(empty($user))
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid login credentials"
            ));
            return;
        }

        $user = $user[0];
        $login_data = array(
            "token"=>md5(guid()),
        );
        $this->db->where('id',$id)->update('users',$login_data);
        $this->print_user_data($id);
    }


    public function get_all_departments(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $all_departments = $this->db->query("SELECT * FROM role ORDER BY role ASC")->result_object();
        echo json_encode(array("action"=>"success","data"=>$all_departments));
    }

    public function add_new_department(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $data = array(
            "role" => $post->name,
        );
        $this->db->insert("role",$data);
        echo json_encode(array("action"=>"success"));
    }

    public function delete_department(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->db->query("DELETE FROM role WHERE id = ".$post->id);
        echo json_encode(array("action"=>"success"));
    }

    public function delete_specific_user(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->db->query("DELETE FROM users WHERE id = ".$post->id);
        echo json_encode(array("action"=>"success"));
    }

    public function update_specific_user_status(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $user = $this->db->query("SELECT * FROM users WHERE id = ".$post->id)->result_object()[0];
        if($user->is_active == 1){
            $active_sttaus = 0;
        } else {
            $active_sttaus = 1;
        }

        $this->db->query("UPDATE users SET is_active = ".$active_sttaus." WHERE id = ".$post->id);
        echo json_encode(array("action"=>"success"));
    }

    public function update_password(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $password_matches = $this->db->where("id",$user_logged->id)->where("password",md5($post->current))->count_all_results("users");

        if($password_matches==0)
        {
            echo json_encode(array("action"=>"failed","error"=>"Old password is incorrect"));
            return;
        }

        if($post->newpass != $post->cnewpass)
        {
            echo json_encode(array("action"=>"failed","error"=>"New & confirm new password is not same."));
            return;
        }

        $arr = array(
            "password"=>md5($post->newpass),
        );
        $this->db->where("id",$user_logged->id)->update("users",$arr);
        echo json_encode(array("action"=>"success"));
    }

    public function get_all_users_tabulator($token){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $user = 
        $this->db->where('token',$token)
        ->get('users')
        ->result_object();
        if(empty($user) || $token=="")
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid login credentials"
            ));
            exit;
        }

        $user_logged = $user[0];
        $user_ = array();
        $user_all = $this->db->query("SELECT * FROM users ORDER BY id DESC")->result_object();
        $per_page = $_GET['size'];
        $uri_segment = $_GET['page'];
        $count_total_result = count($user_all);
        $total_records = round( $count_total_result / $per_page);

        $page = $uri_segment==1?0:$uri_segment;
        if($page == 0){
            $this->db->limit($per_page, $page);
        } else {
            $page__ = $per_page;
            $par_page__ = $per_page*($page-1);
            $this->db->limit($page__, $par_page__);
        }
        $this->db->order_by("id","DESC");
        // echo $_GET['value'];
        if($_GET['value']){
            $this->db->like("LOWER(fname)", strtolower($_GET['value']));
        }
        $show_user_all = $this->db->get('users')->result_object();
        // echo $this->db->last_query();

        foreach ($show_user_all as $key => $row) {
            $namedepartments = "";
            $all_departments = $this->db->query("SELECT * from user_departments WHERE user_id = ".$row->id)->result_object();
            foreach($all_departments as $key_=>$rdep){
                $role_id = $this->db->query("SELECT * FROM role WHERE id = ".$rdep->departement)->result_object()[0];

                $namedepartments .= $role_id->role.",";
            }

            $namedepartments = substr($namedepartments,0,-1);

            $user_[] = array(
                                "id" => $row->id,
                                "name" => $row->fname." ".$row->lname,
                                "email" => $row->email,
                                "departmentslist" => $namedepartments,
                                "status" => $row->is_active,
                                );
        }


        echo json_encode(array("last_page"=>$total_records,"data"=>$user_));
    }
    public function get_all_users(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);
        
        $this->db->order_by("id","DESC");
        $show_user_all = $this->db->get('users')->result_object();
        // echo $this->db->last_query();

        foreach ($show_user_all as $key => $row) {
            $namedepartments = "";
            $all_departments = $this->db->query("SELECT * from user_departments WHERE user_id = ".$row->id)->result_object();
            foreach($all_departments as $key_=>$rdep){
                $role_id = $this->db->query("SELECT * FROM role WHERE id = ".$rdep->departement)->result_object()[0];
                $namedepartments .= $role_id->role.",";
            }
            $namedepartments = substr($namedepartments,0,-1);
            $user_[] = array(
                                "id" => $row->id,
                                "name" => $row->fname." ".$row->lname,
                                "email" => $row->email,
                                "departmentslist" => $namedepartments,
                                "status" => $row->is_active,
                            );
        }


        echo json_encode(array("action"=>"success","data"=>$user_));
    }
    
    public function add_new_user(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $check_email = $this->db->query("SELECT * FROM users WHERE email = '".$post->email."'")->num_rows();
        if($check_email > 0){
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Email Address already exists!"
            ));
            return;
        }

        $data = array(
            "fname" => $post->firstname,
            "lname" => $post->lastname,
            "email" => $post->email,
            "password" => md5($post->password),
            "user_type" => 1,
            "is_active" => 1,
            "created_at" => date("Y-m-d")
        );
        $this->db->insert("users",$data);
        $uid = $this->db->insert_id();

        if(count($post->departments) > 0){
            // $this->db->query("DELETE FROM user_departments WHERE user_id = ".$uid);
            for($i=0;$i<=count($post->departments)-1;$i++){
                $data = array(
                    "user_id" => $uid,
                    "departement" => $post->departments[$i],
                );
                $this->db->insert("user_departments",$data);
            }
        }

        echo json_encode(array("action"=>"success"));
    }

    public function get_specific_user(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $get_user = $this->db->query("SELECT * FROM users WHERE id = '".$post->id."'")->result_object()[0];
        $all_departments = $this->db->query("SELECT * from user_departments WHERE user_id = ".$get_user->id)->result_object();
        $dep_array = array();
        foreach($all_departments as $key=>$row){

            $dep_array[] = $row->departement;
        }


        $get_user_ = array(
                            "id" => $get_user->id,
                            "fname" => $get_user->fname,
                            "lname" => $get_user->lname,
                            "email" => $get_user->email,
                            "departmentalls" => $dep_array,
                            )
        ;

        echo json_encode(array("action"=>"success","data"=>$get_user_));
    }

    

    public function update_user(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $check_email = $this->db->query("SELECT * FROM users WHERE email = '".$post->email."' AND id != ".$post->id)->num_rows();
        if($check_email > 0){
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Email Address already exists!"
            ));
            return;
        }

        $data = array(
            "fname" => $post->firstname,
            "lname" => $post->lastname,
            "email" => $post->email,
            "user_type" => 1,
            "is_active" => 1,
            "created_at" => date("Y-m-d")
        );
        if($post->password!=""){
            $data['password'] = md5($post->password);
        }
        $this->db->where("id",$post->id)->update("users",$data);

        if(count($post->departments) > 0){
            $this->db->query("DELETE FROM user_departments WHERE user_id = ".$post->id);
            for($i=0;$i<=count($post->departments)-1;$i++){
                $data = array(
                    "user_id" => $post->id,
                    "departement" => $post->departments[$i],
                );
                $this->db->insert("user_departments",$data);
            }
        }

        echo json_encode(array("action"=>"success"));
    }


    public function get_all_disciplines(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $all_disciplines = $this->db->query("SELECT * FROM disciplines ORDER BY name ASC")->result_object();
        echo json_encode(array("action"=>"success","data"=>$all_disciplines));
    }

    public function add_new_discipline(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $dis = $this->db->query("SELECT * FROM disciplines")->num_rows();
        if($dis > 5){
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"You can only add 3 disciplines at a time."
            ));
            return;
        }

        $data = array(
            "name" => $post->name,
            "price" => $post->price,
        );
        $this->db->insert("disciplines",$data);
        echo json_encode(array("action"=>"success"));
    }

    public function delete_discipline(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->db->query("DELETE FROM disciplines WHERE id = ".$post->id);
        echo json_encode(array("action"=>"success"));
    }

    public function get_specific_discipline(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $get_user = $this->db->query("SELECT * FROM disciplines WHERE id = '".$post->id."'")->result_object()[0];
        echo json_encode(array("action"=>"success","data"=>$get_user));
    }

    public function update_discipline(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $data = array(
            "name" => $post->name,
            "price" => $post->price,
        );
       
        $this->db->where("id",$post->id)->update("disciplines",$data);
        echo json_encode(array("action"=>"success"));
    }

     public function add_new_proposal(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $data = array(
            "name" => $post->name,
            "description" => $post->description,
            "date_created" => date("Y-m-d H:i:s"),
        );
        $this->db->insert("proposal_templates",$data);
        echo json_encode(array("action"=>"success"));
    }

    public function get_all_templates(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $all_disciplines = $this->db->query("SELECT * FROM proposal_templates ORDER BY id ASC")->result_object();
        echo json_encode(array("action"=>"success","data"=>$all_disciplines));
    }


    public function get_specific_template(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $get_user = $this->db->query("SELECT * FROM proposal_templates WHERE id = '".$post->id."'")->result_object()[0];
        echo json_encode(array("action"=>"success","data"=>$get_user));
    }

    public function update_template(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $data = array(
            "name" => $post->name,
            "description" => $post->description,
        );
       
        $this->db->where("id",$post->id)->update("proposal_templates",$data);
        echo json_encode(array("action"=>"success"));
    }
    
    public function delete_template(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->db->query("DELETE FROM proposal_templates WHERE id = ".$post->id);
        echo json_encode(array("action"=>"success"));
    }

    
    // COSTS
    public function get_all_costs(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $all_disciplines = $this->db->query("SELECT * FROM costs ORDER BY name ASC")->result_object();
        echo json_encode(array("action"=>"success","data"=>$all_disciplines));
    }

    public function add_new_cost(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $data = array(
            "name" => $post->name,
            "price" => $post->price,
            "rate_type" => $post->ratetype,
            "est_hrs" => $post->hrs,
            "unit_price" => $post->unit_price,
            "markup" => $post->markup,
            "markup_type" => $post->pricingmode==null?0:$post->pricingmode,
            "markup_price" => $post->markupprice,
            "markup_cost" => $post->markupcost,
            "desription" => $post->desription,
            "internal_notes" => $post->interal_notes,
            "tax" => $post->tax,
            "single_price" => ($post->price/$post->hrs),
            "label" => $post->label,
        );

        if($post->id == 0){
            $this->db->insert("costs",$data);
        } else {
            $this->db->where("id",$post->id)->update("costs",$data);
        }
        echo json_encode(array("action"=>"success"));
    }

    public function add_asa_cost(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        if($post->proposal == 0){
            $asa = $this->db->query("SELECT * FROM asa_requests WHERE pNumber = '".$post->client_number."'")->result_object()[0];
            $task_pos = $this->db->query("SELECT * FROM asa_cost_data WHERE asa_id = ".$asa->req_id." ORDER BY id DESC LIMIT 1")->result_object()[0];
        } else {
            $task_pos = $this->db->query("SELECT * FROM asa_cost_data WHERE quote_number = '".$post->client_number."' ORDER BY id DESC LIMIT 1")->result_object()[0];
        }

        if($post->edit_task == 1){
            $name_stack = $post->edit_name; 
        } else {
            if($post->showdropdown == 0){
                $name_stack = $post->name; 
            } else {
                $old_ser = $this->db->query("SELECT * FROM costs WHERE id = ".$post->name)->result_object()[0];
                $name_stack = $old_ser->name;
            }
        }

        // $total_pp = (($post->price * $post->hrs));
        $single_price = ($post->price/$post->hrs);
        $total_pp = (($single_price * $post->hrs));

        $markup = 0;

        if($post->pricingmode == 1 ){
            $markup = ($total_pp * $post->markupcost) / 100;
        } else {
            $markup = $post->markupcost;
        }


        // $total_price = ($total_pp + $markup);
        // $tax_val = $post->tax;

        // $new_width = ($tax_val / 100) * $total_price;

        // $new_total_price = ($total_price + $new_width);

        $data = array(
            "asa_id" => $asa->req_id,
            "task_name" => $name_stack,
            "price" => $post->price,
            "task_time" => $post->hrs,
            "rate_type" => $post->ratetype,
            "markup_type" => $post->pricingmode,
            "cost" => $markup,
            "tax" => $post->markupcost,
            "total" => $total_pp,
            "task_description" => $post->desription,
            "task_pos" => ($task_pos->task_pos+1),
            "deleteable" => 1,
            "single_price" => $single_price,
            "label" => $post->label
        );

        if($post->proposal == 1){
            $data['quote'] = 1;
            $data['quote_number'] = $post->client_number;
            $data['asa_id'] = 0;
        }

        if($post->update_id == 0){
            $this->db->insert("asa_cost_data",$data);
        } else {
            unset($data['task_pos']);
            if($task_pos->deleteable==0){
                unset($data['deleteable']);
            }
            $this->db->where("id",$post->update_id)->update("asa_cost_data",$data);
        }

        // $this->db->insert("asa_cost_data",$data);
        echo json_encode(array("action"=>"success"));
    }

    public function save_asa_multiple_costs(){


        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
            $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        if($post->proposal == 0){
            $asa = $this->db->query("SELECT * FROM asa_requests WHERE pNumber = '".$post->client_number."'")->result_object()[0];
        }

        $data_to_save = $post->data;

        foreach($post->checked as $key=>$check){
            foreach($data_to_save as $k_=>$row){
                if($row->id == $check){

                    if($post->proposal == 0){
                        $task_pos = $this->db->query("SELECT * FROM asa_cost_data WHERE asa_id = ".$asa->req_id." ORDER BY id DESC LIMIT 1")->result_object()[0];
                    } else {
                        $task_pos = $this->db->query("SELECT * FROM asa_cost_data WHERE quote_number = '".$post->client_number."' ORDER BY id DESC LIMIT 1")->result_object()[0];
                    }

                    $single_price = ($row->price/$row->est_hrs);

                    $total_pp = (($single_price * $row->est_hrs));


                    $markup = 0;

                    if($row->markup_type == 1){
                        $markup = ($total_pp * $row->markup_cost) / 100;
                    } else {
                        $markup = $row->markup_cost;
                    }

                    $data = array(
                        "asa_id" => $asa->req_id,
                        "task_name" => $row->name,
                        "price" => $row->price,
                        "task_time" => $row->est_hrs,
                        "rate_type" => $row->rate_type,
                        "markup_type" => $row->markup_type,
                        "tax" => $row->markup_cost,
                        "cost" => $markup,
                        "total" => $total_pp,
                        "task_description" => $row->desription,
                        "task_pos" => ($task_pos->task_pos+1),
                        "deleteable" => 1,
                        "single_price" => $single_price,
                        "label" => $row->label
                    );

                    // print_r($data);
                    // die;

                    // $data = array(
                    //     "asa_id" => $asa->req_id,
                    //     "task_name" => $row->name,
                    //     "price" => $row->price,
                    //     "task_time" => $row->est_hrs,
                    //     "cost" => $row->markup_cost,
                    //     "total" => $new_total_price,
                    //     "task_description" => $row->desription,
                    //     "task_pos" => ($task_pos->task_pos+1),
                    //     "deleteable" => 1,
                    //     "label" => $row->label,
                    // );
                    if($post->proposal == 1){
                        $data['quote'] = 1;
                        $data['quote_number'] = $post->client_number;
                        $data['asa_id'] = 0;
                    }
                    $this->db->insert("asa_cost_data",$data);
                }
            }
        }
        
        echo json_encode(array("action"=>"success"));
    }

    public function delete_specific_cost_asa(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->db->query("DELETE FROM asa_cost_data WHERE id = ".$post->id);
        echo json_encode(array("action"=>"success"));
    }



    public function get_specific_cost(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $get_user = $this->db->query("SELECT * FROM costs WHERE id = '".$post->id."'")->result_object()[0];
        echo json_encode(array("action"=>"success","data"=>$get_user));
    }

    public function delete_cost(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->db->query("DELETE FROM costs WHERE id = ".$post->id);
        echo json_encode(array("action"=>"success"));
    }

    // STANDARD SERVICEC
    public function get_all_services(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $all_disciplines = $this->db->query("SELECT * FROM services ORDER BY name ASC")->result_object();
        echo json_encode(array("action"=>"success","data"=>$all_disciplines));
    }

    public function add_asa_task(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        if($post->proposal == 0){
            $asa = $this->db->query("SELECT * FROM asa_requests WHERE pNumber = '".$post->client_number."'")->result_object()[0];
            $task_pos = $this->db->query("SELECT * FROM asa_tasks_data WHERE asa_id = ".$asa->req_id." ORDER BY id DESC LIMIT 1")->result_object()[0];
        } else {
            $task_pos = $this->db->query("SELECT * FROM asa_tasks_data WHERE quote_number = '".$post->client_number."' ORDER BY id DESC LIMIT 1")->result_object()[0];
        }

        if($post->edit_task == 1){
            $name_stack = $post->edit_name; 
        } else {

            if($post->showdropdown == 0){
                $name_stack = $post->name; 
            } else {
                $old_ser = $this->db->query("SELECT * FROM services WHERE id = ".$post->name)->result_object()[0];
                $name_stack = $old_ser->name;
            }
        }

        $single_price = ($post->price/$post->hrs);

        $total_pp = (($single_price * $post->hrs));

        $markup = 0;

        if($post->pricingmode == 1 ){
            $markup = ($total_pp * $post->markupprice) / 100;
        } else {
            $markup = $post->markupprice;
        }

        $total_price = ($total_pp + $markup);
        $tax_val = $post->tax;

        $new_width = ($tax_val / 100) * $total_price;

        $new_total_price = ($total_price + $new_width);


        $data = array(
            "asa_id" => $asa->req_id,
            "task_name" => $name_stack,
            "price" => $post->price,
            "task_time" => $post->hrs,
            "rate_type" => $post->ratetype,
            "cost" => $post->markupcost,
            "tax" => $post->tax,
            "total" => $new_total_price,
            "task_description" => $post->desription,
            "task_pos" => ($task_pos->task_pos+1),
            "deleteable" => 1,
            "markup_rate" => $post->markupprice,
            "markup_type" => $post->pricingmode==""?0:$post->pricingmode,
            "single_price" => ($single_price),
            "label" => $post->label
        );
        
        if($post->proposal == 1){
            $data['quote'] = 1;
            $data['quote_number'] = $post->client_number;
            $data['asa_id'] = 0;
        }

        // print_r($data);
        // die;

        if($post->update_id == 0){
            $this->db->insert("asa_tasks_data",$data);
        } else {
            unset($data['task_pos']);
            if($task_pos->deleteable==0){
                unset($data['deleteable']);
            }
            $this->db->where("id",$post->update_id)->update("asa_tasks_data",$data);
        }
        echo json_encode(array("action"=>"success"));
    }

    public function save_asa_multiple_tasks(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
            $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        if($post->proposal == 0){
            $asa = $this->db->query("SELECT * FROM asa_requests WHERE pNumber = '".$post->client_number."'")->result_object()[0];
        }

        $data_to_save = $post->data;

        foreach($post->checked as $key=>$check){
            foreach($data_to_save as $k_=>$row){
                if($row->id == $check){
                    if($post->proposal == 0){
                        $task_pos = $this->db->query("SELECT * FROM asa_tasks_data WHERE asa_id = ".$asa->req_id." ORDER BY id DESC LIMIT 1")->result_object()[0];
                    } else {
                        $task_pos = $this->db->query("SELECT * FROM asa_tasks_data WHERE quote_number = '".$post->client_number."' ORDER BY id DESC LIMIT 1")->result_object()[0];
                    }
                    // COME BACK HERE

                    $single_price = ($row->price/$row->est_hrs);

                    $total_pp = (($single_price * $row->est_hrs));

                    $markup = 0;

                    if($row->markup_type == 1 ){
                        $markup = ($total_pp * $row->markup_price) / 100;
                    } else if($row->markup_type == 1){
                        $markup = $row->markup_price;
                    } else {
                        if($row->markup_price != 0){
                            $markup = ($total_pp * $row->markup_price) / 100;
                        } else {
                            $markup = $row->markup_price;
                        }
                    }

                    $total_price = ($total_pp + $markup);
                    $tax_val = $row->tax;

                    $new_width = ($tax_val / 100) * $total_price;

                    $new_total_price = ($total_price + $new_width);


                    $data = array(
                        "asa_id" => $asa->req_id,
                        "task_name" => $row->name,
                        "price" => $row->price,
                        "task_time" => $row->est_hrs,
                        "cost" => $row->markup_cost,
                        "tax" => $row->tax,
                        "total" => $new_total_price,
                        "task_description" => $row->desription,
                        "task_pos" => ($task_pos->task_pos+1),
                        "deleteable" => 1,
                        "markup_rate" => $row->markup_price,
                        "markup_type" => $row->markup_type,
                        "single_price" => ($single_price),
                        "label" => $row->label,
                        "rate_type" => $row->rate_type
                    );

                    // $data = array(
                    //     "asa_id" => $asa->req_id,
                    //     "task_name" => $row->name,
                    //     "price" => $row->price,
                    //     "task_time" => $row->est_hrs,
                    //     "cost" => $row->markup_cost,
                    //     "markup_rate" => $row->markup_price,
                    //     "total" => (($row->price * $row->est_hrs) + $row->markup_price),
                    //     "task_description" => $row->desription,
                    //     "task_pos" => ($task_pos->task_pos+1),
                    //     "deleteable" => 1,
                    // );
                    if($post->proposal == 1){
                        $data['quote'] = 1;
                        $data['quote_number'] = $post->client_number;
                        $data['asa_id'] = 0;
                    }

                    // print_r($data);
                    // die;
                    $this->db->insert("asa_tasks_data",$data);
                }
            }
        }
        
        echo json_encode(array("action"=>"success"));
    }

    public function delete_specific_task_asa(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->db->query("DELETE FROM asa_tasks_data WHERE id = ".$post->id);
        echo json_encode(array("action"=>"success"));
    }


    public function add_new_services(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $data = array(
            "name" => $post->name,
            "price" => $post->price,
            "rate_type" => $post->ratetype,
            "est_hrs" => $post->hrs,
            "unit_price" => $post->unit_price,
            "markup" => $post->markup,
            "markup_type" => $post->pricingmode==null?0:$post->pricingmode,
            "markup_price" => $post->markupprice,
            "markup_cost" => $post->markupcost,
            "desription" => $post->desription,
            "internal_notes" => $post->interal_notes,
            "tax" => $post->tax,
            "single_price" => ($post->price/$post->hrs),
            "label" => $post->label
        );

        if($post->id == 0){
            $this->db->insert("services",$data);
        } else {
            $this->db->where("id",$post->id)->update("services",$data);
        }
        echo json_encode(array("action"=>"success"));
    }

    public function delete_services(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $this->db->query("DELETE FROM services WHERE id = ".$post->id);
        echo json_encode(array("action"=>"success"));
    }

    public function get_specific_services(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $get_user = $this->db->query("SELECT * FROM services WHERE id = '".$post->id."'")->result_object()[0];
        echo json_encode(array("action"=>"success","data"=>$get_user));
    }

    public function get_sepecific_task_id(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);



        $get_user = $this->db->query("SELECT * FROM asa_tasks_data WHERE id = '".$post->id."'")->result_object()[0];

        $get_service = $this->db->query("SELECT * FROM services WHERE LOWER(name) = '".strtolower($get_user->task_name)."'")->result_object()[0];
        if(!empty($get_service)){
            // echo "aaa";
            $get_user->service = $get_service->id;
            if($get_user->rate_type != ""){
                $get_user->rate = $get_user->rate_type;
            } else {
                $get_user->rate = $get_service->rate_type;
            }   
            if($get_user->markup_type != 0){
                $get_user->markup = 1;
            } else {
                $get_user->markup = (int) $get_service->markup;
            }   

            
        } else {
            // $get_user->service = $get_service->id;
            $get_user->rate = $get_user->rate_type;
            if($get_user->markup_type != 0){
                $get_user->markup = (int) 1;
            }
        }

        // print_r($get_user);
        // die;
        echo json_encode(array("action"=>"success","data"=>$get_user));
    }

    public function get_sepecific_cost_id(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);



        $get_user = $this->db->query("SELECT * FROM asa_cost_data WHERE id = '".$post->id."'")->result_object()[0];

        $get_service = $this->db->query("SELECT * FROM costs WHERE LOWER(name) = '".strtolower($get_user->task_name)."'")->result_object()[0];
        if(!empty($get_service)){
            $get_user->service = $get_service->id;
            if($get_user->markup_type != ""){

            } else {
                $get_user->markup_type = (int) $get_service->markup_type;
            }
            
            if($get_user->rate_type != ""){
                 $get_user->rate = $get_user->rate_type;
            } else {
                 $get_user->rate = $get_service->rate_type;
            }
           

            $get_user->markup = (int) $get_service->markup;
            $get_user->markup_cost = (int) $get_user->tax;
        } else {
            // $get_user->markup_type = (int) $get_user->markup_type;
            if($get_user->tax != 0){
                $get_user->markup = 1;
            }
            $get_user->rate = $get_user->rate_type;
            $get_user->markup_cost = (int) $get_user->tax;
            
        }

        // print_r($get_user);
        // die;
        echo json_encode(array("action"=>"success","data"=>$get_user));
    }

    public function update_services(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $data = array(
            "name" => $post->name,
            "price" => $post->price,
        );
       
        $this->db->where("id",$post->id)->update("services",$data);
        echo json_encode(array("action"=>"success"));
    }

    public function get_all_asa_requests_tabulator($token){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $user = 
        $this->db->where('token',$token)
        ->get('users')
        ->result_object();
        if(empty($user) || $token=="")
        {
            echo json_encode(array(
                "action"=>"failed",
                "error"=>"Invalid login credentials"
            ));
            exit;
        }

        $user_logged = $user[0];
        $user_ = array();
        $user_all = $this->db->query("SELECT * FROM asa_requests ORDER BY req_id DESC")->result_object();

        $per_page = $_GET['size'];
        $uri_segment = $_GET['page'];
        $count_total_result = count($user_all);
        $total_records = round( $count_total_result / $per_page);

        $page = $uri_segment==1?0:$uri_segment;
        if($page == 0){
            $this->db->limit($per_page, $page);
        } else {
            $page__ = $per_page;
            $par_page__ = $per_page*($page-1);
            $this->db->limit($page__, $par_page__);
        }
        $this->db->order_by("req_id","DESC");
        if($_GET['value']){
            $this->db->like("LOWER(fname)", strtolower($_GET['value']));
        }
        $show_user_all = $this->db->get('asa_requests')->result_object();
        // echo $this->db->last_query();

        foreach ($show_user_all as $key => $row) {
            $user_[] = array(
                                "id" => $row->req_id,
                                "name_number" => $row->asa_project_no." ".$row->asa_project_name,
                                "name" => $row->asa_project_name,
                                "pro_number" => $row->asa_project_no,
                                "email" => date("F, d Y", strtotime($row->asa_request_date)),
                                "status" => $row->request_status,
                                "urgency" => $row->asa_urgency_work==1?'Standard':'Urgent',
                        );
        }

        echo json_encode(array("last_page"=>$total_records,"data"=>$user_));
    }

    public function get_user_role_info($uid){
        $user_type = $this->db->query("SELECT * from user_departments AS D, role AS R WHERE D.user_id = ".$uid." AND R.id = D.departement")->result_object();
        $role = array();
        foreach($user_type as $key_=>$dep){
            $role[] = $dep->role;
        }
        return $role;
    }

     public function get_all_asa_requests($archive=0, $user_logged=[], $pronumber=""){
        $user_ = array();
        $admin_show = 0;
        if(!empty($user_logged)){
            $roles = $this->get_user_role_info($user_logged->id);
            if (in_array("Admin", $roles) || in_array("Account Manager", $roles)){
                $admin_show = 1;
            }
            else {
                if($pronumber==""){
                    $this->db->group_start();
                        // $this->db->WHERE_IN("additional_pm", $user_logged->id);
                        $this->db->WHERE("find_in_set($user_logged->id, additional_pm)");
                        $this->db->OR_WHERE("asa_your_email", $user_logged->email);
                    $this->db->group_end();
                }
                $admin_show = 0;
            }

        }

        $this->db->order_by("req_id","DESC");
        if($archive == 1){
            $this->db->where("archive_status", 1);
        } else {
            $this->db->where("archive_status", 0);
        }
        if($pronumber!=""){
            $this->db->where("pNumber", $pronumber);
        }

        $show_user_all = $this->db->get('asa_requests')->result_object();
        // echo $this->db->last_query();
        // die;

        foreach ($show_user_all as $key => $row) {
            $discipline_array = array();
            $item_array = array();
            $get_discipline_data = $this->db->query("SELECT * FROM asa_disciplines_data WHERE asa_id = ".$row->req_id)->result_object();

            foreach($get_discipline_data as $key=>$drow){
                $dis_data = $this->db->query("SELECT * FROM disciplines WHERE id = ".$drow->dis_id)->result_object()[0];
                $discipline_array[] = array(
                                                "id" => $dis_data->id,
                                                "asa_id" => $drow->asa_id,
                                                "hour" => $drow->hour,
                                                "task" => $drow->tasks,
                                                "dis_name" => $dis_data->name,
                                            );
                // $item_array[] = $dis_data->name;
            }
            if($row->new_asa==1){
                $dis___expl = explode(",", $row->discipline);
                foreach($dis___expl as $key=>$dis){
                    $dis_data = $this->db->query("SELECT * FROM disciplines WHERE id = ".$dis)->result_object()[0];
                    $item_array[] = $dis_data->name;
                }
            } else {
                $item_array = explode(",",$row->discipline);
            }

            $get_created_user_id = $this->db->query("SELECT * FROM users WHERE LOWER(email) = '".strtolower($row->asa_your_email)."'")->result_object()[0]->id;

            $user_[] = array(
                                "id" => $row->req_id,
                                "ASA_PRO_NUMBER" => $row->pNumber,
                                "type" => $row->type,
                                "name_number" => $row->asa_project_no." ".$row->asa_project_name,
                                "name" => $row->asa_project_name,
                                "pro_number" => $row->asa_project_no,
                                "pro_version" =>    $row->pro_version,
                                // "email" => date("F, d Y", strtotime($row->asa_request_date)),
                                "email" => date("m/d/Y", strtotime($row->asa_request_date)),
                                "status" => $row->completed_discipline,
                                "completed_discipline" => (int) $row->completed_discipline,
                                "urgency_text" => $row->asa_urgency_work==1?'Urgent':'Standard',
                                "status_text" => $this->do_get_status($row->completed_discipline),
                                "urgency" => $row->asa_urgency_work,
                                "asa_request_date" => $row->asa_request_date,
                                "asa_full_name" => $row->asa_full_name,
                                "asa_your_email" => $row->asa_your_email,
                                "asa_creator_id" => $get_created_user_id,
                                "asa_project_no" => $row->asa_project_no,
                                "asa_project_name" => $row->asa_project_name,
                                "asa_company_name" => $row->asa_company_name,
                                "asa_email" => $row->asa_email,
                                "standard_services" => $row->standard_services,
                                "asa_urgency_work" => $row->asa_urgency_work,
                                "discipline" => str_replace(",",", ",$row->discipline),
                                "discipline_data" => $discipline_array,
                                "request_due_date" => $row->request_due_date,
                                "send_notification" => str_replace(",",", ",$row->send_notification),
                                "additional_pm" => $row->additional_pm,
                                "client_project_number" => $row->client_project_number,
                                "service_description" => $row->service_description,
                                "company_contact" => $row->company_contact,
                                "items_text" => implode(",",$item_array),
                                "items" => explode(",",$row->discipline),
                                "standard_service_id" => $row->standard_services_id!=null?explode(",",$row->standard_services_id):array(),
                                "additional_pm_ar" => explode(",",$row->additional_pm),
                                "new_asa" => $row->new_asa,
                                "check_admin" => $admin_show,
                                "archive_reason" => $row->archive_reason,
                                "client_address" => $row->client_address,
                                "dateofcompletion" => $row->dateofcompletion
                        );
        }
        // print_r($user_[0]);
        // die;
       
        return $user_;
    }

    public function do_get_status($number){
        if ($number == "1") {
            return "Waiting for E";
        }
        if ($number == "2") {
            return "Waiting for MP";
        }
        if ($number == "3") {
            return "Ready to send";
        }
        if ($number == "4") {
            return "ASA Approved";
        }
        if ($number == "5") {
            return "ASA Declined";
        }
        if ($number == "6") {
            return "Sent to client";
        }
        if ($number == "7") {
            return "ASA Declined - Proceed with work";
        } else {
            return "Unknown";
        }
    }

    public function get_all_standard_services(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;

        $user_logged = $this->do_auth($post);

        $specific_id = "";
        $services_data = array();

        if($post->id != ""){
            $specific_id = $post->id;
            $asa_dat = $this->db->query("SELECT * FROM asa_requests WHERE pNumber = '".$specific_id."'")->result_object()[0];
            $tasks_data = $this->db->query("SELECT * FROM asa_tasks_data WHERE asa_id = ".$asa_dat->req_id." ORDER BY task_pos ASC")->result_object();
        } 

        $get_templates = $this->db->query("SELECT * FROM proposal_templates ORDER BY id DESC")->result_object();
        $get_user = $this->db->query("SELECT * FROM services ORDER BY name ASC")->result_object();
        $get_discipline = $this->db->query("SELECT * FROM disciplines ORDER BY name ASC")->result_object();
        $get_users_list = $this->db->query("SELECT * FROM users ORDER BY id DESC")->result_object();
        echo json_encode(array("action"=>"success","templates"=>$get_templates, "tasks"=>$tasks_data, "data"=>$get_user, "discp"=>$get_discipline, "users_list"=>$get_users_list, "asa_data"=>$this->get_all_asa_requests(0,$user_logged, $specific_id)));
    }



    public function get_asa_client_tasks(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;

        $user_logged = $this->do_auth($post);

        $services_data = array();
        $specific_id = $post->id;

        if($post->proposal == 1){
            $tasks_data = $this->db->query("SELECT * FROM asa_tasks_data WHERE asa_id = 0 AND quote = 1 AND quote_number = '".$specific_id."' ORDER BY task_pos ASC")->result_object();
        } else {
            $asa_dat = $this->db->query("SELECT * FROM asa_requests WHERE pNumber = '".$specific_id."'")->result_object()[0];
            $tasks_data = $this->db->query("SELECT * FROM asa_tasks_data WHERE asa_id = ".$asa_dat->req_id." ORDER BY task_pos ASC")->result_object();
        }

        echo json_encode(array("action"=>"success", "tasks"=>$tasks_data));
    }

    public function get_asa_client_costs(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;

        $user_logged = $this->do_auth($post);

        $services_data = array();
        $specific_id = $post->id;

        if($post->proposal == 1){
            $tasks_data = $this->db->query("SELECT * FROM asa_cost_data WHERE asa_id = 0 AND quote_number = '".$specific_id."' ORDER BY task_pos ASC")->result_object();
        } else {
            $asa_dat = $this->db->query("SELECT * FROM asa_requests WHERE pNumber = '".$specific_id."'")->result_object()[0];
            $tasks_data = $this->db->query("SELECT * FROM asa_cost_data WHERE asa_id = ".$asa_dat->req_id." ORDER BY task_pos ASC")->result_object();
        }

        echo json_encode(array("action"=>"success", "tasks"=>$tasks_data));
    }

    public function get_all_urarchived_asa(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        echo json_encode(array("action"=>"success", "asa_data"=>$this->get_all_asa_requests(1)));
    }

    public function add_new_asa(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;


         $user_logged = $this->do_auth($post);

         $notify_engs = implode(",", $post->notifyengineers);

         
         $notify_eng_emails = "";
         foreach($post->notifyengineers as $nk_=>$eng){
            $eng_user = $this->db->query("SELECT * FROM users WHERE id = '".$eng."'")->result_object()[0];
            if(!empty($eng_user)){
                $notify_eng_emails .= $eng_user->email.",";
            } 
         }
         $new_eng_email = substr($notify_eng_emails,0,-1);

         $get_full_details = $this->db->query("SELECT * FROM users WHERE id = ".$post->fullname)->result_object()[0];

         $s_services = $post->standardService;
         $row_serv = "";
         foreach($s_services as $key=>$srow){
            $sdata = $this->db->query("SELECT * FROM services WHERE id = '".$srow."'")->result_object()[0];
            $row_serv .= $sdata->name.", "; 
         }
         $service_text =  substr($row_serv,0,-2);

         $projectNumber = "P"."00".$this->generateRandomStringCode(4);

         $data = array(
            "pNumber" => $projectNumber,
            "type" => 1,
            "asa_request_date" => $post->requestdate,
            "asa_full_name" => $get_full_details->fname." ".$get_full_details->lname,
            "asa_your_email" => $get_full_details->email,
            "asa_project_no" => $post->projectnumber,
            "asa_project_name" => $post->projectname,
            "client_project_number" => $post->clientprojectnumber,
            "asa_company_name" => $post->client,
            "company_contact" => $post->clientcontact,
            "asa_email" => $post->contactemails,
            "asa_urgency_work" => $post->urgencywork,
            "standard_services" => $service_text,
            "standard_services_id" => implode(",",$post->standardService),
            "service_description" => $post->servicedescription,
            "dateofcompletion" => $post->dateofcompletion,
            "request_due_date" => $post->completiondate,
            "send_notification" => $post->notifyengineers!=null?$new_eng_email:null,
            // "send_notification" => $post->notifyengineers==null?0:$post->notifyengineers,
            "discipline" => implode(",",$post->items),
            "completed_discipline" => $post->compdiscipline,
            "request_code" => $this->generateRandomStringCode(6),
            "request_status" => 0,
            "send_to_client" =>0,
            "additional_pm" => $notify_engs,
            "new_asa"=>1,
            "client_address" => $post->clientaddress,
        );


        $this->db->insert("asa_requests",$data);
        $asa_id = $this->db->insert_id();

        // ADD STANDARD SERVICES IN TASKS
        if(count($post->standardService) > 0){
            for($i=0;$i<=count($post->standardService)-1;$i++){
                $task_pos = $this->db->query("SELECT * FROM asa_tasks_data WHERE asa_id = '".$asa_id."' ORDER BY id DESC LIMIT 1")->result_object()[0];
                $old_ser = $this->db->query("SELECT * FROM services WHERE id = ".$s_services[$i])->result_object()[0];
                // echo $this->db->last_query();
                
                
                $name_stack = $old_ser->name;

                $single_price = ($old_ser->price/$old_ser->est_hrs);

                $total_pp = (($single_price * $old_ser->est_hrs));

                $markup = 0;

                if($old_ser->markup_type == 1 ){
                    $markup = ($total_pp * $old_ser->markupprice) / 100;
                } else {
                    $markup = $old_ser->markupprice;
                }

                $total_price = ($total_pp + $markup);
                $tax_val = $old_ser->tax;

                $new_width = ($tax_val / 100) * $total_price;

                $new_total_price = ($total_price + $new_width);


                $arry_s = array(
                    "asa_id" => $asa_id,
                    "task_name" => $name_stack,
                    "price" => $old_ser->price,
                    "task_time" => $old_ser->est_hrs,
                    "cost" => $old_ser->markup_cost,
                    "tax" => $old_ser->tax,
                    "total" => $new_total_price,
                    "task_description" => $old_ser->desription,
                    "task_pos" => ($task_pos->task_pos+1),
                    "deleteable" => 0,
                    "markup_rate" => $old_ser->markup_price,
                    "markup_type" => $old_ser->markup_type,
                    "single_price" => ($single_price),
                    "label" => $old_ser->label,
                );
                // echo "<pre>";
                // print_r($arry_s);
                
                $this->db->insert("asa_tasks_data",$arry_s);
            }
        }
        // die;
         // INSERT TASKS 
        $tasks_data = $post->items;
        $branches_data = $post->branches;
        foreach ($tasks_data as $itemId) {
            foreach ($branches_data as $branch) {
                if ($branch->id == $itemId) {
                    $get_dis_data = $this->db->query("SELECT * FROM disciplines WHERE id = ".$branch->id)->result_object()[0];
                    $arry_bv = array(
                            "asa_id"        => $asa_id,
                            "task_name"     => $get_dis_data->name,
                            "task_time"     => $branch->hour,
                            "price"         => $get_dis_data->price,
                            "cost"          => 0,
                            "total"         => ($get_dis_data->price*$branch->hour),
                            "task_description"     => $branch->task,
                        );
                    $this->db->insert("asa_tasks_data",$arry_bv);
                }
            }
        }

        if(count($post->branches) > 0){
            for($i=0;$i<=count($post->branches)-1;$i++){
                $arry_b = array(
                                    "asa_id"    => $asa_id,
                                    "dis_id"    => $post->branches[$i]->id,
                                    "hour"      => $post->branches[$i]->hour,
                                    "tasks"     => $post->branches[$i]->task,
                                );
                $this->db->insert("asa_disciplines_data",$arry_b);
            }
        }




         // $get_department_ = $this->db->query("SELECT * FROM role WHERE role = 'Account Manager'")->result_object()[0]->id;
        // $emails_senders_list = $this->db->query("SELECT * FROM `user_departments` AS D, users AS U WHERE D.user_id = U.id AND D.`departement` = ".$get_department_)->result_object();

        // SEND EMAIL TO ACCOUNT MANAGER
        if($post->compdiscipline == 3){
            $template_idddd = 'd-9c3951f1edae473a907d9daf7d059da1';
            $subject_to_send = 'ASA REQUEST COMPLETED : '.$post->projectnumber.' : '.$post->projectname;
        } else {
            $template_idddd = 'd-ccf8d48b895b42d6976bbfccc745fe4f';
            $subject_to_send = 'New ASA Request : '.$post->projectnumber.' : '.$post->projectname;
        }
        
        $emails_senders_list = $this->get_all_account_managers('Account Manager');
        $array_templates = array(
                                    'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                    'project_number'=>$post->projectnumber,
                                    'project_name'=>$post->projectname,
                                    'request_url'=>base_url(),
                                    'subject' => $subject_to_send
                                );
        
        $this->send_grid_email($template_idddd, $subject_to_send, $emails_senders_list, $array_templates);
        
        if($post->additionalpm != 1){
            // SEND EMAIL TO MP
            if($post->compdiscipline == 3){
                $template_iddddsp = 'd-9957b598abe348f59e2605d24a3c2caf';
                $subject_to_send = 'YOUR ASA REQUEST IS COMPLETED : '.$post->projectnumber.' : '.$post->projectname;
            } else {
                $template_iddddsp = 'd-088e5883d95041229cbd2fc3db6d8c38';
                $subject_to_send = 'ASA Action Required : '.$post->projectnumber.' : '.$post->projectname;
            }
            
            if($notify_engs != ""){
                $emails_senders_list = $this->db->query("SELECT * FROM users WHERE id IN (".$notify_engs.")")->result_object();
                   
                $array_templates = array(
                                            'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                            'project_number'=>$post->projectnumber,
                                            'project_name'=>$post->projectname,
                                            'request_url'=>base_url(),
                                            'subject' => $subject_to_send
                                        );
               
                $this->send_grid_email($template_iddddsp, $subject_to_send, $emails_senders_list, $array_templates);
            }
        }

        // IF ITS COMPLETED WHILE CREATING NEW ASA
        

        echo json_encode(array("action"=>"success"));
    }

    public function update_asa(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

         $user_logged = $this->do_auth($post);

         $get_asa_id = $this->db->query("SELECT * FROM asa_requests WHERE req_id = ".$post->editid)->result_object()[0];

         $notify_engs = implode(",", $post->notifyengineers);
         $notify_eng_emails = "";
         foreach($post->notifyengineers as $nk_=>$eng){
            if($eng != ""){
                $eng_user = $this->db->query("SELECT * FROM users WHERE id = '".$eng."'")->result_object()[0];
                $notify_eng_emails .= $eng_user->email.",";
            }
         }
         $new_eng_email = substr($notify_eng_emails,0,-1);

         $get_full_details = $this->db->query("SELECT * FROM users WHERE id = ".$post->fullname)->result_object()[0];

         $s_services = $post->standardService;
         // echo $s_services;
         // die;
         $row_serv = "";
         foreach($s_services as $key=>$srow){
            $sdata = $this->db->query("SELECT * FROM services WHERE id = '".$srow."'")->result_object()[0];
            $row_serv .= $sdata->name.", "; 
         }
         $service_text =  substr($row_serv,0,-2);

         $data = array(
            "asa_request_date" => $post->requestdate,
            "asa_project_no" => $post->projectnumber,
            // "pro_version" =>    ($get_asa_id->pro_version + 1),
            "asa_project_name" => $post->projectname,
            "client_project_number" => $post->clientprojectnumber,
            "asa_company_name" => $post->client,
            "company_contact" => $post->clientcontact,
            "asa_email" => $post->contactemails,
            "asa_urgency_work" => $post->urgencywork,
            "standard_services" => $service_text,
            "standard_services_id" => implode(",",$post->standardService),
            "service_description" => $post->servicedescription,
            "dateofcompletion" => $post->dateofcompletion,
            "request_due_date" => $post->completiondate,
            "send_notification" => $post->notifyengineers!=null?$new_eng_email:null,
            "discipline" => implode(",",$post->items),
            "completed_discipline" => $post->compdiscipline,
            "additional_pm" => $notify_engs,
            "client_address" => $post->clientaddress,
        );

        $this->db->where('req_id',$post->editid)->update('asa_requests',$data);
        $asa_id = $get_asa_id->req_id;


        $this->db->query("DELETE FROM asa_tasks_data WHERE asa_id = ".$asa_id." AND deleteable = 0");

        // ADD STANDARD SERVICES IN TASKS
        if(count($post->standardService) > 0){
            for($i=0;$i<=count($post->standardService)-1;$i++){
                $task_pos = $this->db->query("SELECT * FROM asa_tasks_data WHERE asa_id = '".$asa_id."' ORDER BY id DESC LIMIT 1")->result_object()[0];
                $old_ser = $this->db->query("SELECT * FROM services WHERE id = ".$s_services[$i])->result_object()[0];
                // echo $this->db->last_query();
                
                
                $name_stack = $old_ser->name;

                $single_price = ($old_ser->price/$old_ser->est_hrs);

                $total_pp = (($single_price * $old_ser->est_hrs));

                $markup = 0;

                if($old_ser->markup_type == 1 ){
                    $markup = ($total_pp * $old_ser->markupprice) / 100;
                } else {
                    $markup = $old_ser->markupprice;
                }

                $total_price = ($total_pp + $markup);
                $tax_val = $old_ser->tax;

                $new_width = ($tax_val / 100) * $total_price;

                $new_total_price = ($total_price + $new_width);


                $arry_s = array(
                    "asa_id" => $asa_id,
                    "task_name" => $name_stack,
                    "price" => $old_ser->price,
                    "task_time" => $old_ser->est_hrs,
                    "cost" => $old_ser->markup_cost,
                    "tax" => $old_ser->tax,
                    "total" => $new_total_price,
                    "task_description" => $old_ser->desription,
                    //"task_pos" => ($task_pos->task_pos+1),
                    "deleteable" => 0,
                    "markup_rate" => $old_ser->markup_price,
                    "markup_type" => $old_ser->markup_type,
                    "single_price" => ($single_price),
                    "label" => $old_ser->label,
                );
                // echo "<pre>";
                // print_r($arry_s);
                
                $this->db->insert("asa_tasks_data",$arry_s);
            }
        }

         // INSERT TASKS 
        $tasks_data = $post->items;
        $branches_data = $post->branches;
        
        foreach ($tasks_data as $itemId) {
            foreach ($branches_data as $branch) {
                if ($branch->id == $itemId) {
                    $get_dis_data = $this->db->query("SELECT * FROM disciplines WHERE id = ".$branch->id)->result_object()[0];
                    $arry_bv = array(
                            "asa_id"        => $asa_id,
                            "task_name"     => $get_dis_data->name,
                            "task_time"     => $branch->hour,
                            "price"         => $get_dis_data->price,
                            "cost"          => 0,
                            "total"         => ($get_dis_data->price*$branch->hour),
                            "task_description"     => $branch->task,
                        );
                    $this->db->insert("asa_tasks_data",$arry_bv);
                }
            }
        }

        if(count($post->branches) > 0){
            // DELETE DISCIPLINES
            $this->db->query("DELETE FROM asa_disciplines_data WHERE asa_id = ".$asa_id);

            for($i=0;$i<=count($post->branches)-1;$i++){
                $arry_b = array(
                                    "asa_id"    => $asa_id,
                                    "dis_id"    => $post->branches[$i]->id,
                                    "hour"      => $post->branches[$i]->hour,
                                    "tasks"     => $post->branches[$i]->task,
                                );
                $this->db->insert("asa_disciplines_data",$arry_b);
            }
        }

        // SEND REVISE EMAIL  TO AM, PM1, PM2 WHEN COMPLETED DISICPLINE IS ALREADY 3
        if($get_asa_id->completed_discipline == 3){

            // SEND EMAIL TO ACCOUNT MANAGER
            $template_idddd = 'd-76fb50fb373342e8875559ad280c69a0';
            $subject_to_send = 'ASA REQUEST REVISED  : '.$post->projectnumber.' : '.$post->projectname;

            $emails_senders_list = $this->get_all_account_managers('Account Manager');
            $array_templates = array(
                                        'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                        'project_number'=>$post->projectnumber,
                                        'project_name'=>$post->projectname,
                                        'request_url'=>base_url(),
                                        'subject' => $subject_to_send
                                    );

            $this->send_grid_email($template_idddd, $subject_to_send, $emails_senders_list, $array_templates);

            // SEND EMAIL TO PMs
            $template_iddddsp = 'd-76fb50fb373342e8875559ad280c69a0';
            if($notify_engs != ""){
                if($user_logged->email == $get_asa_id->asa_your_email)
                {
                    $emails_senders_list = $this->db->query("SELECT * FROM users WHERE id IN (".$notify_engs.")")->result_object();
                }
                else {
                    $emails_senders_list = $this->db->query("SELECT * FROM users WHERE email = '".$get_asa_id->asa_your_email."'")->result_object();
                    //$emails_senders_list = $this->db->query("SELECT * FROM users WHERE id = (".$get_user_id->id.")")->result_object();
                }
                $array_templates = array(
                                            'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                            'project_number'=>$post->projectnumber,
                                            'project_name'=>$post->projectname,
                                            'request_url'=>base_url(),
                                            'subject' => $subject_to_send
                                        );
               
                $this->send_grid_email($template_iddddsp, $subject_to_send, $emails_senders_list, $array_templates);
            }
        }

        // SEND COMPLETYED EMAIL  TO AM, PM1, PM2 WHEN COMPLETED DISICPLINE IS SET AS 3
        if($get_asa_id->completed_discipline != 3){


            if($post->compdiscipline == 3){

                // SEND EMAIL TO ACCOUNT MANAGER
                $template_idddd = 'd-9c3951f1edae473a907d9daf7d059da1';
                $subject_to_send = 'ASA REQUEST COMPLETED : '.$post->projectnumber.' : '.$post->projectname;

                $emails_senders_list = $this->get_all_account_managers('Account Manager');
                $array_templates = array(
                                            'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                            'project_number'=>$post->projectnumber,
                                            'project_name'=>$post->projectname,
                                            'request_url'=>base_url(),
                                            'subject' => $subject_to_send
                                        );

                $this->send_grid_email($template_idddd, $subject_to_send, $emails_senders_list, $array_templates);

                // SEND EMAIL TO PMs
                $template_iddddsp = 'd-9957b598abe348f59e2605d24a3c2caf';
                $subject_to_sendsp = 'YOUR ASA REQUEST IS COMPLETED : '.$post->projectnumber.' : '.$post->projectname;

                if($notify_engs != ""){
                    if($user_logged->email == $get_asa_id->asa_your_email)
                    {
                        $emails_senders_list = $this->db->query("SELECT * FROM users WHERE id IN (".$notify_engs.")")->result_object();
                    }
                    else {
                        $emails_senders_list = $this->db->query("SELECT * FROM users WHERE email = '".$get_asa_id->asa_your_email."'")->result_object();
                        //$emails_senders_list = $this->db->query("SELECT * FROM users WHERE id = (".$get_user_id->id.")")->result_object();
                    }
                    // echo $this->db->last_query();
                    // die;
                    $array_templates = array(
                                                'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                                'project_number'=>$post->projectnumber,
                                                'project_name'=>$post->projectname,
                                                'request_url'=>base_url(),
                                                'subject' => $subject_to_sendsp
                                            );
                    $this->send_grid_email($template_iddddsp, $subject_to_sendsp, $emails_senders_list, $array_templates);
                }
            }
        }
        

        echo json_encode(array("action"=>"success"));
    }

    public function archive_single_specific_asa(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $user_logged = $this->do_auth($post);
        $archive_ids = $post->ids;
        $data = array(
            "archive_status" => 1,
            "archive_reason" => $post->reason
        );
        $this->db->where('req_id',$archive_ids)->update('asa_requests',$data);

        echo json_encode(array("action"=>"success", "data"=> $this->db->last_query()));
    }
    public function archive_specific_asa(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

         $user_logged = $this->do_auth($post);
         $archive_ids = $post->ids;
         for($i=0;$i<=count($archive_ids);$i++){
            $data = array(
                "archive_status" => 1,
                'archive_reason' => $post->reason
            );
            $this->db->where('req_id',$archive_ids[$i])->update('asa_requests',$data);
         }

        echo json_encode(array("action"=>"success"));
    }

    public function unarchive_specific_asa(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

         $user_logged = $this->do_auth($post);
         $archive_ids = $post->ids;
        $data = array(
            "archive_status" => 0,
            "archive_reason" => null
        );
        $this->db->where('req_id',$archive_ids)->update('asa_requests',$data);
        echo json_encode(array("action"=>"success"));
    }

    public function delete_all_archive_requestes(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;

        $user_logged = $this->do_auth($post);
        $ids = $post->ids;

        foreach($ids as $k=>$r){
            $this->db->query("DELETE FROM asa_requests WHERE archive_status = 1 AND req_id = ".$r);
        }

       // $this->db->query("DELETE FROM asa_requests WHERE archive_status = 1");
        echo json_encode(array("action"=>"success"));
    }

    public function asa_send_client_actions(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $asa = $this->db->query("SELECT * FROM asa_requests WHERE pNumber = '".$post->asa_id."'")->result_object()[0];
        $check_client_data = $this->db->query("SELECT * FROM asa_client_data WHERE asa_id = ".$asa->req_id)->result_object()[0];

        $data = array(
            "asa_id" => $asa->req_id,
            "template_id" => $post->templatesid,
            "po_number" => $post->ponumber,
            "name" => $post->nameinfo,
            "address" => $post->address,
            "city" => $post->cityinfo,
            "state" => $post->stateinfo,
            "zipcode" => $post->zipcode,
            "description" => $post->description,
            "start_date" => $post->startdate,
            "valid_date" => $post->futrudate,
            "budget" => $post->internalbudget,
            "internal_notes" => $post->internalnotes,
            "pricing_mode" => $post->pricingmode,
            "markup_service" => $post->markup,
            "selected_tasks" => implode(",", $post->billtasks),
            "selected_costs" => empty($post->billcosts)?null:implode(",", $post->billcosts),
         );

        if(empty($check_client_data)){
            $this->db->insert("asa_client_data",$data);
        } else {
            $this->db->where('asa_id',$asa->req_id)->update('asa_client_data',$data);
        }
        
        // UDPATE ASA REQUEST TABLE
        $asa_data = array(
            "asa_project_no" => $post->projectnumber,
            "asa_project_name" => $post->projectname,
            "asa_company_name" => $post->client,
            "company_contact" => $post->clientcontact,
            "asa_email" => $post->contactemails,
            "client_project_number" => $post->clientprojectnumber,
            "asa_urgency_work" => $post->urgencywork,
            "am_id" => $user_logged->id,
            "service_description" => $post->description,
        );

        // if($post->type == 3){
        //     $asa_data['completed_discipline'] = 6;
        //     $asa_data['send_to_client'] = 1;
        // }
        $this->db->where('req_id',$asa->req_id)->update('asa_requests',$asa_data);

        if($post->type != 2){
            $this->do_create_pdf($asa->req_id);
        }
        echo json_encode(array("action"=>"success"));
    }

     public function do_issue_asa_to_client(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $asa = $this->db->query("SELECT * FROM asa_requests WHERE req_id = ".$post->asa_id)->result_object()[0];
        $notify_engs = $asa->additional_pm;
        
        // UDPATE ASA REQUEST TABLE
        $asa_data = array(
            "completed_discipline" => 6,
            "send_to_client" => 1,
        );

        $this->db->where('req_id',$post->asa_id)->update('asa_requests',$asa_data);
        
        // SEND EMAIL TO PMs

        if($asa->additional_pm != "") {
            $template_iddddsp = 'd-ea3f4f95055d43209fced24dc3d60a84';
            $subject_to_sendsp = 'ASA SENT TO CLIENT : '.$asa->asa_project_no.' : '.$asa->asa_project_name;
            $emails_senders_list = $this->db->query("SELECT * FROM users WHERE (id IN (".$notify_engs.") OR email = '".$asa->asa_your_email."')")->result_object();

            $array_templates = array(
                                        'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                        'project_number'=>$asa->asa_project_no,
                                        'project_name'=>$asa->asa_project_name,
                                        'request_url'=>base_url(),
                                        'subject' => $subject_to_sendsp
                                    );
           
            $this->send_grid_email($template_iddddsp, $subject_to_sendsp, $emails_senders_list, $array_templates);
        }

        // SEND EMAIL TO CLIENT HERE AS WELL
        $template_iddddsp = 'd-5e70847d2211427188bce2d2da32f34d';
        $subject_to_sendsp = 'ASA: '.$asa->pNumber.' From Ardebili Engineering : '.$asa->asa_project_name;
        $emails_senders_list = explode(",", $asa->asa_email);
        
        $array_templates = array(
                                    'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                    'project_number'=>$asa->asa_project_no,
                                    'project_name'=>$asa->asa_project_name,
                                    'request_url'=>base_url(),
                                    'subject' => $subject_to_sendsp
                                );
        $url_pdf = base_url()."resources/uploads/".$asa->pNumber.".pdf";
        //$this->send_grid_email($template_iddddsp, $subject_to_sendsp, $emails_senders_list, $array_templates, 1, $url_pdf);


        echo json_encode(array("action"=>"success"));
    }

     public function update_already_task_client(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $asa = $post->task;

        //$single_price = ($asa->price / $asa->task_time);

        $get_task_id = $this->db->query("SELECT * FROM asa_tasks_data WHERE id = ".$asa->id)->result_object()[0];
        if($get_task_id->price != $asa->price){
            $single_ppp = ($asa->price / $asa->task_time);
        } else {
            $single_ppp = $get_task_id->single_price;
        }

        if($post->pricingmode == 1){
            $total_price = (($single_ppp * $asa->task_time));
        } else if($post->pricingmode == 2) {
            $total_price = (($asa->total));
        } else {
            $total_price = (($single_ppp * $asa->task_time));
        }

        if($get_task_id->markup_type ==  1){
            $markup_tax = ($get_task_id->markup_rate / 100);
            $markup_rate_add = ($markup_tax * $total_price);
        } else if($get_task_id->markup_type ==  1){
            $markup_rate_add = $get_task_id->markup_rate;
        } else {
            $markup_rate_add = 0;
        }

        $sub_price = ($total_price + $markup_rate_add);

        $tax_rate_add = 0;
        if($get_task_id->tax != null){
            $__tax = ($get_task_id->tax / 100);
            $tax_rate_add = ($__tax * $sub_price);
        }

        $total_final = ($sub_price + $tax_rate_add);

        $data = array(
            "price" => $asa->price,
            "task_time" => $asa->task_time,
            "cost" => $asa->cost,
            "total" => $total_final,
            "single_price" => $single_ppp
        );

        // print_r($data);
        // die;

        $this->db->where('id',$asa->id)->update('asa_tasks_data',$data);
        echo json_encode(array("action"=>"success"));
    }

    public function update_asa_status_data(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $asa_data['completed_discipline'] = $post->code;

        if($post->proposal == 1){
            $this->db->where('id',$post->ids)->update('asa_quote',$asa_data);
        } else {
            $this->db->where('req_id',$post->ids)->update('asa_requests',$asa_data);
        }


        if($post->proposal == 1 && $post->code == 6){
            // SEND EMAIL TO CLIENT
            $asa = $this->db->query("SELECT * FROM asa_quote WHERE id = ".$post->ids)->result_object()[0];


            // SEND EMAIL TO CLIENT HERE AS WELL
            $template_iddddsp = 'd-5e70847d2211427188bce2d2da32f34d';
            $subject_to_sendsp = 'ASA: '.$asa->quote_number.' From Ardebili Engineering : '.$asa->projectname;
            $emails_senders_list = explode(",", $asa->contactemails);
            
            $array_templates = array(
                                        'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                        'project_number'=>$asa->quote_number,
                                        'project_name'=>$asa->projectname,
                                        'request_url'=>base_url(),
                                        'subject' => $subject_to_sendsp
                                    );
           
            //$this->send_grid_email($template_iddddsp, $subject_to_sendsp, $emails_senders_list, $array_templates, 1);
        }


        if($post->proposal != 1){
            if($post->code == 7){
                // SEND EMAIL TO CLIENT
                $asa = $this->db->query("SELECT * FROM asa_requests WHERE req_id = ".$post->ids)->result_object()[0];


                // SEND EMAIL TO PMS WHEN ASA IS DECLINED BY AM
                $template_iddddsp = 'd-c22d487cf8634c559846dc858f6a49dc';
                $subject_to_sendsp = 'ASA PROCEED WITH WORK : '.$asa->asa_project_no.' : '.$asa->asa_project_name;
                $emails_senders_list = $this->db->query("SELECT * FROM users WHERE (id IN (".$asa->additional_pm.") OR email = '".$asa->asa_your_email."')")->result_object();
                
                $array_templates = array(
                                            'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                            'project_number'=>$asa->asa_project_no,
                                            'project_name'=>$asa->asa_project_name,
                                            'request_url'=>base_url(),
                                            'subject' => $subject_to_sendsp
                                        );
                $this->send_grid_email($template_iddddsp, $subject_to_sendsp, $emails_senders_list, $array_templates);
            }

            if($post->code == 4){
                // SEND EMAIL TO CLIENT
                $asa = $this->db->query("SELECT * FROM asa_requests WHERE req_id = ".$post->ids)->result_object()[0];
                $template_iddddsp = 'd-4faf0f451ccd477c822a7e00b8f59d3d';
                $subject_to_sendsp = 'ASA APPROVED : '.$asa->asa_project_no.' : '.$asa->asa_project_name;
                $emails_senders_list = $this->db->query("SELECT * FROM users WHERE (id IN (".$asa->additional_pm.") OR email = '".$asa->asa_your_email."')")->result_object();
                
                $array_templates = array(
                                            'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                            'project_number'=>$asa->asa_project_no,
                                            'project_name'=>$asa->asa_project_name,
                                            'request_url'=>base_url(),
                                            'subject' => $subject_to_sendsp
                                        );
                $this->send_grid_email($template_iddddsp, $subject_to_sendsp, $emails_senders_list, $array_templates);
            }

            if($post->code == 5){
                // SEND EMAIL TO CLIENT
                $asa = $this->db->query("SELECT * FROM asa_requests WHERE req_id = ".$post->ids)->result_object()[0];
                $template_iddddsp = 'd-4cc76944b6bd492b8bc4480accaff1e0';
                $subject_to_sendsp = 'ASA DECLINED : '.$asa->asa_project_no.' : '.$asa->asa_project_name;
                $emails_senders_list = $this->db->query("SELECT * FROM users WHERE (id IN (".$asa->additional_pm.") OR email = '".$asa->asa_your_email."')")->result_object();
                
                $array_templates = array(
                                            'person_name'=>$user_logged->fname." ".$user_logged->lname,
                                            'project_number'=>$asa->asa_project_no,
                                            'project_name'=>$asa->asa_project_name,
                                            'request_url'=>base_url(),
                                            'subject' => $subject_to_sendsp
                                        );
                $this->send_grid_email($template_iddddsp, $subject_to_sendsp, $emails_senders_list, $array_templates);
            }

        }

        echo json_encode(array("action"=>"success"));
    }

    public function get_specific_asa_client_data(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $asa = $this->db->query("SELECT * FROM asa_requests WHERE pNumber = '".$post->id."'")->result_object()[0];
        $check_client_data = $this->db->query("SELECT * FROM asa_client_data WHERE asa_id = ".$asa->req_id)->result_object()[0];

        if(!empty($check_client_data)){
            $check_client_data->tasks_array = $check_client_data->selected_tasks!=null?explode(",", $check_client_data->selected_tasks):null;
            $check_client_data->cost_array = $check_client_data->selected_costs!=null?explode(",", $check_client_data->selected_costs):null;
        }
        echo json_encode(array("action"=>"success", "data"=>$check_client_data, "description"=>$asa->service_description));
    }



    

    public function fetchPdfFromUrl($url)
    {
        return file_get_contents($url);
    }

    


    public function send_grid_email($template_id, $subject_to_send, $emails_senders_list, $array_templates, $client_email = 0, $url=""){
        require_once('vendor/autoload.php');
        $apiKey = $this->settings()->apikey;
        $sg = new \SendGrid($apiKey);

        $email = new \SendGrid\Mail\Mail();
        $email->setTemplateId($template_id);
        // $email->setFrom("production@ardebiliop.com", "Ardebili Engineering");
        $email->setFrom("notification@ardebiliop.com", "Ardebili Engineering");
        
        $email->setSubject($subject_to_send);
        

        if($client_email == 1){
            foreach($emails_senders_list as $sender){
                $email->addTo($sender, "Client");
            }
        } else {
            foreach($emails_senders_list as $sender){
                $sender_name = $sender->fname." ".$sender->lname;
                $email->addTo($sender->email, $sender_name);
            }
        }

        // Set the dynamic template variables
        foreach($array_templates as $ke=>$row){
            $email->addDynamicTemplateData($ke, $row);
        }


        if($url != ""){
            $pdfUrl = $url;
            $parts = explode('/', $url);
            $fileName = end($parts);
            $fileName = explode('?', $fileName)[0];

            $pdfContent = $this->fetchPdfFromUrl($pdfUrl);
            $attachment = new \SendGrid\Mail\Attachment();
            $attachment->setContent(base64_encode($pdfContent));
            $attachment->setType('application/pdf');
            $attachment->setFilename("asa_".$fileName);
            $attachment->setDisposition('attachment');
            $email->addAttachment($attachment);
        }
        $response = $sg->send($email);


        // if ($response->statusCode() == 202) {
        //   echo 'Email sent successfully.';
        // } else {
        //   echo 'Failed to send email: ' . $response->body();
        // }
    }

     public function quote_client_number_generator(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        // $quote_number =  $this->db->query("SELECT * FROM asa_quote WHERE type = ".$post->type." ORDER BY quote_count DESC LIMIT 1")->result_object()[0];

        // $new_quote_number = ($quote_number->quote_count + 1);

        $new_quote_number = $this->get_misisng_numbers($post->type);

        echo json_encode(array("action"=>"success", "data"=>$new_quote_number));


    }

    public function get_misisng_numbers($type){
        $quote_number =  $this->db->query("SELECT quote_count FROM asa_quote")->result_object();

        if(empty($quote_number)){
            return  $missingNumbers = 1;
            die;
        }

        foreach($quote_number as $k=>$row){
            $existingNumbers[] = $row->quote_count;
        }
        //print_r($existingNumbers);


        for ($i = 1; $i <= count($existingNumbers)+1; $i++) {

            if (!in_array($i, $existingNumbers)) {
                return $missingNumbers = $i;
               die;
            }
        }
        
    }

    public function quote_client_data(){
        $post = json_decode(file_get_contents("php://input"));
        if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);

        $check_client_data = $this->db->query("SELECT * FROM asa_quote WHERE quote_number = '".$post->quote_number."'")->result_object()[0];

        // $quote_number =  $this->db->query("SELECT * FROM asa_quote ORDER BY quote_count DESC LIMIT 1")->result_object()[0];
        // $new_quote_number = ($quote_number->quote_count + 1);

        $new_quote_number = $this->get_misisng_numbers($post->type);

        $data = array(
            "uID" => $user_logged->id,
            "quote_number" => $post->quote_number,
            "quote_count" => $new_quote_number,
            "type" => $post->type,
            "projectnumber" => $post->projectnumber,
            "projectname" => $post->projectname,
            "client" => $post->client,
            "clientcontact" => $post->clientcontact,
            "contactemails" => $post->contactemails,
            "clientprojectnumber" => $post->clientprojectnumber,
            "asa_urgency_work" => $post->urgencywork==0?0:$post->urgencywork,
            "template_id" => $post->templatesid==""?0:$post->templatesid,
            "po_number" => $post->ponumber,
            "name" => $post->nameinfo,
            "address" => $post->address,
            "city" => $post->cityinfo,
            "state" => $post->stateinfo,
            "zipcode" => $post->zipcode,
            "description" => nl2br($post->description),
            "start_date" => $post->startdate,
            "valid_date" => $post->futrudate,
            "budget" => $post->internalbudget==null?0:$post->internalbudget,
            "internal_notes" => $post->internalnotes,
            "pricing_mode" => $post->pricingmode==0?0:$post->pricingmode,
            "markup_service" => $post->markup==""?0:1,
            "tasks_array" => !empty($post->billtasks)?implode(",", $post->billtasks):null,
            "costs_array" => empty($post->billcosts)?null:implode(",", $post->billcosts),
            "completed_discipline" => $post->update_status,
            "client_address" => $post->clientaddress==null?null:$post->clientaddress,
            "am_id" => $user_logged->id
         );

        if(empty($check_client_data)){
            $data['created_at'] = date("d/m/Y");
            $data['revision_number'] = 0;
            $this->db->insert("asa_quote",$data);
        } else {
            unset($data['quote_count']);
            unset($data['completed_discipline']);
            //if($post->revise != -1){
            if(!empty($check_client_data->completed_discipline) && $check_client_data->completed_discipline == 6){
                $rev_number =  $this->db->query("SELECT * FROM asa_quote WHERE quote_number = '".$post->quote_number."'")->result_object()[0];
                $new_rev_number = ($rev_number->revision_number + 1);
                $data['revision_number'] = $new_rev_number;
            }
            // if($post->sendtype == 3){
            //     $data['completed_discipline'] = 6;
            // }
            $this->db->where('quote_number',$post->quote_number)->update('asa_quote',$data);
            if($post->sendtype != 2){
                $this->do_create_pdf($check_client_data->id, 9);
            }
        }

        
        
        echo json_encode(array("action"=>"success"));
    }

    public function get_all_quotes(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;

        $user_logged = $this->do_auth($post);

        $specific_id = "";
        $services_data = array();

        $asa_dat = array();
        if($post->id != ""){
            $specific_id = $post->id;
            $asa_dat = $this->db->query("SELECT * FROM asa_quote WHERE quote_number = '".$specific_id."'")->result_object()[0];
            if(!empty($asa_dat)){
                $asa_dat->task_completed = explode(",", $asa_dat->tasks_array);
                $asa_dat->cost_completed = explode(",", $asa_dat->costs_array);
            }
        } 

        $get_templates = $this->db->query("SELECT * FROM proposal_templates ORDER BY id DESC")->result_object();
        $quotes = $this->db->query("SELECT * FROM asa_quote ORDER BY id DESC")->result_object();
        echo json_encode(array("action"=>"success","templates"=>$get_templates, "tasks"=>$tasks_data, "quote_data"=>$quotes, "asa_data"=>$asa_dat));
    }

    public function get_react_asa_pdf_data(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);
        $asa = $this->db->query("SELECT * FROM asa_requests WHERE pNumber = '".$post->asa_id."'")->result_object()[0];

        $this->do_get_pdf_data($asa->req_id);
    }

    public function get_react_quote_pdf_data(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $user_logged = $this->do_auth($post);
        $asa = $this->db->query("SELECT * FROM asa_quote WHERE quote_number = '".$post->asa_id."'")->result_object()[0];
        $this->do_get_quote_pdf_data($asa->id);
    }


    public function do_check_email_pdf($emails){
        $string = $emails;
        $abc = array();
        $exploded = explode(',', $string); // Split the string into an array using comma as the delimiter
        if (count($exploded) > 1) {
            $valuesAfterFirstComma = array_slice($exploded, 1); // Extract values starting from the second element
            foreach ($valuesAfterFirstComma as $value) {
                $abc[] = trim($value); // Output each value after the first comma, trimming any whitespace
            }
            return $abc;
        } else {
            return null;
        }

    }

    public function do_get_pdf_data($asa_id, $type=1){
        $asa = $this->db->query("SELECT * FROM asa_requests WHERE req_id = ".$asa_id)->result_object()[0];
        $asa->recipients = $asa->send_notification!=null?explode(",",$asa->send_notification):null;
        $asa->client_emails = $asa->asa_email!=null?$this->do_check_email_pdf($asa->asa_email):null;
        $asa->first_email = $asa->asa_email!=null?strtok($asa->asa_email, ","):null;

        if($asa->am_id != 0){
            $am_data = $this->db->query("SELECT * FROM users WHERE id = ".$asa->am_id)->result_object()[0];
            //$asa->am_name = $am_data->fname." ".$am_data->lname;
            $asa->am_name = 'MATTHEW FABROS';
        }
        $asa->signature = base_url()."signature.png";

        $client_data = $this->db->query("SELECT * FROM asa_client_data WHERE asa_id = ".$asa_id)->result_object()[0];
        $client_data->start_asa_date = date("m/d/Y", strtotime($client_data->start_date));
        $client_data->valid_asa_date = date("m/d/Y", strtotime($client_data->valid_date));
        $client_data->service_description = $client_data->description;


        // $tasks_selected = substr($client_data->selected_tasks, 1);
        $tasks_selected = ($client_data->selected_tasks);
        $tasks_selected = $tasks_selected!=""?$tasks_selected:0;
       
        $task_data = $this->db->query("SELECT * FROM asa_tasks_data WHERE id IN (".$tasks_selected.")")->result_object();
        $total = 0;
        foreach($task_data as $k=>$row){
            $total += $row->total;
        }

        if($type==99){
            return array("asa"=>$asa, "client_data"=>$client_data, "tasks"=>$task_data, "total_amount"=>$total);
        } else {
            echo json_encode((array("action"=>"success", "asa"=>$asa, "client_data"=>$client_data, "tasks"=>$task_data, "total_amount"=>$total)));
        }
    }

    public function do_get_quote_pdf_data($asa_id, $type=1){
        $asa = $this->db->query("SELECT * FROM asa_quote WHERE id = ".$asa_id)->result_object()[0];
        $asa->asa_company_name = $asa->client;
        $asa->company_contact = $asa->clientcontact;
        $asa->pNumber = $asa->quote_number;
        $asa->asa_project_name = $asa->projectname;
        $asa->service_description = $asa->description;
        $asa->client_project_number = $asa->clientprojectnumber;
        if($asa->type==1){
            $asa->asa_project_no = $asa->clientprojectnumber;
        } else {
            $asa->asa_project_no = $asa->projectnumber;
        }

        if($asa->am_id != 0){
            $am_data = $this->db->query("SELECT * FROM users WHERE id = ".$asa->am_id)->result_object()[0];
            // $asa->am_name = $am_data->fname." ".$am_data->lname;
            $asa->am_name = 'MATTHEW FABROS';
        }
       $asa->signature = base_url()."signature.png";

        $client_data = $this->db->query("SELECT * FROM asa_quote WHERE id = ".$asa_id)->result_object()[0];
        $client_data->start_asa_date = date("m/d/Y", strtotime($client_data->start_date));
        $client_data->valid_asa_date = date("m/d/Y", strtotime($client_data->valid_date));
        $client_data->service_description = $client_data->description;
         // $client_data->recipients = $client_data->contactemails!=null?explode(",",$client_data->contactemails):null;
        // $client_data->client_emails = $client_data->contactemails!=null?explode(",",$asa->contactemails):null;
        $client_data->client_emails = $client_data->contactemails!=null?$this->do_check_email_pdf($client_data->contactemails):null;
        $client_data->first_email = $client_data->contactemails!=null?strtok($client_data->contactemails, ","):null;

        if($asa->type==1){
            $client_data->clientprojectnumber = $asa->clientprojectnumber;
        } else {
            $client_data->clientprojectnumber = $asa->projectnumber;
        }

        $tasks_selected = $client_data->tasks_array;
        $tasks_selected = $tasks_selected!=""?$tasks_selected:0;
       
        $task_data = $this->db->query("SELECT * FROM asa_tasks_data WHERE quote = 1 AND id IN (0,".$tasks_selected.")")->result_object();
        $total = 0;
        foreach($task_data as $k=>$row){
            $total += $row->total;
        }

        if($type==99){
            return array("asa"=>$asa, "client_data"=>$client_data, "tasks"=>$task_data, "total_amount"=>$total);
        } else {
            echo json_encode((array("action"=>"success", "asa"=>$asa, "client_data"=>$client_data, "tasks"=>$task_data, "total_amount"=>$total)));
        }
    }

    public function do_create_pdf_mpdf($asa_id, $type=1){
        if($type == 9){
            $row = $this->do_get_quote_pdf_data($asa_id, 99);
        } else {
            $row = $this->do_get_pdf_data($asa_id, 99);
        }
        
        //require_once 'vendor/autoload.php';
        
        $this->data['asa'] = $row['asa'];
        $this->data['client_data'] = $row['client_data'];
        $this->data['tasks'] = $row['tasks'];
        $this->data['total_amount'] = $row['total_amount'];
        $image_url_pdf = base_url()."resources/logo_main.png";
        $this->data['image_url'] = base_url()."resources/logo_main.png";
        $this->data['signature'] = base_url()."signature.png";



        

        $mpdfConfig = [
            'orientation' => 'P', 
            'setAutoTopMargin' => 'stretch', 
            'setAutoBottomMargin' => 'stretch'
        ];
        $mpdf = new \Mpdf\Mpdf($mpdfConfig);
        $mpdf->SetHTMLHeader('
                <div style="margin-bottom:15px; display: flex; align-items:center">
                    <div class="logo" style="float:left"><img src="'.$image_url_pdf.'" alt="" style="max-width: 215px; margin-left: -13px;" /></div>
                    <div class="quote_number" style="float:left">
                        Proposal #: '.$row['asa']->pNumber.'
                    </div>
                </div>'
            );
            $mpdf->AddPage();
            $mpdf->SetHTMLFooter('
            <div class="footer_custom" id="footer" style="margin-bottom:30px">
            <table class="footer_table">
                <tbody>
                    <tr>
                        <td >
                            <span>
                                Ardebili Engineering LLC<br>
                                7328 East Stetson Drive<br>
                                SCottSdale, AZ 05251
                            </span>
                        </td>
                        <td>
                            <span>480.628.7020<br>
                            ArdebiliEng.com</span>
                        </td>
                        <td><img src="<?php echo $image_url;?>" alt="" /></td>
                    </tr>
                </tbody>
            </table>
        </div>');

        if($type == 9){
            $html = $this->load->view('pdf_quote', $this->data, true);
            $final_file_name = "Proposal ".$row['asa']->quote_number." from Ardebili Engineering ".$row['asa']->projectname;
        } else {
            $html = $this->load->view('pdf', $this->data, true);
            $final_file_name = "ASA ".$row['asa']->pNumber." from Ardebili Engineering ".$row['asa']->asa_project_name;
        }
        
        $arr_pdf_name = array("pdf_name"=>$final_file_name);
        if($type == 9){
            $this->db->where('id',$asa_id)->update('asa_quote',$arr_pdf_name);
        } else {
            $this->db->where('req_id',$asa_id)->update('asa_requests',$arr_pdf_name);
        }

        // $stylesheet = file_get_contents(base_url().'pdf.css');
        // $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($html, 0);
        $mpdf->Output();
        


    }


    public function do_create_pdf($asa_id, $type=1){
        if($type == 9){
            $row = $this->do_get_quote_pdf_data($asa_id, 99);
        } else {
            $row = $this->do_get_pdf_data($asa_id, 99);
        }
        
        require_once 'vendor/autoload.php';
        $webRoot = getcwd();

        $options = new Options();
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'sans-serif');
        $options->set('TempDir', 'temp');
        $options->set('chroot', '');
        $options->set('dpi','120');
        $options->set('enable_html5_parser',true);

        $whitelist = array('127.0.0.1', "::1", "192.168.100.3", "192.168.100.2", "192.168.100.4", "192.168.18.80");
        if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
            $options->set('isRemoteEnabled', true);
        } else {
            $options->set('isRemoteEnabled', false);
        }

       
        $dompdf = new Dompdf($options);
        
        $this->data['asa'] = $row['asa'];
        $this->data['client_data'] = $row['client_data'];
        $this->data['tasks'] = $row['tasks'];
        $this->data['total_amount'] = $row['total_amount'];
        $image_url_inner = base_url()."resources/logo_main.png";
        $this->data['image_url'] = base_url()."resources/logo_main.png";
        $this->data['signature'] = base_url()."signature.png";



        if($type == 9){
            if($row['asa']->type == 2){
                $html = $this->load->view('pdf', $this->data, true);
                $final_file_name = "ASA ".$row['asa']->quote_number." from Ardebili Engineering ".$row['asa']->projectname;
            } else {
                $html = $this->load->view('pdf_quote', $this->data, true);
                $final_file_name = "Proposal ".$row['asa']->quote_number." from Ardebili Engineering ".$row['asa']->projectname;
            }
            
            
        } else {
            $html = $this->load->view('pdf', $this->data, true);
            $final_file_name = "ASA ".$row['asa']->pNumber." from Ardebili Engineering ".$row['asa']->asa_project_name;
        }
        
        $arr_pdf_name = array("pdf_name"=>$final_file_name);
        if($type == 9){
            $this->db->where('id',$asa_id)->update('asa_quote',$arr_pdf_name);
        } else {
            $this->db->where('req_id',$asa_id)->update('asa_requests',$arr_pdf_name);
        }

        // UPDATE FILE NAME TO ASA

        // HTML content for the header
        if($type == 9 && $row['asa']->type == 1){
            $headerHtml = '<header>
                <div class="header_custom">
                <div class="logo"><img src="'.$image_url_inner.'" alt="" /></div>
                    <div class="quote_number">
                        Proposal #: '.$row['asa']->pNumber.'
                    </div>
                    </div>
            </header>';
        } else {
            $headerHtml = '<header>
                <div class="header_custom">
                <div class="logo"><img src="'.$image_url_inner.'" alt="" /></div>
                    <div class="quote_number_asa">
                        Proposal #: '.$row['asa']->pNumber.'
                        <br>
                        Project #: '.$row['asa']->asa_project_no.'
                    </div>
                    </div>
            </header>';
        }

        // HTML content for the footer
        $footerHtml = '<footer>
        <div class="footer_custom">
                <table class="footer_table">
                    <tbody>
                        <tr>
                            <td >
                                <div>
                                    Ardebili Engineering LLC<br>
                                    7328 East Stetson Drive<br>
                                    SCottSdale, AZ 05251
                                </div>
                            </td>
                            <td>
                                <div>480.628.7020<br>
                                ArdebiliEng.com</div>
                            </td>
                            <td><img src="'.$image_url_inner.'" alt="" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </footer>';

        // HTML content for the main body of the document
        $bodyHtml = $html;

        // Combine header, footer, and body content
$html_o = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Your PDF Title</title>
    <style>
        header {
            position: fixed;
            top:-70px; /* Adjust this value to match margin-top above */
            left: 0;
            right: 0;
            z-index: 9;

        }
        .header_custom {
            position: relative;
        }
        .header_custom .quote_number {
            position: absolute;
            right: 0;
            top: 13px;
            font-size: 13px;
        }
        .header_custom .quote_number_asa {
             position: absolute;
            right: 0;
            top: 10px;
            font-size: 13px;
        }
        .footer_custom table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        footer {
            position: fixed;
            bottom: -60px; /* Adjust this value to match margin-bottom above */
            left: 0;
            right: 0;
            z-index: 9;
        }
       .footer_table {
            width: 100%;
        }
        table.footer_table td div{
            text-transform: uppercase;
            color: #cecece;
            width: 100%;
            font-size: 12px;
        }
        table.footer_table td:nth-child(1) {
            text-align: left;
                width: 25%;
        }
        table.footer_table td:nth-child(2) {
            text-align: center;
                width: 20%;
        }
        table.footer_table td:nth-child(3) {
            text-align: right;
                width: 25%;
        }
        table.footer_table td:nth-child(3) img {
            max-width: 140px;
            filter: grayscale(100%) brightness(70%) sepia(100%) hue-rotate(0deg) saturate(0%);
          /* The above line sets various filters to achieve the desired color change */
          filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(100%) contrast(100%);
          /* The above line is needed for Safari compatibility */
        }
    </style>
</head>
<body>
    $headerHtml
    $footerHtml
    $bodyHtml
    
</body>
</html>
HTML;

        $dompdf->loadHtml($html_o);
        $dompdf->setPaper('letter', 'portrait');

            // $dompdf->add_info('Title', 'Your meta title');
        $dompdf->render();

        // ADD FOOTER PAGES
        $totalPages = $dompdf->getCanvas()->get_page_count();
        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont("helvetica", "normal");

        $canvas->page_text(540, 770, "Page {PAGE_NUM} / $totalPages", $font, 8, array(125 / 255, 125 / 255, 125 / 255));

        // $footer = '<div style="text-align: center; background-color: yellow; padding: 5px;">Page {PAGE_NUM} / ' . $totalPages . '</div>';
        // $canvas->page_text(140, 770, $footer, $font, 8, array(125 / 255, 125 / 255, 125 / 255));

        $pdfContent = $dompdf->output();
        // $dompdf->stream('output.pdf', array('Attachment' => false));
        // die;
        $outputDirectory = './resources/uploads/';
        // $outputFilename = $row['asa']->pNumber.'.pdf';

        $outputFilename = $final_file_name.'.pdf';
        $outputPath = $outputDirectory . $outputFilename;
        file_put_contents($outputPath, $pdfContent);

    }

    public function do_create_download_pdf($file){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;
        $fileName = urldecode($file).".pdf";
        // $fileName = "A23003050.pdf";
        $path = './resources/uploads/'; // change the path to fit your websites document structure
        $fullPath = $path.$fileName;
         
        if ($fd = fopen ($fullPath, "r")) {
            $fsize = filesize($fullPath);
            $path_parts = pathinfo($fullPath);
            $ext = strtolower($path_parts["extension"]);
            switch ($ext) {
                case "pdf":
                header("Content-type: application/pdf"); // add here more headers for diff. extensions
                header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
                break;
                default;
                header("Content-type: application/octet-stream");
                header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
            }
            header("Content-length: $fsize");
            header("Cache-control: private"); //use this to open files directly
            while(!feof($fd)) {
                $buffer = fread($fd, 2048);
                echo $buffer;
            }
        }
        fclose ($fd);
        exit;
    }


    public function delete_specific_archive_asa(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;

        $user_logged = $this->do_auth($post);

        $get_templates = $this->db->query("DELETE FROM asa_requests WHERE req_id = ".$post->ids);
        echo json_encode(array("action"=>"success"));
    }

    public function update_position_of_data(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;

        $user_logged = $this->do_auth($post);

        $tasks = $post->task;
        $i = 1;
        foreach($tasks as $k=>$row){
            $this->db->query("UPDATE asa_tasks_data SET task_pos = ".$i." WHERE id = ".$row->id);
            $i++;
        }

        echo json_encode(array("action"=>"success"));
    }
    public function update_position_of_costs(){
        $post = json_decode(file_get_contents("php://input"));
            if(empty($post))
        $post = (object) $_POST;

        $user_logged = $this->do_auth($post);

        $tasks = $post->task;
        $i = 1;
        foreach($tasks as $k=>$row){
            $this->db->query("UPDATE asa_cost_data SET task_pos = ".$i." WHERE id = ".$row->id);
            $i++;
        }

        echo json_encode(array("action"=>"success"));
    }

    public function do_send_email_sendgrid(){
        // SEND EMAIL TO ACCOUNT MANAGER
        $subject_to_send = 'New ASA Request : abc : test project';
        $get_department_ = $this->db->query("SELECT * FROM role WHERE role = 'Account Manager'")->result_object()[0]->id;
        $emails_senders_list = $this->db->query("SELECT * FROM `user_departments` AS D, users AS U WHERE D.user_id = U.id AND D.`departement` = ".$get_department_)->result_object();
        $array_templates = array(
                                    'person_name'=>'ABC DEVELOPER',
                                    'project_number'=>'P145DEDEV',
                                    'project_name'=>'TEST PROJECT',
                                    'request_url'=>'https://www.google.com/',
                                    'subject' => $subject_to_send
                                );
        $this->send_grid_email('d-ccf8d48b895b42d6976bbfccc745fe4f', $subject_to_send, $emails_senders_list, $array_templates);
    }


    public function get_all_account_managers($role){
        $get_department_ = $this->db->query("SELECT * FROM role WHERE role = '".$role."'")->result_object()[0]->id;
        return $emails_senders_list = $this->db->query("SELECT * FROM `user_departments` AS D, users AS U WHERE D.user_id = U.id AND D.`departement` = ".$get_department_)->result_object();
    }

}
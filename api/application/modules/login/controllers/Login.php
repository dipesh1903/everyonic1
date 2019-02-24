<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends MX_Controller 
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_login');
        
//         $this->module="login";
//         $this->load->module('user_privileges');
    }
 	function index()
	{
       $this->load->module('login');
       if($this->login->auth())
       {
               echo 1;//success
       }else{
           echo 0;//logout
       }
   }
	function auth()
	{
//  	    echo "<pre>";print_r($_SESSION);
//         if($this->uri->segment(1)=='')

//         uncomment as when using login module it is redirecting everytime to login
		if($this->session->userdata('mem_id'))
	        return true;
	    else return false;
	}
	
	
	function check()
	{
// 	    print_r($_POST);die();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user','Username','trim|required');
		$this->form_validation->set_rules('pass','Password','trim|required|min_length[6]');
		if($this->form_validation->run()==true)
		{
// 		    print_r($_POST);die();
			$res=$this->mdl_login->validate();
			if($res==1)
			{
			    $val=array("msg"=>"<div class='alert alert-success'>Successfully Authenticated </div>","type"=>0);   
			}
			else
			{
			    $val=$res;
			}
		}
		else
		{
		    $val=array("msg"=>"<div class='alert alert-danger'>".validation_errors()."</div>","type"=>1);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($val));
	}
	
    function change_password_submit()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('currentpass','Current Password','required|trim');
		$this->form_validation->set_rules('newpass','New Password','required|trim|min_length[6]|max_length[15]');
		$this->form_validation->set_rules('cmpassword','Confirm new Password','required|trim|matches[newpass]');
		
		if($this->form_validation->run()==True)
		{
// 		    print_r($_POST);die();
			$this->load->model('mdl_login');
			if($this->mdl_login->change_pwd()==TRUE)
			{
			    echo 1;
			}
			else
			{
			    echo 0;
			}
		}
		else
		{
			echo validation_errors();
		}
	
	}
     function logout()
     {	
    	$this->session->set_userdata('');
    	$this->session->sess_destroy();
    	echo 0;
    }
	//Copyright @ Groveus (www.groveus.com)
}



<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();  
        $this->load->helper('text');
        $this->load->model('Mdl_admin_back');
    }
   function index()
   {
//        $string1=$_POST['name'];
//         echo $string= substr ($string1 , 0, 5); die();
       $this->load->module('login');
       if($this->login->auth())
       {
          echo 1;//success
       }
       else
       {
           echo 0;//logout
       }
   }
    function save()
    {
//         print_r($_POST);die();
        $this->load->library('form_validation');
        $this->form_validation->set_rules("company_name", "Company Name", "required|trim|is_unique[admin_details.company_name]");
        $this->form_validation->set_rules("name", "Name", "required|trim");
        $this->form_validation->set_rules("email", "Email", "trim|required|is_unique[admin_details.email]");
        $this->form_validation->set_rules("gender", "Gender", "required|trim");
        $this->form_validation->set_rules("pan", "Pan Card No", "required|trim");
        $this->form_validation->set_rules("city", "City", "required|trim");
        $this->form_validation->set_rules("state", "State", "trim");
        $this->form_validation->set_rules("address", "Address", "required|trim");
        $this->form_validation->set_rules("phone", "Phone", "required|trim|min_length[10]|max_length[10]");
        if ($this->form_validation->run()==TRUE)
        {   
            $val['company_name'] = ucfirst($_POST['company_name']);
            $val['name'] = ucfirst($_POST['name']);
            $val['email'] = $_POST['email'];
            if($this->input->post('gender'))
                $val['gender'] = $_POST['gender'];
            $val['pan'] = $_POST['pan'];
            $val['city'] = $_POST['city'];
            if($this->input->post('state'))
                $val['state'] = $_POST['state'];
            $val['address'] = $_POST['address'];
            $val['phone'] = $_POST['phone'];
            //generating com _id for each admin

            $string1=strtolower($_POST['company_name']);
            $string= substr ($string1 , 0, 5); 
            
            $val['com_id']=$string.mt_rand(10, 99).date("his");
//             print_r($val);die();
            if ($this->input->post('admin_id')) // update
            {
                $where['id'] = $this->input->post('admin_id');
                if($this->input->post('status'))
                    $val['status']= $this->input->post('status');
                echo $this->Mdl_admin_back->update_data($where,$val);
            }
            else // add
            { 
                echo $this->Mdl_admin_back->add_data($val);
            }
        }
        else
        {
            echo validation_errors();
        }
    }
    
    function fetch_admin_wallet()
    {
    	$where=null;
    	if (isset($_GET['admin_id']))
    		$where['admin_id']=$_GET['admin_id'];
    	$a=$this->db->where($where)->select('wal_bal')->get('admin_wallet');
    	if ($a->num_rows()>0)
    	{
    		$res=$a->result();
    		$this->output->set_content_type('application/json')->set_output(json_encode($res));
    	}
    	else 
    	{
    		echo 0;
//     		$res=$a->result();
//     		$this->output->set_content_type('application/json')->set_output(json_encode($res));
    	}
    		
    }
    
    function view()
    {
        $where=null;
        if (isset($_GET['admin_id']))
            $where['admin_id']=$_GET['admin_id'];
        if (isset($_GET['data']))
	        $select=$_GET['data'];
	    else $select="*";
	    
	    $return=$this->Mdl_admin_back->view_data($where,$select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    function delete()
    {
        if (isset($_GET['admin_id']) && $_GET['admin_id'])
        {
            $where['admin_id']=$_GET['admin_id'];
            $object=json_encode($this->Mdl_admin_back->view_data($where,"*")->result());
            $data_title= "Admin Deleted";
            $this->load->module("logs");
            if ($this->logs->add_data($data_title,$object))
            {
                echo $this->Mdl_admin_back->delete_data($where);
            }
        }
    }
    
   
}
?>

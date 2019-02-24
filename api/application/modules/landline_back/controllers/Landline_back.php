<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Landline_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();
        $this->load->model('mdl_landline_back');
        //privileges
        $this->module="landline_back";
        $this->load->module('user_privileges');
    }
   function index()
   {
       $this->load->module('login');
       if($this->login->auth())
       {
           //check user privileges
           if(!$this->user_privileges->check_module_privileges($this->module))
               echo 2;//privilege error
           else
                   echo 1;//success
           }else{
               echo 0;//logout
           }
   }
    function save()
    {
        //         print_r($_POST);die();
        $this->load->library('form_validation');
        $this->form_validation->set_rules("number" , "Number", "required|trim");
        $this->form_validation->set_rules("amount","Amount","required|trim");
        $this->form_validation->set_rules("operator", "Operator","required|trim");
        
        if($this->form_validation->run()==TRUE)
        {
            $data['number']=$this->input->post('number');
            $data['amount']=$this->input->post('amount');
            $data['operator']= $this->input->post('operator');
            $data['mem_typ']='ms';
            $data['mem_id']='1001';
            
            if ($this->input->post('lline_id')) //update
            {
                
                $function="update";
                $this->user_privileges->check_privilege($this->module,$function);
                
                $where['id'] = $this->input->post('lline_id');
                $where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
                
                if($this->input->post('status'))
                    $data['status']= $this->input->post('status');
                echo $this->Mdl_landline_back->update_data($where,$data);
            }
            else// add
            {
                $function="add";
                $this->user_privileges->check_privilege($this->module,$function);
                
                $data['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
                echo $this->mdl_landline_back->add_data($data);
            }
        }
        else {
            echo validation_errors();
        }
        
    }
    function view()
    {
        $where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
        
        if (isset($_GET['mb_id']))
            $where['mb_id']=$_GET['mb_id'];
        
        if (isset($_GET['data']))
	        $select=$_GET['data'];
	    else $select="*";
    
	    $return=$this->Mdl_postpaid_back->view_data($where,$select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    function delete()
    {
        if (isset($_GET['mb_id']) && $_GET['mb_id'])
        {
            $where['mb_id']=$_GET['mb_id'];
            $where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
            
            $object=json_encode($this->Mdl_postpaid_back->view_data($where,"*")->result());
            $data_title= "Member Deleted";

            $this->load->module("logs");
            if ($this->logs->add_data($data_title,$object)) {
                echo $this->Mdl_postpaid_back->delete_data($where);
            }
        }
    }
                    
                    
}
?>

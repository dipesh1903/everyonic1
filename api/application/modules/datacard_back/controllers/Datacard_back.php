<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Datacard_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();        
        $this->load->model('Mdl_datacard_back');
        //privileges
        $this->module="datacard_back";
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
        $this->form_validation->set_rules("d_no", "Datacard Number", "required|trim");
        $this->form_validation->set_rules("amount", "Amount", "required|trim");
        $this->form_validation->set_rules("operator", "Operator", "required|trim");

        if ($this->form_validation->run() == TRUE)
        {   
            $val['d_no'] = $this->input->post('d_no');
            $val['amount'] = $this->input->post('amount');
            $val['operator'] = $this->input->post('operator');
            $val['mem_typ'] = 'ms';
            $val['mem_id'] = 'ms101';
//             print_r($_POST);die();
            if ($this->input->post('dc_id')) // update
            {
                $function="update";
                $this->user_privileges->check_privilege($this->module,$function);
                
                $where['id'] = $this->input->post('dc_id');
                $where['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
                
                if($this->input->post('status'))
                    $val['status']= $this->input->post('status');
                echo $this->Mdl_datacard_back->update_data($where,$val);
                
            }
            else // add
            { 
                $function="add";
                $this->user_privileges->check_privilege($this->module,$function);
                
                $val['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
                echo $this->Mdl_datacard_back->add_data($val);
            }
        }
        else
        {
            echo validation_errors();
        }
    }
    function view()
    {
        $where=null;
        $where['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
        
        if (isset($_GET['dc_id']))
            $where['dc_id']=$_GET['dc_id'];
        
        if (isset($_GET['data']))
	        $select=$_GET['data'];
	    else $select="*";
	    
	    $return=$this->Mdl_datacard_back->view_data($where,$select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    function delete()
    {
        if (isset($_GET['dc_id']) && $_GET['dc_id'])
        {
            $where['dc_id']=$_GET['dc_id'];
            $where['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
            
            $object=json_encode($this->Mdl_datacard_back->view_data($where,"*")->result());
            $data_title= "Member Deleted";
            
            $this->load->module("logs");
            if ($this->logs->add_data($data_title,$object)) {
                echo $this->Mdl_datacard_back->delete_data($where);
            }
        }
    }
    
   
}
?>

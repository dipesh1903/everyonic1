<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Service_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mdl_service_back');
    }
   function index()
   {
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
        $this->form_validation->set_rules("name", "Service Name", "required|trim");
        
        if ($this->form_validation->run() == TRUE)
        {
            $val['service_name'] = $this->input->post('name');
            
            if ($this->input->post('id')) // update
            {
                $where['ser_id'] = $this->input->post('id');
                $where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
                
                echo $this->Mdl_service_back->update_data($where,$val);
                
            }
            else // add
            {
                $val['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
                if($this->input->post('status'))
                    $val['status']=$_POST['status'];
                echo $this->Mdl_service_back->add_data($val);
            }
        }
        else
        {
            echo validation_errors();
        }
    }
    function view()
    {
        $where["status"]=1;
//         $where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
        
        if (isset($_GET['id']))
            $where['ser_id']=$_GET['id'];
        
        if (isset($_GET['data']))
            $select=$_GET['data'];
        else $select="ser_id,service_name as sname,status";
        
        $return=$this->Mdl_service_back->view_data($where,$select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    
    function delete()
    {
        if (isset($_GET['ser_id']) && $_GET['ser_id'])
        {
            $where['ser_id']=$_GET['ser_id'];
//             $where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
            
            $object=json_encode($this->Mdl_service_back->view_data($where,"*")->result());
            $data_title= "Member Deleted";
            
            $this->load->module("logs");
            if ($this->logs->add_data($data_title,$object)) {
                echo $this->Mdl_service_back->delete_data($where);
            }
        }
    }
            
            
}
?>

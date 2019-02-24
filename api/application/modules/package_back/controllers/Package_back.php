<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Package_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mdl_package_back');
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
        $this->load->library('form_validation');
        $this->form_validation->set_rules("name", "Package Name", "required|trim|is_unique[packages_details.package_name]");
        $this->form_validation->set_rules("ser_id", "Service Name", "required|trim");
        
        if ($this->form_validation->run() == TRUE)
        {
            $val['package_name'] = $this->input->post('name');
            $val['ser_id'] = $this->input->post('ser_id');
            
            if ($this->input->post('id')) // update
            {
                $where['pac_id'] = $this->input->post('id');
                $where['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
                    
                if($this->input->post('status')==0)
                {
                    $val['status']=$_POST['status'];
                }
                if($this->input->post('status')==1)
                {
                    $val['status']=$_POST['status'];
                }
                echo $this->Mdl_package_back->update_data($where,$val);
                
            }
            else // add
            {
            	$val['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
            	echo $this->Mdl_package_back->add_data($val);
            }
        }
        else
        {
            echo validation_errors();
        }
    }
    function view($join=null)
    {
        $where['packages_details.com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
        if (isset($_GET['id']))
            $where['pac_id']=$_GET['id'];
        if (isset($_GET['data']))
            $select=$_GET['data'];
        if(isset($_GET['join']))
        {
            $select="pac_id as pid,package_name as pname,opcode,sercode,opname";
            $this->db->join('operator_details',"ser_id");
        }
           
        else $select="pac_id as pid,package_name as pname,ser_id,service_name as sname, packages_details.status as status,packages_details.timestamp as time";
            
        $return=$this->Mdl_package_back->view_data($where,$select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
        
    function delete()
    {
        if (isset($_GET['pac_id']) && $_GET['pac_id'])
        {
            $where['pac_id']=$_GET['pac_id'];
            $object=json_encode($this->Mdl_package_back->view_data($where,"*")->result());
            $data_title= "Package Deleted";
            
            $this->load->module("logs");
            if ($this->logs->add_data($data_title,$object)) {
                echo $this->Mdl_package_back->delete_data($where);
            }
        }
    }
}
?>

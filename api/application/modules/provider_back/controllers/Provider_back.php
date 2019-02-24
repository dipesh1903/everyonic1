<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Provider_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mdl_provider_back');
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
        $this->form_validation->set_rules("ser_id", "Service", "required|trim");
        $this->form_validation->set_rules("opcode", "Operator code", "required|trim|is_unique[operator_details.opcode]");
        $this->form_validation->set_rules("opname", "Operator Name", "required|trim");
        $this->form_validation->set_rules("sercode", "Service Code", "required|trim|is_unique[operator_details.sercode]");
        
        if ($this->form_validation->run() == TRUE)
        {
        	
            $val['ser_id'] = $this->input->post('ser_id');
            $val['opcode'] = $this->input->post('opcode');
            $val['opname'] = $this->input->post('opname');
            $val['sercode'] = $this->input->post('sercode');
            if ($this->input->post('pro_id')) // update
            {
                $where['pro_id'] = $this->input->post('pro_id');
                $where['com_id'] = $this->session->userdata['com_id'];
                echo $this->Mdl_provider_back->update_data($where,$val);
            }
            else // add
            {
                $val['com_id'] = $this->session->userdata['com_id'];
                echo $this->Mdl_provider_back->add_data($val);
            }
        }
        else
        {
            echo validation_errors();
        }
    }
    function view()
    {
        $where['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
        
        if (isset($_GET['pro_id']))
            $where['pro_id']=$_GET['pro_id'];
            
            if (isset($_GET['data']))
                $select=$_GET['data'];
                else $select="*";
                
                $return=$this->Mdl_provider_back->view_data($where,$select);
                $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    function delete()
    {
        if (isset($_GET['pro_id']) && $_GET['pro_id'])
        {
            $where['pro_id']=$_GET['pro_id'];
            $object=json_encode($this->Mdl_provider_back->view_data($where,"*")->result());
            $data_title= "Provider Deleted";
            
            $this->load->module("logs");
            if ($this->logs->add_data($data_title,$object)) {
                echo $this->Mdl_provider_back->delete_data($where);
            }
        }
    }
            
            
}
?>

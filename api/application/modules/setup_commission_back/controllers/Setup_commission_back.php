<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Setup_commission_back extends MX_Controller{
    function __construct(){
        parent::__construct();
		$this->load->model('Mdl_setup_commission_back');
    }
    function index(){
       $this->load->module('login');
       if($this->login->auth()){
          echo 1;
       }else{
           echo 0;
       }
    }
	function save(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules("master_commission", "Master Commission", "required|trim|numeric");
		$this->form_validation->set_rules("distributor_commission", "Distributor Commission", "required|trim|numeric");
		$this->form_validation->set_rules("retailer_commission", "Retailer Commission", "required|trim|numeric");
		if ($this->form_validation->run() == TRUE){
			
			$val['master_commission'] = $this->input->post('master_commission');
			$val['distributor_commission'] = $this->input->post('distributor_commission');
			$val['retailer_commission'] = $this->input->post('retailer_commission');
			## Update
			if($this->input->post('cm_id')){ 
				$val['edited'] = date("Y-m-d H:i:s");
				$where['cm_id'] = $this->input->post('cm_id');
				$where['com_id'] = $this->session->userdata('com_id'); //comment when testing with static data
				echo $this->Mdl_setup_commission_back->update_data($where, $val);
			}else{
				$val['created'] = date("Y-m-d H:i:s");
				$val['edited'] = date("Y-m-d H:i:s");
				$val['admin_id'] = $this->session->userdata('mem_id');
				$val['com_id'] = $this->session->userdata('com_id'); //comment when testing with static data
				echo $this->Mdl_setup_commission_back->add_data($val);
			}
		}else{
			echo validation_errors();
		}
	}
	## Get the data in the view
	function view(){
		$where['com_id'] = $this->session->userdata('com_id'); //uncomment when testing with session
		if(isset($_GET['mem_id']))
			$where['admin_id'] = $_GET['mem_id'];
		if(isset($_GET['data']))
			$select = $_GET['data'];
		else $select = "*";
		$return = $this->Mdl_setup_commission_back->view_data($where, $select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
}
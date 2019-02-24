<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Bank_details_back extends MX_Controller{
    function __construct(){
        parent::__construct();
		$this->load->model('Mdl_bank_details_back');
    }
    function index(){
       $this->load->module('login');
       if($this->login->auth()){
          echo 1;//success
       }else{
           echo 0;//logout
       }
    }
	function save(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules("bankname", "bankname", "required|trim");
		$this->form_validation->set_rules("account_number", "account_number", "required|trim");
		$this->form_validation->set_rules("ifsc_neft_code", "ifsc_neft_code", "required|trim");
		$this->form_validation->set_rules("account_holder_name", "account_holder_name", "required|trim");
		$this->form_validation->set_rules("confirm_account_number", "confirm_account_number", "required|trim");
		$this->form_validation->set_rules("bank_address", "bank_address", "required|trim");
		if ($this->form_validation->run() == TRUE){
			$val['bank_name'] = $this->input->post('bankname');
			$val['account_holder_name'] = $this->input->post('account_holder_name');
			$val['account_number'] = $this->input->post('account_number');
			$val['ifsc_code'] = $this->input->post('ifsc_neft_code');
			$val['bank_address'] = $this->input->post('bank_address');
			## Update
			if($this->input->post('bd_id')){ 
				$val['edited'] = date("Y-m-d H:i:s");
				$where['bd_id'] = $this->input->post('bd_id');
				$where['com_id'] = $this->session->userdata('com_id'); //comment when testing with static data
				echo $this->Mdl_bank_details_back->update_data($where, $val);
			}else{
				$val['created'] = date("Y-m-d H:i:s");
				$val['edited'] = date("Y-m-d H:i:s");
				$val['mem_id'] = $this->session->userdata('mem_id');
				$val['com_id'] = $this->session->userdata('com_id'); //comment when testing with static data
				echo $this->Mdl_bank_details_back->add_data($val);
			}
		}else{
			echo validation_errors();
		}
	}
	## Get the data in the view
	function view(){
		$where['com_id'] = $this->session->userdata('com_id'); //uncomment when testing with session
		if(isset($_GET['mem_id']))
			$where['mem_id'] = $_GET['mem_id'];
		if(isset($_GET['data']))
			$select = $_GET['data'];
		else $select = "*";
		$return = $this->Mdl_bank_details_back->view_data($where, $select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	
	function get_bank_details(){
		$where['com_id'] = $this->session->userdata('com_id'); 
		if(isset($_GET['id'])&& $_GET['id'])
			$where['bd_id'] = $_GET['id'];
		$res = $this->db->where($where)->select('*')->get('bank_details')->result();
		return $this->output->set_content_type('application/json')->set_output(json_encode($res));
	}
	
	function get_bank_details_all(){
		$res = $this->db->select('*')->get('bank_details')->result();
		return $this->output->set_content_type('application/json')->set_output(json_encode($res));
	}
}
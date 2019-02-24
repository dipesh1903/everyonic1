<?php
#https://www.ajio.com/performax-panelled-sports-shoes/p/450077545_blkgrn
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Profile_details_back extends MX_Controller{
    function __construct(){
        parent::__construct();
    }
    function index(){
       $this->load->module('login');
       if($this->login->auth()){
          echo 1;
       }else{
           echo 0;
       }
    }
	function view(){
		if(isset($_GET['usertype']) && $_GET['usertype'] == "Admin"){
			#$where['admin_details.com_id'] = $this->session->userdata('com_id'); 
			#if(isset($_GET['mem_id']) && $_GET['mem_id'])
			#	$where['admin_details.admin_id'] = $_GET['mem_id'];
			#$this->db->select('*')->from('admin_details')->join('userpass_details', 'userpass_details.mem_id = admin_details.admin_id')->where($where);
			
			
			$query = $this->db->query("SELECT * from `admin_details` JOIN `userpass_details` on userpass_details.mem_id = admin_details.admin_id where admin_details.admin_id = ".$_GET['mem_id']." ORDER BY admin_details.admin_id LIMIT 0, 1");
			
		}elseif(isset($_GET['usertype']) && $_GET['usertype'] == "Master"){
			#$where['master_details.com_id'] = $this->session->userdata('com_id'); 
			#if(isset($_GET['mem_id']) && $_GET['mem_id'])
			#	$where['master_details.ms_id'] = $_GET['mem_id'];
			#$this->db->select('*')->from('master_details')->join('userpass_details', 'userpass_details.mem_id = master_details.ms_id')->where($where);
			
			
			$query = $this->db->query("SELECT * from `master_details` JOIN `userpass_details` on userpass_details.mem_id = master_details.ms_id where master_details.ms_id = ".$_GET['mem_id']." ORDER BY master_details.ms_id LIMIT 0, 1");
			
		}elseif(isset($_GET['usertype']) && $_GET['usertype'] == "Distributor"){
			#$where['distributor_details.com_id'] = $this->session->userdata('com_id'); 
			#if(isset($_GET['mem_id']) && $_GET['mem_id'])
			#	$where['distributor_details.ds_id'] = $_GET['mem_id'];
			#$this->db->select('*')->from('distributor_details')->join('userpass_details', 'userpass_details.mem_id = distributor_details.ds_id')->where($where);
			$query = $this->db->query("SELECT * from `distributor_details` JOIN `userpass_details` on userpass_details.mem_id = distributor_details.ds_id where distributor_details.ds_id = ".$_GET['mem_id']." ORDER BY distributor_details.ds_id LIMIT 0, 1");
		}elseif(isset($_GET['usertype']) && $_GET['usertype'] == "Retailer"){
			#$where['retailer_details.com_id'] = $this->session->userdata('com_id'); 
			#if(isset($_GET['mem_id']) && $_GET['mem_id'])
			#	$where['retailer_details.rt_id'] = $_GET['mem_id'];
			#$this->db->select('*')->from('retailer_details')->join('userpass_details', 'userpass_details.mem_id = retailer_details.rt_id')->where($where);
			$query = $this->db->query("SELECT * from `retailer_details` JOIN `userpass_details` on userpass_details.mem_id = retailer_details.rt_id where retailer_details.rt_id = ".$_GET['mem_id']." ORDER BY retailer_details.rt_id LIMIT 0, 1");
		}
		#$query = $this->db->get();
		return $this->output->set_content_type('application/json')->set_output(json_encode($query->result_array()));
	}
	function update(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules("name", "Name", "required|trim");
		$this->form_validation->set_rules("mob", "Mobile No", "required|trim|max_length[12]");
		$this->form_validation->set_rules("gender", "Gender", "required|trim");
		$this->form_validation->set_rules("address", "Address", "required|trim");
		$this->form_validation->set_rules("city", "City", "required|trim");
		$this->form_validation->set_rules("pin", "Pin", "required|trim|min_length[6]|max_length[6]");
		$this->form_validation->set_rules("password", "Password", "required|trim|min_length[5]");
		if($this->form_validation->run() == TRUE){
			if($this->input->post('usertype') == "admin"){
				$val['company_name'] = $this->input->post('firmname');
				$val['phone'] = $this->input->post('pn_no');
			}else{
				$val['firmname'] = $this->input->post('firmname');
				$val['pin'] = $this->input->post('pin');
				$val['mob'] = $this->input->post('pn_no');
			}
			$val['name'] = $this->input->post('name');
			$val['email'] = $this->input->post('email');
			$val['gender'] = $this->input->post('gender');
			$val['address'] = $this->input->post('address');
			$val['state'] = $this->input->post('state');
			$val['city'] = $this->input->post('city');
			$usertype = $this->input->post('usertype');
			if(isset($usertype) && ($usertype == "admin")){
				$this->load->module('admin_back');
				$where['admin_id'] = $this->input->post('admin_id');
				$where['com_id'] = $this->session->userdata('com_id');
				$aff = $this->Mdl_admin_back->update_data($where, $val);
			}elseif(isset($usertype) && ($usertype == "ms")){
			    $val['adh_no'] = $this->input->post('adh_no');
				$this->load->module('master_management_back');
				$where['ms_id'] = $this->input->post('ms_id');
				$where['com_id'] = $this->session->userdata('com_id');
				$aff = $this->Mdl_master_management_back->update_data($where, $val);
			}elseif(isset($usertype) && $usertype == "ds"){
				$val['adh_no'] = $this->input->post('adh_no');
				$this->load->module('distributor_management_back');
				$where['ds_id'] = $this->input->post('ds_id');
				$where['com_id'] = $this->session->userdata('com_id');
				$aff = $this->Mdl_distributor_management_back->update_data($where, $val);
			}elseif(isset($usertype) && $usertype == "rt"){
				$val['psa_id'] = $this->input->post('psa_id');
				$val['psa_pass'] = $this->input->post('psa_pass');
				$val['adno'] = $this->input->post('adh_no');
				$this->load->module('retailer_management_back');
				$where['rt_id'] = $this->input->post('rt_id');
				$where['com_id'] = $this->session->userdata('com_id');
				$aff = $this->Mdl_retailer_management_back->update_data($where, $val);
			}
			# Also check if password is not empty, update the password also.
			$this->load->module('create_password_back');
			$where_p['mem_id'] = $this->input->get('mem_id');
			$where_p['com_id'] = $this->session->userdata('com_id');
			$value['password'] = $this->input->post('password');
			$aff_p = $this->Mdl_create_password_back->update_data($where_p, $value);
			echo ($aff == 1 || $aff_p == 1) ? 1 : 0;
		}else{
			echo validation_errors();
		}
	}
}
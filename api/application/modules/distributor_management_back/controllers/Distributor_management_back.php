<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Distributor_management_back extends MX_Controller
{
	//wGtRkO8VoEyUjS
	function __construct()
	{
		parent::__construct();
		$this->load->model('Mdl_distributor_management_back');
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
	function get_user()
	{
		$where['com_id']=$this->session->userdata('com_id');
		$where['usertype']="ds";
		if (isset($_GET['id'])&& $_GET['id'])
			$where['mem_id']=$_GET['id'];
			
		$res=$this->db->where($where)->select('username,password')->get('userpass_details')->result();
		$this->output->set_content_type('application/json')->set_output(json_encode($res));
	}
	function chk_ses()
	{
		print_r($_SESSION);die();
	}
	
	function save(){
        //echo "<pre>";print_r($_POST);die();
		$this->load->library('form_validation');
		if ($this->session->userdata['usertype']=='admin')
			$this->form_validation->set_rules("ms_id", "Master Name", "required|trim|max_length[15]");
		$this->form_validation->set_rules("name", "name", "required|trim");
		$this->form_validation->set_rules("mob", "Mobile No", "required|trim|max_length[12]|numeric|min_length[10]");
		$this->form_validation->set_rules("gender", "Gender", "required|trim");
		$this->form_validation->set_rules("address", "Address", "required|trim");
// 		$this->form_validation->set_rules("acc_no", "Account No", "required|trim|numeric");
// 		$this->form_validation->set_rules("acc_typ", "Account type", "required|trim");
		$this->form_validation->set_rules("city", "City", "required|trim");
		$this->form_validation->set_rules("pin", "Pin", "required|trim|numeric|max_length[6]|min_length[6]");
			
		if ($this->form_validation->run() == TRUE)
		{
// 			if($this->session->userdata['usertype']=='admin')
// 				$val['ms_id'] = $this->input->post('ms_id');
// 			else
			//$val['ms_id'] = $this->session->userdata['mem_id'];
			
			### Change the Master of distributor if needed
			if($this->session->userdata['usertype'] == 'admin'){
				if(!empty($this->input->post('ms_id'))){
					$val['ms_id'] = $this->input->post('ms_id');
				}
			}
			$val['firmname'] = $this->input->post('firmname');
			$val['name'] = $this->input->post('name');
			$val['email'] = $this->input->post('email');
			$val['mob'] = $this->input->post('mob');
			$val['gender'] = $this->input->post('gender');
			$val['address'] = $this->input->post('address');
// 			$val['acc_typ'] = $this->input->post('acc_typ');
			$val['state'] = $this->input->post('state');
			$val['city'] = $this->input->post('city');
			$val['pin'] = $this->input->post('pin');
// 			$val['acc_no'] = $this->input->post('acc_no');
			$val['pn_no'] = $this->input->post('pn_no');
			$val['adh_no'] = $this->input->post('adh_no');
			$val['timestamp'] = date('Y-m-d H:i:s');
			if ($this->input->post('ds_id')){
				$where['ds_id'] = $this->input->post('ds_id');
				$where['com_id'] = $this->session->userdata('com_id'); //comment when testing with some static data
				if($this->input->post('status'))
					$val['status'] = $this->input->post('status');
				echo $this->Mdl_distributor_management_back->update_data($where, $val);
				
				$this->load->module('create_password_back');
				$where_1['mem_id'] = $this->input->post('ds_id');
                $where_1['usertype'] = "ds";
                $val_1['password'] = $this->input->post('password');
				$val_1['timestamp'] = date('Y-m-d H:i:s');
                $this->Mdl_create_password_back->update_data($where_1, $val_1);
			}else{
				$val['com_id'] = $this->session->userdata('com_id'); //comment when testing with static data
				echo $this->Mdl_distributor_management_back->add_data($val);
			}
		}else{
			echo validation_errors();
		}
	}
	function view()
	{
		$where['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
		
		if (isset($_GET['ds_id']))
			$where['ds_id']=$_GET['ds_id'];
		
		if (isset($_GET['ms_id']))
		    $where['ms_id']=$_GET['ms_id'];
		
		if (isset($_GET['data']))
			$select=$_GET['data'];
		else $select="*";
					
		$return=$this->Mdl_distributor_management_back->view_data($where,$select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	function delete()
	{
		if (isset($_GET['ds_id']) && $_GET['ds_id'])
		{
			$where['ds_id']=$_GET['ds_id'];
			$where['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
			
			$object=json_encode($this->Mdl_distributor_management_back->view_data($where,"*")->result());
			$data_title= "Distributor Member Deleted";
			
			$this->load->module("logs");
			if ($this->logs->add_data($data_title,$object)) 
			{
				echo $this->Mdl_distributor_management_back->delete_data($where);
			}
		}
	}
	
	
}
?>

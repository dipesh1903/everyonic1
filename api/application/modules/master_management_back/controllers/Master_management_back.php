<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Master_management_back extends MX_Controller
{
	//wGtRkO8VoEyUjS
	function __construct()
	{
		parent::__construct();
		$this->load->model('Mdl_master_management_back');
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
	function get_user(){
		$where['com_id']=$this->session->userdata('com_id'); 
		$where['usertype']="ms";
		if (isset($_GET['id'])&& $_GET['id'])
			$where['mem_id']=$_GET['id'];
		$res=$this->db->where($where)->select('username,password')->get('userpass_details')->result();
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	
	function get_user_single(){
		$where['com_id'] = $this->session->userdata('com_id'); 
		$where['usertype'] = "ms";
		if (isset($_GET['id'])&& $_GET['id'])
			$where['mem_id'] = $_GET['id'];
		$res = $this->db->where($where)->select('username, password')->get('userpass_details')->result();
		$this->output->set_content_type('application/json')->set_output(json_encode($res));
	}
	
	function save(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules("name", "name", "required|trim");
		$this->form_validation->set_rules("mob", "Mobile No", "required|trim|max_length[12]");
		$this->form_validation->set_rules("gender", "Gender", "required|trim");
		$this->form_validation->set_rules("address", "Address", "required|trim");
// 		$this->form_validation->set_rules("acc_no", "Account No", "required|trim");
// 		$this->form_validation->set_rules("acc_typ", "Account type", "required|trim");
		$this->form_validation->set_rules("city", "City", "required|trim");
		$this->form_validation->set_rules("pin", "Pin", "required|trim|min_length[6]|max_length[6]");
		
		if ($this->form_validation->run() == TRUE){
			$val['firmname'] = $this->input->post('firmname');
			$val['name'] = $this->input->post('name');
			$val['email'] = $this->input->post('email');
			$val['mob'] = $this->input->post('mob');
			$val['gender'] = $this->input->post('gender');
			$val['address'] = $this->input->post('address');
			$val['state'] = $this->input->post('state');
			$val['city'] = $this->input->post('city');
			$val['pin'] = $this->input->post('pin');
			$val['adh_no'] = $this->input->post('adh_no');
			$val['pn_no'] = $this->input->post('pn_no');
			$val['timestamp'] = date('Y-m-d H:i:s');
			if($this->input->post('ms_id')){
				$where['ms_id'] = $this->input->post('ms_id');
				$where['com_id'] = $this->session->userdata('com_id'); //comment when testing with static data
				if($this->input->post('status'))
					$val['status']= $this->input->post('status');
					$this->Mdl_master_management_back->update_data($where, $val);
				### Check if there is password also
				### Update the password also
				$this->load->module('create_password_back');
				$where_1['mem_id'] = $this->input->post('ms_id');
                $where_1['usertype'] = "ms";
                $val_1['password'] = $this->input->post('password');
				$val_1['timestamp'] = date('Y-m-d H:i:s');
                echo $this->Mdl_create_password_back->update_data($where_1, $val_1);
			}else{
				$val['com_id'] = $this->session->userdata('com_id'); //comment when testing with static data
				echo $this->Mdl_master_management_back->add_data($val);
			}
		}else{
			echo validation_errors();
		}
	}
	
	function view(){
		$where['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
		
		if (isset($_GET['ms_id']))
			$where['ms_id']=$_GET['ms_id'];
			
		if (isset($_GET['data']))
			$select=$_GET['data'];
		else $select="*";
				
		$return=$this->Mdl_master_management_back->view_data($where,$select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	function delete()
	{
		if (isset($_GET['ms_id']) && $_GET['ms_id'])
		{
			$where['ms_id']=$_GET['ms_id'];
			$where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
			
			$object=json_encode($this->Mdl_master_management_back->view_data($where,"*")->result());
			$data_title= "Member Deleted";
			
			$this->load->module("logs");
			if ($this->logs->add_data($data_title,$object)) {
				echo $this->Mdl_master_management_back->delete_data($where);
			}
		}
	}
}
?>

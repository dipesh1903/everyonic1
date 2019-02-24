<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Retailer_management_back extends MX_Controller
{
	//wGtRkO8VoEyUjS
	function __construct()
	{
		parent::__construct();
		$this->load->model('Mdl_retailer_management_back');
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
		$where['usertype']="rt";
		if (isset($_GET['id'])&& $_GET['id'])
			$where['mem_id']=$_GET['id'];
			
		$res=$this->db->where($where)->select('username,password')->get('userpass_details')->result();
		$this->output->set_content_type('application/json')->set_output(json_encode($res));
	}
	function save(){
		$this->load->library('form_validation');
		if ($this->session->userdata['usertype']=='admin'||$this->session->userdata['usertype']=='administrator')
			$this->form_validation->set_rules("ds_id", "Distributor Name", "required|trim");
			$this->form_validation->set_rules("name", "Retailor Name", "required|trim");
			$this->form_validation->set_rules("mob", "Mobile No", "required|trim|max_length[10]|min_length[10]|numeric");
			$this->form_validation->set_rules("gender", "Gender", "required|trim");
			$this->form_validation->set_rules("address", "Address", "required|trim|max_length[200]");
			$this->form_validation->set_rules("adno", "Adhar No", "required|trim|min_length[8]");
			$this->form_validation->set_rules("pn_no", "Pan No", "required|trim|min_length[8]");
// 			$this->form_validation->set_rules("acc_no", "Account No", "required|trim|min_length[11]|numeric");
// 			$this->form_validation->set_rules("acc_typ", "Account type", "required|trim");
			$this->form_validation->set_rules("city", "City", "required|trim");
			$this->form_validation->set_rules("pin", "Pin", "required|trim|max_length[6]|min_length[6]|numeric");
			if ($this->input->post('rt_id')){
				$this->form_validation->set_rules("email", "Email", "trim");
			}else{
				$this->form_validation->set_rules("email", "Email", "required|trim|is_unique[retailer_details.email]");
			}
			
			if ($this->form_validation->run() == TRUE){
				if($this->session->userdata['usertype']=='admin' || $this->session->userdata['usertype']=='administrator')
					$val['ds_id'] = $this->input->post('ds_id');
				else
					$val['ds_id'] = $this->session->userdata['mem_id'];
					
					$val['firmname'] = $this->input->post('firmname');
					$val['name'] = $this->input->post('name');
					$val['email'] = $this->input->post('email');
					$val['mob'] = $this->input->post('mob');
					$val['gender'] = $this->input->post('gender');
					$val['address'] = $this->input->post('address');
					$val['state'] = $this->input->post('state');
					$val['city'] = $this->input->post('city');
					$val['pin'] = $this->input->post('pin');
					$val['city'] = $this->input->post('city');
					$val['pn_no'] = $this->input->post('pn_no');
					$val['adno'] = $this->input->post('adno');
					// 	$val['acc_no'] = $this->input->post('acc_no');
					// 	$val['acc_typ'] = $this->input->post('acc_typ');
					$val['timestamp'] = date('Y-m-d H:i:s');
					if($this->input->post('rt_id')){
					    $val['psa_id'] = $this->input->post('psa_id');
					    $val['psa_pass'] = $this->input->post('psa_pass');
						$where['rt_id'] = $this->input->post('rt_id');
						$where['com_id']= $this->session->userdata('com_id'); //comment when testing with static data
						if($this->input->post('status'))
							$val['status'] = $this->input->post('status');
							echo $this->Mdl_retailer_management_back->update_data($where, $val);
							
						$this->load->module('create_password_back');
						$where_1['mem_id'] = $this->input->post('rt_id');
						$where_1['usertype'] = "rt";
						$val_1['password'] = $this->input->post('password');
						$val_1['timestamp'] = date('Y-m-d H:i:s');
						$this->Mdl_create_password_back->update_data($where_1, $val_1);	
					}else{
						$val['com_id'] = $this->session->userdata('com_id'); //uncomment when testing with session
						echo $this->Mdl_retailer_management_back->add_data($val);
					}
			}else{
				echo validation_errors();
			}
	}
	function view()
	{
		$where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
		if (isset($_GET['rt_id']))
			$where['rt_id']=$_GET['rt_id'];
		if (isset($_GET['ds_id']))
			$where['ds_id']=$_GET['ds_id'];
				
		if (isset($_GET['data']))
			$select=$_GET['data'];
		else $select="*";
					
		$return=$this->Mdl_retailer_management_back->view_data($where,$select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	function delete()
	{
		if (isset($_GET['rt_id']) && $_GET['rt_id'])
		{
			$where['rt_id']=$_GET['rt_id'];
			$where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
			
			$object=json_encode($this->Mdl_retailer_management_back->view_data($where,"*")->result());
			$data_title= "Member Deleted";
			
			$this->load->module("logs");
			if ($this->logs->add_data($data_title,$object)) {
				echo $this->Mdl_retailer_management_back->delete_data($where);
			}
		}
	}
	
	
}
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Pan_back extends MX_Controller
{
	//wGtRkO8VoEyUjS
	function __construct()
	{
		parent::__construct();
		$this->load->model('Mdl_pan_back');
	}
	function index()
	{
		$this->load->module('login');
		if($this->login->auth())
		{
			echo 1;//success
		}
		else{
			echo 0;//logout
		}
	}
	function save()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules("uti_no", "UTIITSL User ID", "required|trim");
		$this->form_validation->set_rules("uti_name", "UTIITSL PSName", "required|trim");
		$this->form_validation->set_rules("coupan_rq", "Total Coupan", "required|trim");
		
		if ($this->form_validation->run() == TRUE)
		{
			$val['uti_no'] = $this->input->post('uti_no');
			$val['uti_name'] = $this->input->post('uti_name');
			$val['coupan_rq'] = $this->input->post('coupan_rq');
			
			if ($this->input->post('pan_id')) // update
			{
				$val['mem_id']=$this->input->post('mem_id');
				$val['mem_typ']="rt";
				$val['status']=$_POST['status'];
				$val['com_id']=$this->session->userdata('com_id');
				
				if($this->input->post('status')==1)
				{
					$where['mem_id']=$val['mem_id'];
					$where['mem_typ']=$val['mem_typ'];
					$where['com_id']=$val['com_id'];
					
					$this->db->where($where);
					$this->db->order_by('cus_wal_id','desc');
					$this->db->limit(1);
					$this->db->select('cus_wal_id,net_wal');
					$a=$this->db->get('wallet_customer');
					if($a->num_rows()>0)
					{
						$res=$a->result();
						$where['opcode']="ag001";  // static value
						$this->db->where($where);
						$this->db->select('charge,char_typ');
						$p=$this->db->get('package_assigner');
						if($a->num_rows()>0)
						{
							$op=$p->result();
							if($op[0]->char_typ=='r')
							{
								$add=$res[0]->net_wal+$op[0]->charge*$val['coupan_rq'];
								$data=array(
										"com_id"=>$val['com_id'],
										"mem_id"=>$val['mem_id'],
										"mem_typ"=>"rt",
										"wal_bal"=>$res[0]->net_wal,
										"credit"=>$op[0]->charge*$val['coupan_rq'],
										"net_wal"=>$add,
										"desc"=>"pan card-addition of coupan",
										"status"=>2
								);
								
								// incrementing wallet balance as per coupan req
								// after inserting the data in walletcus format
								$this->db->insert('wallet_customer',$data);
								
								//after inserting updating the pan card status with 1
								$where1['pan_id'] = $this->input->post('pan_id');
								$where1['com_id']=$val['com_id'];
								
								echo $this->Mdl_pan_back->update_data($where1,$val);
							}
							if($op[0]->char_typ=='p')
							{
								$padd=$res[0]->net_wal+$val['coupan_rq']*$op[0]->charge/100;
								$data=array(
										"com_id"=>$val['com_id'],
										"mem_id"=>$val['mem_id'],
										"mem_typ"=>"rt",
										"wal_bal"=>$res[0]->net_wal,
										"credit"=>$val['coupan_rq']*$op[0]->charge/100,
										"net_wal"=>$padd,
										"desc"=>"pan card-addition of coupan",
										"status"=>2
								);
								
								// incrementing wallet balance as per coupan req
								// after inserting the data in walletcus format
								$this->db->insert('wallet_customer',$data);
								
								//after inserting updating the pan card status with 1
								$where1['pan_id'] = $this->input->post('pan_id');
								$where1['com_id']=$val['com_id'];
								
								echo $this->Mdl_pan_back->update_data($where1,$val);
							}
						}
						else
						{
							$data=array(
									"error"=>1,
									"msg"=>"no data found"
							);
						}
					}
					else
					{
						$data=array(
								"error"=>1,
								"msg"=>"No Wallet Records Found!! Set Retailer Wallet"
						);
						echo $data;
					}
				}
				else
				{
					$where['pan_id'] = $this->input->post('pan_id');
					$where['com_id']=$val['com_id'];
					
					echo $this->Mdl_pan_back->update_data($where,$val);
				}
			}
			else // add
			{
				$val['mem_id']=$this->session->userdata('mem_id'); //comment:open when testing is done
				
				$val['status'] = '0';
				$val['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
				echo $this->Mdl_pan_back->add_data($val);
			}
		}
		else
		{
			echo validation_errors();
		}
	}
	
	function view()
	{
		$where['pan.com_id']=$this->session->userdata('com_id'); //comment when testing with static data
		
		if(isset($_GET['status']) && $_GET['status']==0)
			$where['pan.status']=0;
		if (isset($_GET['pan_id']))
			$where['pan_id']=$_GET['pan_id'];
		if (isset($_GET['mem_id']))
			$where['mem_id']=$_GET['mem_id'];
		if (isset($_GET['data']))
			$select=$_GET['data'];
		else $select="pan_id,uti_no,uti_name,coupan_rq as cp,mem_id,name,pan.status as status,date";
		
		$return=$this->Mdl_pan_back->view_data($where,$select);
		
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	
	function notify()
	{
		$where['pan.com_id']=$this->session->userdata('com_id'); //comment when testing with static data
		if(isset($_GET['status']) && $_GET['status']==0)
			$where['pan.status']=0;
		if (isset($_GET['pan_id']))
			$where['pan_id']=$_GET['pan_id'];
		if (isset($_GET['mem_id']))
			$where['mem_id']=$_GET['mem_id'];
		if (isset($_GET['data']))
			$select=$_GET['data'];
		else $select="pan.status as status,date";
		
		$return=$this->Mdl_pan_back->view_data($where,$select);
		$return=$return->num_rows();
		
		$this->output->set_output($return);
	}
	
	function delete()
	{
		if (isset($_GET['pan_id']) && $_GET['pan_id'])
		{
			$where['pan_id']=$_GET['pan_id'];
			$where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
			
			$object=json_encode($this->Mdl_pan_back->view_data($where,"*")->result());
			$data_title= "Member Deleted";
			
			$this->load->module("logs");
			if ($this->logs->add_data($data_title,$object)) {
				echo $this->Mdl_pan_back->delete_data($where);
			}
		}
	}
	
	
}
?>

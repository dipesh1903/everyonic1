<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Wallet_request_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
	function __construct()
	{
		parent::__construct();
		$this->load->model('Mdl_wallet_request_back');
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
	# New function added to accept decline the request
	function save_request(){
		if($this->input->post('wq_id')){
			$val['mem_id'] = $this->input->post('mem_id');
			$val['mem_typ'] = $this->input->post('mem_typ');
			$val['com_id'] = $this->input->post('com_id');
			$val['amt'] = $this->input->post('amt');
			if($this->input->post('status') == 1){
				$where['mem_id'] = $val['mem_id'];
				$where['mem_typ'] = $val['mem_typ'];
				$where['com_id'] = $val['com_id'];
				$this->db->where($where);
				$this->db->order_by('cus_wal_id', 'desc');
				$this->db->limit(1);
				$this->db->select('cus_wal_id, net_wal');
				$a = $this->db->get('wallet_customer');
				if($a->num_rows() > 0){
					$res = $a->result();
					$add = $res[0]->net_wal + $val['amt'];
					$data = array(
						"com_id"	=> $val['com_id'],
						"mem_id"	=> $val['mem_id'],
						"mem_typ"	=> $val['mem_typ'],
						"wal_bal"	=> $res[0]->net_wal,
						"credit"	=> $val['amt'],
						"net_wal"	=> $add,
						"desc"		=> "Wallet requested and credited to the member.",
						"status"	=> 3
					);
					#incrementing wallet balance as per coupan req
					# after inserting the data in walletcus format
					$x = $this->db->insert('wallet_customer', $data);
					# After inserting updating the pan card status with 1
					$where1['wq_id'] = $this->input->post('wq_id');
					$where1['com_id'] = $val['com_id'];
					if($this->input->post('status'))
						$val['status'] = $this->input->post('status');
					$x = $this->Mdl_wallet_request_back->update_data($where1, $val);
					if($x == 1){
						$data1 = array(
							"error"		=> 0,
							"msg"		=> "Wallet Requested Accepted!! Wallet Balance Added to Member.",
						);
						$this->output->set_content_type('application/json')->set_output(json_encode($data1));
					}
				}else{
					$data = array(
						"com_id"	=> $val['com_id'],
						"mem_id"	=> $val['mem_id'],
						"mem_typ"	=> $val['mem_typ'],
						"wal_bal"	=> $val['amt'],
						"credit"	=> $val['amt'],
						"net_wal"	=> $val['amt'],
						"desc"		=> "Wallet requested and credited to the member.",
						"status"	=> 3
					);
					$x = $this->db->insert('wallet_customer', $data);
					$where1['wq_id'] = $this->input->post('wq_id');
					$where1['com_id'] = $val['com_id'];
					if($this->input->post('status'))
						$val['status'] = $this->input->post('status');
					$x = $this->Mdl_wallet_request_back->update_data($where1, $val);
					if($x == 1){
						$data1 = array(
							"error"		=> 0,
							"msg"		=> "Wallet Requested Accepted!! Wallet Balance Added to Member.",
						);
						$this->output->set_content_type('application/json')->set_output(json_encode($data1));
					}else{
						$data1 = array(
							"error"		=> 1,
							"msg"		=> "Some error occurred.",
						);
						$this->output->set_content_type('application/json')->set_output(json_encode($data1));
					}
				}
			}else{
				$where1['wq_id'] = $this->input->post('wq_id');
				$where1['com_id'] = $val['com_id'];
				if($this->input->post('status'))
					$val['status'] = $this->input->post('status');
				$x = $this->Mdl_wallet_request_back->update_data($where1,$val);
				if($x == 1){
					$data1=array(
						"error"			=> 1,
						"msg"			=> "Wallet Balance is Declined! NO wallet is set.",
					);
					$this->output->set_content_type('application/json')->set_output(json_encode($data1));
				}
			}
		}else{
			echo validation_errors();
		}
	}
	function save()
	{
// 		print_r($_POST);die();
		$this->load->library('form_validation');
		$this->form_validation->set_rules("acc_no", "Account Number", "required|trim");
		$this->form_validation->set_rules("amt", "Amount", "required|trim");
		$this->form_validation->set_rules("trans_id", "Transaction ID", "required|trim");
// 		$this->form_validation->set_rules("p_mde", "Payment Mode", "required|trim");
// 		$this->form_validation->set_rules("p_dte", "Payment Date", "required|trim");
		if ($this->form_validation->run() == TRUE)
		{
			$val['acc_no'] = $this->input->post('acc_no');
			$val['amt'] = $this->input->post('amt');
			$val['acc_nam'] = $this->input->post('acc_nam');
			$val['trans_id'] = $this->input->post('trans_id');
			$val['p_mde'] = $this->input->post('p_mde');
			$val['p_dte'] = $this->input->post('p_dte');
			
			if ($this->input->post('wq_id')) // update
			{
				$val['mem_id']=$this->input->post('mem_id');
				$val['mem_typ']=$this->input->post('mem_typ');
				$val['com_id']=$this->input->post('com_id');
// 				$val['com_id']=$this->session->userdata('com_id');
				
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
						$add=$res[0]->net_wal+$val['amt'];
						$data=array(
								"com_id"=>$val['com_id'],
								"mem_id"=>$val['mem_id'],
								"mem_typ"=>$val['mem_typ'],
								"wal_bal"=>$res[0]->net_wal,
								"credit"=>$val['amt'],
								"net_wal"=>$add,
								"desc"=>"wallet requested and credited to member",
								"status"=>3
							);
						// incrementing wallet balance as per coupan req
						// after inserting the data in walletcus format
						$x=$this->db->insert('wallet_customer',$data);
						
						//after inserting updating the pan card status with 1
						$where1['wq_id'] = $this->input->post('wq_id');
						$where1['com_id']=$val['com_id'];
						
						if($this->input->post('status'))
							$val['status']= $this->input->post('status');
						$x=$this->Mdl_wallet_request_back->update_data($where1,$val);
						if ($x==1)
						{
							$data1=array(
									"error"=>0,
									"msg"=>"Wallet Requested Accepted!! Wallet Balance Added to Member"
							);
							$this->output->set_content_type('application/json')->set_output(json_encode($data1));
						}
					}
					else 
					{
						$data1=array(
								"error"=>1,
								"msg"=>"Assign Wallet to this Member!! Add Wallet"
						);
						$this->output->set_content_type('application/json')->set_output(json_encode($data1));
					}
				}
				else 
				{
					$where1['wq_id'] = $this->input->post('wq_id');
					$where1['com_id']=$val['com_id'];
					
					if($this->input->post('status'))
						$val['status']= $this->input->post('status');
					$x=$this->Mdl_wallet_request_back->update_data($where1,$val);
					if ($x==1)
					{
						$data1=array(
								"error"=>1,
								"msg"=>"Wallet Balance is Declined!!NO wallet is set"
						);
						$this->output->set_content_type('application/json')->set_output(json_encode($data1));
					}
				}
			}
			else // add
			{
				$val['status'] = '0';
				$val['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
				$val['mem_typ'] = $this->session->userdata['usertype'];
				$val['mem_id'] = $this->session->userdata['mem_id'];
				echo $this->Mdl_wallet_request_back->add_data($val);
			}
		}// if condition of validation true endhere
		else
		{
			echo validation_errors();
		}
	}
	
	function notify()
	{
// 		$where['wallet_request.com_id']=$this->session->userdata('com_id'); //comment when testing with static data
		if(isset($_GET['status']) && $_GET['status']==0)
			$where['wallet_request.status']=0;
		if (isset($_GET['wq_id']))
			$where['wq_id']=$_GET['wq_id'];
		if (isset($_GET['mem_id']))
			$where['mem_id']=$_GET['mem_id'];
		if (isset($_GET['data']))
			$select=$_GET['data'];
		else $select="wallet_request.status";
						
		$return=$this->Mdl_wallet_request_back->view_data($where,$select);
		$return=$return->num_rows();
		$this->output->set_output($return);
	}
	
	
	function view()
	{
// 		$where['wallet_request.com_id']=$this->session->userdata('com_id'); //comment when testing with static data
		if(isset($_GET['status']) && $_GET['status']==0)
			$where['wallet_request.status']=0;
		if (isset($_GET['wq_id']))
			$where['wq_id']=$_GET['wq_id'];
		if (isset($_GET['mem_id']))
			$where['mem_id']=$_GET['mem_id'];
		if (isset($_GET['data']))
			$select=$_GET['data'];
		else $select="*";
		
		$return=$this->Mdl_wallet_request_back->view_data($where,$select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	
//     function view()
//     {
//         $where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data

//         if (isset($_GET['dt_id']))
	//             $where['dt_id']=$_GET['dt_id'];

	//         if (isset($_GET['data']))
		// 	        $select=$_GET['data'];
		// 	    else $select="*";
	
		// 	    $return=$this->Mdl_dth_back->view_data($where,$select);
		//         $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
		//     }
//     function delete()
//     {
//         if (isset($_GET['dt_id']) && $_GET['dt_id'])
	//         {
	//             $where['dt_id']=$_GET['dt_id'];
	//             $where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data

	//             $object=json_encode($this->Mdl_dth_back->view_data($where,"*")->result());
	//             $data_title= "Member Deleted";

	//             $this->load->module("logs");
	//             if ($this->logs->add_data($data_title,$object)) {
	//                 echo $this->Mdl_dth_back->delete_data($where);
	//             }
	//         }
		//     }
			
	
}
?>

<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed');
class Pancard_coupon_back extends MX_Controller{
    function __construct(){
        parent::__construct();
		$this->load->model('Mdl_pancard_coupon_back');
    }
    function index(){
       $this->load->module('login');
       if($this->login->auth()){
          echo 1;//success
       }else{
           echo 0;//logout
       }
    }
	
	function update_request(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules("pr_id", "pr_id", "required|trim|numeric");
		$this->form_validation->set_rules("can_give", "can_give", "required|trim|numeric");		
		if($this->input->post('status') == 1){
			$this->load->module('wallet_back');
			### Make changes to the wallet of the member	
			$debit = (int)$this->input->post('can_give') * 107;
			$query = $this->db->query("SELECT net_wal, mem_id, mem_typ from `wallet_customer` where `mem_id` = ".$this->input->post('mem_id')." ORDER BY `cus_wal_id` DESC LIMIT 0, 1");
			$n = $query->result_array();
			if(!empty($n)){
				$net_wal = (int)$n[0]['net_wal'];
				$mem_typ = $n[0]['mem_typ'];
				$mem_id = (int)$n[0]['mem_id'];
				$val_1['com_id'] = $this->session->userdata('com_id');
				$val_1['mem_id'] = $mem_id;
				$val_1['mem_typ'] = $mem_typ;
				$val_1['wal_bal'] = (int)$net_wal;
				$val_1['credit'] = 0;
				$val_1['debit'] = (int)$debit;
				$total_bal = (int)$net_wal - (int)$debit;
				$val_1['net_wal'] = $total_bal;
				$val_1['desc'] = "Commision credited from Retailer ID ".$this->input->post('mem_id');
				$val_1['status'] = 3;
				$val_1['timestamp'] = date("Y-m-d H:i:s");
				$this->Mdl_wallet_back->add_data($val_1);
				### Now get the commision details
				## Firstly set the commision for Retailer
				$commission = $this->db->query("SELECT * from `commission_details` where 1 ORDER BY cm_id DESC LIMIT 0, 1");
				$c = $commission->result_array()[0];
				/* Calculate the commission for the retailer first */
				$retailer_c = (int)$this->input->post('can_give') * (int)$c['retailer_commission'];
				$val_1['wal_bal'] = (int)$total_bal;
				$val_1['credit'] = $retailer_c;
				$val_1['debit'] = 0;
				$total_net_wal = (int)$total_bal + (int)$retailer_c;
				$val_1['net_wal'] = $total_net_wal;
				/* Below will insert the data for retailer commission */
				$this->Mdl_wallet_back->add_data($val_1);
				/* Get the Distributor for this retailer */
				$ret_dis = $this->db->query("SELECT ds_id from `retailer_details` where rt_id = ".$this->input->post('mem_id')." LIMIT 0, 1");
				$ds_id = $ret_dis->result_array()[0]['ds_id'];
				/* Get Master of Distributor detail */
				$dis_mas = $this->db->query("SELECT ms_id, com_id from `distributor_details` where ds_id = ".$ds_id." LIMIT 0, 1");
				$ms_id = $dis_mas->result_array()[0]['ms_id'];
				$dis_com_id = $dis_mas->result_array()[0]['com_id'];
				## Insert commission for distributor
				$wallet_ds = $this->db->query("SELECT * from `wallet_customer` where `mem_id` = ".$ds_id." ORDER BY `cus_wal_id` DESC LIMIT 0, 1");
				/* Add the wallet details for distributor */
				$distributor_c = (int)$this->input->post('can_give') * (int)$c['distributor_commission'];
				if(!empty($wallet_ds->result_array())){
					$wds = $wallet_ds->result_array()[0];
					/* If there is record in wallet_customer then add a new one with new details */
					$val_ds['com_id'] = $dis_com_id;
					$val_ds['mem_id'] = $ds_id;
					$val_ds['mem_typ'] = "ds";
					$val_ds['wal_bal'] = $wds['net_wal'];
					$val_ds['credit'] = $distributor_c;
					$val_ds['debit'] = 0;
					$val_ds['net_wal'] = $wds['net_wal'] + $distributor_c;
					$val_ds['desc'] = "Commision credited from Retailer ID ".$this->input->post('mem_id');
					$val_ds['status'] = 3;
					$val_ds['timestamp'] = date("Y-m-d H:i:s");
					$this->Mdl_wallet_back->add_data($val_ds);
				}else{
					/* If there is no record in wallet_customer then add one */
					$val_ds['com_id'] = $dis_com_id;
					$val_ds['mem_id'] = $ds_id;
					$val_ds['mem_typ'] = "ds";
					$val_ds['wal_bal'] = 0;
					$val_ds['credit'] = $distributor_c;
					$val_ds['debit'] = 0;
					$val_ds['net_wal'] = $distributor_c;
					$val_ds['desc'] = "Commision credited from Retailer ID ".$this->input->post('mem_id');
					$val_ds['status'] = 3;
					$val_ds['timestamp'] = date("Y-m-d H:i:s");
					$this->Mdl_wallet_back->add_data($val_ds);
				}
				/* Now lets add the commission details for Master */
				$mas_det = $this->db->query("SELECT ms_id, com_id from `master_details` where ms_id = ".$ms_id." LIMIT 0, 1");
				$mas_com_id = $mas_det->result_array()[0]['com_id'];
				$wallet_ms = $this->db->query("SELECT * from `wallet_customer` where `mem_id` = ".$ms_id." ORDER BY `cus_wal_id` DESC LIMIT 0, 1");
				$master_c = (int)$this->input->post('can_give') * (int)$c['master_commission'];
				if(!empty($wallet_ds->result_array())){
					$wms = $wallet_ds->result_array()[0];
					/* If there is record in wallet_customer then add a new one with new details */
					$val_ms['com_id'] = $mas_com_id;
					$val_ms['mem_id'] = $ms_id;
					$val_ms['mem_typ'] = "ms";
					$val_ms['wal_bal'] = $wms['net_wal'];
					$val_ms['credit'] = $master_c;
					$val_ms['debit'] = 0;
					$val_ms['net_wal'] = $wms['net_wal'] + $master_c;
					$val_ms['desc'] = "Commision credited from Distributor ID ".$ds_id;
					$val_ms['status'] = 3;
					$val_ms['timestamp'] = date("Y-m-d H:i:s");
					$this->Mdl_wallet_back->add_data($val_ds);
				}else{
					/* If there is no record in wallet_customer then add one */
					$val_ms['com_id'] = $mas_com_id;
					$val_ms['mem_id'] = $ms_id;
					$val_ms['mem_typ'] = "ms";
					$val_ms['wal_bal'] = 0;
					$val_ms['credit'] = $master_c;
					$val_ms['debit'] = 0;
					$val_ms['net_wal'] = $distributor_c;
					$val_ms['desc'] = "Commision credited from Distributor ID ".$ds_id;
					$val_ms['status'] = 3;
					$val_ms['timestamp'] = date("Y-m-d H:i:s");
					$this->Mdl_wallet_back->add_data($val_ms);
				}
			}else{
				echo 0;
				die();
			}
		}
		if($this->input->post('pr_id') && $this->input->post('can_give')){ 
			if($this->input->post('status') == 1){
				$val['no_of_coupons_added'] = $this->input->post('can_give');
			}else{
				$val['no_of_coupons_added'] = 0;
			}
			$val['edited'] = date("Y-m-d H:i:s");
			$val['coupon_request_status'] = $this->input->post('status');
			$where['pr_id'] = $this->input->post('pr_id');
			$where['com_id'] = $this->session->userdata('com_id'); //comment when testing with static data
			echo $this->Mdl_pancard_coupon_back->update_data($where, $val);
		}else{
			echo 0;
		}
	}
	
	
	## Get the data in the view
	function view(){
		$where['com_id'] = $this->session->userdata('com_id'); //uncomment when testing with session
		if(isset($_GET['mem_id']))
			$where['mem_id'] = $_GET['mem_id'];
		$select = "*";
		$return = $this->Mdl_pancard_coupon_back->view_data($where, $select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	/**/
	function view_request(){
		$query = $this->db->query("SELECT * FROM `pancard_coupon_request` order by created DESC");
		$r = array();
		if(!empty($query->result_array())){
			foreach($query->result_array() as $each){
				$wallet_bal = $this->db->query("SELECT net_wal, mem_id from `wallet_customer` where `mem_id` = ".$each['mem_id']." ORDER BY `cus_wal_id` DESC LIMIT 0, 1");
				if(!empty($wallet_bal->result_array()[0])){
					$wbr = $wallet_bal->result_array()[0];
					$noc = (int)$each['no_of_coupons_requested'];
					if(empty($wbr['net_wal'])){
						$each['net_wal'] = 0;
					}else{
						$each['net_wal'] = $wbr['net_wal'];
					}
					$tot_requested = $noc * 107;
					if($each['net_wal'] > $tot_requested){
						$can_give = $noc;
					}else{
						$can_give = floor($each['net_wal']/107);
					}
					$each['can_give'] = $can_give;
				}else{
					$each['net_wal'] = 0;
					$each['can_give'] = 0;
				}
				$r[] = $each;
			}
			return $this->output->set_content_type('application/json')->set_output(json_encode($r));
		}else{
			echo 1;
		}		
	}
	function pancard_coupon_request(){
		$where['coupon_request_status'] = 0; //uncomment when testing with session
		$select = "pr_id";
		$return = $this->Mdl_pancard_coupon_back->view_data($where, $select);
		$this->load->module('master_management_back');
		$this->output->set_content_type('application/json')->set_output(json_encode(count($return->result_array())));
	}
	## Get the data in the view
	function view_single(){
		$where['com_id'] = $this->session->userdata('com_id'); //uncomment when testing with session
		if(isset($_GET['rt_id']))
			$where['rt_id'] = $_GET['rt_id'];
		$select = "*";
		$return = $this->Mdl_pancard_coupon_back->view_data($where, $select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	function save(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules("retailer_name", "retailer_name", "required|trim");
		$this->form_validation->set_rules("psa_id", "psa_id", "required|trim");
		$this->form_validation->set_rules("no_of_coupons_requested", "no_of_coupons_requested", "required|trim|numeric");
		if($this->form_validation->run() == TRUE){
			$val['retailer_name'] = $this->input->post('retailer_name');
			$val['psa_id'] = $this->input->post('psa_id');
			$val['no_of_coupons_requested'] = $this->input->post('no_of_coupons_requested');
			## Update
			if($this->input->post('pr_id')){ 
				$val['edited'] = date("Y-m-d H:i:s");
				$where['pr_id'] = $this->input->post('pr_id');
				$where['com_id'] = $this->session->userdata('com_id'); //comment when testing with static data
				echo $this->Mdl_pancard_coupon_back->update_data($where, $val);
			}else{
				$val['mem_typ'] = $this->input->get('mem_typ');
				$val['created'] = date("Y-m-d H:i:s");
				$val['edited'] = date("Y-m-d H:i:s");
				$val['mem_id'] = $this->input->get('mem_id');
				$val['com_id'] = $this->session->userdata('com_id'); //comment when testing with static data
				echo $this->Mdl_pancard_coupon_back->add_data($val);
			}
		}else{
			echo validation_errors();
		}
	}
}
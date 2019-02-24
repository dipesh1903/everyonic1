<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dth_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();        
        $this->load->model('Mdl_dth_back');
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
    function save()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules("custno", "DTH Number", "required|trim|max_length[12]");
        $this->form_validation->set_rules("amount", "Amount", "required|trim");
        $this->form_validation->set_rules("sercode", "Operator", "required|trim");
        $this->form_validation->set_rules("stv", "Recharge Type", "required|trim");

        if ($this->form_validation->run() == TRUE)
        {
        	$where['com_id']=$this->session->userdata['com_id'];
        	$where['mem_typ']=$this->session->userdata['usertype'];
        	$where['mem_id']=$this->session->userdata['mem_id'];
        	//fetching walllet balance for checking wallet lock
        	$this->db->where($where);
        	$this->db->order_by('cus_wal_id','desc');
        	$this->db->limit(1);
        	$this->db->select('net_wal');
        	$wal=$this->db->get('wallet_customer')->result();
        	//fetching walllet lock balance for checking wallet
        	$this->db->where($where);
        	$this->db->order_by('wloc_id','desc');
        	$this->db->limit(1);
        	$this->db->select('loc_bal');
        	$wallock=$this->db->get('wallet_lock')->result();
        	
        	if($wal[0]->net_wal>$wallock[0]->loc_bal)
        	{
        		$data['cust_no']= $this->input->post('custno');
        		$data['sercode']=$this->input->post('sercode');
        		$data['amt']= $this->input->post('amount');
        		$data['stv']=$this->input->post('stv');
        		//unique refeerence no to send api
        		$refno=$data['sercode'].mt_rand(100,999).date("his");
        		
        		$val = array(
        				'member_id' => '7478713655',
						'api_password' => '123456',
						'api_pin' => '123456',
        				//'MobileNo' => '9832999339',
        				//'APIKey' =>'3MegCUMI9CfNFOrMEp6rPQydHmnaeEHXLv8',
        				"request_id" => $refno,//'com11',
        				'opcode'=>$data['sercode'],
        				'number'=>$data['cust_no'],
        				'amount'=>$data['amt'],
        		);
        		$url="http://everyonicweb.com/recharge_api/recharge?";
        		$ch=curl_init();
        		curl_setopt_array($ch, array(
        				CURLOPT_URL => $url,
        				CURLOPT_RETURNTRANSFER=> TRUE,
        				CURLOPT_POST =>TRUE,
        				CURLOPT_POSTFIELDS =>$val
        		));
        		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
        		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
        		$output=curl_exec($ch);
        		if(curl_errno($ch))
        		{
        			$error= 'error'.$ch ;
        		}
        		curl_close($ch);
        		
        		$output_array=json_decode($output,TRUE);
//         		print_r($output_array);die();
        		
        		$data['mem_id']=$this->session->userdata['mem_id'];  
        		$data['mem_typ']=$this->session->userdata['usertype']; 
        		$data['com_id']=$this->session->userdata['com_id'];
        		$data['trans_status']=$output_array['STATUS'];
        		$data['trans_desc']=$output_array['MESSAGE'];
				$data['trans_id']=$output_array['OPTXNID'];
				if($data['trans_status'] == 'SUCCESS') {
				$data['trans_scode'] = 1;
				} else {
					$data['trans_scode'] = 0;
				}
        		$data['refno']=$output_array['MEMBERREQID'];
        		$data['status']=2;
        		
        		//$output_array['TRNSTATUS']=0;       // testing purpose
        		//$output_array['STATUSCODE']=0;      // testing purpose
        		$a=$this->db->insert('mob_dth_recharge',$data);
        		if ($a==1)
        		{
        			if($output_array['STATUS']=='SUCCESS'&&($output_array['STATUS']=='FAILED'||$output_array['STATUS']=='SUCCESS'))
        			{
// getting wallet of retailer,fetching commission and calculating the commission of retailer
        				$where['com_id']=$this->session->userdata['com_id'];
        				$where['mem_typ']=$data['mem_typ'];
        				$where['mem_id']=$data['mem_id'];
        				
        				$this->db->where($where);
        				$this->db->order_by('cus_wal_id','desc');
        				$this->db->limit(1);
        				$this->db->select('cus_wal_id,net_wal');
        				$ret_wal=$this->db->get('wallet_customer');
        				
        				if($ret_wal->num_rows()>0)
        				{
        					$ret_wal1=$ret_wal->result();
        					
        					// getting package commission
        					$where['sercode']=$data['sercode'];
        					$this->db->where($where);
        					$this->db->select('commission,com_typ');
        					$com_ret=$this->db->get('package_assigner')->result();
//         					print_r($com_ret);die();
        					if($com_ret[0]->com_typ=='r')
        					{
        						$add=$ret_wal1[0]->net_wal-$data['amt']+$com_ret[0]->commission;
        						$dataret=array(
        								"com_id"=>$this->session->userdata['com_id'],
        								"mem_id"=>$data['mem_id'],
        								"mem_typ"=>"rt",
        								"wal_bal"=>$ret_wal1[0]->net_wal,
        								"credit"=>$com_ret[0]->commission,
        								"net_wal"=>$add,
        								"debit"=>0,
        								"desc"=>"commission of DTH recharge",
        								"status"=>4
        						);
        						
        						// incrementing wallet balance as per commission
        						// after inserting the data in walletcus format
        						$x=$this->db->insert('wallet_customer',$dataret);
        					}
        					if($com_ret[0]->com_typ=='p')
        					{
        						$padd=$ret_wal1[0]->net_wal-$data['amt']+$data['amt']*$com_ret[0]->commission/100;
        						$dataret=array(
        								"com_id"=>$this->session->userdata['com_id'],
        								"mem_id"=>$data['mem_id'],
        								"mem_typ"=>"rt",
        								"wal_bal"=>$ret_wal1[0]->net_wal,
        								"credit"=>$data['amt']*$com_ret[0]->commission/100,
        								"net_wal"=>$padd,
        								"debit"=>0,
        								"desc"=>"commission of DTH recharge",
        								"status"=>4
        						);
        						// incrementing wallet balance as per commission
        						// after inserting the data in walletcus format
        						$x=$this->db->insert('wallet_customer',$dataret);
        					}
        				}
        				
// getting wallet of distributor,fetching commission and calculating the commission of distri
        				$where1['rt_id']=$data['mem_id'];
        				$where1['com_id']=$this->session->userdata['com_id'];
        				$where1['mem_typ']=$data['mem_typ'];
        				// getting distributor id of that retailer
        				$ds_id=$this->db->where($where1)->select('ds_id')->get('retailer_details')->result();
        				
        				//getting wallet of distributor
        				$where2['mem_id']=$ds_id[0]->ds_id;
        				$where2['com_id']=$this->session->userdata['com_id'];
        				$where2['mem_typ']="ds";
        				
        				$this->db->where($where2);
        				$this->db->order_by('cus_wal_id','desc');
        				$this->db->limit(1);
        				$this->db->select('cus_wal_id,net_wal');
        				$dis_wal=$this->db->get('wallet_customer');
        				
        				if($ret_wal->num_rows()>0)
        				{
        					$dis_wal1=$dis_wal->result();
        					
        					// getting package commission
        					$where2['sercode']=$data['sercode'];
        					$this->db->where($where2);
        					$this->db->select('commission,com_typ');
        					$com_dis=$this->db->get('package_assigner')->result();
        					
        					if($com_dis[0]->com_typ=='r')
        					{
        						$add=$dis_wal1[0]->net_wal+$com_dis[0]->commission;
        						$datadis=array(
        								"com_id"=>$this->session->userdata['com_id'],
        								"mem_id"=>$ds_id[0]->ds_id,
        								"mem_typ"=>"ds",
        								"wal_bal"=>$dis_wal1[0]->net_wal,
        								"credit"=>$com_dis[0]->commission,
        								"net_wal"=>$add,
        								"debit"=>0,
        								"desc"=>"commission of DTH recharge",
        								"status"=>4
        						);
        						
        						// incrementing wallet balance as per commission
        						// after inserting the data in walletcus format
        						$x=$this->db->insert('wallet_customer',$datadis);
        					}
        					if($com_dis[0]->com_typ=='p')
        					{
        						$padd=$dis_wal1[0]->net_wal+$data['amt']*$com_dis[0]->commission/100;
        						$datadis=array(
        								"com_id"=>$this->session->userdata['com_id'],
        								"mem_id"=>$ds_id[0]->ds_id,
        								"mem_typ"=>"ds",
        								"wal_bal"=>$dis_wal1[0]->net_wal,
        								"credit"=>$data['amt']*$com_dis[0]->commission/100,
        								"net_wal"=>$padd,
        								"debit"=>0,
        								"desc"=>"commission of DTH recharge",
        								"status"=>4
        						);
        						// incrementing wallet balance as per commission
        						// after inserting the data in walletcus format
        						$x=$this->db->insert('wallet_customer',$datadis);
        					}
        				}
// getting wallet of master,fetching commission and calculating the commission of master
        				// getting master form above distributor id
        				$where3['ds_id']=$ds_id[0]->ds_id;
        				$where3['com_id']=$this->session->userdata['com_id'];
        				$where3['mem_typ']="ds";
        				$ms_id=$this->db->where($where3)->select('ms_id')->get('distributor_details')->result();
        				// getting wallet of master
        				$where4['mem_id']=$ms_id[0]->ms_id;
        				$where4['com_id']=$this->session->userdata['com_id'];
        				$where4['mem_typ']="ms";
        				
        				$this->db->where($where4);
        				$this->db->order_by('cus_wal_id','desc');
        				$this->db->limit(1);
        				$this->db->select('cus_wal_id,net_wal');
        				$mas_wal=$this->db->get('wallet_customer');
        				
        				if($ret_wal->num_rows()>0)
        				{
        					$mas_wal1=$mas_wal->result();
        					
        					// getting package commission
        					$where4['sercode']=$data['sercode'];
        					$this->db->where($where4);
        					$this->db->select('commission,com_typ');
        					$com_mas=$this->db->get('package_assigner')->result();
        					
        					if($com_mas[0]->com_typ=='r')
        					{
        						$add=$mas_wal1[0]->net_wal+$com_mas[0]->commission;
        						$datamas=array(
        								"com_id"=>$this->session->userdata['com_id'],
        								"mem_id"=>$ms_id[0]->ms_id,
        								"mem_typ"=>"ms",
        								"wal_bal"=>$mas_wal1[0]->net_wal,
        								"credit"=>$com_mas[0]->commission,
        								"net_wal"=>$add,
        								"debit"=>0,
        								"desc"=>"commission of DTH recharge",
        								"status"=>4
        						);
        						
        						// incrementing wallet balance as per commission
        						// after inserting the data in walletcus format
        						$x=$this->db->insert('wallet_customer',$datamas);
        					}
        					if($com_mas[0]->com_typ=='p')
        					{
        						$padd=$mas_wal1[0]->net_wal+$data['amt']*$com_mas[0]->commission/100;
        						$datamas=array(
        								"com_id"=>$this->session->userdata['com_id'],
        								"mem_id"=>$ms_id[0]->ms_id,
        								"mem_typ"=>"ms",
        								"wal_bal"=>$mas_wal1[0]->net_wal,
        								"credit"=>$data['amt']*$com_mas[0]->commission/100,
        								"debit"=>0,
        								"net_wal"=>$padd,
        								"desc"=>"commission of DTH recharge",
        								"status"=>4
        						);
        						// incrementing wallet balance as per commission
        						// after inserting the data in walletcus format
        						$x=$this->db->insert('wallet_customer',$datamas);
        					}
        				}
//getting wallet of master,fetching commission and calculating the commission of master EndHere
        				if ($x==1)
        				{
        					$data=array(
        							"error"=>0,
        							'msg'=>$output_array['MESSAGE']
        					);
        					header("Content-Type: application/json");
        					echo json_encode($data);
        				}
        			} // if condition of $output_array end here
        			else
        			{
        				$data=array(
        						"error"=>1,
        						'msg'=>$output_array['MESSAGE']
        				);
        				header("Content-Type: application/json");
        				echo json_encode($data);
        			}// else condition of output array end here
        		} // if condition of mob-dth recharge insertin end here
        	}
        	else
        	{
        		$data=array(
        				"error"=>1,
        				'msg'=>"You don't Have Sufficient Balance To Recharge!! Kindly Check Your Balance"
        		);
        		header("Content-Type: application/json");
        		echo json_encode($data);
        	}
        } // if condition end of validation true
        else
        {
        	echo validation_errors();
        }
    }
   
}
?>

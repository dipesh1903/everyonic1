<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dth_report_back extends MX_Controller
{
    //wGtRkO8VoEyUjS 
    function __construct()
    {
        parent::__construct();
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
    function admin_dth_view()
    {
    	$th['mob_dth_recharge.com_id']=$_SESSION['com_id'];
    	$th['mob_dth_recharge.status']=2;
    	
    	$this->db->where($th);
    	$this->db->order_by("mob_dth_recharge.timestamp","desc");
    	$this->db->join("retailer_details","retailer_details.rt_id=mob_dth_recharge.mem_id");
    	$this->db->select('mem_id,mob_dth_recharge.mem_typ,cust_no,sercode,amt,trans_id,trans_scode,retailer_details.name as name');
    	$total_view=$this->db->get('mob_dth_recharge')->result();
    	$this->output->set_content_type('application/json')->set_output(json_encode($total_view));
    }
    
    // Masters
    function mas_dth_view() //calculating mobile view records of masters
    {
    	$mwhere['distributor_details.com_id']=$_SESSION['com_id'];
    	$mwhere['ms_id']=$_SESSION['mem_id'];
    	
    	$d=$this->db->where($mwhere)->select('ds_id')->get('distributor_details');
    	$array = array();
    	$array1 = array();
    	if ($d->num_rows()>0)
    	{ 
    		$dis=$d->result();
    		foreach ($dis as $r)
    		{
    			$where['retailer_details.com_id']=$_SESSION['com_id'];
    			$where['ds_id']=$r->ds_id;
    			$ret=$this->db->where($where)->select('rt_id')->get('retailer_details')->result();
    			foreach($ret as $row)
    			{
    				$where1['mob_dth_recharge.com_id']=$_SESSION['com_id'];
    				$where1['mem_id']=$row->rt_id;
    				$where1['mob_dth_recharge.mem_typ']="rt";
    				$where1['mob_dth_recharge.status']=2;
					
    				$this->db->where($where1);
    				$this->db->order_by("mob_dth_recharge.timestamp","desc");
    				$this->db->join("retailer_details","retailer_details.rt_id=mob_dth_recharge.mem_id");
    				$this->db->select('mem_id,mob_dth_recharge.mem_typ,cust_no,sercode,amt,trans_id,trans_scode,retailer_details.name as name');
    				$array=$this->db->get('mob_dth_recharge')->result();
    				$array1=array_merge($array1, $array);
    			}
    		}
    	}
    	$this->output->set_content_type('application/json')->set_output(json_encode($array1));
    }
    //Distributor
    function dis_dth_view() //calculating mobile view records of masters
    {
    	$mwhere['retailer_details.com_id']=$_SESSION['com_id'];
    	$mwhere['ds_id']=$_SESSION['mem_id'];
    	
    	$d=$this->db->where($mwhere)->select('rt_id')->get('retailer_details');
    	$array = array();
    	$array1 = array();
    	if ($d->num_rows()>0)
    	{
    		$dis=$d->result();
    		foreach ($dis as $row)
    		{
    			$where1['mob_dth_recharge.com_id']=$_SESSION['com_id'];
    			$where1['mem_id']=$row->rt_id;
    			$where1['mob_dth_recharge.mem_typ']="rt";
    				
    			$where1['mob_dth_recharge.status']=2;
    			
    			$this->db->where($where1);
    			$this->db->order_by("mob_dth_recharge.timestamp","desc");
    			$this->db->join("retailer_details","retailer_details.rt_id=mob_dth_recharge.mem_id");
    			$this->db->select('mem_id,mob_dth_recharge.mem_typ,cust_no,sercode,amt,trans_id,trans_scode,retailer_details.name as name');
    			$array=$this->db->get('mob_dth_recharge')->result();
    			$array1=array_merge($array1, $array);
    		}
    	}
    	$this->output->set_content_type('application/json')->set_output(json_encode($array1));
    }
    
    // Retailer
    function ret_dth_view() //calculating mobile view records of retailer
    {
     	$where1['mob_dth_recharge.com_id']=$_SESSION['com_id'];
    	$where1['mem_id']=$_SESSION['mem_id'];
    	$where1['mob_dth_recharge.mem_typ']="rt";
    	$where1['mob_dth_recharge.status']=2;
    	
    	$this->db->where($where1);
    	$this->db->order_by("mob_dth_recharge.timestamp","desc");
    	$this->db->join("retailer_details","retailer_details.rt_id=mob_dth_recharge.mem_id");
    	$this->db->select('mem_id,mob_dth_recharge.mem_typ,cust_no,sercode,amt,trans_id,trans_scode,retailer_details.name as name');
    	$array=$this->db->get('mob_dth_recharge')->result();
    	$this->output->set_content_type('application/json')->set_output(json_encode($array));
    }
    
}
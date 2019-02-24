<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends MX_Controller
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
    function admin()
    {
        $this->load->view('view');
    }
    
    function fetch_userdata()
    {
//         echo $this->session->userdata('usertype');die();
        $result['username']=$this->session->userdata('username');
        $result['mem_id']=$this->session->userdata('mem_id');
        $result['usertype']=$this->session->userdata('usertype');
        $result['com_id']=$this->session->userdata('com_id');
        $result['api_pin']=$this->session->userdata('api_pin');
        $result['api_pass']=$this->session->userdata('api_pass');
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
    
    function wallet_bal()
    {
    	$where=null;
    	$mem_typ=strtolower($_GET['mem_typ']);
    	
    	if ($mem_typ=='admin')
    	{
    		$where['admin_id']=$_GET['id'];
    		$admin_wal=$this->db->where($where)->select('wal_bal')->get('admin_wallet')->result();
    		$res=array(
    				"wal_bal"=>$admin_wal[0]->wal_bal
    		);
    		$this->output->set_content_type('application/json')->set_output(json_encode($res));
    	}
    	else
    	{
    		if($mem_typ=='master')$mem_typ='ms';
    		if($mem_typ=='distributor')$mem_typ='ds';
    		if($mem_typ=='retailer')$mem_typ='rt';
    		$where['com_id']=$_SESSION['com_id'];
    		$where['mem_id']=$_GET['id'];
    		$where['mem_typ']=$mem_typ;
    		
    		$this->db->where($where);
    		$this->db->order_by('cus_wal_id','desc');
    		$this->db->limit(1);
    		$this->db->select('net_wal');
    		$a=$this->db->get('wallet_customer')->result();
    		
    		$res=array(
    				"wal_bal"=>$a[0]->net_wal
    		);
    		$this->output->set_content_type('application/json')->set_output(json_encode($res));
    	}
    }
    function admin_total_mem()
    {
    	$mwhere['com_id']=$_SESSION['com_id'];
    	$total_mas=$this->db->where($mwhere)->get('master_details')->num_rows();
    	
    	$dwhere['com_id']=$_SESSION['com_id'];
    	$total_dis=$this->db->where($dwhere)->get('distributor_details')->num_rows();
    	
    	$rwhere['com_id']=$_SESSION['com_id'];
    	$total_ret=$this->db->where($rwhere)->get('retailer_details')->num_rows();
    	
    	$total_mem=$total_mas+$total_dis+$total_ret;
    	$res=array(
    			"master"=>$total_mas,
    			"distributor"=>$total_dis,
    			"retailer"=>$total_ret,
    			"total_mem"=>$total_mem
    	);
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    }
    
    function admin_mob_total()
    {
    	$th['com_id']=$_SESSION['com_id'];
    	$th['mob_dth_recharge.status']=1;
    	$th['mob_dth_recharge.trans_scode']=0;
    	$total_mob1=$this->db->where($th)->select('amt')->get('mob_dth_recharge')->result();
    	
    	// mobile success records
    	$total_mob_suc=0;
    	if(!empty($total_mob1))
    	{
    		foreach($total_mob1 as $row1)
    		{
    			$total_mob_suc=$total_mob_suc+$row1->amt;
    		}
    	}
    	// mobile failed transaction
    	$total_fail=$this->db->where(array('mob_dth_recharge.trans_scode >' =>'0','mob_dth_recharge.status' =>'1','com_id'=>$th['com_id']))->select('amt')->get('mob_dth_recharge')->result();
    	$famt=0;
    	if(!empty($total_fail))
    	{
    		foreach($total_fail as $fail)
    		{
    			$famt=$famt+$fail->amt;
    		}
    		
    	}
    	$res=array(
    			"mob_success"=>$total_mob_suc,
    			"mob_fail"=>$famt,
    			"mob_total"=>$famt+$total_mob_suc
    	);
    	
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    	
    }
    function admin_dth_total()
    {
    	$th['com_id']=$_SESSION['com_id'];
    	$th['mob_dth_recharge.status']=2;
    	$th['mob_dth_recharge.trans_scode']=0;
    	$total_dth=$this->db->where($th)->select('amt')->get('mob_dth_recharge')->result();
    	
    	// dth success records
    	$total_dth_suc=0;
    	if(!empty($total_dth))
    	{
    		foreach($total_dth as $row1)
    		{
    			$total_dth_suc=$total_dth_suc+$row1->amt;
    		}
    	}
    	// mobile failed transaction
    	$total_fail=$this->db->where(array('mob_dth_recharge.trans_scode >' =>'0','mob_dth_recharge.status' =>'2','com_id'=>$th['com_id']))->select('amt')->get('mob_dth_recharge')->result();
    	$famt=0;
    	if(!empty($total_fail))
    	{
    		foreach($total_fail as $fail)
    		{
    			$famt=$famt+$fail->amt;
    		}
    		
    	}
    	$res=array(
    			"dth_success"=>$total_dth_suc,
    			"dth_fail"=>$famt,
    			"dth_total"=>$famt+$total_dth_suc
    	);
    	
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    	
    }
    // Masters
    function mas_total_mem()// calculating total member of master
    {
    	$mwhere['com_id']=$_SESSION['com_id'];
    	$mwhere['ms_id']=$_SESSION['mem_id'];
    	
    	$total_dis=$this->db->where($mwhere)->get('distributor_details')->num_rows();
    	$d=$this->db->where($mwhere)->select('ds_id')->get('distributor_details');
    	$rt=0;
    	if ($d->num_rows()>0)
    	{
    		$dis=$d->result();
    		foreach ($dis as $r)
    		{
    			$where['com_id']=$_SESSION['com_id'];
    			$where['ds_id']=$r->ds_id;
    			$t=$this->db->where($where)->get('retailer_details')->num_rows();
    			$rt=$rt+$t;
    		}
    	}
    	$res=array(
    			"mas_distri"=>$total_dis,
    			"mas_retail"=>$rt,
    			"mas_total"=>$total_dis+$rt
    	);
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    }
    
    function mas_mob_total() //calculating mobile transaction of masters
    {
    	$mwhere['com_id']=$_SESSION['com_id'];
    	$mwhere['ms_id']=$_SESSION['mem_id'];
    	
    	$d=$this->db->where($mwhere)->select('ds_id')->get('distributor_details');
    	$succ_amt=0;
    	$famt=0;
    	if ($d->num_rows()>0)
    	{ 
    		$dis=$d->result();
    		foreach ($dis as $r)
    		{
    			$where['com_id']=$_SESSION['com_id'];
    			$where['ds_id']=$r->ds_id;
    			$ret=$this->db->where($where)->select('rt_id')->get('retailer_details')->result();
    			foreach($ret as $row)
    			{
    				$where1['com_id']=$_SESSION['com_id'];
    				$where1['mem_id']=$row->rt_id;
    				$where1['mem_typ']="rt";
    				
    				$where1['mob_dth_recharge.status']=1;
    				$where1['mob_dth_recharge.trans_scode']=0;
    				//success
    				$total_success=$this->db->where($where1)->select('amt')->get('mob_dth_recharge')->result();
    				if(!empty($total_success))
    				{
    					foreach($total_success as $row1)
    					{
    						$succ_amt=$succ_amt+$row1->amt;
    					}
    				}
    				//fail
    				$total_fail=$this->db->where(array('mob_dth_recharge.trans_scode >' =>'0','mob_dth_recharge.status' =>'1','mem_id'=>$row->rt_id,'mem_typ'=>'rt','com_id'=>$where1['com_id']))->select('amt')->get('mob_dth_recharge')->result();
    				
    				if(!empty($total_fail))
    				{
    					foreach($total_fail as $fail)
    					{
    						$famt=$famt+$fail->amt;
    					}
    				}
    			}
    		}
    	}
    	$res=array(
    			"mas_mob_succ"=>$succ_amt,
    			"mas_mob_fail"=>$famt,
    			"mas_mob_total"=>$succ_amt+$famt
    	);
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    	
    }
    function mas_dth_total() // calculating dth ttransaction of masters
    {
    	$mwhere['com_id']=$_SESSION['com_id'];
    	$mwhere['ms_id']=$_SESSION['mem_id'];
    	
    	$d=$this->db->where($mwhere)->select('ds_id')->get('distributor_details');
    	$succ_amt=0;
    	$famt=0;
    	if ($d->num_rows()>0)
    	{
    		$dis=$d->result();
    		foreach ($dis as $r)
    		{
    			$where['com_id']=$_SESSION['com_id'];
    			$where['ds_id']=$r->ds_id;
    			$ret=$this->db->where($where)->select('rt_id')->get('retailer_details')->result();
    			foreach($ret as $row)
    			{
    				$where1['com_id']=$_SESSION['com_id'];
    				$where1['mem_id']=$row->rt_id;
    				$where1['mem_typ']="rt";
    				
    				$where1['mob_dth_recharge.status']=2;
    				$where1['mob_dth_recharge.trans_scode']=0;
    				//success
    				$total_success=$this->db->where($where1)->select('amt')->get('mob_dth_recharge')->result();
    				if(!empty($total_success))
    				{
    					foreach($total_success as $row1)
    					{
    						$succ_amt=$succ_amt+$row1->amt;
    					}
    				}
    				//fail
    				$total_fail=$this->db->where(array('mob_dth_recharge.trans_scode >' =>'0','mob_dth_recharge.status' =>'2','mem_id'=>$row->rt_id,'mem_typ'=>'rt','com_id'=>$where1['com_id']))->select('amt')->get('mob_dth_recharge')->result();
    				
    				if(!empty($total_fail))
    				{
    					foreach($total_fail as $fail)
    					{
    						$famt=$famt+$fail->amt;
    					}
    				}
    			}
    		}
    	}
    	$res=array(
    			"mas_dth_succ"=>$succ_amt,
    			"mas_dth_fail"=>$famt,
    			"mas_dth_total"=>$succ_amt+$famt
    	);
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    }
    
    //Distributor
    function dis_total_mem()
    {
    	$mwhere['com_id']=$_SESSION['com_id'];
    	$mwhere['ds_id']=$_SESSION['mem_id'];
    	
    	$total_ret=$this->db->where($mwhere)->get('retailer_details')->num_rows();
    	$res=array(
    			"dis_retail"=>$total_ret
    	);
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    }
    
    function dis_mob_total() //calculating mobile transaction of masters
    {
    	$mwhere['com_id']=$_SESSION['com_id'];
    	$mwhere['ds_id']=$_SESSION['mem_id'];
    	
    	$d=$this->db->where($mwhere)->select('rt_id')->get('retailer_details');
    	$succ_amt=0;
    	$famt=0;
    	if ($d->num_rows()>0)
    	{
    		$dis=$d->result();
    		foreach ($dis as $row)
    		{
    			$where1['com_id']=$_SESSION['com_id'];
    			$where1['mem_id']=$row->rt_id;
    			$where1['mem_typ']="rt";
    				
    			$where1['mob_dth_recharge.status']=1;
    			$where1['mob_dth_recharge.trans_scode']=0;
    			//success
    			$total_success=$this->db->where($where1)->select('amt')->get('mob_dth_recharge')->result();
    			if(!empty($total_success))
    			{
    				foreach($total_success as $row1)
    				{
    					$succ_amt=$succ_amt+$row1->amt;
    				}
    			}
    			//fail
    			$total_fail=$this->db->where(array('mob_dth_recharge.trans_scode >' =>'0','mob_dth_recharge.status' =>'1','mem_id'=>$row->rt_id,'mem_typ'=>'rt','com_id'=>$where1['com_id']))->select('amt')->get('mob_dth_recharge')->result();
    				
    			if(!empty($total_fail))
    			{
    				foreach($total_fail as $fail)
    				{
    					$famt=$famt+$fail->amt;
    				}
    			}
    		}
    	}
    	$res=array(
    			"dis_mob_succ"=>$succ_amt,
    			"dis_mob_fail"=>$famt,
    			"dis_mob_total"=>$succ_amt+$famt
    	);
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    }
    function dis_dth_total() // calculating dth ttransaction of distributor
    {
    	$mwhere['com_id']=$_SESSION['com_id'];
    	$mwhere['ds_id']=$_SESSION['mem_id'];
    	
    	$d=$this->db->where($mwhere)->select('rt_id')->get('retailer_details');
    	$succ_amt=0;
    	$famt=0;
    	if ($d->num_rows()>0)
    	{
    		$dis=$d->result();
    		foreach ($dis as $row)
    		{
    			$where1['com_id']=$_SESSION['com_id'];
    			$where1['mem_id']=$row->rt_id;
    			$where1['mem_typ']="rt";
    				
    			$where1['mob_dth_recharge.status']=2;
    			$where1['mob_dth_recharge.trans_scode']=0;
    			//success
    			$total_success=$this->db->where($where1)->select('amt')->get('mob_dth_recharge')->result();
    			if(!empty($total_success))
    			{
    				foreach($total_success as $row1)
    				{
    					$succ_amt=$succ_amt+$row1->amt;
    				}
    			}
    			//fail
    			$total_fail=$this->db->where(array('mob_dth_recharge.trans_scode >' =>'0','mob_dth_recharge.status' =>'2','mem_id'=>$row->rt_id,'mem_typ'=>'rt','com_id'=>$where1['com_id']))->select('amt')->get('mob_dth_recharge')->result();
    				
    			if(!empty($total_fail))
    			{
    				foreach($total_fail as $fail)
    				{
    					$famt=$famt+$fail->amt;
    				}
    			}
    		}
    	}
    	$res=array(
    			"dis_dth_succ"=>$succ_amt,
    			"dis_dth_fail"=>$famt,
    			"dis_dth_total"=>$succ_amt+$famt
    	);
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    }
    
    // Retailer
    function ret_mob_total() //calculating mobile transaction of retailer
    {
    	$succ_amt=0;
    	$famt=0;
    	
    	$where1['com_id']=$_SESSION['com_id'];
    	$where1['mem_id']=$_SESSION['mem_id'];
    	$where1['mem_typ']="rt";
    	$where1['mob_dth_recharge.status']=1;
    	$where1['mob_dth_recharge.trans_scode']=0;
    	//success
    	$total_success=$this->db->where($where1)->select('amt')->get('mob_dth_recharge')->result();
    	if(!empty($total_success))
    	{
    		foreach($total_success as $row1)
    		{
    			$succ_amt=$succ_amt+$row1->amt;
    		}
    	}
    	//fail
    	$total_fail=$this->db->where(array('mob_dth_recharge.trans_scode >' =>'0','mob_dth_recharge.status' =>'1','mem_id'=>$where1['mem_id'],'mem_typ'=>'rt','com_id'=>$where1['com_id']))->select('amt')->get('mob_dth_recharge')->result();
    			
    	if(!empty($total_fail))
    	{
    		foreach($total_fail as $fail)
    		{
    			$famt=$famt+$fail->amt;
    		}
    	}
    	$res=array(
    			"ret_mob_succ"=>$succ_amt,
    			"ret_mob_fail"=>$famt,
    			"ret_mob_total"=>$succ_amt+$famt
    	);
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    }
    function ret_dth_total() //calculating mobile transaction of retailer
    {
    	$succ_amt=0;
    	$famt=0;
    	
    	$where1['com_id']=$_SESSION['com_id'];
    	$where1['mem_id']=$_SESSION['mem_id'];
    	$where1['mem_typ']="rt";
    	$where1['mob_dth_recharge.status']=2;
    	$where1['mob_dth_recharge.trans_scode']=0;
    	//success
    	$total_success=$this->db->where($where1)->select('amt')->get('mob_dth_recharge')->result();
    	if(!empty($total_success))
    	{
    		foreach($total_success as $row1)
    		{
    			$succ_amt=$succ_amt+$row1->amt;
    		}
    	}
    	//fail
    	$total_fail=$this->db->where(array('mob_dth_recharge.trans_scode >' =>'0','mob_dth_recharge.status' =>'2','mem_id'=>$where1['mem_id'],'mem_typ'=>'rt','com_id'=>$where1['com_id']))->select('amt')->get('mob_dth_recharge')->result();
    	
    	if(!empty($total_fail))
    	{
    		foreach($total_fail as $fail)
    		{
    			$famt=$famt+$fail->amt;
    		}
    	}
    	$res=array(
    			"ret_dth_succ"=>$succ_amt,
    			"ret_dth_fail"=>$famt,
    			"ret_dth_total"=>$succ_amt+$famt
    	);
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    }
    
    
    
    function ret_profile() //calculating mobile transaction of retailer
    {
    	
    	
    	$where1['com_id']=$_SESSION['com_id'];
    	$where1['rt_id']=$_SESSION['mem_id'];
    	$where1['mem_typ']="rt";
    	//success
    	$ret_nm=$this->db->where($where1)->select('name')->get('retailer_details')->result();
    	$ret_fnm=$this->db->where($where1)->select('firmname')->get('retailer_details')->result();
    	$ret_em=$this->db->where($where1)->select('email')->get('retailer_details')->result();
    	$ret_mb=$this->db->where($where1)->select('mob')->get('retailer_details')->result();
    	$ret_ps=$this->db->where($where1)->select('psa_id')->get('retailer_details')->result();
    	$ret_ad=$this->db->where($where1)->select('address')->get('retailer_details')->result();
    	$ret_st=$this->db->where($where1)->select('state')->get('retailer_details')->result();
    	$ret_ct=$this->db->where($where1)->select('city')->get('retailer_details')->result();
    	$ret_pn=$this->db->where($where1)->select('pin')->get('retailer_details')->result();
    	
    	$res=array(
    			$ret_nm=$ret_name,
    	);
    	$this->output->set_content_type('application/json')->set_output(json_encode($res));
    }
    
    
    
    function view($bid)
    {
        $blg=$this->db->where('b_id',$bid)->get('blog');
        $b=$blg->result();
        $data['query']=$b;
        if ($blg->num_rows()>0){
            $blg=$blg->result();
            $data['bquery']=$blg;
            $data['title']=$blg[0]->title;
            $data['description']=$blg[0]->description;
            $data['module']="blog";
            $data['view_file']="view";
            echo Modules::run('template/layout2',$data);
        }else{
            echo "Invalid Blog url";
        }
    }
    
}
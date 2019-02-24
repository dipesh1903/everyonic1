<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Wallet_lock extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();        
        $this->load->model('Mdl_wallet_lock');
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
        $where=null;
        $this->load->library('form_validation');
        if($_POST['mem_typ'])
        {
            $this->form_validation->set_rules("ms_id", "Member type", "trim");
            $this->form_validation->set_rules("ds_id", "dis Member type", "trim");
            $this->form_validation->set_rules("rt_id", "ret Member type", "trim");
        }
        else 
        {
            $this->form_validation->set_rules("type", "Any Member type", "required|trim");
        }
        $this->form_validation->set_rules("mem_typ", "Member type", "required|trim");
        $this->form_validation->set_rules("loc_bal", "Lock Balance", "required|trim|numeric");
        
        if ($this->form_validation->run() == TRUE)
        {   
            $val['mem_typ'] = $this->input->post('mem_typ');
            $val['loc_bal'] = $this->input->post('loc_bal');
            $val['com_id'] =$this->session->userdata['com_id'];//session com id
            
            if($this->input->post('ms_id')=='a' && $this->input->post('mem_typ')=='ms')
            {
            	$this->db->where('com_id',$val['com_id']);
            	$a=$this->db->select('ms_id')->get('master_details')->result();
                foreach ($a as $m)
                {
                    $val['mem_id']=$m->ms_id;
                    
                    $where['mem_id']=$val['mem_id'];
                    $where['mem_typ']=$_POST['mem_typ'];
                    $where['com_id']=$val['com_id'];
                    
                    $b=$this->db->where($where)->get('wallet_lock');
                    if($b->num_rows()>0)
                    {
                        $this->db->where($where);
                        $c=$this->db->update('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                    }
                    else
                    {
                        $c=$this->db->insert('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                    }
                }
                if ($x==1)
                	echo $x;
                else 
                	echo 1;
            }
            elseif($this->input->post('ds_id')=='b' && $this->input->post('mem_typ')=='ds')
            {
            	$this->db->where('com_id',$val['com_id']);
            	$a=$this->db->select('ds_id')->get('distributor_details')->result();
            	foreach ($a as $m)
                {
                    $val['mem_id']=$m->ds_id;
                    
                    $where['mem_id']=$val['mem_id'];
                    $where['mem_typ']=$_POST['mem_typ'];
                    $where['com_id']=$val['com_id'];
                    
                    $b=$this->db->where($where)->get('wallet_lock');
                    if($b->num_rows()>0)
                    {
                        $this->db->where($where);
                        $c=$this->db->update('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                    }
                    else
                    {
                        $c=$this->db->insert('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                    }
                }
                echo $x;
            }
            elseif($this->input->post('rt_id')=='c' && $this->input->post('mem_typ')=='rt')
            {
            	$this->db->where('com_id',$val['com_id']);
            	$a=$this->db->select('rt_id')->get('retailer_details')->result();
            	foreach ($a as $m)
                {
                    $val['mem_id']=$m->rt_id;
                    
                    $where['mem_id']=$val['mem_id'];
                    $where['mem_typ']=$_POST['mem_typ'];
                    $where['com_id']=$val['com_id'];
                    
                    $b=$this->db->where($where)->get('wallet_lock');
                    if($b->num_rows()>0)
                    {
                        $this->db->where($where);
                        $c=$this->db->update('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                    }
                    else
                    {
                        $c=$this->db->insert('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                    }
                }
                echo $x;
            }
            else 
            {
            	if($this->input->post('mem_typ')=='ms' && $this->input->post ( 'ms_id' ))
                {
                	$val['mem_id']=$this->input->post ( 'ms_id' );
                        
                    $where['mem_id']=$val['mem_id'];
                    $where['mem_typ']=$_POST['mem_typ'];
                    $where['com_id']=$val['com_id'];
                    
                    $a=$this->db->where($where)->get('wallet_lock');
                    if($a->num_rows()>0)
                    {
                    	$this->db->where($where);
                        $c=$this->db->update('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                    }
                    else 
                    {
                    	$c=$this->db->insert('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                     }
                     echo $x;
                }
                elseif($this->input->post('mem_typ')=='ds' && $this->input->post ( 'ds_id' ))
                {
                	$val['mem_id']=$this->input->post ( 'ds_id' );
                        
                    $where['mem_id']=$val['mem_id'];
                    $where['mem_typ']=$_POST['mem_typ'];
                    $where['com_id']=$val['com_id'];
                        
                    $a=$this->db->where($where)->get('wallet_lock');
                    if($a->num_rows()>0)
                    {
                    	$this->db->where($where);
                        $c=$this->db->update('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                    }
                    else
                    {
                    	$c=$this->db->insert('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                    }
                    echo $x;
                }
                elseif($this->input->post('mem_typ')=='rt' && $this->input->post ( 'rt_id' ))
                {
                	$val['mem_id']=$this->input->post ( 'rt_id' );
                        
                    $where['mem_id']=$val['mem_id'];
                    $where['mem_typ']=$_POST['mem_typ'];
                    $where['com_id']=$val['com_id'];
                    
                    $a=$this->db->where($where)->get('wallet_lock');
                    if($a->num_rows()>0)
                    {
                    	$this->db->where($where);
                        $c=$this->db->update('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                    }
                    else
                    {
                    	$c=$this->db->insert('wallet_lock',$val);
                        $x=$this->db->affected_rows($c);
                    }
                }
            }
        }
        else
        {
            echo validation_errors();
        }
    }
    
    function fetch_wallet()
    {
    	$where['com_id'] =$this->session->userdata['com_id'];//session com id
        
        if (isset($_GET['id']))
            $where['cust_id']=$_GET['id'];
        
        if (isset($_GET['mem_typ']))
            $where['mem_typ']=$_GET['mem_typ'];
        
            $this->db->where($where);
            $a=$this->db->get('wallet_customer');
        if($a->num_rows()>0)
        {
            $res=$a->result();
            $this->output->set_content_type('application/json')->set_output(json_encode($res));
        }
        else 
        {
            $res=array(
                "error"=>1,
                "msg"=>"no data found"
            );    
            $this->output->set_content_type('application/json')->set_output(json_encode($res));
        }
        
    }
    
    function view()
    {
    	$where['com_id'] =$this->session->userdata['com_id'];//session com id
        
        if (isset($_GET['ms_id']))
            $where['ms_id']=$_GET['ms_id'];
    
        if (isset($_GET['data']))
	        $select=$_GET['data'];
	    else $select="*";
	    
	    $return=$this->Mdl_wallet_back->view_data($where,$select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    function delete()
    {
        if (isset($_GET['ms_id']) && $_GET['ms_id'])
        {
            $where['ms_id']=$_GET['ms_id'];
            $object=json_encode($this->Mdl_wallet_back->view_data($where,"*")->result());
            $data_title= "Member Deleted";
            
            $this->load->module("logs");
            if ($this->logs->add_data($data_title,$object)) {
                echo $this->Mdl_wallet_back->delete_data($where);
            }
        }
    }
    
}
?>

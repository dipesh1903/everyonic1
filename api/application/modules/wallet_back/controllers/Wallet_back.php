<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Wallet_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();        
        $this->load->model('Mdl_wallet_back');
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
        $debit=intval($_POST['debit']);
        $wal=intval($_POST['wal_bal']);
        
        $this->load->library('form_validation');
        if($this->input->post('ms_id')||$this->input->post('ds_id')||$this->input->post('rt_id'))
        {
            $this->form_validation->set_rules("ms_id", "Member type", "trim");
            $this->form_validation->set_rules("ds_id", "dis Member type", "trim");
            $this->form_validation->set_rules("rt_id", "ret Member type", "trim");
        }
        else 
        {
            $this->form_validation->set_rules("Member type", "trim|required");
        }
        $this->form_validation->set_rules("mem_typ", "Member type", "required|trim");
        $this->form_validation->set_rules("wal_bal", "Wallet Balance", "required|trim|numeric");
        $this->form_validation->set_rules("net_wal", "Net Balance", "required|trim|max_length[12]|numeric");
        if($_POST['debit'])
        {
            if($debit>$wal)
            {
                $this->form_validation->set_rules('debit',"Wallet Bal greater than Debit ", "required|trim||greater_than[wal_bal]|numeric");
            }
            else
            {
                $this->form_validation->set_rules("debit", "Debit", "trim|numeric");
            }
        }
        else if($_POST['credit'])
        {
            $this->form_validation->set_rules("credit", "Credit", "trim|numeric");
        }
        else 
        {
            $this->form_validation->set_rules("debit", "Debit", "required|trim|numeric");
            $this->form_validation->set_rules("credit", "credit", "required|trim|numeric");
        }
        $this->form_validation->set_rules("desc", "Description", "trim");
        
        if ($this->form_validation->run() == TRUE)
        {   
            if($this->input->post('ms_id'))
                $val['mem_id'] = $this->input->post('ms_id');
            if($this->input->post('ds_id'))
                $val['mem_id'] = $this->input->post('ds_id');
            if($this->input->post('rt_id'))
                $val['mem_id'] = $this->input->post('rt_id');
            $val['mem_typ'] = $this->input->post('mem_typ');
            $val['wal_bal'] = $this->input->post('wal_bal');
            $val['net_wal'] = $this->input->post('net_wal');
            if($this->input->post('credit'))
                $val['credit'] = $this->input->post('credit');
            if($this->input->post('debit'))
                $val['debit'] = $this->input->post('debit');
            $val['desc'] = $this->input->post('desc');
            
            if ($this->input->post('cus_wal_id')) // update
            {
            	$where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
                $where['cus_wal_id'] = $this->input->post('id');
                echo $this->Mdl_wallet_back->update_data($where,$val);
            }
            else // add
            { 
                $val['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
                echo $this->Mdl_wallet_back->add_data($val);
            }
        }
        else
        {
            echo validation_errors();
        }
    }
    
    function fetch_wallet()
    {
        $where['com_id']=$this->session->userdata('com_id');
        
        if (isset($_GET['id']))
            $where['mem_id']=$_GET['id'];
        
        if (isset($_GET['mem_typ']))
            $where['mem_typ']=$_GET['mem_typ'];
        
            $this->db->where($where);
            $this->db->order_by('cus_wal_id','desc');
            $this->db->limit(1);
            $this->db->select('net_wal');
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
        
        if($this->session->userdata('usertype')=='Administrator')
            $where=null;
        else
            $where['com_id']=$this->session->userdata('com_id');
        
        if (isset($_GET['ms_id']))
            $where['ms_id']=$_GET['ms_id'];
    
        if (isset($_GET['data']))
	        $select=$_GET['data'];
	    else $select="*";
	    
	    $return=$this->Mdl_wallet_back->view_data($where,$select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    
    
//     function delete()
//     {
//         if (isset($_GET['ms_id']) && $_GET['ms_id'])
//         {
//             $where['ms_id']=$_GET['ms_id'];
//             $object=json_encode($this->Mdl_wallet_back->view_data($where,"*")->result());
//             $data_title= "Member Deleted";
            
//             $this->load->module("logs");
//             if ($this->logs->add_data($data_title,$object)) {
//                 echo $this->Mdl_wallet_back->delete_data($where);
//             }
//         }
//     }
    
}
?>

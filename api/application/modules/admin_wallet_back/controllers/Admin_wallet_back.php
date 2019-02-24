<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_wallet_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();        
        $this->load->model('Mdl_admin_wallet_back');
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
    function save()
    {
//         print_r($_POST);die();
        $this->load->library('form_validation');
        $this->form_validation->set_rules("admin_id", "Admin Name", "required|trim");
        $this->form_validation->set_rules("wal_bal", "Wallet Balance", "required|trim");
        if ($this->form_validation->run()==TRUE)
        {   
            $val['admin_id'] = $_POST['admin_id'];
            if($this->input->post('status'))
            	$val['status']= $this->input->post('status');
            
            $this->db->where('admin_id',$val['admin_id']);
            $a=$this->db->get('admin_wallet');
            if ($a->num_rows()>0)
            {
            	$res=$a->result();
            	$val['wal_bal']=$_POST['wal_bal']+$res[0]->wal_bal;
            	
            	echo $this->Mdl_admin_wallet_back->update_data($res[0]->adminwal_id,$val);
            }
            else 
            {
            	$val['wal_bal'] = $_POST['wal_bal'];
            	echo $this->Mdl_admin_wallet_back->add_data($val);
            }
        }
        else
        {
            echo validation_errors();
        }
    }
    function view()
    {
        $where=null;
        if (isset($_GET['admin_id']))
            $where['admin_id']=$_GET['admin_id'];
    
        if (isset($_GET['data']))
	        $select=$_GET['data'];
	    else $select="*";
	    
	    $return=$this->Mdl_admin_wallet_back->view_data($where,$select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    function delete()
    {
        if (isset($_GET['admin_id']) && $_GET['admin_id'])
        {
            $where['admin_id']=$_GET['admin_id'];
            $object=json_encode($this->Mdl_admin_wallet_back->view_data($where,"*")->result());
            $data_title= "Admin Deleted";
            $this->load->module("logs");
            if ($this->logs->add_data($data_title,$object))
            {
                echo $this->Mdl_admin_wallet_back->delete_data($where);
            }
        }
    }
    
   
}
?>

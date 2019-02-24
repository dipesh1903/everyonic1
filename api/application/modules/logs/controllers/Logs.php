<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logs extends MX_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('logs_mdl');
    }
 	public function index()
	{
       $this->load->module('login');
       if($this->login->auth())
       {
           //check user privileges
           if(!$this->user_privileges->check_module_privileges($this->module))
               echo 2;//privilege error
           else 
               echo 1;//success
       }else{
           echo 0;//logout
       }
   }
	function view_data()
	{
	    $where['logs.com_id']=$this->data['com_id'];
	    $where['logs.branch_id']=$this->data['branch_id'];
	    if (isset($_GET['data']))
	        $select=$_GET['data'];
	    else $select="logs.auto_id,logs.com_id,logs.branch_id,logs.date,logs.time,logs.data_title,logs.data,logs.timestamp,hr_staff_details.username";
	    $this->db->join('hr_staff_details','hr_staff_details.username=logs.user');
	    $return=$this->logs_mdl->view_data($where,$select);
	    $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	function get_object()
	{
	    $return=$this->db->where('auto_id',$_GET['id'])->get('logs')->result();
        print_r($return[0]->data);
	    
	}
	function add_data($title, $object)
	{
	    $data['data']=$object;
	    $data['data_title']=$title;
	    $data['date'] = date("d/m/Y");
	    $data['time'] = date("h:i:s");
	    $data['user']=$this->session->userdata['usertype'];
	    $data['com_id']=$this->session->userdata['com_id'];
	   
	    return $this->logs_mdl->add_data($data);
	}
	//Copyright @ Groveus (www.groveus.com)	
}
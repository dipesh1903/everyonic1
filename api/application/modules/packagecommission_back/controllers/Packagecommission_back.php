<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Packagecommission_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mdl_packagecommission_back');
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
        if ($this->input->post('charge'))
        {
        	$this->form_validation->set_rules("charge", "Charge ", "trim");
        	$this->form_validation->set_rules("char_type", "Charge type", "trim|required");
        }
        if ($this->input->post('char_type'))
        {
        	$this->form_validation->set_rules("char_type", "Charge type", "trim");
        	$this->form_validation->set_rules("charge", "Charge ", "trim|required");
        }
        if ($this->input->post('commission'))
        {
        	$this->form_validation->set_rules("commission", "Commission", "trim");
        	$this->form_validation->set_rules("com_typ", "Commission type", "trim|required");
        }
        if($this->input->post('com_typ'))
        {
        	$this->form_validation->set_rules("com_typ", "Commission type", "trim");
        	$this->form_validation->set_rules("commission", "Commission", "trim|required");
        }
 
        if ($this->form_validation->run() == TRUE)
        {
            $val['pac_id'] = $this->input->post('pac_id');
            $val['sercode'] = $this->input->post('sercode');
            if($_SESSION['usertype']!='administrator')
            	$val['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
            $w=$this->db->where($val)->get('package_assigner');
            if ($w->num_rows()>0)
            {
            	if ($this->input->post('charge'))
                	$data['charge']=$this->input->post('charge');
               	if ($this->input->post('char_type'))
                	$data['char_typ']=$this->input->post('char_type');
                if ($this->input->post('commission'))
                	$data['commission']=$this->input->post('commission');
                if($this->input->post('com_typ'))
                	$data['com_typ']=$this->input->post('com_typ');

                $this->db->where($val);
                $c=$this->db->update('package_assigner',$data);
               echo $c;die();
                return $this->db->affected_rows($c);
            }
            else
            {
                $data=array(
                	"error"=>1,
                    'msg'=>"First Assigner This Package From Add Package Assigner"
                );
                header("Content-Type: application/json");
                echo json_encode($data);
            }
        }
        else
        {
            echo validation_errors();
        }
    }
    function view()
    {
        $where['package.com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
        $where["package.status"]=1;
        
        if (isset($_GET['id']))
            $where['pac_id']=$_GET['id'];
        if (isset($_GET['data']))
            $select=$_GET['data'];
        else $select="pac_id as id,package_name as pname,ser_id,service_name as sname, package.status as status";
            
        $return=$this->Mdl_packagecommission_back->view_data($where,$select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    
    function delete()
    {
        if (isset($_GET['pac_id']) && $_GET['pac_id'])
        {
            $where['pac_id']=$_GET['pac_id'];
            $where['package.com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
            
            $object=json_encode($this->Mdl_packagecommission_back->view_data($where,"*")->result());
            $data_title= "Member Deleted";
            
            $this->load->module("logs");
            if ($this->logs->add_data($data_title,$object)) {
                echo $this->Mdl_packagecommission_back->delete_data($where);
            }
        }
    }
}
?>

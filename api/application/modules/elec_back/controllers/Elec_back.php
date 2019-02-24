<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Elec_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mdl_elec_back');
        //privileges
        $this->module="elec_back";
        $this->load->module('user_privileges');
    }
   function index()
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
    function save()
    {
        //         print_r($_POST);die();
        $this->load->library('form_validation');
        $this->form_validation->set_rules("c_no", "Consumer Number", "required|trim|max_length[12]");
        $this->form_validation->set_rules("c_name", "Consumer Name", "required|trim");
        $this->form_validation->set_rules("c_mob", "Consumer Mobile", "required|trim");
        $this->form_validation->set_rules("operator", "Operator", "required|trim");
        $this->form_validation->set_rules("amount", "Amount", "required|trim");
        $this->form_validation->set_rules("date", "Date", "required|trim");
       print_r($_POST);die();
        if ($this->form_validation->run() == TRUE)
        {
        	$custno = $this->input->post('c_no');
        	$custname = $this->input->post('c_name');
        	$custmob = $this->input->post('c_mob');
        	$sercode = $this->input->post('operator');
        	$amt= $this->input->post('amount');
        	$dt=$this->input->post('date');
        	
        	$val['CUSTNO'] = $custno;
        	$val['CUSTNAME'] = $custname;
        	$val['AMT'] = $amt;
        	$val['sercode'] = $sercode;
        	$val['APIKey'] = '3MegCUMI9CfNFOrMEp6rPQydHmnaeEHXLv8';
        	$val['REQTYPE'] = 'RECH';
        	$val['REFNO'] = 'com11';
        	$val['MobileNo'] = '9832999339';
        	$val['RESPTYPE'] = 'JSON';
        	$val['mem_typ'] = 'rt';
        	
        	$data = array('member_id' => '9832999339','api_password' => '123456' ,'api_pin' => '123456', "request_id" => 'com12','opcode'=>$sercode,'number'=>$custno,'amount'=>$amt);
        	$url="http://everyonicweb.com/recharge_api/recharge?";
        	$ch=curl_init();
        	curl_setopt_array($ch, array(
        			CURLOPT_URL => $url,
        			CURLOPT_RETURNTRANSFER=> TRUE,
        			CURLOPT_POST =>TRUE,
        			CURLOPT_POSTFIELDS =>$data
        	));
        	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
        	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
        	$output=curl_exec($ch);
        	if(curl_errno($ch)){
        		echo 'error'.$ch ;
        	}
        	curl_close($ch);
        	?>
		    <p>Response ID:<?php echo $output; ?>Message </p>
            $val['c_no'] = $this->input->post('c_no');
            $val['c_name'] = $this->input->post('c_name');
            $val['c_mob'] = $this->input->post('c_mob');
            $val['operator'] = $this->input->post('operator');
            $val['amount'] = $this->input->post('amount');
            $val['date'] = $this->input->post('date');
            $val['mem_typ'] = 'ms';
            $val['mem_id'] = 'm1001';
            if ($this->input->post('el_id')) // update
            {
                $function="update";
                $this->user_privileges->check_privilege($this->module,$function);
                
                $where['id'] = $this->input->post('el_id');
                $where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
                
                if($this->input->post('status'))
                    $val['status']= $this->input->post('status');
                echo $this->Mdl_elec_back->update_data($where,$val);
                    
            }
            else // add
            {
                $function="add";
                $this->user_privileges->check_privilege($this->module,$function);
                
                $val['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
                echo $this->Mdl_elec_back->add_data($val);
            }
        }
        else
        {
            echo validation_errors();
        }
    }
    function view()
    {
        $where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
        
        if (isset($_GET['el_id']))
            $where['el_id']=$_GET['el_id'];
            
            if (isset($_GET['data']))
                $select=$_GET['data'];
                else $select="*";
                
                $return=$this->Mdl_elec_back->view_data($where,$select);
                $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    function delete()
    {
        if (isset($_GET['el_id']) && $_GET['el_id'])
        {
            $where['el_id']=$_GET['el_id'];
            $where['com_id']=$this->session->userdata('com_id'); //comment when testing with static data
            
            $object=json_encode($this->Mdl_elec_back->view_data($where,"*")->result());
            $data_title= "Member Deleted";
            
            $this->load->module("logs");
            if ($this->logs->add_data($data_title,$object)) {
                echo $this->Mdl_elec_back->delete_data($where);
            }
        }
    }
            
            
}
?>

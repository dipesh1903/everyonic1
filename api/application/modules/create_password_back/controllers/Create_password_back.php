<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Create_password_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();        
        $this->load->model('Mdl_create_password_back');
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
    function save(){
        $this->load->library('form_validation');
        if($this->input->post('admin_id')||$this->input->post('ms_id')||$this->input->post('ds_id')||$this->input->post('rt_id')){
        	$this->form_validation->set_rules("admin_id", "Admin", "trim");
        	$this->form_validation->set_rules("ms_id", "Master", "trim");
        	$this->form_validation->set_rules("ds_id", "Distributor", "trim");
        	$this->form_validation->set_rules("rt_id", "Retailer", "trim");
        }else{
			$this->form_validation->set_rules("Select Any One Member", "trim|required");
		}
        
        $this->form_validation->set_rules("usertype", "User Type", "trim|required");
        $this->form_validation->set_rules("username", "User Name", "trim|required|is_unique[userpass_details.username]");
        $this->form_validation->set_rules("password", "Password", "required|trim|min_length[6]|max_length[20]");
        $this->form_validation->set_rules('confpass', 'Password Confirmation', 'required|matches[password]');
        
        if($this->form_validation->run() == TRUE){   
            if($this->input->post('admin_id'))
                $val['mem_id'] = $this->input->post('admin_id');
            if($this->input->post('ms_id'))
                $val['mem_id'] = $this->input->post('ms_id');
            if($this->input->post('ds_id'))
                $val['mem_id'] = $this->input->post('ds_id');
            if($this->input->post('rt_id'))
                $val['mem_id'] = $this->input->post('rt_id');
            
            $val['usertype'] = $this->input->post('usertype');
            $val['username'] = $this->input->post('username');
            $val['password'] = $this->input->post('password');
            $val['timestamp'] = date('Y-m-d H:i:s');
			$val['com_id'] = $this->session->userdata('com_id');
			if($this->input->post('usertype') == 'ms'){
				$where['ms_id'] = $val['mem_id'];
				$where['com_id'] = $this->session->userdata('com_id');
				$com_id = $this->db->where($where)->select('mob')->get('master_details')->result();
				$mobile = $com_id[0]->mob;
			}
			if($this->input->post('usertype') == 'ds'){
				$where['ds_id'] = $val['mem_id'];
				$where['com_id'] = $this->session->userdata('com_id');
				$com_id = $this->db->where($where)->select('mob')->get('distributor_details')->result();
				$mobile = $com_id[0]->mob;
			}
			if($this->input->post('usertype') == 'rt'){
				$where['rt_id'] = $val['mem_id'];
				$where['com_id'] = $this->session->userdata('com_id');
				$com_id = $this->db->where($where)->select('mob')->get('retailer_details')->result();
				$mobile = $com_id[0]->mob;
			}
            if($this->input->post('id')){
                $where['auto_id'] = $this->input->post('id');
                if($this->input->post('status'))
                    $val['status'] = $this->input->post('status');
                echo $this->Mdl_create_password_back->update_data($where,$val);
            }else{
                $con = $this->Mdl_create_password_back->add_data($val);
                $msg = "Your Username is ".$val['username']." and Password is ".$val['password'].". Thanks for choosing PARISHEBAN DIGITECH. Visit Our Website: http://www.parishebandigitech.com/ ";
                if($con == 1){
                	$authkey = urlencode('235671AOI1qo5YY5b8ecb8a');
                	// Message details
                	$mobiles = $mobile;
                	$sender = urlencode('PARISE');
                	$message = rawurlencode($msg);
                	$route = 4;
                	$response = "json";
                	// Prepare data for POST request
                	$data1 = array('authkey' => $authkey, 'mobiles' => $mobiles, "sender" => $sender, "message" => $message, "route"=>$route,"response"=>$response);
                	$url = "http://login.wpssms.com/api/sendhttp.php?";
                	$ch = curl_init();
                	curl_setopt_array($ch, array(
                		CURLOPT_URL 			=> $url,
                		CURLOPT_RETURNTRANSFER	=> TRUE,
                		CURLOPT_POST 			=>TRUE,
                		CURLOPT_POSTFIELDS 		=> $data1
                	));
                	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
                	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
                	$output = curl_exec($ch);
                	if(curl_errno($ch)){
                		echo 'error';
                	}
                	curl_close($ch);
                	$output_array = json_decode($output, TRUE);
                	if($output_array['type'] == "success"){
                		echo 1;
                	}else{
						echo $output_array['type'];
					}
                }
            }
        }else{
            echo validation_errors();
        }
    }
    
    function view(){
        $where['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
        
        if (isset($_GET['id']))
            $where['admin_id']=$_GET['id'];
        
        if (isset($_GET['data']))
	        $select=$_GET['data'];
	    else $select="*";
	    
	    $return=$this->Mdl_create_password_back->view_data($where,$select);
        $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
    }
    function delete()
    {
        if (isset($_GET['id']) && $_GET['id'])
        {
            $where['auto']=$_GET['auto'];
            $where['com_id']=$this->session->userdata('com_id'); //uncomment when testing with session
            
            $object=json_encode($this->Mdl_create_password_back->view_data($where,"*")->result());
            $data_title= "Member's Deleted";
            
            $this->load->module("logs");
            if ($this->logs->add_data($data_title,$object)) {
                echo $this->Mdl_create_password_back->delete_data($where);
            }
        }
    }
}
?>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sms_back extends MX_Controller
{
	//wGtRkO8VoEyUjS
	function __construct()
	{
		parent::__construct();
		$this->load->model('Mdl_sms_back');
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
		$this->form_validation->set_rules("number", "Number is required", "required|trim");
		$this->form_validation->set_rules("msg", "Message is required", "required|trim");

		if($this->form_validation->run()){
		$mobile = $this->input->post('number');	
 		$msg = $this->input->post('msg');
// 		$data=$this->input->post();
//		unset($data['submit']);

			
		// Account details
		$apiKey = urlencode('H0qcGvrDOYM-UGUi4jqIewjtcIFdzej0uK29g7rzjv');
		// Message details
		$numbers = array($mobile);	
		$sender = urlencode('TXTLCL');
		$message = rawurlencode($msg);
		$numbers = implode(',', $numbers);
		
		// Prepare data for POST request
		$data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
		$url="https://api.textlocal.in/send/";
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
			echo 'error';
			
		}
		curl_close($ch);
		?>
		<p>Response ID:<?php echo $output; ?>Message </p>
		<?php 
// 		Response ID:{"balance":9,"batch_id":568549678,"cost":1,"num_messages":1,"message":{"num_parts":1,"sender":"TXTLCL","content":"Happy New Year"},"receipt_url":"","custom":"","messages":[{"id":"1671448987","recipient":916295529286}],"status":"success"}Message Successfully
// 		Response ID:{"balance":5,"batch_id":568554342,"cost":1,"num_messages":1,"message":{"num_parts":1,"sender":"TXTLCL","content":"Happy New Year POPO Potterhead .."},"receipt_url":"","custom":"","messages":[{"id":"1671454588","recipient":918001327556}],"status":"success"}Message Successfully
// 		Response ID:{"balance":4,"batch_id":568556137,"cost":1,"num_messages":1,"message":{"num_parts":1,"sender":"TXTLCL","content":"Happy New Year Python... tu sabse bara saap hey .. Sapola"},"receipt_url":"","custom":"","messages":[{"id":"1671456477","recipient":917060326763}],"status":"success"}Message Successfully
		
		}
		else
		{
			echo validation_errors();
		}
	}

}
?>

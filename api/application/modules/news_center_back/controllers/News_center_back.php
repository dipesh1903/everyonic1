<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');
class News_center_back extends MX_Controller{
    function __construct(){
        parent::__construct();
		$this->load->model('Mdl_news_center_back');
    }
    function index(){
       $this->load->module('login');
       if($this->login->auth()){
          echo 1;//success
       }else{
           echo 0;//logout
       }
    }
	function save(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules("mem_id", "mem_id", "required|trim");
		$this->form_validation->set_rules("news_text", "news_text", "required|trim");
		if ($this->form_validation->run() == TRUE){
			$val['edited'] = date("Y-m-d H:i:s");
			$val['com_id'] = $this->session->userdata('com_id');
			$val['news_text'] = $this->input->post('news_text');
			$val['news_status'] = $this->input->post('news_status');
			## Update
			if($this->input->post('ns_id')){ 
				$val['edited'] = date("Y-m-d H:i:s");
				$where['ns_id'] = $this->input->post('ns_id');
				echo $this->Mdl_news_center_back->update_data($where, $val);
			}else{
				$val['created'] = date("Y-m-d H:i:s");
				$val['ms_id'] = $this->input->post('mem_id');
				echo $this->Mdl_news_center_back->add_data($val);
			}
		}else{
			echo validation_errors();
		}
	}
	## Get the data in the view
	function view(){
		$where['com_id'] = $this->session->userdata('com_id'); //uncomment when testing with session
		if(isset($_GET['mem_id']))
			$where['ms_id'] = $_GET['mem_id'];
		if(isset($_GET['data']))
			$select = $_GET['data'];
		else $select = "*";
		$return = $this->Mdl_news_center_back->view_data($where, $select);
		$this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
	}
	
	function view_dash(){
		$where['com_id'] = $this->session->userdata('com_id');
		$select = "*";
		$return = $this->Mdl_news_center_back->view_data($where, $select);
		if(!empty($return->result_array())){
			$n = array();
			$i = 0;
			foreach($return->result_array() as $each){
				if($each['news_status'] == 1){
					$n[$i]['id'] = $each['ns_id'];
					$n[$i]['content'] = $each['news_text'];
					$i++;
					$n[$i]['id'] = "||";
					$n[$i]['content'] = "||";
					$i++;
				}
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($n));
		}else{
			echo 0;
		}
	}
}
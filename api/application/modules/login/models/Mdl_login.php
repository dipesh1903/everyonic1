<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_login extends CI_Model
{
	public function validate()
	{
	    $where=array(
	        "username"=>$this->input->post('user'),
	        "password"=>$this->input->post('pass'),
	    );
	    $query=$this->db->where($where)->get('userpass_details');
	    if($query->num_rows()>0)
	    {
	        $res=$query->result();
	        if ($res[0]->status==0)
	        {
	            $msg="Failed !! ".$this->input->post('user').", you are not active in the system.";
	            return array("msg"=>"<div class='alert alert-danger'>$msg</div>","type"=>1);
	        }

// 	        if($res[0]->usertype=='admin'|| $res[0]->usertype=='administrator')
// 	        {
//                 $wh['com_id']=$res[0]->com_id;
//                 $wh['admin_id']=$res[0]->mem_id;
//                 $data=$this->db->where($wh)->select("api_pass,api_pin,name")->get('admin_details')->result();
// 	        }
// 	        else 
// 	        {
// 	            $data=$this->db->where('com_id',$res[0]->com_id)->select("api_pass,api_pin")->get('admin_details')->result();
// 	        }

	        $ses_data=array(
	            'usertype'=>@$res[0]->usertype,
	            'com_id'=>@$res[0]->com_id,
	            'mem_id'=>@$res[0]->mem_id,
	            'username'=>@$res[0]->username,
// 	            'api_pass'=>@$data[0]->api_pass,
// 	            'api_pin'=>@$data[0]->api_pin,
	        );
	        $this->session->set_userdata($ses_data);
	        return 1;//successfull logged to dashboard
	    }
	    else
	    {
	        $msg="Invalid Username or Password";
	        return array("msg"=>"<div class='alert alert-danger'>$msg</div>","type"=>1);
	    }
	}
    function change_pwd()
	{
		$where['username']=$this->session->userdata('username');
		$old_password=$_POST['currentpass'];
		$new_password=$_POST['newpass'];
		
		$this->load->helper('security');
		$new_pwd=do_hash($new_password,'md5');
		$old_pwd=do_hash($old_password,'md5');
		
		$where['password']=$old_pwd;
		
		$this->db->where($where);
		$query=$this->db->get('hr_staff_details');
		$row = $query->num_rows();
		if($row>0)
		{
			$data=array(
						"password"=>$new_pwd
					);
			$this->db->where($where);
			return $this->db->update('hr_staff_details',$data);
		}
		else
		{
			return false;
		}
	}
	//Copyright @ Groveus (www.groveus.com)
}
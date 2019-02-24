<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_privileges extends MX_Controller
{
    private $type;
    function __construct()
	{
		parent::__construct();
		$this->load->model('mdl_user_privileges');
		$this->type="Admin"; // for checking purpose only 
// 		$this->type=$this->session->userdata('usertype');
		
// 		if (!$this->memtype) 
// 		{
// 		    return false;
// 		    echo "Api Error: Invalid Request";die();
// 		}
		
	}
	function index()
	{
       $this->load->module('login');
       if($this->login->auth())
       {
           //check user privileges
           if(!$this->type || $this->type=="Administrator" || $this->type=="Admin")
//            if(!$this->type || $this->type=="U")
               echo 1;//success
           else 
               echo 2;//privilege error
       }
       else
       {
           echo "logout";
           
           echo 0;//logout
       }
    }
    
//    function set_name(){
//        $i=0;
//        $j=0;
//        $r=$this->db->get('hr_staff_details')->result();
//        foreach ($r as  $e){
//            if ($e->staff_name==''){
//                $da['staff_name']=$e->username;
//                $w['emp_id']=$e->emp_id;
//                $da['staff_name']=$e->username;
//                $this->db->where($w)->update('hr_staff_details',$da);
//                $i++;   
//            }
//            $j++;
//        }
//        echo "Total Rows Found $j.<br>";
//        echo "Total Rows renamed $i.";
//    }

	function repair_privileges()
	{
	    $modules=$this->privileges_module();
	
	    $this->db->select('usertype');
	    $r=$this->db->where('status',1)->get('userpass_details');
	    $i=0;
	    $j=0;
	    $k=0;
	    foreach ($r->result() as $user)
	    {
	        foreach ($modules as $mod=>$val)
	        {
	            $where=array(
	                'module'=>$mod,
// 	                'mem_id'=>$user->mem_id,
	                'usertype'=>$user->usertype
	            );
	            if($this->db->where($where)->get('user_privileges')->num_rows()<1)
	            {
	                $data['usertype']=$user->usertype;
	                $data['module']=$mod;
	
	                $this->db->insert('user_privileges',$data);
	
	                $i=$i+1;
	            }
	            $k=$k+1;
	        }
	        $j=$j+1;
	    }
	    echo "<pre align='center'>";
	    echo "Total Users: <b>$j</b>\n";
	    echo "Rows Checked: <b>$k</b>\n";
	    echo "Rows inserted(Repaired): <b>$i</b>\n";
	}
	
	
	function check_privilege($module,$function)
	{
	    $where=array(
	        "usertype"=>$this->session->userdata('usertype'),
	        "module"=>$module,
	        $function=>1
	    );
	    $this->db->where($where);
	    if($this->db->get('user_privileges')->num_rows()<1)
	    {
	        if ($function=='view') 
	        {
	            $module=ucfirst($module);
	            echo "<br><br><div class='col-sm-6 col-sm-offset-3 alert alert-danger'><b>$module</b> Privileges not assigned by administrator</div>";die();
	            echo json_encode(array());die();
	        }
	        else
	        {
	            echo "Privilege Not Assigned";die();
	        }
	    }
	    else
	        return true;
	}
	
	function privileges_module()//static modules assigned for privileges
	{
	    return $module=array(
	        "admin_back"=>"Admin Details Module",
	        "create_password_back"=>"Create Pasword Page",
	        "distributor_management_back"=>"Distributor Details Module",
	        "master_management_back"=>"Master Details Module",
	        "retailer_management_back"=>"Retailer Details Module",
	        "package_assigner_back"=>"Package Assigner Module",
	        "package_back"=>"Package Module",
	        "packagecommission_back"=>"Package Commission Module",
	        "provider_back"=>"Operator Module",
	        "service_back"=>"Service Module",
	        "wallet_lock"=>"Wallet Lock Module",
	        "wallet_back"=>"Wallet Module",
	    );
	}
	
	function view_privileges_json()
	{
	    $this->output->set_content_type('application/json')->set_output(json_encode($this->privileges_module()));
	}
	
	function add_default_privileges($emp_id,$usertype="E")
	{
	    if ($usertype=="A")
	        return $this->admin_default_privileges($emp_id);
	    else
	        return $this->user_default_privileges($emp_id);
	}
	function user_default_privileges($emp_id)
	{
	    //         $data['com_id']=$this->data['com_id'];
	    $data['emp_id']=$emp_id;
	    $module=$this->privileges_module();
	
	    foreach ($module as $mod=>$val)
	    {
	        $data['module']=$mod;
	        $this->mdl_user_privileges->add_data($data);
	    }
	    return true;
	}
	function admin_default_privileges($emp_id)
	{
	    //         $data['com_id']=$this->data['com_id'];
	    $data['emp_id']=$emp_id;
	    $module=$this->privileges_module();
	
	    foreach ($module as $mod=>$val)
	    {
	        $data['add']=1;
	        $data['view']=1;
	        $data['update']=1;
	        $data['delete']=1;
	        $data['module']=$mod;
	        $this->mdl_user_privileges->add_data($data);
	    }
	    return true;
	}
	
	function update_data()
	{
// 	    print_r($_POST);die();
	    
	    $data['usertype']=$_POST['usertype'];
	     
	    $result = $this->mdl_user_privileges->view_data($data);
	    if($result->num_rows()>0)
	    {
	        foreach ($result->result() as $row)
	        {
	            $id=$row->auto_id;
	            //add_data
	            if (isset($_POST["add$id"]))
	                $add_data=array(
	                    "add"=>1
	                );
	                else
	                    $add_data=array(
	                        "add"=>0
	                    );
	                    //view_data
	                    if (isset($_POST["view$id"]))
	                        $view_data=array(
	                            "view"=>1
	                        );
	                        else
	                            $view_data=array(
	                                "view"=>0
	                            );
	                            //update_data
	                            if (isset($_POST["update$id"]))
	                                $update_data=array(
	                                    "update"=>1
	                                );
	                                else
	                                    $update_data=array(
	                                        "update"=>0
	                                    );
	                                    //delete_data
	                                    if (isset($_POST["delete$id"]))
	                                        $delete_data=array(
	                                            "delete"=>1
	                                        );
	                                        else
	                                            $delete_data=array(
	                                                "delete"=>0
	                                            );
	                                            $data=array_merge($add_data,$update_data,$view_data,$delete_data);//merging all the values of array
	                                            $where['auto_id']=$id;
	                                            $where['usertype']=$_POST['usertype'];
	                                            if($this->mdl_user_privileges->update_data($where,$data)==false)
	                                            {
	                                                $data=array(
	                                                    "type"=>1,
	                                                    "error"=>"Oops Process Terminated due to some technical issue..."
	                                                );
	                                                echo json_encode($data);
	                                                die(0);
	                                            }
	        }
	        $data=array(
	            "type"=>0,
	            "error"=>"<img src='".base_url('assets/icons/ok.svg')."' height=20px ><span style='display:inline-block'> Privileges Assigned Successfully.
	             </span><br><br>"
	        );
	    }
	    else
	    {
	        $data=array(
	            "type"=>1,
	            "error"=>"User not Found!"
	        );
	    }
	    echo json_encode($data);
	}
	function view_data()
	{
	    if (isset($_GET['usertype']) && $_GET['usertype'])
	    {
	        $data['usertype']=$_GET['usertype'];
	        $result = $this->mdl_user_privileges->view_data($data);
	        $this->output->set_content_type('application/json')->set_output(json_encode($result->result_array()));
	    }
	}
	function check_module_privileges($module)//based on view_data status
	{
	    $data['module']=$module;
	    $data['view']=1;
	    $data['usertype']=$this->session->userdata('usertype');
// 	    var_dump($this->mdl_user_privileges->check_module_privileges($data));
	    return $this->mdl_user_privileges->check_module_privileges($data);
	}
}
?>
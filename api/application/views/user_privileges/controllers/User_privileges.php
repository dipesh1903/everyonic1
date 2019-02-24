<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_privileges extends MX_Controller
{
    private $type;
    function __construct()
	{
		parent::__construct();
		$this->load->model('mdl_user_privileges');
		$this->type=$this->session->userdata('type');
		if (!$this->type)
		{
		    redirect('login');
		    die();
		}
	}
	function index(){
	    $this->load->view('index');
	}
	function self_update($key,$id,$table){
	    if ($this->type!="A"){
	        $ddd=$this->db->where($key,$id)->get($table)->result();
	        if ($ddd[0]->bagent!=$this->session->userdata('emp_id')){
	            echo "Sorry other's agent's data cannot be updated";die();
	        }else return true;
	    }else return true;
	}

	function repair_privileges()
	{
	    $modules=$this->privileges_module();
	
	    $this->db->select('emp_id');
	    $r=$this->db->where('status',1)->get('employee');
	    $i=0;
	    $j=0;
	    $k=0;
	    foreach ($r->result() as $user)
	    {
	        foreach ($modules as $mod=>$val)
	        {
	            $where=array(
	                'module'=>$mod,
	                'emp_id'=>$user->emp_id
	            );
	            if($this->db->where($where)->get('user_privileges')->num_rows()<1)
	            {
	                $data['emp_id']=$user->emp_id;
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
	        "emp_id"=>$this->session->userdata('emp_id'),
	        "module"=>$module,
	        $function=>1
	    );
	    $this->db->where($where);
	    if($this->db->get('user_privileges')->num_rows()<1)
	    {
	        if ($function=='view') {
	            $module=ucfirst($module);
	            echo "<br><br><div class='col-sm-6 col-sm-offset-3 alert alert-danger'><b>$module</b> Privileges not assigned by administrator</div>";die();
	            echo json_encode(array());die();
	        }else{
	            echo "Privilege Not Assigned";die();
	        }
	    }
	    else
	        return true;
	}
	
	function privileges_module()//static modules assigned for privileges
	{
	    return $module=array(
	        "document_details"=>"Document Details",
	        "category"=>"Category",
	        "vendors"=>"Vendors",
	        "vehicle_reg"=>"Vehicle Registration",
	        "drivers"=>"Drivers",
	        "hotels"=>"Hotels",
	        "hotels_room"=>"Hotels Room",
	        "itinenary"=>"Itinenary Master",
	        "locations"=>"Locations Master",
	        "destinations"=>"Destinations Master",
	        "journals"=>"Payment & Expenses",
	        "customer"=>"Customer Bookings",
	        "report1"=>"Report 1",
	        "report2"=>"Report 2",
	        "report3"=>"Report 3",
	    );
	}
	
	function view_privileges_json(){
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
	    $data['emp_id']=$_POST['emp_id'];
	    // 	    $data['com_id']=$this->data['com_id'];
	     
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
	                                            $where['emp_id']=$_POST['emp_id'];
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
	    if (isset($_GET['id']) && $_GET['id']){
	        $data['emp_id']=$_GET['id'];
	        $result = $this->mdl_user_privileges->view_data($data);
	        $this->output->set_content_type('application/json')->set_output(json_encode($result->result_array()));
	    }
	}
	function check_module_privileges($module)//based on view_data status
	{
	    $data['module']=$module;
	    $data['view_data']=1;
	    $data['username']=$this->session->userdata('username');
	    return $this->mdl_user_privileges->check_module_privileges($data);
	}
}
?>
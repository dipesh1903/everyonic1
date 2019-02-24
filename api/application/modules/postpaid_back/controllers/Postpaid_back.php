<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Postpaid_back extends MX_Controller
{
    //wGtRkO8VoEyUjS
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mdl_postpaid_back');
        //privileges
        //         $this->module="master_management_back";
        //         $this->load->module('user_privileges');
    }
    //    function index()
    //    {
    //        $this->load->module('login');
    //        if($this->login->auth())
        //        {
        //            //check user privileges
        //            if(!$this->user_privileges->check_module_privileges($this->module))
            //                echo 2;//privilege error
            //            else
                //                echo 1;//success
                //        }else{
                //            echo 0;//logout
                //        }
                //    }
            function save()
            {
                //         print_r($_POST);die();
                $this->load->library('form_validation');
                $this->form_validation->set_rules("amount", "Amount", "required|trim");
                $this->form_validation->set_rules("operator", "Operator", "required|trim");
                $this->form_validation->set_rules("number", "Mobile Number", "required|trim");
                
                if ($this->form_validation->run() == TRUE)
                {
                    $val['amount'] = $this->input->post('amount');
                    $val['operator'] = $this->input->post('operator');
                    $val['number'] = $this->input->post('number');
                    $val['mem_id'] = '1001';
                    $val['mem_type'] = 'ms';
                    $val['com_id'] = 'c101';
                    
                    //             print_r($_POST);die();
                    if ($this->input->post('pp_id'))
                    {
                        //                 $function="update";
                        //                 $this->user_privileges->check_privilege($this->module,$function);
                        $where['id'] = $this->input->post('pp_id');
                        if($this->input->post('status'))
                            $val['status']= $this->input->post('status');
                            echo $this->Mdl_postpaid_back->update_data($where,$val);
                            
                    }
                    else // add
                    {
                        //                 $function="add";
                        //                 $this->user_privileges->check_privilege($this->module,$function);
                        
                        echo $this->Mdl_postpaid_back->add_data($val);
                    }
                }
                else
                {
                    echo validation_errors();
                }
            }
            //     function view()
            //     {
            //         $where=null;
            
            //         if (isset($_GET['mb_id']))
                //             $where['mb_id']=$_GET['mb_id'];
            
                //         if (isset($_GET['data']))
                    // 	        $select=$_GET['data'];
                    // 	    else $select="*";
                
                    // 	    $return=$this->Mdl_postpaid_back->view_data($where,$select);
                    //         $this->output->set_content_type('application/json')->set_output(json_encode($return->result_array()));
                    //     }
                    //     function delete()
                    //     {
                    //         if (isset($_GET['mb_id']) && $_GET['mb_id'])
                        //         {
                        //             $where['mb_id']=$_GET['mb_id'];
                        //             $object=json_encode($this->Mdl_postpaid_back->view_data($where,"*")->result());
                        //             $data_title= "Member Deleted";
                    
                        //             $this->load->module("logs");
                        //             if ($this->logs->add_data($data_title,$object)) {
                        //                 echo $this->Mdl_postpaid_back->delete_data($where);
                        //             }
                        //         }
                    //     }
                    
                    
}
?>

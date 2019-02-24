<?php if (! defined('BASEPATH')) exit('No direct script access allowed');
class Hr_report extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
//      privileges
        $this->module="hr_grades";
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
    function get_daily()
    {
        if ($this->input->post('type')=="AR")
        {
            $this->attendance();
        }
        elseif ($this->input->post('type')=="LR")
        {
            $this->get_leave_report();
        }
        elseif ($this->input->post('type')=="PE")
        {
            $this->get_expanse();
        }
        elseif ($this->input->post('type')=="FR")
        {
            $this->get_follow_report();
        }
        elseif ($this->input->post('type')=="PR")
        {
            $this->get_pay_slip_report();
        }
    }

    function attendance()
    {
//          print_r($_POST);die();
        $data['hr_attendance_head.com_id'] = $this->data['com_id'];
        $data['hr_attendance_head.branch_id']=$this->data['branch_id'];
        $match = null;
        $where = "";
        $and = false;
        $text="";
        if (@$_POST['grade'] && $_POST['grade'] != "ALL")
        {
            $data['hr_staff_details.grade'] = $_POST['grade'];
            $text.=" Grade : ".$_POST['grade']."<br>";
        }
        if ($this->input->post('status')=='0')
        {
            $data['hr_attendance.status'] = $_POST['status'];
            $text.=" Status: ".$_POST['status']."<br>";
        }
        if ($this->input->post('status')=='1')
        {
            $data['hr_attendance.status'] = $_POST['status'];
            $text.=" Status: ".$_POST['status']."<br>";
        }
        if (@$_POST['emp_id'] && $_POST['emp_id'] != "ALL")
        {
            $data['hr_attendance.emp_id'] = $_POST['emp_id'];
            $text.=" Emp ID: ".$_POST['emp_id']."<br>";
        }
//         print_r($data);die();
        if (@$_POST['day']) 
        {
            $text.=" for: ".$_POST['day']."<br>";
            $data['hr_attendance.date'] = $_POST['day'];
            $this->db->select('hr_attendance.id,hr_attendance.date,hr_attendance.status,hr_attendance.duration,hr_attendance.time_in,
                                hr_attendance.time_out,hr_staff_details.staff_name,hr_staff_details.grade');
            $this->db->from('hr_attendance');
            $this->db->join('hr_attendance_head', 'hr_attendance_head.head_id = hr_attendance.head_id');
            $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_attendance.emp_id');
//             $this->db->join('grade_master', 'grade_master.grade_id = employee.grade_id');
            if ($data)
                $this->db->where($data);
            $this->db->order_by('hr_attendance.date',"desc");
//             echo $this->db->get_compiled_select();die();
            $this->daily_view($this->db->get(),$text);
        }
        if (@$_POST['sdate'] && @$_POST['edate']) 
        {
            $sdate = $_POST['sdate'];
            $edate = $_POST['edate'];
            $text.=" from $sdate to $edate<br>";
            
            if ($sdate != null && $edate != null) 
            {
                $dates_in = $this->get_dates_pattern($sdate, $edate);
                if (sizeof($dates_in) > 0) {
                    // $this->db->order_by('result_id','desc');
                    $this->db->where_in("hr_attendance.date", $dates_in);
                   $this->db->select('hr_attendance.id,hr_attendance.date,hr_attendance.status,hr_attendance.duration,hr_attendance.time_in,
                                        hr_attendance.time_out,hr_staff_details.staff_name,hr_staff_details.grade');
                    $this->db->from('hr_attendance');
                    $this->db->join('hr_attendance_head', 'hr_attendance_head.head_id = hr_attendance.head_id');
                    $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_attendance.emp_id');
                    if ($data)
                        // print_r(data);
                        $this->db->where($data);
//                     echo $this->db->get_compiled_select();die();
                    $this->db->order_by('hr_attendance.date',"desc");
                    $this->daily_view($this->db->get(),$text);
                }
            }
        }
        if (@$_POST['month']) 
        {
            $month = array(
                'January' => '01',
                'February' => '02',
                'March' => '03',
                'April' => '04',
                'May' => '05',
                'June' => '06',
                'July' => '07',
                'August' => '08',
                'September' => '09',
                'October' => '10',
                'November' => '11',
                'December' => '12'
            );
            $pattern = $month[$_POST['month']] . "/" . date('Y');
            $text.=" for month of : ".$_POST['month']."<br>";
            $this->db->like('hr_attendance.date', $pattern, 'before');
            $this->db->select('hr_attendance.id,hr_attendance.date,hr_attendance.status,hr_attendance.duration,hr_attendance.time_in,
                                hr_attendance.time_out,hr_staff_details.staff_name,hr_staff_details.grade');
            $this->db->from('hr_attendance');
            $this->db->join('hr_attendance_head', 'hr_attendance_head.head_id = hr_attendance.head_id');
            $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_attendance.emp_id');
            if ($data)
                $this->db->where($data);
//              echo $this->db->get_compiled_select();die();
            $this->db->order_by('hr_attendance.date',"desc");
            $this->daily_view($this->db->get(),$text);
        }
    }

    function get_leave_report()
    {
//         print_r($_POST);die();
        $data['hr_leaves.com_id'] = $this->data['com_id'];
        $data['hr_leaves.branch_id']=$this->data['branch_id'];
        $match = null;
        $where = "";
        $and = false;
        if (@$_POST['grade'] && $_POST['grade'] != "ALL")
            $data['hr_staff_details.grade'] = $_POST['grade'];
        
        if (@$_POST['leave_type'] && $_POST['leave_type'] != "ALL")
            $data['hr_leaves.type'] = $_POST['leave_type'];
        
        if (@$_POST['emp_id'] && $_POST['emp_id'] != "ALL")
            $data['hr_staff_details.emp_id'] = $_POST['emp_id'];
        
        if (@$_POST['day']) 
        {
            $data['hr_leaves.doa'] = $_POST['day'];
            $this->db->select('hr_leaves.type,hr_leaves.doa,hr_leaves.from,hr_leaves.to,hr_leaves.days,hr_leaves.reason,hr_staff_details.staff_name,hr_staff_details.grade');
            $this->db->from('hr_leaves');
            $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_leaves.emp_id');
            if ($data)
                $this->db->where($data);
//             echo $this->db->get_compiled_select();die();
            $this->daily_leave_view($this->db->get());
        }
        if (@$_POST['sdate'] && @$_POST['edate']) 
        {
            $sdate = $_POST['sdate'];
            $edate = $_POST['edate'];
            
            if ($sdate != null && $edate != null)
            {
                $dates_in = $this->get_dates_pattern($sdate, $edate);
                if (sizeof($dates_in) > 0) {
                    $this->db->where_in("hr_leaves.from", $dates_in);
                    $this->db->select('hr_leaves.type,hr_leaves.doa,hr_leaves.from,hr_leaves.to,hr_leaves.days,hr_leaves.reason,hr_staff_details.staff_name,hr_staff_details.grade');
                    $this->db->from('hr_leaves');
                    $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_leaves.emp_id');
                    if ($data)
                        $this->db->where($data);
//                     echo $this->db->get_compiled_select();die();
                    $this->daily_leave_view($this->db->get());
                }
            }
        }
        if (@$_POST['month']) {
            $month = array(
                'January' => '01',
                'February' => '02',
                'March' => '03',
                'April' => '04',
                'May' => '05',
                'June' => '06',
                'July' => '07',
                'August' => '08',
                'September' => '09',
                'October' => '10',
                'November' => '11',
                'December' => '12'
            );
            $pattern = $month[$_POST['month']] . "/" . date('Y');
            $this->db->like('hr_leaves.from', $pattern, 'before');
            $this->db->select('hr_leaves.type,hr_leaves.doa,hr_leaves.from,hr_leaves.to,hr_leaves.days,hr_leaves.reason,hr_staff_details.staff_name,hr_staff_details.grade');
            $this->db->from('hr_leaves');
            $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_leaves.emp_id');
            if($data)   
                $this->db->where($data);
//             echo $this->db->get_compiled_select();die();
            $this->daily_leave_view($this->db->get());
        }
    }
    function get_expanse()
    {
        $data['hr_journals.com_id'] = $this->data['com_id'];
        $data['hr_journals.branch_id']=$this->data['branch_id'];
        $match = null;
        $where = "";
        $and = false;
    
        //if ($this->input->post('title'))
        // $this->db->like('title',$this->input->post('title'),"both");
    
        if (@$_POST['cat_id'] && $_POST['cat_id'] != "ALL")
        {
            $data['cat_id'] = $_POST['cat_id'];
        }
        if (@$_POST['tran_type'] && $_POST['tran_type'] != "ALL")
        {
            if ($_POST['tran_type']=="d")
            {
                $data['debit!=']=0;
            }
            elseif ($_POST['tran_type']=="c")
            {
                $data['credit!=']=0;
            }
        }
        if (@$_POST['emp_id'] && $_POST['emp_id'] != "ALL")
            $data['hr_staff_details.emp_id'] = $_POST['emp_id'];
    
        if (@$_POST['day'])
        {
            $data['hr_journals.date'] = $_POST['day'];
            $this->db->select('hr_journals.title,hr_journals.desc,hr_journals.debit,hr_journals.credit,hr_journals.date,hr_journals.timestamp,hr_staff_details.staff_name,hr_staff_details.grade');
            $this->db->from('hr_journals');
            $this->db->join('hr_staff_details','hr_staff_details.emp_id = hr_journals.user');
            if($data)
                $this->db->where($data);
            //echo $this->db->get_compiled_select();die();
            $this->daily_expanse_view($this->db->get());
        }
        if (@$_POST['sdate'] && @$_POST['edate']) {
            $sdate = $_POST['sdate'];
            $edate = $_POST['edate'];
    
            if ($sdate != null && $edate != null) {
                $dates_in = $this->get_dates_pattern($sdate, $edate);
                if (sizeof($dates_in) > 0) {
                    $this->db->where_in("date", $dates_in);
                    $this->db->select('hr_journals.title,hr_journals.desc,hr_journals.debit,hr_journals.credit,hr_journals.date,hr_journals.timestamp,hr_staff_details.staff_name,hr_staff_details.grade');
                    $this->db->from('hr_journals');
                    $this->db->join('hr_staff_details','hr_staff_details.emp_id = hr_journals.user');
                    if ($data)
                        $this->db->where($data);
                    // echo $this->db->get_compiled_select();die();
                    $this->daily_expanse_view($this->db->get());
                }
            }
        }
        if (@$_POST['month']) {
            $month = array(
                'January' => '01',
                'February' => '02',
                'March' => '03',
                'April' => '04',
                'May' => '05',
                'June' => '06',
                'July' => '07',
                'August' => '08',
                'September' => '09',
                'October' => '10',
                'November' => '11',
                'December' => '12'
            );
            $pattern = $month[$_POST['month']] . "/" . date('Y');
            $this->db->like('date', $pattern, 'both');
            $this->db->select('hr_journals.title,hr_journals.desc,hr_journals.debit,hr_journals.credit,hr_journals.date,hr_journals.timestamp,hr_staff_details.staff_name,hr_staff_details.grade');
            $this->db->from('hr_journals');
            $this->db->join('hr_staff_details','hr_staff_details.emp_id = hr_journals.user');
            if ($data)
                $this->db->where($data);
            //echo $this->db->get_compiled_select();die();
            $this->daily_expanse_view($this->db->get());
        }
    }
    
    function get_follow_report()
    {
//         print_r($_POST);die();
        $data['hr_follow_up.com_id'] = $this->data['com_id'];
        $data['hr_follow_up.branch_id']=$this->data['branch_id'];
        $match = null;
        $where = "";
        $and = false;
        if (@$_POST['grade'] && $_POST['grade'] != "ALL")
            $data['hr_staff_details.grade'] = $_POST['grade'];
    
        if (@$_POST['title'] && $_POST['title'] != "ALL")
            $data['hr_follow_up.activity'] = $_POST['title'];
        
        if (@$_POST['task_title'] && $_POST['task_title'] != "ALL")
            $data['hr_follow_up.title'] = $_POST['task_title'];
    
        if (@$_POST['emp_id'] && $_POST['emp_id'] != "ALL")
            $data['hr_staff_details.emp_id'] = $_POST['emp_id'];
        
        if (@$_POST['day']) {
            $data['hr_follow_up.date'] = $_POST['day'];
            $this->db->select('hr_follow_up.activity,hr_follow_up.title,hr_follow_up.notes,hr_follow_up.rem_date,hr_follow_up.rem_time,hr_staff_details.staff_name,hr_staff_details.grade');
            $this->db->from('hr_follow_up');
            $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_follow_up.user');
            if ($data)
                $this->db->where($data);
//               echo $this->db->get_compiled_select();die();
            $this->daily_follow_up_view($this->db->get());
        }
        if (@$_POST['sdate'] && @$_POST['edate']) {
            $sdate = $_POST['sdate'];
            $edate = $_POST['edate'];
            if ($sdate != null && $edate != null) {
                $dates_in = $this->get_dates_pattern($sdate, $edate);
                if (sizeof($dates_in) > 0) {
                    // $this->db->order_by('result_id','desc');
                    $this->db->where_in("hr_follow_up.date", $dates_in);
                    $this->db->select('hr_follow_up.activity,hr_follow_up.title,hr_follow_up.notes,hr_follow_up.rem_date,hr_follow_up.rem_time,hr_staff_details.staff_name,hr_staff_details.grade');
                    $this->db->from('hr_follow_up');
                    $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_follow_up.user');
                    if ($data)
                        $this->db->where($data);
                    // print_r(data);
//                     echo $this->db->get_compiled_select();die();
                    $this->daily_follow_up_view($this->db->get());
                }
            }
        }
        if (@$_POST['month']) {
            $month = array(
                'January' => '01',
                'February' => '02',
                'March' => '03',
                'April' => '04',
                'May' => '05',
                'June' => '06',
                'July' => '07',
                'August' => '08',
                'September' => '09',
                'October' => '10',
                'November' => '11',
                'December' => '12'
            );
            $pattern = $month[$_POST['month']] . "/" . date('Y');
            $this->db->like('hr_follow_up.date', $pattern, 'before');
            $this->db->select('hr_follow_up.activity,hr_follow_up.title,hr_follow_up.notes,hr_follow_up.rem_date,hr_follow_up.rem_time,hr_staff_details.staff_name,hr_staff_details.grade');
            $this->db->from('hr_follow_up');
            $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_follow_up.user');
            if($data)
                $this->db->where($data);
//             echo $this->db->get_compiled_select();die();
            $this->daily_follow_up_view($this->db->get());
        }
    }
    
    function get_salary_report()
    {
//         print_r($_POST);die();
        $data['hr_staff_details.com_id'] =$this->data['com_id'];
        $data['hr_staff_details.branch_id'] =$this->data['branch_id'];
        $match = null;
        $where = "";
        $and = false;
        if (@$_POST['grade'] && $_POST['grade'] != "ALL")
            $data['hr_staff_details.grade'] = $_POST['grade'];
    
        if (@$_POST['designation'] && $_POST['designation'] != "ALL")
            $data['hr_staff_details.designation'] = $_POST['designation'];
        
        if($this->input->post('min_salary')&&$this->input->post('max_salary'))
        {
            $data['hr_staff_details.b_salary >=']=$this->input->post('min_salary');
            $data['hr_staff_details.b_salary <=']=$this->input->post('max_salary');
        }
    
        if (@$_POST['emp_id'] && $_POST['emp_id'] != "ALL")
            $data['hr_staff_details.emp_id'] = $_POST['emp_id'];
        
        if (@$_POST['city'])
            $this->db->like('hr_staff_details.city',$this->input->post('city'),'both');
        
        if (@$_POST['state'])
            $this->db->like('hr_staff_details.state',$this->input->post('state'),'both');
    
        $this->db->select('*');
        $this->db->from('hr_staff_details');
        if ($data)
            $this->db->where($data);
//         echo $this->db->get_compiled_select();die();
        $this->daily_salary_view($this->db->get());

    }
    
    function get_pay_slip_report()
    {
//          print_r($_POST);die();
        $data['hr_payslip.com_id'] =$this->data['com_id'];
        $data['hr_payslip.branch_id'] =$this->data['branch_id'];
        $match = null;
        $where = "";
        $and = false;
        if (@$_POST['grade'] && $_POST['grade'] != "ALL")
            $data['hr_staff_details.grade'] = $_POST['grade'];
    
        if (@$_POST['designation'] && $_POST['designation'] != "ALL")
            $data['hr_staff_details.designation'] = $_POST['designation'];
    
        if (@$_POST['emp_id'] && $_POST['emp_id'] != "ALL")
            $data['hr_staff_details.emp_id'] = $_POST['emp_id'];
    
        if (@$_POST['day']) {
            $data['hr_payslip.pay_date'] = $_POST['day'];
            $this->db->select('hr_payslip.month,hr_payslip.year,hr_payslip.pay_total,hr_payslip.net_deduction,hr_payslip.absent_deduction,
                                hr_payslip.work_days,hr_payslip.present,hr_payslip.absent,hr_payslip.key1,hr_payslip.key2,hr_payslip.value1,
                                hr_payslip.value2,hr_payslip.pay_mode,hr_payslip.ref_no,hr_payslip.remarks,hr_staff_details.staff_name,
                                hr_staff_details.grade,hr_staff_details.designation,hr_staff_details.email');
            $this->db->from('hr_payslip');
            $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_payslip.emp_id');
            if ($data)
                $this->db->where($data);
//               echo $this->db->get_compiled_select();die();
            $this->daily_payslip_view($this->db->get());
        }
        if (@$_POST['sdate'] && @$_POST['edate']) {
            $sdate = $_POST['sdate'];
            $edate = $_POST['edate'];
            if ($sdate != null && $edate != null) {
                $dates_in = $this->get_dates_pattern($sdate, $edate);
                if (sizeof($dates_in) > 0) {
                    // $this->db->order_by('result_id','desc');
                    $this->db->where_in("hr_payslip.pay_date", $dates_in);
                    $this->db->select('hr_payslip.month,hr_payslip.year,hr_payslip.pay_total,hr_payslip.net_deduction,hr_payslip.absent_deduction,
                                hr_payslip.work_days,hr_payslip.present,hr_payslip.absent,hr_payslip.key1,hr_payslip.key2,hr_payslip.value1,
                                hr_payslip.value2,hr_payslip.pay_mode,hr_payslip.ref_no,hr_payslip.remarks,hr_staff_details.staff_name,
                                hr_staff_details.grade,hr_staff_details.designation,hr_staff_details.email');
                    $this->db->from('hr_payslip');
                    $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_payslip.emp_id');
                    if ($data)
                        $this->db->where($data);
                    // print_r(data);
//                     echo $this->db->get_compiled_select();die();
                    $this->daily_payslip_view($this->db->get());
                }
            }
        }
        if (@$_POST['month']) {
            $month = array(
                'January' => '01',
                'February' => '02',
                'March' => '03',
                'April' => '04',
                'May' => '05',
                'June' => '06',
                'July' => '07',
                'August' => '08',
                'September' => '09',
                'October' => '10',
                'November' => '11',
                'December' => '12'
            );
            $pattern = $month[$_POST['month']] . "/" . date('Y');
            $this->db->like('hr_payslip.pay_date', $pattern, 'before');
            $this->db->select('hr_payslip.month,hr_payslip.year,hr_payslip.pay_total,hr_payslip.net_deduction,hr_payslip.absent_deduction,
                                hr_payslip.work_days,hr_payslip.present,hr_payslip.absent,hr_payslip.key1,hr_payslip.key2,hr_payslip.value1,
                                hr_payslip.value2,hr_payslip.pay_mode,hr_payslip.ref_no,hr_payslip.remarks,hr_staff_details.staff_name,
                                hr_staff_details.grade,hr_staff_details.designation,hr_staff_details.email');
            $this->db->from('hr_payslip');
            $this->db->join('hr_staff_details', 'hr_staff_details.emp_id = hr_payslip.emp_id');
            if($data)
                $this->db->where($data);
            //print_r($data);
//             echo $this->db->get_compiled_select();die();
            $this->daily_payslip_view($this->db->get());
        }
    }
    
//     function get_daily_task()
//     {
//         // echo "fsd";die();
//         // print_r($_POST);die();
//         $data = null;
//         $match = null;
//         $where = "";
//         $and = false;
//         $text="";
//         if (@$_POST['grade_id'] && $_POST['grade_id'] != "ALL"){
//             $data['employee.grade_id'] = $_POST['grade_id'];
//             $text.=" Grade ID: ".$_POST['grade_id']."<br>";
//         }
//         if (@$_POST['assign_id'] && $_POST['assign_id'] != "ALL"){
//             $this->db->like('task_assigner.title',$_POST['assign_id'],'both');
//             $text.=" Task Title: ".$_POST['assign_id']."<br>";
//         }
//         if (@$_POST['emp_id'] && $_POST['emp_id'] != "ALL"){
//             $data['task.emp_id'] = $_POST['emp_id'];
//             $text.=" Employee ID: ".$_POST['emp_id']."<br>";
//         }
//         // echo $data['attendance.status'];die();
//         // if (@$_POST['search_head'] && @$_POST['text'] && $_POST['text']!="ALL")
//         // {
//         // $key=$_POST['search_head'];
//         // if ($key=="Army No"):
//         // $data["exam_attempt.army_no"]=$_POST['text'];
//         // elseif($key=="Name"):
//         // $this->db->like('name', $_POST['text'], 'both');
//         // endif;
//         // }
//         if (@$_POST['day']) {
//             $text.=" for ".$_POST['day']."<br>";
//             $data['task.end_date'] = $_POST['day'];
//             $this->db->select('employee.name,grade_master.grade_name,task_assigner.title,task.end_date,task.comment,task.duration');
//             $this->db->from('task');
//             $this->db->join('task_assigner', 'task_assigner.assign_id = task.assign_id');
//             $this->db->join('employee', 'employee.emp_id = task.emp_id');
//             $this->db->join('grade_master', 'grade_master.grade_id = employee.grade_id');
//             if ($data)
//                 $this->db->where($data);
//             // echo $this->db->get_compiled_select();die();
//             $this->daily_task_view($this->db->get(),$text);
//         }
//         if (@$_POST['sdate'] && @$_POST['edate']) {
//             $text.=" from ".$_POST['sdate']."-".$_POST['edate']."<br>";
//             $sdate = $_POST['sdate'];
//             $edate = $_POST['edate'];
            
//             if ($sdate != null && $edate != null) {
//                 $dates_in = $this->get_dates_pattern($sdate, $edate);
//                 if (sizeof($dates_in) > 0) {
//                     // $this->db->order_by('result_id','desc');
//                     $this->db->where_in("task.end_date", $dates_in);
//                     $this->db->select('employee.name,grade_master.grade_name,task_assigner.title,task.end_date,task.comment,task.duration');
//                     $this->db->from('task');
//                     $this->db->join('task_assigner', 'task_assigner.assign_id = task.assign_id');
//                     $this->db->join('employee', 'employee.emp_id = task.emp_id');
//                     $this->db->join('grade_master', 'grade_master.grade_id = employee.grade_id');
//                     if ($data)
//                         // print_r(data);
//                         $this->db->where($data);
//                     // echo $this->db->get_compiled_select();die();
//                     $this->daily_task_view($this->db->get(),$text);
//                 }
//             }
//         }
//         if (@$_POST['month']) {
//             $month = array(
//                 'January' => '01',
//                 'February' => '02',
//                 'March' => '03',
//                 'April' => '04',
//                 'May' => '05',
//                 'June' => '06',
//                 'July' => '07',
//                 'August' => '08',
//                 'September' => '09',
//                 'October' => '10',
//                 'November' => '11',
//                 'December' => '12'
//             );
//             $pattern = $month[$_POST['month']] . "/" . date('Y');
//             $text.=" of $pattern<br>";
//             $this->db->like('task.end_date', $pattern, 'before');
//             $this->db->select('employee.name,grade_master.grade_name,task_assigner.title,task.end_date,task.comment,task.duration');
//             $this->db->from('task');
//             $this->db->join('task_assigner', 'task_assigner.assign_id = task.assign_id');
//             $this->db->join('employee', 'employee.emp_id = task.emp_id');
//             $this->db->join('grade_master', 'grade_master.grade_id = employee.grade_id');
//             if ($data)
//                 $this->db->where($data);
//             // print_r(data);
//             // echo $this->db->get_compiled_select();die();
//             $this->daily_task_view($this->db->get(),$text);
//         }
//     }

    function csv()
    {
        if (isset($_GET['csv'])) {
            $this->load->module('dbbackup');
            $filename = "$category-$value-" . date('dmY') . "_data.csv";
            $this->dbbackup->csv_result($data['squery'], $filename);
        }
    }
    function view_staff_detail(){
        echo '<table class="table table-hover">
          <tr>
            <th>Staff Name</th>
            <th>Designation</th> 
            <th>Gender</th> 
            <th>D.O.B</th>
          </tr>
          <tr ng-repeat="d in documents">
            <td>{{d.doc_name}}</td>
            <td>{{d.doc_type}}</td>
            <td>{{d.doc_type}}</td>
            <td>{{d.description}}</td>
          </tr>
        </table>';
    }

    function daily_view($report,$text)
    {
        $total_rows = $report->num_rows();
        if ($total_rows < 1) {
            echo "<br><h3 align='center' style='color:red'>No Data Found..</h3><br><br>";
            die();
        }
//         echo 34343;die();
        
        $table_data = '';
        $chart_name = "";
        $chart_percent = "";
        $pass = 0;
        $fail = 0;
        
        $total_percentage = 0;
        $i = 0;
        $pre = 0;
        $abs = 0;
        $j = 0;
        $total_att = 0;
        // $barchart_data="";
//         print_r($report->result());die();
        foreach ($report->result() as $row) :
        
            $total_att ++;
            if ($row->status == 1) 
            {
                $pre ++;
                $st="present";
            }
            if ($row->status == 0) 
            {
                $abs ++;
                $st="absent";
            }
                $table_data .= "<tr>
            <td>".$row->date."</td>
            <td>".$row->grade."</td>
	        <td>".$row->staff_name."</td>
	        <td>".$row->duration."</td>
	        <td class='danger'>".$st."</td>
	        <td class='info'><a ng-click='document_details($row->id)' data-toggle='modal' data-target='#image_modal'><button>Click</button></a></td>
	        </tr>";
            
            // $chart_name.="'$row->name',";
            // $chart_percent.="$per,";
            // $barchart_data.="['$row->name', $per],";
            // end charts data
            // check if user wants pass data or fail data or terminated data
            $i ++;
        endforeach
        ;
//         print_r($table_data,$total_att);die();
        $chart_data = array(
            "total_att" => $total_att,
            "total_rows" => $total_rows,
            "pre" => $pre,
            "abs" => $abs,
            "fail" => 1,
            "total_percentage" => 1,
            "table_data" => $table_data,
            "text"=>$text,
        );
//         print_r($chart_data);die();
        $this->load->view('attendance_data', $chart_data);
    }

    function daily_leave_view($report)
    {
        $total_rows = $report->num_rows();
        if ($total_rows < 1) {
            echo "<br><h3 align='center' style='color:red'>No Data Found..</h3><br><br>";
            die();
        }
        
        $table_data = '';
        $chart_name = "";
        $chart_percent = "";
        $pass = 0;
        $fail = 0;
        $total_percentage = 0;
        $i = 0;
        // $barchart_data="";
//         print_r($report->result());die();
        foreach ($report->result() as $row) :
            if ($row->type == "1") {
                $table_data .= "<tr>
            <td>".$row->staff_name."</td>
            <td>".$row->grade."</td>
            <td>Casual Leave</td>
            <td>".$row->from."</td>
            <td>".$row->to."</td>
            <td>".$row->days."</td>
            <td>'>".$row->reason."</td>
            </tr>";
            }
            if ($row->type == "2") 
            {
                $table_data .= "<tr>
            <td>".$row->staff_name."</td>
            <td>".$row->grade."</td>
            <td>Sick Leave</td>
            <td>".$row->from."</td>
            <td>".$row->to."</td>
            <td>".$row->days."</td>
            <td>'>".$row->reason."</td>
            </tr>";
            }
            if ($row->type == "3") 
            {
                $table_data .= "<tr>
            <td>".$row->staff_name."</td>
            <td>".$row->grade."</td>
            <td>Privileged/Earned Leave</td>
            <td>".$row->from."</td>
            <td>".$row->to."</td>
            <td>".$row->days."</td>
            <td>'>".$row->reason."</td>
            </tr>";
            }
            if ($row->type == "4") {
                $table_data .= "<tr>
            <td>".$row->staff_name."</td>
            <td>".$row->grade."</td>
            <td>Maternity Leave</td>
            <td>".$row->from."</td>
            <td>".$row->to."</td>
            <td>".$row->days."</td>
            <td>'>".$row->reason."</td>
            </tr>";
            }
            if ($row->type == "5") {
                $table_data .= "<tr>
            <td>".$row->staff_name."</td>
            <td>".$row->grade."</td>
            <td>others Leave</td>
            <td>".$row->from."</td>
            <td>".$row->to."</td>
            <td>".$row->days."</td>
            <td>'>".$row->reason."</td>
            </tr>";
            }
            
            // $chart_name.="'$row->name',";
            // $chart_percent.="$per,";
            // $barchart_data.="['$row->name', $per],";
            // end charts data
            // check if user wants pass data or fail data or terminated data
//             print_r($table_data);die();
            $i ++;
        endforeach
        ;
        
        $chart_data = array(
            "total_rows" => $total_rows,
            "pass" => 1,
            "fail" => 1,
            "total_percentage" => 1,
            "table_data" => $table_data
        );
//         print_r($chart_data);die();
        $this->load->view('leave_chart_view', $chart_data);
    }
    
    function daily_expanse_view($report)
    {
        $total_rows = $report->num_rows();
        if ($total_rows < 1) {
            echo "<br><h3 align='center' style='color:red'>No Data Found..</h3><br><br>";
            die();
        }
        $table_data = '';
        $dr_total=0;$cr_total=0;
//         print_r($report->result());die();
        foreach ($report->result() as $row) :
        $dr_total+=$row->debit;
        $cr_total+=$row->credit;
            $table_data .= "<tr>
            <td>".$row->staff_name."</td>
            <td>".$row->grade."</td>   
            <td>".$row->title."</td>
            <td>".$row->credit."</td>
            <td>".$row->debit."</td>
            <td>".$row->desc."</td>
            </tr>";
        endforeach;
    
        $chart_data = array(
            "total_rows" => $total_rows,
            "table_data" => $table_data,
            "dr_total"=>$dr_total,
            "cr_total"=>$cr_total,
        );
//         print_r($chart_data);die();
        $this->load->view('expense_chart_view', $chart_data);
    }
    
    function daily_follow_up_view($report)
    {
        $total_rows = $report->num_rows();
        if ($total_rows < 1) 
        {
            echo "<br><h3 align='center' style='color:red'>No Data Found..</h3><br><br>";
            die();
        }
        $table_data = '';
//      print_r($report->result());die();
        foreach ($report->result() as $row) :
        $table_data .="<tr>
            <td>".$row->staff_name."</td>
            <td>".$row->grade."</td>
            <td>".$row->activity."</td>
            <td>".$row->title."</td>
            <td>".$row->notes."</td>
            <td>".$row->rem_date." | ".$row->rem_time."</td>
            </tr>";
        endforeach;
    
        $chart_data = array(
            "total_rows" => $total_rows,
            "table_data" => $table_data,
        );
//           print_r($table_data);die();
        $this->load->view('follow_up_chart_view', $chart_data);
    }
    
    function daily_salary_view($report)
    {
        $total_rows = $report->num_rows();
        if ($total_rows < 1)
        {
            echo "<br><h3 align='center' style='color:red'>No Data Found..</h3><br><br>";
            die();
        }
        $table_data = '';
//         print_r($report->result());die();
        foreach ($report->result() as $row) :
        if($row->designation=='1')
        {
        $table_data .="<tr>
            <td>".$row->staff_name."</td>
            <td>".$row->grade."</td>
            <td>Principle</td>
            <td>".$row->b_salary."</td>
            <td>".$row->fathers_name."</td>
            <td>".$row->gender."</td>
            <td>".$row->dob."</td>
            <td>".$row->city."</td>
            <td>".$row->state."</td>
            <td>".$row->contact_no."</td>
            <td>".$row->email."</td>
            </tr>";
       }
       if($row->designation=='2')
       {
           $table_data .="<tr>
            <td>".$row->staff_name."</td>
            <td>".$row->grade."</td>
            <td>Teacher</td>
            <td>".$row->b_salary."</td>
            <td>".$row->fathers_name."</td>
            <td>".$row->gender."</td>
            <td>".$row->dob."</td>
            <td>".$row->city."</td>
            <td>".$row->state."</td>
            <td>".$row->contact_no."</td>
            <td>".$row->email."</td>
            </tr>";
       }
       if($row->designation=='3')
       {
           $table_data .="<tr>
            <td>".$row->staff_name."</td>
            <td>".$row->grade."</td>
            <td>Director</td>
            <td>".$row->b_salary."</td>
            <td>".$row->fathers_name."</td>
            <td>".$row->gender."</td>
            <td>".$row->dob."</td>
            <td>".$row->city."</td>
            <td>".$row->state."</td>
            <td>".$row->contact_no."</td>
            <td>".$row->email."</td>
            </tr>";
       }
       if($row->designation=='4')
       {
           $table_data .="<tr>
            <td>".$row->staff_name."</td>
            <td>".$row->grade."</td>
            <td>Accountant</td>
            <td>".$row->b_salary."</td>
            <td>".$row->fathers_name."</td>
            <td>".$row->gender."</td>
            <td>".$row->dob."</td>
            <td>".$row->city."</td>
            <td>".$row->state."</td>
            <td>".$row->contact_no."</td>
            <td>".$row->email."</td>
            </tr>";
       }
        endforeach;
    
        $chart_data = array(
            "total_rows" => $total_rows,
            "table_data" => $table_data,
        );
        //           print_r($table_data);die();
        $this->load->view('staff_details_chart_view', $chart_data);
    }
    function daily_payslip_view($report)
    {
        $total_rows = $report->num_rows();
        if ($total_rows < 1) {
            echo "<br><h3 align='center' style='color:red'>No Data Found..</h3><br><br>";
            die();
        }
        $table_data = '';
        $dr_total=0;$cr_total=0;
//         print_r($report->result());die();
        foreach ($report->result() as $row) :
            if($row->designation=='1')
                {
                $table_data .="<tr>
                    <td>".$row->staff_name."</td>
                    <td>".$row->grade."</td>
                    <td>Principle</td>
                    <td>".$row->month."</td>
                    <td>".$row->year."</td>
                    <td>".$row->work_days."</td>
                    <td>".$row->present."</td>
                    <td>".$row->absent."</td>
                    <td>".$row->pay_total."</td>
                    <td>".$row->net_deduction."</td>
                    <td>".$row->absent_deduction."</td>
                    <td>".$row->remarks."</td>
                    <td>".$row->email."</td>
                   </tr>";
               }
               if($row->designation=='2')
               {
                   $table_data .="<tr>
                    <td>".$row->staff_name."</td>
                    <td>".$row->grade."</td>
                    <td>Teacher</td>
                    <td>".$row->month."</td>
                    <td>".$row->year."</td>
                    <td>".$row->work_days."</td>
                    <td>".$row->present."</td>
                    <td>".$row->absent."</td>
                    <td>".$row->pay_total."</td>
                    <td>".$row->net_deduction."</td>
                    <td>".$row->absent_deduction."</td>
                    <td>".$row->remarks."</td>
                    <td>".$row->email."</td>
                    </tr>";
               }
               if($row->designation=='3')
               {
                   $table_data .="<tr>
                    <td>".$row->staff_name."</td>
                    <td>".$row->grade."</td>
                    <td>Director</td>
                    <td>".$row->month."</td>
                    <td>".$row->year."</td>
                    <td>".$row->work_days."</td>
                    <td>".$row->present."</td>
                    <td>".$row->absent."</td>
                    <td>".$row->pay_total."</td>
                    <td>".$row->net_deduction."</td>
                    <td>".$row->absent_deduction."</td>
                    <td>".$row->remarks."</td>
                    <td>".$row->email."</td>
                    </tr>";
               }
               if($row->designation=='4')
               {
                   $table_data .="<tr>
                    <td>".$row->staff_name."</td>
                    <td>".$row->grade."</td>
                    <td>Accountant</td>
                    <td>0".$row->month."</td>
                    <td>20".$row->year."</td>
                    <td>".$row->work_days."</td>
                    <td>".$row->present."</td>
                    <td>".$row->absent."</td>
                    <td>".$row->absent_deduction."</td>
                    <td>".$row->net_deduction."</td>
                    <td>".$row->pay_total."</td>
                    <td>".$row->email."</td>
                    <td>".$row->remarks."</td>
                    </tr>";
               }
        endforeach;
    
        $chart_data = array(
            "total_rows" => $total_rows,
            "table_data" => $table_data,
        );
//                 print_r($chart_data);die();
        $this->load->view('payslip_chart_view', $chart_data);
    }
    
    
    
    
    function daily_task_view($report,$text=null)
    {
        $total_rows = $report->num_rows();
        if ($total_rows < 1) {
            echo "<br><h3 align='center' style='color:red'>No Data Found..</h3><br><br>";
            die();
        }
        
        $table_data = '';
        $chart_name = "";
        $chart_percent = "";
        $pass = 0;
        $fail = 0;
        $total_percentage = 0;
        $i = 0;
        $seconds=0;
        // $barchart_data="";
        // print_r($report->result());die();
        foreach ($report->result() as $row) :
            $dur = explode(":", $row->duration);
            $seconds +=  ( $dur[0] * 3600 )      //Hours to Seconds
            +  ( $dur[1] * 60 )        //Minutes to Seconds
            +  ( $dur[2]  );           //Seconds to Seconds
        
        
            if ($dur[0] < 6) {
                
                $duration_st = 'danger';
            }
            if ($dur[0] >= 6 && $dur[0] <= 8) {
                $duration_st = 'success';
            }
            if ($dur[0] > 8) {
                $duration_st = 'warning';
            }
            $table_data .= "<tr>
        <td>$row->name</td>
        <td>$row->grade_name</td>
        <td>$row->title</td>
        <td>$row->comment</td>
        <td>$row->end_date</td>
        <td class='" . $duration_st . "'>$row->duration</td>
        </tr>";
            // $chart_name.="'$row->name',";
            // $chart_percent.="$per,";
            // $barchart_data.="['$row->name', $per],";
            // end charts data
            // check if user wants pass data or fail data or terminated data
            
            $i ++;
        endforeach
        ;
        
        $chart_data = array(
            "total_rows" => $total_rows,
            "pass" => 1,
            "fail" => 1,
            "total_percentage" => 1,
            "table_data" => $table_data,
            "total_seconds"=>$seconds,
            "text"=>$text,
            // "names"=>$chart_name,
            // "percent"=>$chart_percent,
            // "showlabel"=>$_POST['showlabel'],
            // "barchart_data"=>$barchart_data
        );
        $this->load->view('report/task_chart_view', $chart_data);
    }

    function returnDates($sdate, $edate)
    {
        $sdate = \DateTime::createFromFormat('d/m/Y', $sdate);
        $edate = \DateTime::createFromFormat('d/m/Y', $edate);
        return new \DatePeriod($sdate, new \DateInterval('P1D'), $edate->modify('+1 day'));
    }

    function get_dates_pattern($sdate, $edate)
    {
        $datePeriod = $this->returnDates($sdate, $edate);
        $pattern = array();
        foreach ($datePeriod as $date) {
            $dated = array(
                $date->format('d/m/Y')
            );
            $pattern = array_merge($pattern, $dated);
        }
        return $pattern;
    }

    function get_user_report($user_id)
    {
        $this->db->order_by('result_id', 'desc');
        $this->db->like('exam_attempt.date', $pattern, 'before');
        $this->db->select('result.exam_code,department_name,exam_name,result.exam_id,result.army_no,exam_attempt.department_id,date,result_id,name,pass_percentage,percentage,total_question,attempts,correct_question,total_time,time_taken');
        $this->db->from('exam_attempt');
        $this->db->join('result', 'exam_attempt.exam_code = result.exam_code');
        if ($data)
            $this->db->where($data);
        $this->daily_view($this->db->get());
        
        // $this->db->where('army_no')->get('result');
    }

    function csv_result($query, $filename)
    {
        $this->load->dbutil();
        
        $data = $this->dbutil->csv_from_result($query);
        
        $this->load->helper('download');
        
        force_download($filename, $data);
    }
    // Copyright @ Groveus (www.groveus.com)
}
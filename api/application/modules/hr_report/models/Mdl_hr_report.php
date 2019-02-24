<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_hr_follow_up extends CI_Model
{
    private $table;
    
    function __construct()
    {
        parent::__construct();
        $this->table = "hr_follow_up"; //view
//         $this->db->query("CREATE TABLE IF NOT EXISTS `follow_up` (
//               `f_id` int(11) NOT NULL auto_increment,
//               `date` varchar(50) NOT NULL,
//               `title` varchar(200) NOT NULL,
//               `notes` text NOT NULL,
//               `rem_date` varchar(50) NOT NULL,
//               `rem_time` varchar(50) NOT NULL,
//               `user` int(11) NOT NULL,
//               `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
//               PRIMARY KEY  (`f_id`)
//             ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
//             ");
    }
    function view_data($where=null,$select)
    {
        $this->db->select($select);
        if($where)
            $this->db->where($where);
        $this->db->order_by('f_id','desc');
        return $this->db->get( $this->table);
    }
    function add_data($data)
    {
        $a=$this->db->insert($this->table,$data);
        return $this->db->affected_rows($a);
    }
    function update_data($where,$data)
    {
        $this->db->where($where);
        $a=$this->db->update($this->table,$data);
        return $this->db->affected_rows($a);
    }
    function delete_data($where)
    {
        $this->db->where($where);
        $a=$this->db->delete($this->table);
        return $this->db->affected_rows($a);
    }
}
?>
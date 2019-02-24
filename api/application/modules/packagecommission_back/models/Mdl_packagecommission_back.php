<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_packagecommission_back extends CI_Model
{
    private $table;
    
    function __construct()
    {
        parent::__construct();
        $this->table = "package_assigner"; //view
//         $this->db->query("
//              CREATE TABLE IF NOT EXISTS `package` (
//             `pac_id` int(11) NOT NULL auto_increment,
//             `com_id` varchar(50) NOT NULL,
//             `package_name` varchar(50) NOT NULL,
//             `status` smallint(2) NOT NULL default '1',
//             `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
//             PRIMARY KEY  (`pac_id`)
//             ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
//         ");
        
    }
    function view_data($where=null,$select)
    {
        $this->db->select($select);
        if($where)
            $this->db->where($where);
        $this->db->join('service',"ser_id");
        $this->db->order_by('pac_id',"desc");
        return $this->db->get( $this->table);
    }
    function add_data($data)
    {
//         echo 'cscscscs';die();
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
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_user_privileges extends MX_Controller
{
	private $table;
	
	function __construct()
	{
		parent::__construct();
		$this->table = "user_privileges";
// 		$this->db->query("CREATE TABLE IF NOT EXISTS `hr_user_privileges` (
//               `auto_id` int(11) NOT NULL auto_increment,
//               `emp_id` int(11) NOT NULL,
//               `module` varchar(100) NOT NULL,
//               `add` tinyint(1) NOT NULL default '0',
//               `view` tinyint(1) NOT NULL default '0',
//               `update` tinyint(1) NOT NULL default '0',
//               `delete` tinyint(1) NOT NULL default '0',
//               PRIMARY KEY  (`auto_id`)
//             ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
	}
    
    function add_data($data)
	{
	   return $this->db->insert($this->table,$data);
	}
	
	function update_data($where,$values)
	{
	    $this->db->where($where);
	    return $this->db->update($this->table,$values);
	}
	
	
    function view_data($data)
	{
	    $this->db->where($data);
	    $this->db->order_by('auto_id',"desc");
		return $this->db->get($this->table);
	}
	function delete_data($data)
	{
	    $this->db->where($data);
	    return $this->db->delete($this->table);
	}
    function check_module_privileges($where)
	{
	    $this->db->where($where);
	    if($this->db->get($this->table)->num_rows()>0)
	       return true;
	    else return false;
	}
	
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logs_mdl extends CI_Model{
    
    private $table;
    
    function __construct()
    {
        parent::__construct();
        $this->table = "logs";
        $this->db->query("CREATE TABLE IF NOT EXISTS `logs` (
        	  `auto_id` int(11) NOT NULL AUTO_INCREMENT,
        	  `user` varchar(100) NOT NULL,
        	  `date` varchar(100) NOT NULL,
        	  `time` varchar(100) NOT NULL,
        	  `data_title` varchar(100) NOT NULL,
        	  `data` text NOT NULL,
        	  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        	  PRIMARY KEY (`auto_id`)
        	) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=latin1;
                    
        ");
    }
    
    function add_data($data)
    {
        return $this->db->insert($this->table,$data);
    }
    function view_data($where=null,$select)
    {
        $this->db->select($select);
        if($where)
            $this->db->where($where);
        $this->db->order_by('auto_id',"desc");
        return $this->db->get( $this->table);
    }
    //Copyright @ Groveus (www.groveus.com)
}

?>
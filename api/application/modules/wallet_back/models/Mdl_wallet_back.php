<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_wallet_back extends CI_Model
{
    private $table;
    
    function __construct()
    {
        parent::__construct();        
        $this->table = "wallet_customer"; //view
            }
    function view_data($where=null,$select)
    {
        $this->db->select($select);
        if($where)
            $this->db->where($where);
        $this->db->order_by('cus_wal_id',"desc");
        $this->db->order_by('mem_id',"desc");
        $this->db->order_by('mem_typ',"desc");
        $this->db->group_by('mem_id');
        $this->db->group_by('mem_typ');
        $this->db->distinct();
//         $this->db->limit(1);
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
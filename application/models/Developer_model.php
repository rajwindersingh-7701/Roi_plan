<?php

class Developer_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function get_single_record($table, $where, $select) 
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->row_array();
        // echo $this->db->last_query();
        return $res;
    }

    public function update($table, $where, $data)
    {
        $this->db->where($where);
        return $this->db->update($table, $data);
    }
    public function get_records($table, $where, $select)
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->result_array();
        //        echo $this->db->last_query();
        return $res;
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function delete($table, $id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($table);
    }
    

}

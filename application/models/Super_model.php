<?php

class Super_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function get_single_record($table, $where, $select, $queryshow = false)
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->row_array();
        if ($queryshow == true)
            echo $this->db->last_query();
        return $res;
    }

    public function get_records($table, $where, $select)
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->result_array();
        return $res;
    }

    public function get_single_object($table, $where, $select)
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->row_object();
        return $res;
    }

    public function add($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function update($table, $where, $data)
    {
        $this->db->where($where);
        $res = $this->db->update($table, $data);
        //        echo $this->db->last_query();
        return $res;
    }

    public function delete($table, $where)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    public function get_limit_records($table, $where, $select, $limit, $offset, $des_asc, $show = false)
    {
        $this->db->select($select);
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('id', $des_asc);
        $query = $this->db->get($table);
        $res = $query->result_array();
        if ($show == true)
            echo $this->db->last_query();
        return $res;
    }

    public function get_sum($table, $where, $select)
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->row_object();
        return $res->sum;
    }
}

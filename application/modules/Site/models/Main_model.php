<?php

class Main_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_single_record($table, $where, $select) {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->row_array();
//        echo $this->db->last_query();
        return $res;
    }

    public function update_directs($user_id) {
        $this->db->set('directs',  'directs + 1', FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users');
    }

    public function update_business($position, $user_id , $business) {
        $this->db->set($position, $position . ' + '.$business, FALSE);
        $this->db->where(['user_id' => $user_id]);
        $this->db->update('tbl_users');
    }


    public function get_single_record1($table,$select) {
        $this->db->select($select);
        $this->db->order_by('id desc');
        $query = $this->db->get($table);
        $res = $query->row_array();
//        echo $this->db->last_query();
        return $res;
    }

    public function get_records($table, $where, $select) {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->result_array();
//        echo $this->db->last_query();
        return $res;
    }
    public function top_ranks() {
        $this->db->select('user_id,name,directs,image');
        $this->db->from('tbl_users');
        $this->db->order_by('directs' , 'desc');
        $this->db->limit(25);
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }
    public function top_earners() {
        //select ifnull(sum(tbl_income_wallet.amount),0) as total_income , tbl_users.user_id , tbl_users.first_name , tbl_users.name from tbl_income_wallet INNER join tbl_users on tbl_income_wallet.user_id = tbl_users.user_id group by tbl_income_wallet.user_id order by total_income desc limit 20
        $this->db->select('ifnull(sum(tbl_income_wallet.amount),0) as total_income , tbl_users.user_id , tbl_users.name,tbl_users.image');
        $this->db->from('tbl_income_wallet');
        $this->db->join('tbl_users' , 'tbl_users on tbl_income_wallet.user_id = tbl_users.user_id' , 'inner');
        $this->db->where(array('tbl_income_wallet.amount > ' => 0));
        $this->db->group_by('tbl_income_wallet.user_id');
        $this->db->order_by('total_income' , 'desc');
        $this->db->limit(25);
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }

    public function add($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function update($table, $where, $data) {
        $this->db->where($where);
        return $this->db->update($table, $data);
    }

    public function update_count($position, $user_id) {
        $this->db->set($position, $position . ' + 1', FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users');
//        echo $this->db->last_query();
    }

}

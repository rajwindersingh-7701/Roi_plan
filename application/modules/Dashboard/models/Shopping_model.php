<?php

class Shopping_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get_single_record($table, $where, $select) {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->row_array();
        return $res;
    }

    public function get_records($table, $where, $select) {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->result_array();
//        echo $this->db->last_query();
        return $res;
    }

    public function add($table, $data) {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function update($table, $where, $data) {
        $this->db->where($where);
        $res = $this->db->update($table, $data);
//        echo $this->db->last_query();
        return $res;
    }   
    public function update_business($position, $user_id , $business) {
        $this->db->set($position, $position . ' + '.$business, FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users');
    //    echo $this->db->last_query();
    }

    public function get_product() {
        $this->db->select('tbl_products.* ,ifnull(tbl_product_images.image,"no_image.png") as image');
        $this->db->from('tbl_products');
        $this->db->group_by('tbl_products.id');
        $this->db->join('tbl_product_images', 'tbl_products.id = tbl_product_images.product_id', 'left');
        $query = $this->db->get();
        $res = $query->result_array();
        // echo $this->db->last_query();
        return $res;
    }

    public function cart_items($user_id) {
        $this->db->select('tbl_cart.quantity,tbl_cart.id as cart_id,tbl_products.*,ifnull(tbl_product_images.image,"no_image.png") as image');
        $this->db->from('tbl_cart');
        $this->db->group_by('tbl_cart.id');
        $this->db->join('tbl_products', 'tbl_cart.product_id = tbl_products.id');
        $this->db->join('tbl_product_images', 'tbl_cart.product_id = tbl_product_images.product_id', 'left');
        $this->db->where(array('tbl_cart.user_id' => $user_id));
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }
    public function order_details($order_id) {
        $this->db->select('tbl_order_details.quantity,tbl_products.*,ifnull(tbl_product_images.image,"no_image.png") as image');
        $this->db->from('tbl_order_details');
        $this->db->group_by('tbl_order_details.id');
        $this->db->join('tbl_products', 'tbl_order_details.product_id = tbl_products.id');
        $this->db->join('tbl_product_images', 'tbl_order_details.product_id = tbl_product_images.product_id', 'left');
        $this->db->where(array('tbl_order_details.order_id' => $order_id));
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }

    public function delete($table,$where) {
        $this->db->where($where);
        return $this->db->delete($table);
    }

}

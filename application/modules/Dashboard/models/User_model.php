<?php

class User_model extends CI_Model
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

    public function updatePaidTeam($field, $user_id, $package)
    {
        $this->db->set($field,  $field . ' + 1', FALSE);
        $this->db->where(['user_id !=' => $user_id, 'paid_status' => 1, 'package_amount' => $package]);
        $this->db->update('tbl_users');
    }

    public function get_single_record_status($table, $where, $select)
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->num_rows();
        return $res;
    }

    public function get_records_desc($table, $where, $select)
    {
        $this->db->select($select);
        $this->db->where($where);
        $this->db->order_by('created_at', 'asc');
        $query = $this->db->get($table);
        $res = $query->result_array();
        echo $this->db->last_query();
        return $res;
    }
    public function get_matrix_pacakges()
    {
        $this->db->select('*');
        $this->db->order_by('price', 'asc');
        $query = $this->db->get('tbl_matrix_packages');
        $res = $query->result_array();
        return $res;
    }

    public function get_records_csv_desc($table, $where, $select)
    {
        $this->db->select($select);
        $this->db->where($where);
        $this->db->order_by('Mid', 'asc');
        $query = $this->db->get($table);
        $res = $query->result_array();
        echo $this->db->last_query();
        return $res;
    }
    public function count_cookies($user_id)
    {
        $this->db->select('ifnull(count(id),0) as cookie_count,sponser_refferal_count');
        $this->db->where(array('sponser_id' => $user_id));
        $this->db->from('tbl_users');
        $this->db->group_by('sponser_refferal_count');
        $query = $this->db->get();
        $res = $query->result_array();
        //        echo $this->db->last_query();
        return $res;
    }
    public function calculate_team($user_id, $status)
    {
        $this->db->select('ifnull(count(tbl_sponser_count.downline_id),0) as team, tbl_users.paid_status');
        $this->db->from('tbl_users');
        $this->db->join('tbl_sponser_count', 'tbl_users.user_id = tbl_sponser_count.downline_id');
        $this->db->where(array('tbl_sponser_count.user_id' => $user_id, 'tbl_users.paid_status' => $status, 'tbl_sponser_count.level !=' => 1));
        $query = $this->db->get();
        $res = $query->row_array();
        return $res;
    }

    public function calculate_Position_team($user_id, $status, $position)
    {
        $this->db->select('ifnull(count(tbl_sponser_count.downline_id),0) as team, tbl_users.paid_status,tbl_users.position');
        $this->db->from('tbl_users');
        $this->db->join('tbl_sponser_count', 'tbl_users.user_id = tbl_sponser_count.downline_id');
        $this->db->where(array('tbl_sponser_count.user_id' => $user_id, 'tbl_users.paid_status' => $status, 'tbl_users.position =' => $position));
        $query = $this->db->get();
        $res = $query->row_array();
        return $res;
    }

    public function get_user_package_commison($user_id)
    {
        $this->db->select('tbl_user_positions.sponser_id,tbl_package.commision');
        $this->db->from('tbl_user_positions');
        $this->db->join('tbl_package', 'tbl_user_positions.package = tbl_package.id');
        $this->db->where(array('tbl_user_positions.user_id' => $user_id));
        $query = $this->db->get();
        $res = $query->row_array();
        //        echo $this->db->last_query();
        return $res;
    }
    public function update_directs($user_id)
    {
        $this->db->set('directs',  'directs + 1', FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users');
    }
    public function update_package_directs($table, $where)
    {
        $this->db->set('directs',  'directs + 1', FALSE);
        $this->db->where($where);
        $this->db->update($table);
    }
    public function get_single_object($table, $where, $select)
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->row_object();
        //        echo $this->db->last_query();
        return $res;
    }

    //     public function get_tree_user($user_id) {
    //         $this->db->select('tbl_user_positions.user_id,tbl_user_positions.sponser_id,tbl_user_positions.upline_id,tbl_user_positions.created_at as topup_date,tbl_user_positions.position,tbl_user_positions.left_node,tbl_user_positions.right_node,tbl_user_positions.left_count,tbl_user_positions.right_count,tbl_users.first_name,tbl_users.last_name,tbl_users.courtesy_title,tbl_users.email,tbl_users.created_at as joining_date,tbl_package.commision');
    //         $this->db->from('tbl_user_positions');
    //         $this->db->join('tbl_users', 'tbl_user_positions.user_id = tbl_users.user_id');
    //         $this->db->join('tbl_package', 'tbl_user_positions.package = tbl_package.id');
    //         $this->db->where(array('tbl_user_positions.user_id' => $user_id));
    //         $query = $this->db->get();
    //         $res = $query->row_object();
    // //        echo $this->db->last_query();
    //         return $res;
    //     }
    public function get_tree_user($user_id)
    {
        $this->db->select('tbl_users.user_id,tbl_users.name,tbl_users.sponser_id,tbl_users.upline_id,tbl_users.position,tbl_users.left_node,tbl_users.right_node,tbl_users.left_count,tbl_users.right_count,tbl_users.leftPower,tbl_users.rightPower,tbl_users.leftBusiness,tbl_users.rightBusiness,tbl_users.package_amount,tbl_users.email,tbl_users.created_at');
        $this->db->from('tbl_users');
        $this->db->where(array('tbl_users.user_id' => $user_id));
        $query = $this->db->get();
        $res = $query->row_object();
        //    echo $this->db->last_query();
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

    public function update_count($position, $user_id)
    {
        $this->db->set($position, $position . ' + 1', FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users');
        //        echo $this->db->last_query();
    }
    public function update_business($position, $user_id, $business)
    {
        $this->db->set($position, $position . ' + ' . $business, FALSE);
        $this->db->where(['user_id' => $user_id]);
        $this->db->update('tbl_users');
    }

    public function get_single_record1($table, $select)
    {
        $this->db->select($select);
        $this->db->order_by('id desc');
        $query = $this->db->get($table);
        $res = $query->row_array();
        //        echo $this->db->last_query();
        return $res;
    }

    public function update_bv($position, $user_id, $bv)
    {
        $this->db->set($position, $position . ' + ' . $bv, FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_user_positions');
        //        echo $this->db->last_query();
    }

    public function user_chat($user_id)
    {
        $this->db->select('tbl_support_message.*,tbl_users.first_name,tbl_users.last_name,tbl_users.image');
        $this->db->from('tbl_support_message');
        $this->db->join('tbl_users', 'tbl_support_message.user_id = tbl_users.user_id', 'inner');
        $this->db->where(array('tbl_support_message.user_id' => $user_id));
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }
    public function magic_users()
    {
        $this->db->select('sum(amount) as total_amount,user_id');
        $this->db->from('tbl_repurchase_income');
        $this->db->having('total_amount > ', 3600);
        $this->db->group_by('user_id');
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }
    public function get_limit_records($table, $where, $select, $limit, $offset, $show = false)
    {
        $this->db->select($select);
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($table);
        $res = $query->result_array();
        if ($show == true)
            echo $this->db->last_query();
        return $res;
    }

    public function getLimitRecords($table, $where, $select, $show = false)
    {
        $this->db->select($select);
        $this->db->where($where);
        $this->db->limit(10);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($table);
        $res = $query->result_array();
        if ($show == true)
            echo $this->db->last_query();
        return $res;
    }

    public function getLimitRecords5($table, $where, $select, $show = false)
    {
        $this->db->select($select);
        $this->db->where($where);
        $this->db->limit(5);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get($table);
        $res = $query->result_array();
        if ($show == true)
            echo $this->db->last_query();
        return $res;
    }

    public function user_packages($user_id)
    {
        $this->db->select('tbl_package.*');
        $this->db->from('tbl_package');
        $this->db->join('tbl_users_packages', 'tbl_package.id = tbl_users_packages.package_id', 'inner');
        $this->db->where(array('tbl_users_packages.user_id' => $user_id));
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }

    public function pool_packages($user_id)
    {
        $this->db->select('tbl_matrix_packages.*');
        $this->db->from('tbl_matrix_packages');
        $this->db->join('tbl_matrix_users', 'tbl_matrix_packages.id = tbl_matrix_users.pool_id', 'inner');
        $this->db->where(array('tbl_matrix_users.user_id' => $user_id));
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }


    public function payout_sum($table, $select)
    {
        $this->db->select($select);
        $this->db->group_by('date(created_at)');
        $query = $this->db->get($table);
        $res = $query->result_array();
        return $res;
    }
    public function payout_sum2($table, $where, $select)
    {
        $this->db->select($select);
        $this->db->group_by('date(created_at)');
        $this->db->where($where);
        $query = $this->db->get($table);
        $res = $query->result_array();
        return $res;
    }

    public function payout_summary($where, $limit, $offset)
    {
        $this->db->select('date(created_at) as date');
        $this->db->group_by('date');
        $this->db->limit($limit, $offset);
        $query = $this->db->get_where('tbl_income_wallet', $where);
        $res = $query->result_array();
        return $res;
    }

    public function week_summary()
    {
        $this->db->select('WEEK(created_at)%MONTH(created_at)+1 as date, WEEK(created_at)+1 as week, year(created_at) as year');
        $this->db->group_by('date');
        $query = $this->db->get('tbl_income_wallet');
        $res = $query->result_array();
        return $res;
    }

    public function get_incomes($table, $where, $select)
    {
        $this->db->select($select);
        $this->db->group_by('type');
        $query = $this->db->get_where($table, $where);
        $res = $query->result_array();
        return $res;
    }

    public function get_sum($table, $where, $select)
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->row_object();
        return $res->sum;
    }

    public function getBusiness($user_id)
    {
        $this->db->select('ifnull(sum(tbl_users.total_package),0) as teamBusiness ');
        $this->db->from('tbl_sponser_count');
        $this->db->join('tbl_users', 'tbl_sponser_count.downline_id = tbl_users.user_id', 'inner');
        $this->db->where(array('tbl_sponser_count.user_id' => $user_id));
        $query = $this->db->get();
        $res = $query->row_array();
        return $res;
    }


    public function getLevelMember($user_id)
    {
        $this->db->select('count(downline_id) as team,level');
        $this->db->where(['user_id' => $user_id]);
        $this->db->group_by('level');
        $query = $this->db->get('tbl_sponser_count');
        $result = $query->result_array();
        return $result;
    }

    public function getLevelBusiness($user_id, $level)
    {
        $this->db->select('ifnull(sum(tbl_users.package_amount),0) as teamBusiness ');
        $this->db->from('tbl_sponser_count');
        $this->db->join('tbl_users', 'tbl_sponser_count.downline_id = tbl_users.user_id', 'inner');
        $this->db->where(array('tbl_sponser_count.user_id' => $user_id, 'level' => $level));
        $query = $this->db->get();
        $res = $query->row_array();
        return $res;
    }

    public function calculateTeam($user_id, $status, $position)
    {
        $this->db->select('ifnull(count(tbl_downline_count.downline_id),0) as team, tbl_users.paid_status');
        $this->db->from('tbl_users');
        $this->db->join('tbl_downline_count', 'tbl_users.user_id = tbl_downline_count.downline_id');
        $this->db->where(array('tbl_downline_count.user_id' => $user_id, 'tbl_users.paid_status' => $status, 'tbl_downline_count.position' => $position, 'tbl_downline_count.level !=' => 1));
        $query = $this->db->get();
        $res = $query->row_array();
        return $res;
    }

    public function getBusinessPosition($user_id, $position)
    {
        $this->db->select('ifnull(sum(tbl_users.total_package),0) as teamBusiness,tbl_users.position');
        $this->db->from('tbl_sponser_count');
        $this->db->join('tbl_users', 'tbl_sponser_count.downline_id = tbl_users.user_id', 'inner');
        $this->db->where(array('tbl_sponser_count.user_id' => $user_id, 'tbl_users.position' => $position, 'tbl_sponser_count.level !=' => 1));
        $query = $this->db->get();
        $res = $query->row_array();
        return $res;
    }

    public function calculateTeamBusiness($user_id, $status, $position)
    {
        $this->db->select('ifnull(sum(tbl_users.total_package),0) as teamBusiness, tbl_users.paid_status');
        $this->db->from('tbl_users');
        $this->db->join('tbl_downline_business', 'tbl_users.user_id = tbl_downline_business.downline_id');
        $this->db->where(array('tbl_downline_business.user_id' => $user_id, 'tbl_users.paid_status' => $status, 'tbl_downline_business.position' => $position,));
        $query = $this->db->get();
        $res = $query->row_array();
        return $res;
    }

    public function calculateAcheiveLevel($user_id, $level)
    {
        $this->db->select('ifnull(count(tbl_sponser_count.downline_id),0) as team, tbl_users.paid_status');
        $this->db->from('tbl_users');
        $this->db->join('tbl_sponser_count', 'tbl_users.user_id = tbl_sponser_count.downline_id');
        $this->db->where(array('tbl_sponser_count.user_id' => $user_id, 'tbl_users.paid_status' => 1, 'tbl_sponser_count.level' => $level));
        $query = $this->db->get();
        $res = $query->row_array();
        return $res;
    }


    public function getTeamBalance($user_id)
    {
        $this->db->select('ifnull(sum(tbl_income_wallet.amount),0) as Balance ');
        $this->db->from('tbl_sponser_count');
        $this->db->join('tbl_income_wallet', 'tbl_sponser_count.downline_id = tbl_income_wallet.user_id', 'inner');
        $this->db->where(array('tbl_sponser_count.user_id' => $user_id));
        $query = $this->db->get();
        $res = $query->row_array();
        return $res;
    }
    public function grouped_by_levels($where, $limit, $offset)
    {
        $this->db->select('level');
        $this->db->group_by('level');
        $this->db->limit($limit, $offset);
        $query = $this->db->get_where('tbl_sponser_count', $where);
        $res = $query->result_array();
        return $res;
    }
 
     public function grouped_by_level2($table, $where, $select)
    {
        $this->db->select($select);
        $this->db->group_by('level');
        $this->db->where($where);
        $query = $this->db->get($table);
        $res = $query->result_array();
        return $res;
    }
    public function calculateTeamLevel($user_id, $status, $level)
    {
        $this->db->select('count(tbl_sponser_count.downline_id) as team, tbl_users.paid_status');
        $this->db->from('tbl_users');
        $this->db->join('tbl_sponser_count', 'tbl_users.user_id = tbl_sponser_count.downline_id');
        $this->db->where(array('tbl_sponser_count.user_id' => $user_id, 'tbl_users.paid_status' => $status, 'tbl_sponser_count.level ' => $level));
        $query = $this->db->get();
        $res = $query->row_array();
        return $res;
    }
}

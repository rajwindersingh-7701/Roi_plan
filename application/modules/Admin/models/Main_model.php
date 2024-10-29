<?php

class Main_model extends CI_Model
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
        //        echo $this->db->last_query();
        return $res;
    }

    public function get_single_record_desc($table, $where, $select)
    {
        $this->db->select($select);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get_where($table, $where);
        $res = $query->row_array();
        //        echo $this->db->last_query();
        return $res;
    }

    public function withdraw_users($minimum_amount)
    {
        $this->db->select('sum(amount) as total_amount,user_id');
        $this->db->from('tbl_income_wallet');
        $this->db->having(array('total_amount >=' => $minimum_amount));
        $this->db->group_by('user_id');
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }

    public function availableIncome()
    {
        $this->db->select('sum(amount) as total_amount,user_id');
        $this->db->from('tbl_income_wallet');
        $this->db->order_by('total_amount', 'desc');
        $this->db->having(array('total_amount >=' => 1));
        $this->db->group_by('user_id');
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }

    public function get_records($table, $where, $select)
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->result_array();
        //        echo $this->db->last_query();
        return $res;
    }

    public function get_limit_records($table, $where, $select, $limit, $offset)
    {
        $this->db->select($select);
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $query = $this->db->get($table);
        $res = $query->result_array();
        //        echo $this->db->last_query();
        return $res;
    }

    public function get_sum($table, $where, $select)
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->row_object();
        return $res->sum;
    }

    public function get_incomes($table, $where, $select)
    {
        $this->db->select($select);
        $this->db->group_by('type');
        $query = $this->db->get_where($table, $where);
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

    public function getTeamBusiness($user_id)
    {
        $this->db->select('ifnull(sum(tbl_users.total_package),0) as business');
        $this->db->from('tbl_users');
        $this->db->join('tbl_sponser_count', 'tbl_users.user_id = tbl_sponser_count.downline_id', 'inner');
        $this->db->where("tbl_sponser_count.user_id = '" . $user_id . "'");
        $query = $this->db->get();
        $res = $query->row_array();
        return $res;
    }


    public function get_chat_users()
    {
        $this->db->select('tbl_users.id,tbl_users.user_id,tbl_users.first_name,tbl_users.last_name,tbl_users.phone,tbl_users.sponser_id,tbl_users.image,tbl_support_message.*');
        $this->db->from('tbl_users');
        $this->db->group_by('tbl_users.user_id');
        $this->db->join('tbl_support_message', 'tbl_users.user_id = tbl_support_message.user_id', 'inner');
        $this->db->where(array());
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }

    public function get_all_users()
    {
        $this->db->select('tbl_users.id,tbl_users.user_id,tbl_users.name,tbl_users.first_name,tbl_users.last_name,tbl_users.phone,tbl_users.sponser_id,tbl_users.created_at');
        $this->db->from('tbl_users');
        // $this->db->join('countries', 'tbl_users.country = countries.id');
        $this->db->where(array());
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }

    public function position_paid_users()
    {
        $this->db->select('count( DISTINCT position ) as position_count, sponser_id ,count(id)');
        $this->db->from('tbl_users');
        // $this->db->join('countries', 'tbl_users.country = countries.id');
        $this->db->where(array('paid_status' => 1));
        $this->db->group_by('sponser_id');
        $this->db->having(array('position_count > ' => 1));
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }

    public function update_business($position, $user_id, $business)
    {
        $this->db->set($position, $position . ' + ' . $business, FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users');
        //    echo $this->db->last_query();
    }
    public function count_position_directs($user_id)
    {
        $this->db->select('user_id');
        $this->db->group_by('position');
        $this->db->where(['sponser_id' => $user_id, 'paid_status' => 1]);
        $query = $this->db->get('tbl_users');
        $res = $query->result_array();
        return $res;
    }
    public function update_directs($user_id)
    {
        $this->db->set('directs', 'directs + 1', FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users');
    }

    public function get_single_object($table, $where, $select)
    {
        $this->db->select($select);
        $query = $this->db->get_where($table, $where);
        $res = $query->row_array();
        //        echo $this->db->last_query();
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
        return $this->db->update($table, $data);
    }

    public function update_count($position, $user_id)
    {
        $this->db->set($position, $position . ' + 1', FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users');
        //        echo $this->db->last_query();
    }

    public function delete($table, $id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($table);
    }

    public function get_tree_user($user_id)
    {
        $this->db->select('tbl_user_positions.user_id,tbl_user_positions.sponser_id,tbl_user_positions.upline_id,tbl_user_positions.created_at as topup_date,tbl_user_positions.position,tbl_user_positions.left_node,tbl_user_positions.right_node,tbl_user_positions.left_count,tbl_user_positions.right_count,tbl_users.first_name,tbl_users.last_name,tbl_users.courtesy_title,tbl_users.email,tbl_users.created_at as joining_date');
        $this->db->from('tbl_user_positions');
        $this->db->join('tbl_users', 'tbl_user_positions.user_id = tbl_users.user_id');
        $this->db->where(array('tbl_user_positions.user_id' => $user_id));
        $query = $this->db->get();
        $res = $query->row_object();
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

    public function user_chat($user_id)
    {
        $this->db->select('tbl_support_message.*,tbl_users.first_name,tbl_users.last_name,tbl_users.image');
        $this->db->from('tbl_support_message');
        $this->db->join('tbl_users', 'tbl_support_message.user_id = tbl_users.user_id', 'inner');
        $this->db->where(array('tbl_support_message.user_id' => $user_id));
        $query = $this->db->get();
        $res = $query->result_array();
        //        echo $this->db->last_query();
        return $res;
    }

    public function todayPair()
    {
        $where = 'type = "matching_bonus" and date(created_at) = date(now()) - 1';
        $this->db->select('amount');
        $this->db->order_by('amount', 'ASC');
        $this->db->limit('1');
        $query = $this->db->get_where('tbl_income_wallet', $where);
        $result = $query->result_array();
        if (!empty($result)) {
            return $result[0];
        }
    }
    public function get_roi_users($having)
    {
        $this->db->select('*,day(created_at) as date ,DATEDIFF(now(),created_at)as date_diff');
        $this->db->having($having);
        $this->db->where(['amount >' => 0, 'type !=' => 'salary']);
        $query = $this->db->get('tbl_roi');
        $res = $query->result_array();
        return $res;
    }

    public function getBusiness($business)
    {
        $this->db->select('ifnull(sum(tbl_users.package_amount),0) as teamBusiness,tbl_sponser_count.user_id');
        $this->db->from('tbl_sponser_count');
        $this->db->join('tbl_users', 'tbl_sponser_count.downline_id = tbl_users.user_id', 'inner');
        $this->db->group_by('tbl_sponser_count.user_id');
        $this->db->having('teamBusiness >=', $business);
        $this->db->where('tbl_sponser_count.user_id !=', 'none');
        $query = $this->db->get();
        $res = $query->result_array();
        return $res;
    }

    public function deleteCron($table, $where)
    {
        $this->db->where($where);
        return $this->db->delete($table);
    }
}

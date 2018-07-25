<?php
/**
 * 推荐位管理数据模型
 */

 class Position_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database('default');
        $this->table="cms_position";
    }

    /**
     * 根据id获取文章数据
     */
    public function getPositionById($id = '') {
        if (!$id) {
            return false;
        }
        $query = $this->db->where('id', $id)->get($this->table);
        $res = $query->result_array();
        
        return $res ? $res : false;
    }

    /**
     * 修改或增加数据
     * @param Array $data 要修改或增加的数据
     * @param Int|String  $id 修改数据的条件
     * @return 
     */
    public function modify($data, $id = '') {
        if (!$data || !is_array($data)) {
            return false;
        }
        if ($id) {
            // 修改
            $this->db->where('id', $id);
            $res = $this->db->update($this->table, $data);
            return $res ? $this->db->affected_rows() : $res;
        } else {
            // 增加
            $res = $this->db->insert($this->table, $data);
            return $res ? $this->db->insert_id() : $res;
        }
    }

    /**
     * 获取所有数据
     */
    public function getAllList() {
        $query = $this->db->get($this->table);
        $res = $query->result_array();
        return $res ? $res : false;
    }

 }
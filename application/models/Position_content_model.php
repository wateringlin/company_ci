<?php
/**
 * 菜单数据模型
 */

class Position_content_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database('default');
        $this->table = 'cms_position_content';
    }

    /**
     * 修改或新增数据
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

    public function getPositionContentById($id = '') {
        if (!$id) {
            return false;
        }
        $query = $this->db->where('id', $id)->get($this->table);
        $res = $query->result_array();

        return $res ? $res : false;
    }

}
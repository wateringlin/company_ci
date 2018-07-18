<?php
/**
 * 文章数据模型
 */

class Article_model extends MY_Model {

  public function __construct() {
    parent::__construct();
    $this->load->database('default');
    $this->table = 'cms_news';
  }

  /**
   * 获取前端栏目数据
   */
  public function getHomeMenus() {
    $this->table = 'cms_menu';
    $data = array(
      'status' => 1,
      'type' => 0
    );
    $query = $this->db->where($data)->get($this->table);
    $res = $query->result_array();
    return $res;
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
      $this->db->where('news_id', $id);
      $res = $this->db->update($this->table, $data);
      return $res ? $this->db->affected_rows() : $res;
    } else {
      // 增加
      $res = $this->db->insert($this->table, $data);
      return $res ? $this->db->insert_id() : $res;
    }
    // var_dump($this->db->last_query());
  }

  public function modifyContentData($data, $id = '') {
    if (!$data || !is_array($data)) {
      return false;
    }
    $this->table = 'cms_news_content';
    if ($id) {
      // 修改
      $this->db->where('id', $id);
      $res = $this->db->update($this->table, $data);
      return $res ? $this->db->affected_rows() : $res;
    } else {
      // 增加
      $res = $this->db->insert($this->table, $data);
      // var_dump($this->db->last_query());
      return $res ? $this->db->insert_id() : $res;
    }
  }

}
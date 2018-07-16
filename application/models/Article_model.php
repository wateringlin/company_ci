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

}
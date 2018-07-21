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

  /**
   * 根据ID获取文章数据
   */
  public function getArticleById($id = '') {
    if (!$id) {
      return false;
    }
    $query = $this->db->where('news_id', $id)->get($this->table);
    $res = $query->result_array();
    if (!empty($res)) {
      $content_res = $this->getContentById($res[0]['news_id']);
      $res[0]['content'] = $content_res[0]['content'];
    }

    return $res ? $res : false;
  }

  /**
   * 根据文章id修改或增加文章内容副表
   */
  public function modifyContentData($data, $id = '') {
    if (!$data || !is_array($data)) {
      return false;
    }
    $this->table = 'cms_news_content';
    if ($id) {
      // 修改文章内容副表
      $this->db->where('news_id', $id);
      $res = $this->db->update($this->table, $data);
      return $res ? $this->db->affected_rows() : $res;
    } else {
      // 增加文章内容副表
      $res = $this->db->insert($this->table, $data);
      return $res ? $this->db->insert_id() : $res;
    }
  }

  public function getContentById($id = '') {
    if (!$id) {
      return false;
    }
    $this->table = 'cms_news_content';
    $query = $this->db->where('news_id', $id)->get($this->table);
    $res = $query->result_array();

    return $res ? $res : false;
  }

}
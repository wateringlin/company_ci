<?php
/**
 * 菜单数据模型
 */

 class Menu_model extends MY_Model {

  public function __construct() {
    parent::__construct();
    $this->load->database('default');
    $this->table = 'cms_menu';
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
      $this->db->where('menu_id', $id);
      $res = $this->db->update($this->table, $data);
    } else {
      // 增加
      $res = $this->db->insert($this->table, $data);
    }
    // var_dump($this->db->last_query());
    return $res ? $this->db->affected_rows() : $res;
  }

  /**
   * 根据id获取菜单数据
   */
  public function getMenuById($id = '') {
    if (!$id) {
      return false;
    }
    $query = $this->db->where('menu_id', $id)->get($this->table);
    $res = $query->result_array();
    
    return $res ? $res : false;
  }

  /**
   * 获取后端菜单数据
   */
  public function getAdminMenus() {
    $data['status'] = ['value' => 1, 'operator' => '='];
    $data['type'] = ['value' => 1, 'operator' => '='];
    $data['orderby'] = ['value' => 'listorder desc, menu_id desc', 'operator' => 'orderby'];
    $res = $this->findWhere($data);
    return $res;
  }

 }






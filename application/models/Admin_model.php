<?php
/**
 * 用户数据模型
 */

 class Admin_model extends CI_Model {

  public function __construct() {
    parent::__construct();
    // 开启操作数据库
    $this->load->database('default');
    // 设置要操作的数据表
    $this->table = 'cms_admin';
  }

  /**
   * 根据用户名获取用户信息
   */
  public function getAdminByUsername($username) {
    $res = $this->db->where('username', $username)
          ->get($this->table);
    if ($res->num_rows()) {
      $res = $res->result_array();
    } else {
      $res = array();
    }
    return $res;
  }

 }


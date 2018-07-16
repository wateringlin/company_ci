<?php if (!defined('BASEPATH')) exit('No direct access allowed.');
/**
 * 扩展CI_Controller
 */

class MY_Controller extends CI_Controller {
  public $user = array();

  public function __construct() {
    parent::__construct();
    $this->load->library('session');
    $this->user = $this->getLoginUser();
    
    $this->_init();
  }

  /**
   * 获取已登录用户信息
   */
  public function getLoginUser() {
    return $this->session->adminUser;
  }

  /**
   * 判断是否已登录
   */
  public function isLogin() {
    $user = $this->getLoginUser();
    // if ($user && is_array($user)) {
      return true;
    // } else {
    //   return false;
    // }
  }

  /**
   * 初始化
   */
  private function _init() {
    // 判断是否登录
    $isLogin = $this->isLogin();
    if (!$isLogin) {
      // 如果未登录，则跳转到登录页面
      redirect('admin/login');
    }
  }
  
}
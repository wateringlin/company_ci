<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

  public function __construct() {
    parent::__construct();
    // 加载用户数据模型
    $this->load->model('Admin_model');
    // 加载session类
    $this->load->library('session');
  }

  /**
   * 显示登录页面
   */
  public function index() {
    if ($this->session->has_userdata('adminUser')) {
      redirect('admin/index');
    }
    $this->load->view('admin/login/login');
  }

  /**
   * 验证登录
   */
  public function check() {
    $username = $this->input->post('username', true);
    $password = $this->input->post('password', true);
    if (!trim($username)) {
      show(-1, '用户名不能为空');
    }
    if (!trim($password)) {
      show(-1, '密码不能为空');
    }

    // 根据用户名获取用户数据
    $res = $this->Admin_model->getAdminByUsername($username);
    // 校验数据
    if (!count($res)) {
      show(-1, '该用户不存在');
    }
    if ($res[0]['password'] != getMd5Password($password)) {
      show(-2, '密码错误');
    }

    // 登录成功，使用session保存用户信息
    $this->session->set_userdata('adminUser', $res[0]);
    show(0, '登录成功', $res);
  }

  // 退出登录
  public function logout() {
    $this->session->unset_userdata('adminUser');
    redirect('admin/login/index');
  }

}
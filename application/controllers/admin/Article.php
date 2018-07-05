<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends MY_Controller {

  public function __construct() {
    parent::__construct();
  }

  /**
   * 显示index页面
   */
  public function index() {
    $this->load->view('admin/article/index');
  }

  /**
   * 显示增加页面
   */
  public function add() {
    $this->load->view('admin/article/add');
  }

  public function uploadImage() {
    // var_dump('UPLOADED_DATA: ', UPLOADED_DATA);
    var_dump('base_url(): ', base_url().'uploads/images/');
    $config['upload_path']      = base_url().'uploads/images/';
    $config['allowed_types']    = 'gif|jpg|png';
    $config['max_size']     = 100;
    $config['max_width']        = 1024;
    $config['max_height']       = 768;

    $this->load->library('upload', $config);
    $this->upload->initialize($config);

    if (!$this->upload->do_upload('file')) {
      $error = array('error' => $this->upload->display_errors());
      return show(-1, '上传失败', $error);
    } else {
      $data = array('upload_data' => $this->upload->data());
      return show(0, '上传成功', $data);
    }
  }

}




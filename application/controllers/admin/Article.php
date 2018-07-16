<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends MY_Controller {

  public function __construct() {
    parent::__construct();
    // 加载文章数据模型
    $this->load->model('Article_model', 'article');
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

  /**
   * 上传图片，返回客户端可访问的图片地址
   */
  public function getUploadedImage() {
    $params = array(
      'file_name' => 'file',
      'upload_path' => 'images'
    );
    $file_path = $this->uploadImage($params);
    $upload_path = transferUploadPath($file_path);
    return show(0, '上传成功', $upload_path);
  }

  /**
   * 编辑器接口
   */
  public function editorUpload() {
    $params = array(
      'file_name' => 'imgFile', // 调试得知该editor的name值为imgFile
      'upload_path' => 'images'
    );
    $file_path = $this->uploadImage($params);
    $upload_path = transferUploadPath($file_path);
    if ($upload_path) {
      return showEditor(0, $upload_path);
    }
    return showEditor(1, '上传失败');
  }

  /**
   * 上传文件
   */
  protected function uploadImage($params = array()) {
    $this->load->library('FileUpload', $params);
    $file_path = $this->fileupload->uploadFile();
    return $file_path;
  }

  public function getHomeMenus() {
    $res = $this->article->getHomeMenus();
    if (!empty($res)) {
      return show(0, '获取成功', $res);
    }
    return show(-1, '获取失败', $res);
  }

}




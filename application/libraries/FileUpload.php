<?php
/**
 * 上传类
 */
class FileUpload {

  protected $file_name; // 上传文件名
  protected $max_size; // 最大上传文件
  protected $allow_mime; // 允许的文件类型
  protected $upload_path; // 文件存放路径
  protected $img_flag; // 
  protected $file_info; // 上传的文件信息
  protected $error; // 错误信息
  protected $ext; // 文件扩展名

  public function __construct($file_name='myFile', $upload_path='images', $img_flag=true, $max_size='5242880', $allow_ext=array('jpeg', 'jpg', 'png', 'gif'), $allow_mime=array('image/jpeg', 'image/png', 'image/gif')) {
    $this->file_name = $file_name;
    $this->max_size = $max_size;
    $this->allow_mime = $allow_mime;
    $this->upload_path = $upload_path;
    $this->img_flag = $img_flag;
    $this->file_info = $_FILES[$this->file_name];
  }

  /**
   * 检测上传文件是否出错
   */
  protected function checkError() {
    if (!is_null($this->file_info)) {
      if ($this->file_info['error'] > 0) {
        switch($this->file_info['error']) {
          case 1:
            $this->error = '超过了PHP配置文件中upload_max_filesize选项的值';
          case 2:
            $this->error = '超过了表单中MAX_FILE_SIZE设置的值';
          case 3:
            $this->error = '文件部分被上传';
          case 4:
            $this->error = '没有选择上传文件';
          case 6:
            $this->error = '没有找到临时目录';
          case 7:
            $this->error = '文件不可写';
          case 8:
            $this->error = '由于PHP的扩展程序中断文件上传';
        }
        return false;
      } else {
        return true;
      }
    } else {
      $this->error = '文件上传出错';
      return true;
    }
  }

  /**
   * 检测上传文件的大小
   */
  protected function checkSize() {
    if ($this->file_info['size'] > $this->max_size) {
      $this->error = '上传文件过大';
      return false;
    }
    return true;
  }

  /**
   * 检测上传文件的扩展名
   */
  protected function checkExt() {
    $this->ext = strtolower(pathinfo($this->file_info['name'], PATHINFO_EXTENSION));
    if (!in_array($this->ext, $this->allowExt)) {
      $this->error = ' 不允许的扩展名';
      return false;
    }
    return true;
  }

  public function uploadFile() {
    if ($this->checkError() && $this->checkSize() && $this->checkExt() && $this->checkMime() && $this->checkTrueImg() && $this->checkHttpPost()) {

    } else {

    }
  }


}




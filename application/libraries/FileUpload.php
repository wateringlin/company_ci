<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 上传类
 */

class FileUpload {
    protected $file_name;
    protected $max_size;
    protected $allow_mime;
    protected $allow_ext;
    protected $upload_path;
    protected $img_flag;
    protected $file_info;
    protected $error;
    protected $ext;
    protected $file_folder;

    // 初始化
    public function __construct($file_name='file', $upload_path='./uploads', $img_flag=true, $max_size=5242880, $allow_ext=array('jpeg', 'jpg', 'png', 'gif', 'csv'), $allow_mime=array('image/jpeg', 'image/png', 'image/gif')) {
        $this->file_name = $file_name;
        $this->max_size = $max_size;
        $this->allow_mime = $allow_mime;
        $this->allow_ext = $allow_ext;
        $this->upload_path = SYSTEM_UPLOAD_DIR.$upload_path;
        $this->img_flag = $img_flag;
        $this->file_info = $_FILES[$this->file_name];
    }

    // 检测上传文件是否出错
    protected function checkError() {
        if (!is_null($this->file_info)) {
            if ($this->file_info['error'] > 0) {
                switch ($this->file_info['error']) {
                    case 1:
                        $this->error = '超过了PHP配置文件中upload_max_filesize选项的值';
                        break;
                    case 2:
                        $this->error = '超过了表单中MAX_FILE_SIZE设置的值';
                        break;
                    case 3:
                        $this->error = '文件部分被上传';
                        break;
                    case 4:
                        $this->error = '没有选择上传文件';
                        break;
                    case 6:
                        $this->error = '没有找到临时目录';
                        break;
                    case 7:
                        $this->error = '文件不可写';
                        break;
                    case 8:
                        $this->error = '由于PHP的扩展程序中断文件上传';
                        break;
                }
                return false;
            } else {
                return true;
            }
        } else {
            $this->error = '文件上传出错';
            return false;
        }
    }
    
    // 检测上传文件的大小
        protected function checkSize() {
            if ($this->file_info['size'] > $this->max_size) {
                $this->error = '上传文件过大';
                return false;
            }
            return true;
        }

        /**
        * 检测文件的扩展名
        */
        protected function checkExt() {
            $this->ext = strtolower(pathinfo($this->file_info['name'], PATHINFO_EXTENSION));
            if (!in_array($this->ext, $this->allow_ext)) {
                $this->error = '不允许的扩展名';
                return false;
            }
            return true;
        }

        /**
        * 检测文件的类型
        */
        protected function checkMime() {
            // var_dump('$this->file_info: ', $this->file_info);
            // if (!in_array($this->file_info['type'], $this->allow_mime)) {
            //     $this->error = '不允许的文件类型';
            //     return false;
            // }
            return true;
        }

        /**
        * 检测是否是真实图片
        */
        protected function checkTrueImg() {
            if ($this->img_flag) {
                if (!@getimagesize($this->file_info['tmp_name'])) {
                    $this->error = '不是真实图片';
                }
                return true;
            }
        }

        /**
        * 检测是否通过HTTP POST方式上传上来的
        */
        protected function checkHttpPost() {
            if (!is_uploaded_file($this->file_info['tmp_name'])) {
                $this->error = '文件不是通过HTTP POST方式上传上来的';
                return false;
            }
            return true;
        }

        /**
        * 显示错误
        */
        protected function showError() {
            exit('<span style="color: red">'.$this->error.'</span>');
        }

        /**
        * 检测目录不存在则创建
        */
        protected function checkUploadPath() {
            $this->file_folder .= $this->upload_path.'/'.date('Ymd').'/';
            if (!file_exists($this->file_folder)) {
                mkdir($this->file_folder, 0777, true);
            }
        }

        // 年月日/年月日时分秒_随机数.后缀
        protected function getFileName() {
            // return md5(uniqid(microtime(true), true));
            return date('YmdHis').'_'.rand(10000, 99999).'.'.$this->ext;
        }

    public function uploadFile() {
        if ($this->checkError() && $this->checkSize() && $this->checkExt() && $this->checkMime() && $this->checkTrueImg() && $this->checkHttpPost()) {
            $this->checkUploadPath();
            $this->new_file_name = $this->getFileName();
            $this->file_folder .= $this->new_file_name;
            if (@move_uploaded_file($this->file_info['tmp_name'], $this->file_folder)) {
                return $this->file_folder;
            } else {
                $this->error = '文件移动失败';
                $this->showError();
            }
        } else {
            $this->showError();
        }
    }
}
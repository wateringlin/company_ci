<?php
/**
 * 公用方法
 */

/**
 * 返回json数据给客户端
 */
if (!function_exists('show')) {
  function show($retcode, $msg, $data = array()) {
    $res = array(
      'retcode' => $retcode,
      'msg' => $msg,
      'data' => $data
    );
    exit(json_encode($res));
  }
}

/**
 * 加密密码
 */
if (!function_exists('getMd5Password')) {
  function getMd5Password($password) {
    return md5($password.MD5_PREFIX);
  }
}

/**
 * 转换数据返回给客户端
 * 1 后台菜单
 * 0 前端导航
 */
if (!function_exists('getMenuType')) {
 function getMenuType($type) {
  return $type == 1 ? '后台菜单' : '前端导航';
 }
}

/**
 * 转换数据返回给客户端
 * 0 关闭
 * 1 正常
 * -1 删除
 */
if (!function_exists('getStatus')) {
  function getStatus($status) {
    if ($status == 0) {
      $str = '关闭';
    } elseif ($status == 1) {
      $str = '正常';
    } elseif ($status == -1) {
      $str = '删除';
    }
    return $str;
  }
}

/**
 * 使用nginx转发规则，将/uploads/重定向到/data/appdata/company_ci/uploads目录下
 */
function transferUploadPath($file_path) {
  $path_arr = explode('/', $file_path);
  if (empty($path_arr)) {
    return;
  }
  $path_arr = array_slice($path_arr, count($path_arr) - 4);
  $path = implode('/', $path_arr);
  $upload_path = base_url().$path;
  return $upload_path;
}

/**
 * 前端kindeditor富文本编辑器数据返回格式
 */
function showEditor($status, $data) {
  header('Content-type: application/json; charset=UTF-8');
  if ($status == 0) {
    exit(json_encode(array('error' => 0, 'url' => $data)));
  }
  exit(json_encode(array('error' => 1, 'message' => '上传失败')));
}


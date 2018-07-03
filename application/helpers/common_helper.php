<?php
/**
 * 公用方法
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

if (!function_exists('getMd5Password')) {
  function getMd5Password($password) {
    return md5($password.MD5_PREFIX);
  }
}

if (!function_exists('getMenuType')) {
 function getMenuType($type) {
  return $type == 1 ? '后台菜单' : '前端导航';
 }
}

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
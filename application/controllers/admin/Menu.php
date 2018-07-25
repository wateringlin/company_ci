<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MY_Controller {

  public function __construct() {
    parent::__construct();
    // 加载菜单数据模型
    $this->load->model('Menu_model', 'menu');
  }

  /**
   * 显示index页面
   */
  public function index() {
    $this->load->view('admin/menu/index');
  }

  /**
   * 显示增加页面
   */
  public function add() {
    $this->load->view('admin/menu/add');
  }

  /**
   * 显示编辑页面，并获取数据填充编辑页面
   */
  public function edit() {
    // 编辑页面不方便使用js渲染，使用PHP替代
    $id = $this->input->get('id', true);
    $res = $this->menu->getMenuById($id);
    $data = array();
    if ($res) {
      $data['menu'] = $res[0];
    }
    
    $this->load->view('admin/menu/edit', $data);
  }

  /**
   * 获取分页数据
   */
  public function getList() {
    $where = array();
    if ($this->input->get_post('search', true)) {
      $where = $this->input->get_post('search', true);
    }
    $page = $this->input->get_post('page', true);
    $pageSize = $this->input->get_post('pageSize', true);

    // 设置获取数据状态为未删除，排序为降序
    $where['status'] = array('value' => '-1', 'operator' => '!=');
    $where['orderby'] = array('value' => 'listorder desc, menu_id desc', 'operator' => 'orderby');

    $res = $this->menu->paginate($where, $page, $pageSize);
    $data = array();
    if ($res['data'] !== false) {
      // 处理需要转化为中文的数据，这里使用引用的方式，下面改了$item就会改变$res['data']的数据了
      foreach ($res['data'] as &$item) {
        if (is_array($item)) {
          foreach ($item as $k => $v) {
            if ($k === 'type') {
              $item[$k] = getMenuType($v);
            }
            if ($k === 'status') {
              $item[$k] = getStatus($v);
            }
          }
        }
      }

      $data['data'] = $res['data'];
      $data['total'] = $res['total'];
      $data['hasMore'] = $res['hasMore'];
      return show(0, '获取数据成功', $data);
    } else {
      return show(-1, '获取数据失败', $data);
    }
  }

  /**
   * 增加或修改数据
   */
  public function modify() {
    $id = $this->input->post('id', true);
    $data = $this->input->post('fields', true);
    // 校验数据
    if (!isset($data['name']) || !$data['name']) {
      return show(-1, '菜单名不能为空');
    } else if (!isset($data['m']) || !$data['m']) {
      return show(-1, '模块名不能为空');
    } else if (!isset($data['c']) || !$data['c']) {
      return show(-1, '控制器不能为空');
    } else if (!isset($data['f']) || !$data['f']) {
      return show(-1, '方法名不能为空');
    }
    
    if ($id) {
      // 修改
      $data['update_time'] = date('Y-m-d H:i:s');
      $data['update_user'] = $this->user['username'];
      $res = $this->menu->modify($data, $id);
    } else {
      // 新增
      $data['create_time'] = date('Y-m-d H:i:s');
      $data['create_user'] = $this->user['username'];
      $res = $this->menu->modify($data);
    }
    if ($res) {
      return show(0, '编辑成功', $res);
    }
    return show(-1, '编辑失败', $res);
  }

  /**
   * 删除数据，只是修改了数据的status值，不删除实际数据
   */
  public function delete() {
    $id = $this->input->post('id', true);
    if (!$id) {
      return show(-1, 'id不能为空');
    }
    $data = $this->input->post('fields', true);
    if (!$data['status']) {
      return show(-1, '状态值不能为空');
    }
    $data['update_time'] = date('Y-m-d H:i:s');
    $data['update_user'] = $this->user['username'];

    $res = $this->menu->modify($data, $id);
    if ($res) {
      return show(0, '删除成功', $res);
    } else {
      return show(-1, '删除失败', $res);
    }
  }

  /**
   * 排序数据
   */
  public function sort() {
    $data = $this->input->post('fields', true);
    if (!$data) {
      return show(-1, '排序数据不能为空');
    }
    $errors = array();
    try{
      foreach ($data as $menu_id => $listorder) {
        $data = array(
          'listorder' => intval($listorder)
        );
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['update_user'] = $this->user['username'];
        $res = $this->menu->modify($data, $menu_id);
        if ($res === false) {
          $errors[] = $menu_id;
        }
      }
    } catch (Exception $e) {
      return show(-1, $e->getMessage());
    }
    if ($errors) {
      return show(-1, '排序失败-'.implode(',', $errors));
    } else {
      return show(0, '排序成功');
    }
  }

  /**
   * 保存上传的文件
   */
  public function uploadFile() {
    $params = array(
      'file_name' => 'file',
      'upload_path' => 'csv'
    );
    $this->load->library('FileUpload', $params);
    $file_path = $this->fileupload->uploadFile();
    return $file_path;
  }

  /**
   * 读取csv文件，导入数据
   */
  public function importData() {
    // 获取上传的文件路径
    $file_path = $this->uploadFile();

    // 定义获取文件的每列标题，只用于循环，不赋值
    $keylist = array();
    $keylist['name'] = 0; // 菜单名
    $keylist['type'] = 1; // 菜单类型
    $keylist['m'] = 2; // 模块名
    $keylist['c'] = 3; // 控制器
    $keylist['f'] = 4; // 方法
    $keylist['status'] = 5; // 状态

    // 不限制上传文件的大小和时间
    set_time_limit(0);
    // init_set('memory_limit', '-1');

    // 读取上传文件的内容
    if (file_exists($file_path)) {
      $file = fopen($file_path, 'r');
      $line_number = 0;
      if ($file === false) {
        return show(-1, '无法打开文件');
      }
      // 如果还有数据，则继续循环读取
      while (!feof($file)) {
        // 读取一行数据
        $line = trim(fgets($file)); 
        // 跳过表头
        if ($line_number == 0) {
          $line_number += 1;
          continue;
        }
        // var_dump('$line: ', $line);
        // 添加要插入的数组
        $insertData = array(); 
        if (strlen($line) > 0) {
          // 每行数据以逗号分割为数组
          $tmpArr = explode(',', $line);
          if (!empty($tmpArr)) {
            foreach ($keylist as $k => $v) {
              if (!empty($tmpArr[$v])) {
                $insertData[$k] = $tmpArr[$v];
              } else {
                $insertData[$k] = '';
              }
            }
          }
          $res = $this->menu->modify($insertData);
        }
      }
      fclose($file);
      return show(0, '导入成功', $res);
    } else {
      return show(-1, '上传文件不存在');
    }
  }

}
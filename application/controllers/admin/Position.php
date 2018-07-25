<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Position extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // 加载文章数据模型
        $this->load->model('Position_model', 'position');
    }

    /**
     * 显示index页面
     */
    public function index() {
        $this->load->view('admin/position/index');
    }

    /**
     * 显示增加页面
     */
    public function add() {
        $this->load->view('admin/position/add');
    }

    /**
     * 显示编辑页面
     */
    public function edit() {
        // 编辑页面不方便使用js渲染，使用PHP代替
        $id = $this->input->get('id', true);
        $res = $this->article->getPositionById($id);
        $data = array();
        if ($res) {
            $data['position'] = $res[0];
        }

        $this->load->view('admin/position/edit', $data);
    }

    /**
     * 增加或修改数据
     */
    public function modify() {
        $id = $this->input->post('id', true);
        $data = $this->input->post('fields', true);

        // 校验数据
        if (!isset($data['name']) || !$data['name']) {
            return show(-1, '推荐位名称不能为空');
        } elseif (!isset($data['description']) || !$data['description']) {
            return show(-1, '推荐位描述不能为空');
        } elseif (!isset($data['status']) || !in_array($data['status'], [0, 1])) {
            return show(-1, '状态不能为空');
        }

        if ($id) {
            // 修改
            $data['update_time'] = date('Y-m-d H:i:s');
            $data['update_user'] = $this->user['username'];
            $res = $this->position->modify($data, $id);
        } else {
            // 增加
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['create_user'] = $this->user['username'];
            $res = $this->position->modify($data);
        }
        if ($res) {
            return show(0, '编辑成功', $res);
        }
        return show(-1, '编辑失败', $res);
    }

    /**
     * 获取分页数据
     */
    public function getList() {
        $where = [];
        if ($this->input->get_post('search', true)) {
            $where = $this->input->get_post('search', true);
        }
        $page = $this->input->get_post('page', true);
        $pageSize = $this->input->get_post('pageSize', true);

        // 设置获取数据状态为未删除，排序为降序
        $where['status'] = ['value' => '-1', 'operator' => '!='];
        $where['orderby'] = ['value' => 'id desc', 'operator' => 'orderby'];

        $res = $this->position->paginate($where, $page, $pageSize);
        $data = [];
        if ($res['data'] !== false) {
            foreach ($res['data'] as &$item) {
                if (is_array($item)) {
                    foreach ($item as $k => $v) {
                        $item[$k] = getStatus($v);
                    }
                }
            }
            $data['data'] = $res['data'];
            $data['total'] = $res['total'];
            $data['hasMore'] = $es['hasMore'];
            return show(0, '获取数据成功', $data);
        } else {
            return show(-1, '获取数据失败', $data);
        }
    }

    /**
     * 获取全部数据
     */
    public function getAllList() {
        $res = $this->position->getAllList();
        // var_dump('$res: ', $res);
        $data = [];
        if ($res !== false) {
            foreach ($res as &$item) {
                if (is_array($item)) {
                    foreach ($item as $k => $v) {
                        if ($k === 'status') {
                            $item[$k] = getStatus($v);
                        }
                    }
                }
            }
            $data['data'] = $res;
            return show(0, '获取数据成功', $data);
        } else {
            return show(-1, '获取数据失败', $data);
        }
    }

}


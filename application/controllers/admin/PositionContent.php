<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PositionContent extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // 加载推荐位内容数据模型
        $this->load->model('Position_content_model', 'position_content');
    }

    public function index() {
        $this->load->view('admin/positionContent/index');
    }

    public function add() {
        $this->load->view('admin/positionContent/add');
    }

    public function edit() {
        // 编辑页面不方便使用js渲染，使用PHP代替
        $id = $this->input->get('id', true);
        $res = $this->position_content->getPositionContentById($id);
        $data = array();
        if ($res) {
            $data['pcontent'] = $res[0];
        }
        $this->load->view('admin/positionContent/edit', $data);
    }

    public function getList() {
        $where = array();
        if ($this->input->get_post('search', true)) {
            $where = $this->input->get_post('search', true);
        }
        $page = $this->input->get_post('page', true);
        $pageSize = $this->input->get_post('pageSize', true);

        // 设置获取数据状态为未删除，排序为降序
        $where['status'] = array('value' => '-1', 'operator' => '!=');
        $where['orderby'] = array('value' => 'listorder desc, id desc', 'operator' => 'orderby');

        $res = $this->position_content->paginate($where, $page, $pageSize);
        if ($res['data'] !== false) {
            foreach ($res['data'] as &$item) {
                if (is_array($item)) {
                    foreach ($item as $k => $v) {
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

    public function modify() {
        $id = $this->input->post('id', true);
        $data = $this->input->post('fields', true);
        // 校验数据

        if ($id) {
            // 修改
            $data['update_time'] = date('Y-m-d H:i:s');
            $data['update_user'] = $this->user['username'];
            $res = $this->position_content->modify($data, $id);
        } else {
            // 增加
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['create_user'] = $this->user['username'];
            $res = $this->position_content->modify($data);
        }
        if ($res) {
            return show(0, '编辑成功', $res);
        }
        return show(-1, '编辑失败', $res);
    }

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

        $res = $this->position_content->modify($data, $id);
        if ($res) {
            return show(0, '删除成功', $res);
        } else {
            return show(-1, '删除失败', $res);
        }
    }

}
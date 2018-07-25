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
   * 显示编辑页面，并获取数据填充编辑页面
   */
  public function edit() {
    // 编辑页面不方便使用js渲染，使用PHP替代
    $id = $this->input->get('id', true);
    $res = $this->article->getArticleById($id);
    $data = array();
    if ($res) {
      $data['article'] = $res[0];
    }

    $this->load->view('admin/article/edit', $data);
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
    $where['orderby'] = array('value' => 'listorder desc, news_id desc', 'operator' => 'orderby');

    $res = $this->article->paginate($where, $page, $pageSize);
    $data = array();
    if ($res['data'] !== false) {
      // 处理需要转化为中文的数据，这里使用引用的方式，下面改了$item就会改变$res['data']的数据了
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

  /**
   * 获取前端栏目
   */
  public function getHomeMenus() {
    $res = $this->article->getHomeMenus();
    if (!empty($res)) {
      return show(0, '获取成功', $res);
    }
    return show(-1, '获取失败', $res);
  }

  /**
   * 增加或修改数据
   */
  public function modify() {
    $id = $this->input->post('id', true);
    $data = $this->input->post('fields', true);
    // 校验数据
    if (!isset($data['title']) || empty($data['title'])) {
      return show(-1, '标题不存在');
    }
    if (!isset($data['content']) || empty($data['content'])) {
      return show(-1, '文章内容不存在');
    }

    // 添加文章内容表的数据，删除往主表添加的内容字段
    $articleContentData['content'] = $data['content'];
    unset($data['content']);

    if ($id) {
      // 修改
      $data['update_time'] = date('Y-m-d H:i:s');
      $data['update_user'] = $this->user['username'];
      $res = $this->article->modify($data, $id);
      if ($res) {
        // 更新成功，则$id为文章id，使用$id再去更新文章内容副表
        $articleContentData['news_id'] = $id;
        $articleContentData['update_time'] = date('Y-m-d H:i:s');
        $articleContentData['update_user'] = $this->user['username'];
        $content_id = $this->article->modifyContentData($articleContentData, $id);
        if ($content_id) {
          return show(0, '修改成功', $content_id);
        } else {
          return show(-1, '主表修改成功，副表修改失败', $content_id);
        }
      } else {
        return show(-1, '修改失败'.$res);
      }
    } else {
      // 新增
      $data['create_time'] = date('Y-m-d H:i:s');
      $data['create_user'] = $this->user['username'];
      $insert_id = $this->article->modify($data);
      if ($insert_id) {
        // 增加成功，返回新增的文章id，使用文章id再去新增文章内容副表
        $articleContentData['news_id'] = $insert_id;
        $articleContentData['create_time'] = date('Y-m-d H:i:s');
        $articleContentData['create_user'] = $this->user['username'];
        $content_id = $this->article->modifyContentData($articleContentData);
        if ($content_id) {
          return show(0, '新增成功', $content_id);
        } else {
          return show(-1, '主表插入成功，副表插入失败', $content_id);
        }
      } else {
        return show(-1, '新增失败', $insert_id);
      }
    }
  }

  /**
   * 删除或改变状态，只是修改了数据的status值，不删除实际数据
   */
  public function delete() {
    $id = $this->input->post('id', true);
    if (!$id) {
      return show(-1, 'id不能为空');
    }
    $data = $this->input->post('fields', true);
    if (!in_array($data['status'], [-1, 0, 1])) {
      return show(-1, '状态值不能为空');
    }
    $data['update_time'] = date('Y-m-d H:i:s');
    $data['update_user'] = $this->user['username'];

    $res = $this->article->modify($data, $id);
    if ($res) {
      // 更新成功，则$id为文章id，使用$id再去更新文章内容副表
      $articleContentData['news_id'] = $id;
      $articleContentData['update_time'] = date('Y-m-d H:i:s');
      $articleContentData['update_user'] = $this->user['username'];
      $content_id = $this->article->modifyContentData($articleContentData, $id);
      if ($content_id) {
        return show(0, '修改成功', $content_id);
      } else {
        return show(-1, '主表修改成功，副表修改失败', $content_id);
      }
    } else {
      return show(-1, '修改失败'.$res);
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
      foreach ($data as $news_id => $listorder) {
        $data = array(
          'listorder' => intval($listorder)
        );
        $data['update_time'] = date('Y-m-d H:i:s');
        $data['update_user'] = $this->user['username'];
        $res = $this->article->modify($data, $news_id);
        if ($res === false) {
          $errors[] = $news_id;
        }
      }
    } catch(Exception $e) {
      return show(-1, $e->getMessage());
    }
    if ($errors) {
      return show(-1, '排序失败-'.implode(',', $error));
    } else {
      return show(0, '排序成功');
    }
  }

  /**
   * 将选中的文章推荐到相关推荐位中
   */
  public function push() {
    // 获取前端传来数据
    $position_id = $_POST['position_id'];
    $push = $_POST['push'];
    // 校验
    if (!$position_id) {
      return show (0, '没有选择推荐位');
    }
    if (!$push || !is_array($push)) {
      return show(0, '请选择推荐的文章ID进行推荐');
    }
    // 根据传来的id数组获取对应文章
    try {
      $articles = $this->article->getDataByIds($push);
      if (!$articles) {
        return show(-1, '没有相关内容');
      }
      // 循环将相关文章插入推荐位内容表，position_id为前端已选择的推荐位
      $this->load->model('position_content_model', 'position_content');
      foreach ($articles as $article) {
        $data = array(
          'position_id' => $position_id,
          'title' => $article['title'],
          'thumb' => $article['thumb'],
          'news_id' => $article['news_id'],
          'status' => 1,
          'create_time' => $article['create_time']
        );
        $res = $this->position_content->modify($data);
      }
    } catch(Exception $e) {
      return show(-1, $e->getMessage());
    }
    
    return show(0, '推荐成功');
  }

}



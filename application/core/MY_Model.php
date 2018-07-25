<?php
/**
 * 扩展CI_Model，定义公用方法
 */

 class MY_Model extends CI_Model {

  protected $table; // 设置查询表
  protected $primary_key = 'id'; // 表主键，默认为id

  /**
   * 获取当前连接表名
   */
  public function getTable() {
    return $this->table;
  }

  /**
   * 设置当前连接表名
   */
  public function setTable($table) {
    $this->table = $table;
  }

  /**
   * 设置查询条件
   * @param array $where
   * $where 参数格式如下：
   * $where['title'] = array('value' => 'test', 'operator' => '=');
   * $where['id'] = array('value' => array(1, 2, 3), 'operator' => 'in');
   * join子句比较特殊，由于‘join’关键字为键，所以还需要设为二维数组，这样才方便执行多个不同的连接查询
   * $where['join'][] = array('value' => array('t_test as a', 'a.id = b.id'), 'operator' => 'left');
   * $where['create_time'] = array(
   *  'value' => array(
   *    'sdate' => $sdate.' 00:00:00',
   *    'edate' => $edate.' 23:59:59'
   *  ),
   *  'operator' => 'range'
   * );
   */
  /**
   * join子句语法如下：
   * $this->db->select('*');
   * $this->db->from('blogs');
   * $this->db->join('comments', 'comments.id = blogs.id');
   * $query = $this->db->get(); 
   */
  public function setSearchWhere($where) {
    if (!is_array($where)) {
      return false;
    }
    foreach ($where as $key => $item) {
      if ($key === 'join') {
        // 连接查询
        foreach ($item as $joindata) {
          $this->db->join($joindata['value'][0], $joindata['value'][1], $joindata['operator']);
        }
      } elseif ($item['operator'] === 'like' || $item['operator'] === 'likeboth') {
        // 模糊查询
        $this->db->like($key, $item['value']);  
      } elseif ($item['operator'] === 'likeafter') {
        // 右边模糊查询
        $this->db->like($key, $item['value'], 'after');
      } elseif ($item['operator'] === 'likebefore') {
        // 左边模糊查询
        $this->db->like($key, $item['value'], 'before');
      } elseif ($item['operator'] === 'orderby') {
        // 排序 ASC（升序），DESC（降序），不需要用到键
        $this->db->order_by($item['value']);
      } elseif ($item['operator'] === 'in') {
        // in查询
        $this->db->where_in($key, $item['value']);
      } elseif ($item['operator'] === 'range') {
        // 时间区间查询
        if (!empty($item['value']['sdate'])) {
          $this->db->where($key.' >=', $item['value']['sdate']);
        }
        if (!empty($item['value']['edate'])) {
          $this->db->where($key.' <=', $item['value']['edate']);
        }
      } elseif ($item['operator'] === '=') {
        // where查询 简单key/value方式
        $this->db->where($key, $item['value']);
      } else {
        // where查询 自定义key/value方式
        $this->db->where($key.' '.$item['operator'], $item['value']);
      }
    }
  }

  /**
   * 分页查询
   */
  public function paginate($where = array(), $page, $pageSize) {
    if (!$page) {
      $page = 1;
    }
    if (!$pageSize) {
      $pageSize = 10;
    }
    // 查询条件
    $this->db->start_cache();
    $this->setSearchWhere($where); // 由于这里是循环多次查询条件，所以需要把结果缓存起来，才能最后多个查询结果一起执行
    $this->db->stop_cache();

    // 偏移量
    $offset = ($page - 1) * $pageSize;
    // 从$offset开始获取$pageSize条数据
    $this->db->limit($pageSize, $offset);
    // 获取结果集
    $query = $this->db->get($this->table);
    // var_dump($this->db->last_query()); // 打印sql语句测试
    // 查询总数
    $total = $this->db->from($this->table)->count_all_results();
    
    // 从结果集获取数据
    if (!empty($query)) {
      $res = $query->result_array();
    } else {
      $res = array();
    }
    
    // $this->db->flush_cache();
    $result = array();
    $result['data'] = $res;
    $result['total'] = $total;
    $result['hasMore'] = $total > $offset + $pageSize ? true : false;
    return $result;
  }

  /**
   * 根据条件查找数据
   */
  public function findWhere($where) {
    $this->setSearchWhere($where);
    $query = $this->db->get($this->table);
    $res = $query->result_array();
    // var_dump($this->db->last_query());
    return $res ? $res : false;
  }
 }
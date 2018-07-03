<?php
defined('BASEPATH') OR exit ('No direct script access allowed');

class Test extends CI_Controller {
  public function index() {
    $this->load->service('Test_service');
    $this->Test_service->index();
    var_dump('Test index');
  }
}
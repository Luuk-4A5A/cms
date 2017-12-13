<?php

class Dashboard extends Controller {
  protected $requireLogin = true;
  private $css = '';

  public function __construct() {
    $this->checkLogin();
    $this->dbconn = DbHandler::GetInstance();
    $this->css = $this->CreateCSSLinks(['/view/css/custom.css']);
    $this->validator = $this->model('validator');
  }

  public function index() {
    $this->view('view/pages/dashboard/dashboard.php', [
      'title' => 'Dashboard',
      'css'   => $this->css
    ]);
  }

  public function pages($action = '', $id = '') {

    $this->view('view/pages/dashboard/pages.php', [
      'title'   => 'Pages',
      'css'     => $this->css,
      'content' => ''
    ]);
  }

}

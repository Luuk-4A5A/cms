<?php

class Login extends Controller {
  private $validator = null;

  public function __construct() {
    $this->validator = $this->model('validator');
  }

  public function index() {
    $this->view('view/pages/login/login.php', [
      'title' => 'Login to cms',
      'css'   => $this->CreateCSSLinks(['view/css/custom.css']),
      'js'    => $this->CreateJSLinks(['view/javascript/login.js'])
    ]);
  }

  public function post() {
    if(isset($_POST) && empty($_POST)){
      $this->view('view/pages/error/404.php', []);
      return false;
    }

    $filters = [
      'username'  => 'empty|alphanumeric',
      'password'  => 'empty'
    ];

    try {
      $outcome = $this->validator->filter($_POST, $filters);
    } catch(Exception $e) {
      printr($e->getMessage());
    }

    if(is_array($outcome)) {
      jsonp($outcome);
      return;
    }




  }

}

<?php

class Login extends Controller {
  private $validator = null;
  private $user = null;

  public function __construct() {
    $this->validator = $this->model('validator');
    $this->dbconn = DbHandler::GetInstance();
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
      'username'  => 'not_empty',
      'password'  => 'not_empty'
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

    $sanitize_options = [
      'username' => 'trim',
      'password' => ''
    ];

    try {
      $sanitizedPost = $this->validator->sanitize($_POST, $sanitize_options);
    } catch(Exception $e) {
      printr($e->getMessage());
    }

    // $this->user = $this->model('user', $sanitizedPost['username']);
    //
    // printr($this->dbconn->ReadData([
    //   'query'     => 'SELECT user.password FROM user WHERE user.username = :username',
    //   'bindParam' => [':username' => $_POST['username']]
    // ]));

  }

}

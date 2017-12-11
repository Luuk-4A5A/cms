<?php

abstract class Controller {
  protected $requireLogin = false;
  protected static $dbconn;


  public function __construct() {
    static::$dbconn = $this->Dbconn();
    $this->CheckLogin();
  }

  protected function CheckLogin() {
    if($this->requireLogin) {
      session_start();
      if(!$this->checkSession('cms')){
        header('location: /lh-login');
      }
    }
  }


  protected function Dbconn() {
    return new DbHandler(SERVERNAME, DBNAME, USERNAME, PASSWORD);
  }


  protected function view($url, $data = []) {
    if(!file_exists($url)) {
      return false;
    }
    require_once($url);
    return true;
  }


  protected function CreateCSSLinks($arrayOfLinks) {
    $string = '';
    foreach($arrayOfLinks as $cssFilePath) {
      $string .= '<link rel="stylesheet" href="' . $cssFilePath . '">';
    }
    return $string;
  }


  protected function CreateJSLinks($arrayOfJsFiles) {
    $string = '';
    foreach($arrayOfJsFiles as $jsfile) {
      $string .= '<script src="' . $jsfile . '"></script>';
    }
    return $string;
  }


  protected function model($model, array $parameters = []) {
    if(!file_exists('model/' . $model . '.php')) {
      return false;
    }
    require_once('model/' . $model . '.php');
    return new $model($parameters);
  }


  protected function checkSession($sessionName) {
    if(isset($_SESSION[$sessionName])) {
      return true;
    }
    return false;
  }


  abstract public function index();
}

<?php

/**
 * require the config file to use constants globally
 */
 require_once('config.php');
 require_once('controller/controller.php');
 require_once('model/dbhandler.php');
 // require_once('model/model.php');

/**
 * Functions used for testing only.
 */

/**
 * Print_r's out array in pre tags so you dont have to constantly write it out.
 * @param  array $array array to be printed nicely :)
 * @return boolean true on success
 */

function printr($array) {
  echo '<pre>';
  print_r($array);
  echo '</pre>';
  return true;
}

/**
 * Var_dump's out array in pre tags so you dont have to constantly write it out.
 * @param  array $array array to be dumped nicely :)
 * @return boolean true on success
 */

function vardump($array) {
   echo '<pre>';
   var_dump($array);
   echo '</pre>';
   return true;
 }

function jsonp($array) {
  echo json_encode($array);
}

/**
 * End of the test functions.
 */

/**
 * Router class catches url request and processes it into a controller and method. Sends parameters with it aswell.
 */

final class Router {
  private $url = [];
  private $dbhandler = null;

  public function __construct() {
    $this->dbhandler = new DbHandler('localhost', 'l-cms', 'root', '');
    $this->url = $this->ProcessUrl();
    $this->DetermineDestination();
  }

  /**
   * gets the url and splits it up in an array by using the '/'.
   * @return array processed url
   */
  private function ProcessUrl() {
    $url = (isset($_GET['url']) && !empty($_GET['url'])) ? $_GET['url'] : '';
    $url = explode('/', $url);
    return $url;
  }


  /**
   * uses the url we made in ProcessUrl() and determines the classname, method, and the parameters to be send into the classes.
   * @return null
   */
  private function DetermineDestination() {
    $arrayValues = array_values($this->url);
    $parameters = [];

    (isset($arrayValues[0]) && $arrayValues[0] == 'cms') ? header('location: /lh-login'): '';
    (isset($arrayValues[1]) && empty($arrayValues[1])) ? header('location: /' . $arrayValues[0]) : '';

    $class = (isset($arrayValues[0]) && $arrayValues[0] != '') ? str_replace('-', '', $arrayValues[0]) : 'init';
    $method = (isset($arrayValues[1]) && $arrayValues[1] != '') ? $arrayValues[1] : 'index';

    for($i = 2; $i < count($this->url); $i += 1) {
      array_push($parameters, $arrayValues[$i]);
    }


    $this->SendToDestination($class, $method, $parameters);
  }


  /**
   * Checks if the classname is existent in the database.
   * If not, its a custom page, wich we made a controller for.
   * If so, its a page made by the user and we have a generic class made for that.
   * @param string $class      name of the class/page.
   * @param string $method     method to be called if the page is not existing in the db.
   * @param string $parameters possible parameters to be used for the methods
  */

  private function SendToDestination($class, $method, $parameters) {
    if($this->CheckIfCMSPage($class)) {
      $this->CMS($class, $method, $parameters);
      return true;
    }

    $this->CustomMadePage($class, $method, $parameters);
    return true;
  }



  private function CustomMadePage($class, $method, $parameters) {
    if(!file_exists('controller/' . $class . '.php')) {
      require_once('view/pages/error/404.php');
      return false;
    }

    require_once('controller/' . $class . '.php');
    $class = new $class;

    $this->CallController($class, $method, $parameters);
  }


  private function CheckIfCMSPage($pageName) {
    $check = $this->dbhandler->ReadData([
      'query' => 'SELECT NULL FROM page WHERE page.page_name = :pagename',
      'bindParam' => [':pagename' => $pageName]
    ]);
    if(empty($check)) {
      return false;
    }
    return true;
  }


  private function CMS($pageName, $method, $parameters) {
    require_once('controller/cms.php');
    $class = new Cms($pageName, $method, $parameters);
    $this->CallController($class, $method, $parameters);
  }


  private function CallController($class, $method, $parameters) {
    if(!method_exists($class, $method)) {
      require_once('view/pages/error/404.php');
      return false;
    }
    call_user_func_array([$class, $method], $parameters);
    return true;
  }

}

$router = new Router();

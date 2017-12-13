<?php

class User {
  protected $username = '';
  protected $dbhandler = null;
  protected $userData = [];

  public function __construct($username) {
    $this->username = $username;
    $this->dbhandler = DbHandler::GetInstance();

    $user = $this->dbhandler->ReadData([
      'query'     => 'SELECT * from user WHERE username = :username',
      'bindParam' => [':username' => $this->username]
    ]);

    if(!empty($user)) {
      $this->userData($user[0]);
    }


  }

  public function userData($data = []) {
    if(empty($data)) {
      return $this->userData;
    }

    $this->userData = $data;
  }

  public function checkLogin($password) {
    if(empty($this->userData)) {
      return false;
    }

    if(password_verify($password, $this->userData['password'])) {
      return true;
    }

    return false;
  }


}

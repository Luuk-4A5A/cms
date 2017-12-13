<?php

class Init extends Controller {


  public function index() {
    $this->view('view/pages/home/home.php', [
      'title' => 'Home',
      'css'   => $this->CreateCSSLinks(['view/css/custom.css'])
    ]);
  }
}

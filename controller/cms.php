<?php

class cms extends Controller {
  private $dbhandler = null;
  private $pagename = '';
  private $method = '';
  private $parameters = [];


  public function __construct($pageName, $method, $parameters) {
    $this->pagename = $pageName;
    $this->method = $method;
    $this->parameters = $parameters;
    $this->dbhandler = $this->DbConn();
  }

  public function index() {
    $pageContent = $this->dbhandler->ReadData([
      'query'     => 'SELECT content.content FROM content INNER JOIN page ON page.page_id = content.pages_page_id WHERE page.page_name = :pagename',
      'bindParam' => [':pagename' => $this->pagename]
    ])[0]['content'];


    $this->view('view/pages/cms/cms.php', [
      'content' => $pageContent,
      'title'   => $this->pagename
    ]);

    return null;
  }
}

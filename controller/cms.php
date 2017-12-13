<?php

class cms extends Controller {
  private $pagename = '';
  private $method = '';
  private $parameters = [];



  public function __construct($pageName, $method, $parameters) {
    $this->dbconn = DbHandler::GetInstance();
    $this->pagename = $pageName;
    $this->method = $method;
    $this->parameters = $parameters;
  }

  public function index() {
    $pageContent = $this->dbconn->ReadData([
      'query'     => 'SELECT content.content FROM content INNER JOIN page ON page.page_id = content.pages_page_id WHERE page.page_name = :pagename',
      'bindParam' => [':pagename' => $this->pagename]
    ])[0]['content'];


    $this->view('view/pages/cms/cms.php', [
      'content' => $pageContent,
      'title'   => $this->pagename,
      'css'     => $this->CreateCSSLinks(['view/css/master.css'])
    ]);

    return null;
  }
}

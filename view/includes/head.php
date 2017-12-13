<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?= (isset($data['title'])) ? $data['title'] : WEBSITE_TITLE; ?></title>
    <?= (isset($data['css'])) ? $data['css'] : ''; ?>
    <script src="view/javascript/main.js"></script>
  </head>

  <body>

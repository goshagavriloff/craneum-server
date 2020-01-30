<?php
$body =json_encode(array ('Code'=>'403 Forbidden','content'=>array('message'=>'You need authorization')));
//return $body;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    {{$body}}
  </body>
</html>

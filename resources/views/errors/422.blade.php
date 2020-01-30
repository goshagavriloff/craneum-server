<?php
$body =json_encode(array ('Code'=>'422 Unprocessable entity','content'=>json_decode($exception->getMessage())));
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

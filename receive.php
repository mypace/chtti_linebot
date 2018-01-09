<?php
  $json_str=file_get_contents('php://input'); //接收REQUEST的BODY. 此為string format
  $json_obj=json_decode($json_str); //轉為json格式

  $myfile=fopen("log.txt","w+") or die("Unable to open file");
  fwrite($myfile, "\xEF\xBB\xBF".$json_str);  //在字串前加入\xEF\xBB\xBF 轉成utf-8格式
  fclose($myfile);
?>

<?php

  ///$db = new mysqli ('localhost','6239010023','pass6239010023','6239010023');
  $station_name = $_GET["st"];
  $count = $_GET["c"];
  $sql = "insert into handgel ("station_name,count") values ('".$station_name."','".$count."')";
  print $sql;
  //$db -> query($sql);
?>

<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  $db = new mysqli ("localhost","6239010023","pass6239010023","6239010023");
  $station_name = $_GET["st"];
  $count = $_GET["c"];
  $sql = "insert into handgel ("station_name,count") values ('".$station_name."','".$count."')";
  print $sql;
  $db -> query($sql);
?>

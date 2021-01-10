<?php
  require_once('db.php');

  $qry = "select temp , COUNT(temp) as counttemp FROM checkted GROUP BY temp";
  $rst = mysqli_query($db, $qry);
  $row = array();
  $data = array();

  $data['col'] = array(
    array(
      'label' => 'temp',
      'type' => 'number'
    ),
    array(
      'label' => 'counttemp',
      'type' => 'number'
    )
    );
    while($row = mysqli_fetch_array($rst)){
      $sub_array = array();
      $sub_array[] = array("v" => $row["temp"]);
      $sub_array[] = array("v" => $row["counttemp"]);
      $rows[] = array(
        "c" => $sub_array
      );
    }
  $data['rows'] = $rows;
  $jsontable = json_encode($data);
  echo $jsontable;
?>
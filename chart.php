<?php
  require_once('db.php');

  header('Content-type: application/json');

  $sql = "select st_hand as names, count(id) as number from checkted Group by st_hand";
  $rst = mysqli_query($db, $sql);

  $objQuery = $db->query($strSQL);
	$intNumField = $objQuery->field_count;
	$resultArray = array();
	while($obResult = $objQuery->fetch_array())
	{
		$arrCol = array();
		for($i=0;$i<$intNumField;$i++)
		{
			$arrCol[$objQuery->fetch_field_direct($i)->name] = $obResult[$i];
		}
		array_push($resultArray,$arrCol);
	}
	
	
	echo json_encode($resultArray);
?>
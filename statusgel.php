<?php
require_once("db.php");
	$strSQL = "select * from stations";

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
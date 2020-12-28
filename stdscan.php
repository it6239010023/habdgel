<?php
require_once("db.php");
	$strSQL = "select id, studentID, prefix, fullname, record, temp, st_health from student st inner join checkted ch on st.rfidID = ch.rfidID ORDER BY ch.id DESC LIMIT 0,5";

    
    $objQuery = $db->query($objConnect,$strSQL);
	$intNumField = $objQuery->field_count;
	$resultArray = array();
	while($obResult = $objquery->fetch_array())
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
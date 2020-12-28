<?php
    // $objConnect = mysqli_connect("localhost","root","","handgels");
    $objConnect = mysqli_connect("localhost","6239010023","pass6239010023","6239010023");
	$strSQL = "select id, studentID, prefix, fullname, record, temp, st_health from student st inner join checkted ch on st.rfidID = ch.rfidID ORDER BY ch.id DESC LIMIT 0,5";

    
    $objQuery = mysqli_query($objConnect,$strSQL);
	$intNumField = mysqli_num_fields($objQuery);
	$resultArray = array();
	while($obResult = mysqli_fetch_array($objQuery))
	{
		$arrCol = array();
		for($i=0;$i<$intNumField;$i++)
		{
			$arrCol[mysqli_fetch_field_direct($objQuery,$i)->name] = $obResult[$i];
		}
		array_push($resultArray,$arrCol);
	}
	
	mysqli_close($objConnect);
	
	echo json_encode($resultArray);
?>
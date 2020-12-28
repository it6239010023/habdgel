<?php
  $db = new mysqli("localhost","6239010023","pass6239010023","6239010023");
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }else {
    
  if(isset($_GET["rfid"])){

        $rfid = $_GET["rfid"];
        $temp = $_GET["temp"];
        $heal = $_GET["heal"];
        $sql = "insert into checkted (rfidID,temp,st_health) values ('".$rfid."','".$temp."','".$heal."')";
        $db -> query($sql);
        //print $sqla. " success";

        //อัพเดทอุณหภูมินักเรียน
        $sql = "update student set Tempreture = '".$temp."' where rfidID = '".$rfid."'";
        $db -> query($sql);

        //
        $strSQL = "select id, studentID, prefix, fullname, record, temp, st_health from student st inner join checkted ch on st.rfidID = ch.rfidID ORDER BY ch.id DESC LIMIT 0,5";
	      $objQuery = $db->query($strSQL);
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

        //หาข้อมูลนักเรียน
        $sql = " select * from student where rfidID = ".$_GET["rfid"]."";
        //print $sqls ." success";
        $rst = $db -> query($sql);
        if ($rst->num_rows > 0) {
            while($row = $rst->fetch_assoc()) {
              echo "เลขที่นักเรียน : " .$row["studentID"]. " " .$row["prefix"]."".$row["fullname"]."";
              echo " มีค่าอุณหภูมิ : ".$row["Tempreture"]. " c*" ." สถานะ : ".$heal."";
            }
          } else {
            echo "ข้อมูลผิดพลาด";
          }

  }else{
        print "ไม่มีข้อมูล";
    }
  }
?>
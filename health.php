<?php
require_once("db.php");
   
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

?>
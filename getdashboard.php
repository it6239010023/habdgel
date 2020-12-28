<?php
//$db = new mysqli("localhost","root","","handgels");
$db = new mysqli("localhost","6239010023","pass6239010023","6239010023");
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}else {

$datenow = date('Y-m-d');

// สรุปผู้ใช้งานทั้งหมด
$rst = $db->query("select count(id) as total from checkted where record like '$datenow%'");
$data = $rst->fetch_assoc();
$total = $data['total'];
// หาคนเสี่ยงติดโควิด
$rst = $db->query("select count(temp) as risk from checkted where record like '$datenow%' and temp between 37.5 and 39");
$data = $rst->fetch_assoc();
$risk = $data['risk'];
// สรุปอุณหภูมิเฉลี่ย
$rst = $db->query("select avg(temp) as avgtemp from checkted where record like '$datenow%' ");
$data = $rst->fetch_assoc();
$avgtemp = number_format($data['avgtemp'],2);


// หาคนร่างกายปรกติ
$rst = $db->query("select count(temp) as fine from checkted where record like '$datenow%' and temp between 33 and 37");
$data = $rst->fetch_assoc();
$fine = $data['fine'];
}

$outArr=array("total"=>$total,"risk"=>$risk,"avgtemp"=>$avgtemp,"fine"=>$fine);
$jsonResponse=json_encode($outArr);
echo $jsonResponse;
?>
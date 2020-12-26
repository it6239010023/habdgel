<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">

</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <?php
$db = new mysqli ('localhost','root','','handgels');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}else {

  $datenow = date('Y-m-d');

  $sql = "select count(id) as total from checkted where record like '$datenow%'";
  $result = $db->query($sql);
  $data = $result->fetch_assoc();
  $count = $data['total'];
  ?>

<!-- จำนวนคนกดเจล -->
<div class="container-fluid">
    <div class="row">
      <!-- <div class="col-lg-3 col-3"></div> -->
      <div class="col-lg-3 col-3">
        <div class="small-box bg-info">
          <div class="inner">
              <h3><?php echo $count ?></h3>
              <p>จำนวนคนกดเจลวันนี้</p>
          </div>
          <div class="icon">
                <i class="ion ion-person"></i>
          </div>
        </div>
      </div>
    </div>
</div>


<?php



$sql = "select max(temp) as maxtemp, min(temp) as mintemp from checkted where record like '$datenow%' and temp between 33 and 39";
$result = $db->query($sql);
$data = $result->fetch_assoc();
$maxtemp = $data['maxtemp'];
$mintemp = $data['mintemp'];
echo "อุณหภูมิสูงสุดคือ" . $maxtemp . " *c" ;
echo "อุณหภูมิต่ำคือ" . $mintemp . " *c" ;

$sqlt = "select id, studentID, prefix, fullname, record, temp, st_health from student st inner join checkted ch on st.rfidID = ch.rfidID ORDER BY ch.id DESC LIMIT 0,5";

echo '<table id="example" class="table table-save" style="width:50%">
<thead>
      <tr"> 
        <th>เลขที่</th> 
        <th>เลขประจำตัวนักเรียน</th> 
        <th>ชื่อ-นามสกุล</th> 
        <th>วันเวลาบันทึก</th> 
        <th>อุณหภูมิ</th>
        <th>สถานะ</th> 
      </tr>
      </thead>
      <tbody>';
      
if ($result = $db->query($sqlt)) {
    while ($row = $result->fetch_assoc()) {
        $row_id = $row["id"];
        $row_studentID = $row["studentID"];
        $row_name = $row["prefix"].$row["fullname"];
        $row_record = $row["record"];
        $row_temp = $row["temp"];
        $row_st_health = $row["st_health"];

        echo '<tr> 
                <td>' . $row_id . '</td> 
                <td>' . $row_studentID . '</td> 
                <td>' . $row_name . '</td> 
                <td>' . $row_record . '</td> 
                <td>' . $row_temp . " *C" . '</td> 
                <td>' . $row_st_health . '</td> 
              </tr>';
    }
    echo '</tbody>
    <tr"> 
      <td>เลขที่</td> 
      <td>เลขประจำตัวนักเรียน</td> 
      <td>ชื่อ-นามสกุล</td> 
      <td>วันเวลาบันทึก</td> 
      <td>อุณหภูมิ</td>
      <td>สถานะ</td> 
    </tr>
    </table>';
    $result->free();
  }else{
    echo "ERROR";
  }
}
$db->close();
?>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="plugins/sparklines/sparkline.js"></script>
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="dist/js/adminlte.js"></script>
<script src="dist/js/demo.js"></script>
<script src="dist/js/pages/dashboard.js"></script>
<script>$(document).ready(function() {$('#example').DataTable();} );</script>
</body>
</html>


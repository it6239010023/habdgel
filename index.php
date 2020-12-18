<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
  <!-- data table -->
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
<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $count ?></h3>

                <p>จำนวนคนกดเจลวันนี้</p>
              </div>
              <div class="icon">
                <i class="ion ion-person"></i>
              </div>
              <a href="#" class="small-box-footer"></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>

                <p>Bounce Rate</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>44</h3>

                <p>User Registrations</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>65</h3>

                <p>Unique Visitors</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>


<?php



$sql = "select max(temp) as maxtemp, min(temp) as mintemp from checkted where record like '$datenow%' and temp between 33 and 39";
$result = $db->query($sql);
$data = $result->fetch_assoc();
$maxtemp = $data['maxtemp'];
$mintemp = $data['mintemp'];
echo "อุณหภูมิสูงสุดคือ" . $maxtemp . " *c" ;
echo "อุณหภูมิต่ำคือ" . $mintemp . " *c" ;

$sqlt = "select id, studentID, prefix, fullname, record, temp, st_health from student st inner join checkted ch on st.rfidID = ch.rfidID ORDER BY ch.id ASC";

echo '<table id="example" class="display" style="width:100%">
<thead>
      <tr"> 
        <td>เลขที่</td> 
        <td>เลขประจำตัวนักเรียน</td> 
        <td>ชื่อ-นามสกุล</td> 
        <td>วันเวลาบันทึก</td> 
        <td>อุณหภูมิ</td>
        <td>สถานะ</td> 
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
    <tfoot>
    <tr"> 
      <td>เลขที่</td> 
      <td>เลขประจำตัวนักเรียน</td> 
      <td>ชื่อ-นามสกุล</td> 
      <td>วันเวลาบันทึก</td> 
      <td>อุณหภูมิ</td>
      <td>สถานะ</td> 
    </tr>
    </tfoot>
    </table>';
    $result->free();
  }else{
    echo "ERROR";
  }
}
$db->close();
?>
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable();
} );
</script>
</body>
</html>


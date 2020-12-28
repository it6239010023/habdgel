<!DOCTYPE html>
<html lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<head>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
    <title>Dashboard</title>
<style>
        .fa{
        position: relative;
        font-size: 3em;
        width: 100%;
        padding: 5px;
        }

        .fa.float-style>i {
        float: right;
        line-height:26px; 
        }
	</style>
	
<script>


function getDataFromDb()
{
	$.ajax({ 
				url: "stdscan.php" ,
				type: "POST",
				data: ''
			})
			.success(function(result) { 
				var obj = jQuery.parseJSON(result);
					if(obj != '')
					{
						  //$("#myTable tbody tr:not(:first-child)").remove();
						  $("#myBody").empty();
						  $.each(obj, function(key, val) {
									var tr = "<tr>";
									tr = tr + "<td>" + val["studentID"] + "</td>";
									tr = tr + "<td>" + val["fullname"] + "</td>";
									tr = tr + "<td>" + val["record"] + "</td>";
									tr = tr + "<td>" + val["temp"] + "</td>";
									tr = tr + "<td>" + val["st_health"] + "</td>";
									tr = tr + "</tr>";
									$('#myTable > tbody:last').append(tr);
						  });
					}

			});

}

 setInterval(getDataFromDb, 1000);// 1000 = 1 second

function getdashboard(){
    $.ajax({
  	type: "GET",
  	url: "getdashboard.php",                 
  	success: function(data) { 
    if (data && data.length > 0) {    
      data=$.parseJSON(data); 
      $("#totalscan").empty(); 
      $("#totalscan").append(data.total);
      $("#risk").empty(); 
      $("#risk").append(data.risk);
      $("#fine").empty(); 
      $("#fine").append(data.fine);
      $("#avgtemp").empty(); 
      $("#avgtemp").append(data.avgtemp);

    }
  }
})
}
setInterval(getdashboard, 1000);

</script>

</head>
<body>
<div class ="container">
        &nbsp;    
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                    </ol>
                </nav>
    <h1 class="h2">HANDGEL IOT</h1>
    <p>โครงการเครื่องจ่ายเจลอัตโนมัติ : แถบสรุปสถานะทั้งหมด</p>
    
        <div class="row my-4">
                    <div class="col-12 col-md-6 col-lg-3 mb-4 mb-lg-0">
                        <div class="card">
                            <h5 class="card-header">ผู้ใช้งานทั้งหมด</h5>
                            <div class="card-body">
                            <div class="fa float-style">
                            <i class="fas fa-users"></i>
                            </div>
                              <h5 class="card-title"><span id = "totalscan"> </span> คน</h5>
                              <p class="card-text">ภาคเรียนที่ 2/2563</p>
							  <p class="card-text text-success">จำนวนผู้ใช้งานทั้งหมด </p>
                            </div>
                          </div>
                    </div>
                    <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">ความเสี่ยง</h5>
                            <div class="card-body">
                            <div class="fa float-style">
                            <i class="fas fa-head-side-cough"></i>
                            </div>
                              <h5 class="card-title"><span id = "risk"> </span> คน</h5>
                              <p class="card-text">ภาคเรียนที่ 2/2563</p>
                              <p class="card-text text-danger">จำนวนคนที่เสี่ยงติดโควิดทั้งหมด</p>
                            </div>
                          </div>
                    </div>
                    <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">อุณหภูมิปกติ</h5>
                            <div class="card-body">
                            <div class="fa float-style">
                            <i class="fas fa-heartbeat"></i>
                            </div>
                              <h5 class="card-title"><span id = "fine"> </span> คน</h5>
                              <p class="card-text">ภาคเรียนที่ 2/2563</p>
                              <p class="card-text text-success">จำนวนคนอุณหภูมิปกติทั้งหมด</p>
                            </div>
                          </div>
                    </div>
                    <div class="col-12 col-md-6 mb-4 mb-lg-0 col-lg-3">
                        <div class="card">
                            <h5 class="card-header">อุณหภูมิเฉลี่ย</h5>
                            <div class="card-body">
                            <div class="fa float-style">
                            <i class="fas fa-thermometer-three-quarters"></i>
                            </div>
                              <h5 class="card-title"><span id = "avgtemp"> </span> ํC</h5>
                              <p class="card-text">ภาคเรียนที่ 2/2563</p>
                              <p class="card-text text-success">อุณหภูมิร่างกายเฉลี่ยทั้งหมด</p>
                            </div>
                        </div>
                    </div>
                </div>

&nbsp;


 <div class="col-9 col-md-9 col-lg-9 mb-9 mb-lg-0">
    <div class = "card">
    <h5 class="card-header">นักเรียนที่แจ้งเตือนล่าสุด</h5>
        <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-bordered" style="width:100%" id="myTable">
      <!-- head table -->
            <thead>
  <tr>
    <th > <div align="center">รหัส</div></th>
    <th > <div align="center">ชื่อ-สกุล </div></th>
    <th > <div align="center">เวลาบันทึก </div></th>
    <th> <div align="center">อุณหภูมิ</div></th>
    <th> <div align="center">สถานะ</div></th>
  </tr>
</thead>
<!-- body dynamic rows -->
<tbody id="myBody" >

</tbody>
</table>

<script src="http://code.jquery.com/jquery-latest.js"></script>
 <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> -->

    <script>$(document).ready(function() {
    $('#myTable').DataTable( {
        //"pageLength": 3,
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,
        "lengthChange": false
    } );
    } );</script>

</body>
</html>
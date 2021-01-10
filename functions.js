
// ตารางนักเรียน
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
                  					tr = tr + "<td>" + val["record"] + "</td>";
									tr = tr + "<td>" + val["studentID"] + "</td>";
									tr = tr + "<td>" + val["prefix"] + val["fullname"] + "</td>";
									tr = tr + "<td>" + val["temp"] + "ํ ํC" + "</td>";
									tr = tr + "<td>" + val["st_health"] + "</td>";
									tr = tr + "</tr>";
									$('#myTable > tbody:last').append(tr);
						  });
					}

			});

}
setInterval(getDataFromDb, 1000);// 1000 = 1 second

// // ตารางจ่ายเจล
// function getDatastatusgel()
// {
// 	$.ajax({ 
// 				url: "statusgel.php" ,
// 				type: "POST",
// 				data: ''
// 			})
// 			.success(function(result) { 
// 				var obj = jQuery.parseJSON(result);
// 					if(obj != '')
// 					{
// 						  //$("#myTable tbody tr:not(:first-child)").remove();
// 						  $("#mygel").empty();
// 						  $.each(obj, function(key, val) {
// 									var tr = "<tr>";
// 									tr = tr + "<td>" + val["time_updated"] + "</td>";
// 									tr = tr + "<td>" + val["station_name"] + "</td>";
// 									tr = tr + "<td>" + val["count"] + " คน" + "</td>";
// 									tr = tr + "<td>" + val["st_gels"] + "</td>";
// 									tr = tr + "</tr>";
// 									$('#mygels > tbody:last').append(tr);
// 						  });
// 					}

// 			});

// }
// setInterval(getDatastatusgel, 1000);// 1000 = 1 second

// ตารางนับจำนวน
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


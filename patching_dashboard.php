<!DOCTYPE>
<html>
<head>
<meta http-equiv="refresh" content="30" />
<style>
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: inline;
}
li {
font-size: 2.9em;
    display: block;
    height: 90px;
    float: left;
    padding:40px 14px 10px 10px ;
}
#head {
}
.btn {
  -webkit-border-radius: 14;
  -moz-border-radius: 14;
  border-radius: 14px;
  font-family: Arial;
  color: #ffffff;
  font-size: 20px;
  text-decoration: none;
}
.FAILED {
background-color: red;
color: white;
  padding: 10px 80px 10px 80px;
}
.COMPLETED {
background-color: green;
color: white;
  padding: 10px 50px 10px 50px;
}

.RUNNING {
  background-color: #CCCC00;
color: white;
  padding: 10px 70px 10px 70px;
}
.QUEUED {
color: white;
background-color: #005C99;
  padding: 10px 72px 10px 72px;
}
.CANCELLED {
background-color: gray;
color: white;
  padding: 10px 55px 10px 55px;
}
tr {
font-size: 2.4em;
font-family: Arial;
}
tr td {
    color: white;
}
tr.alt td {
    color: white;
}
     body {
        background-color: #003366;
        /*background-size: 100%;
        background-repeat: no-repeat;*/
        /*background: url(../dashboard/jg/de.jpeg) no-repeat center center fixed;*/
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
        }

#footer {
}
tr.top td { border-top: thin solid white; }
tr.bottom td { border-bottom: thin solid white; }
ttr.left td { border-left: thin solid white; }
xxtr.right td { border-right: thin solid white; }
ttr.row td:first-child { border-left: thin solid white; }
ttr.row td:last-child { border-right: thin solid white; }
 th {
    font-size: 1.1em;
    text-align: left;
    padding-bottom: 4px;
}
</style>

<!-- DataTables CSS -->
<link href="../DataTables-1.10.6/media/css/jquery.dataTables.css" type="text/css" rel="stylesheet">
  
<!-- jQuery -->
<script src="../DataTables-1.10.6/media/js/jquery-1.11.1.min.js" charset="utf8" type="text/javascript"></script>
<!-- DataTables -->
<script src="../DataTables-1.10.6/media/js/jquery.dataTables.min.js" charset="utf8" type="text/javascript"></script>
<link href="../helpdesk/charts/circful/jquery-plugin-circliful-master/css/jquery.circliful.css" rel="stylesheet" type="text/css" />
<link href="../helpdesk/charts/circful/jquery-plugin-circliful-master/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<script src="../helpdesk/charts/circful/jquery-plugin-circliful-master/js/jquery.circliful.min.js"></script>

<title> EIS - Maintenance Status</title>
</head>
<body > 
<script>
$(document).ready( function () {
    $('#currenit_list').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false
    });
} );
</script>
<div id="CURRENT" style="padding-top:50px;width: 100%;">
 <h1 align="center" style=""><span  style="padding: 0px 7px 7px;color: white;align: center;font-size:40px;"> Maintenance Status</span></h1>
 <h3 align="center" style=""><span  style="padding: 0px 7px 7px;color: white;align: center;"> Last 24 Hours + 6 Hours ahead</span></h3>
<?php
include("vars.php");
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
 $date = date('Y-m-d');
    $status = "  where scheduled_date like '$date%' ";
    $sql = "select id, host, status, scheduled_date, started_date, completed_date, os_type, logfile,sla from maintenance_requests_queue where scheduled_date >= DATE_ADD(NOW() , INTERVAL -1 DAY )  and scheduled_date <= DATE_ADD( NOW() , INTERVAL 6 HOUR  ) and status not like 'AUTO_%' order by scheduled_date";
#echo $sql;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $tbody = array("FAILED" => '', "COMPLETED" =>'', "CANCELLED"=>'',"RUNNING" =>'', "QUEUED" => '');
    $statuscount = array("FAILED" => 0, "COMPLETED" =>0, "CANCELLED"=>0,"RUNNING" =>0 , "QUEUED" => 0);
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $count = 1;
    foreach(($stmt->fetchAll()) as $k=>$v) {
      $value = 'top bottom row';
      if($count%2 == 0) {
      $value = 'alt top bottom row';
      }
      $count++;
      $statuscount[$v['status']] += 1;
      $tbody[$v['status']] .= "<tr class='$value'>"
      ."<td>".$v['host']."</td><td>".$v['sla']."</td><td><img style='width: 60px; height: 50px;' src='".$v['os_type'].".png' > </img></td><td>".$v['scheduled_date']."</td><td>"
          ."<span class='btn ".$v['status']."' ><b>".$v['status']."<b></span>"
      ."</td></tr>";
    }
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;
?>
<div style="margin:auto;width: 95%;height: 100px;">
<div  id="TOTAL" style="float: left;" data-dimension="290" data-text="<?php echo $statuscount['FAILED']+$statuscount['FAILED']+$statuscount['RUNNING']+$statuscount['COMPLETED']+$statuscount['CANCELLED']+$statuscount['QUEUED']; ?>" data-info="TOTAL" data-width="30" data-fontsize="50" data-percent="35" data-fgcolor="" data-bgcolor="#fff" data-type="half" data-fill="#ddd"></div>
<div id="COMPLETED" style="float: left;" data-dimension="290" data-text="<?php echo $statuscount['COMPLETED']; ?>" data-info="COMPLETED" data-width="30" data-fontsize="50" data-percent="35" data-fgcolor="#7ea568" data-bgcolor="#eee" data-type="half" data-fill="#ddd"></div>
<div style="float: left;" id="FAILED" data-dimension="290" data-text="<?php echo $statuscount['FAILED']; ?>" data-info="Failed" data-width="30" data-fontsize="50" data-percent="35" data-fgcolor="red" data-bgcolor="#eee" data-type="half" data-fill="#ddd"></div>
<div style="float: left;" id="RUNNING" data-dimension="290" data-text="<?php echo $statuscount['RUNNING']; ?>" data-info="RUNNING" data-width="30" data-fontsize="50" data-percent="35" data-fgcolor="yellow" data-bgcolor="#eee" data-type="half" data-fill="#ddd"></div>
<div id="CANCELLED" style="float: left;" data-dimension="290" data-text="<?php echo $statuscount['CANCELLED']; ?>" data-info="CANCELLED" data-width="30" data-fontsize="50" data-percent="35" data-fgcolor="grey" data-bgcolor="#eee" data-type="half" data-fill="#ddd"></div>
<div id="QUEUED"  style="float: left;" data-dimension="290" data-text="<?php echo $statuscount['QUEUED']; ?>" data-info="QUEUED" data-width="30" data-fontsize="50" data-percent="35" data-fgcolor="#005C99" data-bgcolor="#eee" data-type="half" data-fill="#ddd"></div>
</div>
<script>
$( document ).ready(function() {
        $('#TOTAL').circliful();
        $('#COMPLETED').circliful();
                $('#FAILED').circliful();
                $('#CANCELLED').circliful();
                $('#RUNNING').circliful();
    $('#QUEUED').circliful();
    });
</script>
</div>
<div style="float:clear;padding-left:90px;padding-top:130px;position:absolute;width:90%;">
<table id="current_list" class="display" cellspacing="0" border=0 width="100%" data-page-length="50" data-order="[[ 1, &quot;asc&quot; ]]">
<thead>
<tr style='color: white;'  class= 'top bottom row'>
<th> Hostname </th><th>SLA </th> <th>OS </th> <th>Scheduled At </th> <th>Status</th>
</tr>
</thead>
<tbody>
<?php
     echo $tbody['FAILED'];
     echo $tbody['RUNNING'];
     #echo $tbody['CANCELLED'];
     echo $tbody['QUEUED'];
?>
</tbody>
</table>
</div>
</body>
</html>

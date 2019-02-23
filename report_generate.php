<!DOCTYPE html>
<html>
<?php 
include('header.php'); 
#print_r($_POST);
    $team = array('ITOC','Unix','Windows','All');
    $auto = array('Manual','Auto','All');
    $from="";
    $to="";
    $select = "";
    $ord = "";
    $ncondition = "";
    $pcondition = "";
    if(preg_match('/daily/',$_REQUEST['type']) ) {
      $select = "DAYOFMONTH(scheduled_date) as DATE,CONCAT(CONCAT(YEAR(scheduled_date),DATE_FORMAT(scheduled_date,'%m')),DATE_FORMAT(scheduled_date,'%d')) as ORD";
      $from=$_REQUEST['year']."-".$_REQUEST['month']."-01";
      $to=$_REQUEST['year']."-".$_REQUEST['month']."-31";
    }
    else if(preg_match('/weekly/',$_REQUEST['type']) ){
      $select = "concat(SUBSTR(MONTHNAME(scheduled_date),1,3),WEEK(scheduled_date,5)-WEEK(DATE_SUB(scheduled_date, INTERVAL DAYOFMONTH(scheduled_date)-1 DAY),5)+1) as DATE,(YEAR(scheduled_date)*100+MONTH(scheduled_date))*10+WEEK(scheduled_date,5)-WEEK(DATE_SUB(scheduled_date, INTERVAL DAYOFMONTH(scheduled_date)-1 DAY),5)+1 as ORD";
      $from=$_REQUEST['from'];
      $to=$_REQUEST['to'];
    }
    else {
      $select = "concat(SUBSTR(MONTHNAME(scheduled_date),1,3),YEAR(scheduled_date)) as DATE,YEAR(scheduled_date)*100+MONTH(scheduled_date) as ORD";
      $from=$_REQUEST['from'];
      $to=$_REQUEST['to'];
    }
    if($_REQUEST['team'] == 0) {
	$ncondition = "and hostname like 'dev%'";
	$pcondition = "and maintenance_requests_queue.host like 'dev%'";
    }
    else if($_REQUEST['team'] == 1) {
	$ncondition = "and hostname not like 'dev%' and maintenance_registered_hosts.os_type != 'WINDOWS' ";
	$pcondition = "and maintenance_requests_queue.host not like 'dev%' and maintenance_registered_hosts.os_type != 'WINDOWS' ";
    }
    else if($_REQUEST['team'] == 2) {
	$ncondition = "and maintenance_registered_hosts.os_type = 'WINDOWS' ";
	$pcondition = "and maintenance_registered_hosts.os_type = 'WINDOWS' ";
    }
    if($_REQUEST['auto'] == 0) {
        $pcondition .= " and maintenance_requests_queue.auto_patch='N'";
    }
    else if($_REQUEST['auto'] == 1) {
        $pcondition .= " and maintenance_requests_queue.auto_patch='Y'";
    }
    else if($_REQUEST['auto'] == 2) {
    }
    	
?>
  <script type="text/javascript">
  window.onload = function () {
    var notify_chart = new CanvasJS.Chart("notchartContainerCount",
    {
      title:{
        text: "Notification report by <?php echo $_REQUEST['type'];?>"    
      },
      animationEnabled: true,
      axisY: {
        title: "No Of Notification"
      },
      legend: {
        verticalAlign: "bottom",
        horizontalAlign: "center"
      },
      theme: "theme2",
      data: [

      {        
        type: "column",  
        showInLegend: true, 
        legendMarkerColor: "grey",
        legendText: "Day of Month",
        dataPoints: [      
<?php
include "vars.php";
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
#print_r($_REQUEST);
    $sql = "select count(*) as Count,$select from maintenance_requests,maintenance_registered_hosts where maintenance_registered_hosts.host = maintenance_requests.hostname and scheduled_date >= '$from' and scheduled_date <= '$to' $ncondition group by DATE,ORD order by  ORD";
#echo $sql;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

#    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach(($stmt->fetchAll()) as $k=>$v) {
?>
        {y: <?php echo $v['Count'];?>,  label: "<?php echo $v['DATE'];?>"},        
<?php
}
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;

?>
        ]
      }   
      ]
    });

    notify_chart.render();

    var chart = new CanvasJS.Chart("chartContainerCount",
    {
      title:{
        text: "Patching report"    
      },
      animationEnabled: true,
      axisY: {
        title: "No Of Patching"
      },
      legend: {
        verticalAlign: "bottom",
        horizontalAlign: "center"
      },
      theme: "theme2",
      data: [

      {        
        type: "column",  
        showInLegend: true, 
        legendMarkerColor: "grey",
        legendText: "Day of Month",
        dataPoints: [      
<?php
include "vars.php";
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
#print_r($_REQUEST);
    $sql = "select count(*) as Count,$select from maintenance_requests_queue,maintenance_registered_hosts where maintenance_registered_hosts.host = maintenance_requests_queue.host and scheduled_date >= '$from' and scheduled_date <= '$to' $pcondition group by DATE,ORD order by ORD";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

#    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach(($stmt->fetchAll()) as $k=>$v) {
?>
        {y: <?php echo $v['Count'];?>,  label: "<?php echo $v['DATE'];?>"},        
<?php
}
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;

?>
        ]
      }   
      ]
    });

    chart.render();
//This is for PIE Chart of Patching based on status

var notchartPie = new CanvasJS.Chart("notchartContainerStatus",
        {
                title:{
                        text: "Notification Report by Status"
                },
                animationEnabled: true,
                legend:{
                        verticalAlign: "center",
                        horizontalAlign: "right",
                        fontSize: 15,
                        fontFamily: "Helvetica"        
                },
                theme: "theme1",
                data: [
                {        
                        type: "pie",       
			indexLabelFontFamily: "Garamond",       
			indexLabelFontSize: 20,
			indexLabelFontWeight: "bold",
			startAngle:0,
			toolTipContent: "{name}: {y}hrs",
			showInLegend: true,
			indexLabel: "{label} #percent%",
                        dataPoints: [
<?php
include "vars.php";
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select count(*) as Count,status from maintenance_requests,maintenance_registered_hosts where maintenance_registered_hosts.host = maintenance_requests.hostname and scheduled_date >= '$from' and scheduled_date <= '$to' $ncondition group by status order by status";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $array;
    $total=0;
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach(($stmt->fetchAll()) as $k=>$v) {
        $total+=$v['Count'];
        $array[$v['status']] = $v['Count'];
    
}
#print_r($array);
 foreach($array as $count => $value) {
   #echo "$count => $value";
?>
        {y: <?php echo round(($value/$total)*100);?>, legendText: "<?php echo $count;?>", label: "<?php echo $count;?>"},        
<?php
   }
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;

?>
        ]
      }   
      ]
    });

    notchartPie.render();

var chartPie = new CanvasJS.Chart("chartContainerStatus",
	{
		title:{
			text: "Patching Report by Status"
		},
                animationEnabled: true,
		legend:{
			verticalAlign: "bottom",
			horizontalAlign: "left",
			fontSize: 15,
			fontFamily: "Helvetica"        
		},
		theme: "theme2",
		data: [
		{        
			type: "doughnut",       
			indexLabelFontFamily: "Garamond",       
			indexLabelFontSize: 20,
			indexLabel: "{y}%",
			startAngle:-20,      
			showInLegend: true,
			toolTipContent:"{legendText} {y}%",
			dataPoints: [
<?php
// Create connection
$array=[];
try {
include "vars.php";
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select count(*) as Count,status from maintenance_requests_queue,maintenance_registered_hosts where maintenance_registered_hosts.host = maintenance_requests_queue.host and scheduled_date >= '$from' and scheduled_date <= '$to' $pcondition group by status order by status";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $array;
    $total=0;
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach(($stmt->fetchAll()) as $k=>$v) {
	$total+=$v['Count'];
 	$array[$v['status']] = $v['Count'];	
    
}
#print_r($array);
 foreach($array as $count => $value) {
   #echo "$count => $value";
?>
        {y: <?php echo round(($value/$total)*100);?>, legendText: "<?php echo $count;?>", label: "<?php echo $count;?>"},        
<?php
   }
$array=[];
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;

?>
        ]
      }   
      ]
    });

    chartPie.render();
  }
  </script>
  <script type="text/javascript" src="../js/canvas.min.js"></script>
</div>
<br>
<div class="container">
	<div  class="row" >
	  <div  class="col-lg-10" > <b>Report Criteria: </b>
	    <span class="label label-primary">Team - <?php echo ucfirst($team[$_REQUEST['team']]); ?></span>
	    <span class="label label-primary">Patching - <?php echo ucfirst($auto[$_REQUEST['auto']]); ?></span>
	    <span class="label label-primary">Type - <?php echo ucfirst($_REQUEST['type']); ?></span>
	    <span class="label label-primary"> <?php 
		if(isset($_REQUEST['from']) && !empty($_REQUEST['from'])) {
			echo "Date - ". $_REQUEST['from']." To ".$_REQUEST['to'];
		}
		else {echo "Year ".$_REQUEST['year']." Month ".$_REQUEST['month']; } ?></span>
	</div>
	</div>
	<div  class="panel panel-primary" style="height:460px;width:79%;margin-top:10px;">
	  <div class="panel-heading"> Notification Report - By Count</div>
	<div  class="panel-body" >
	  <div  class="col-lg-12" style="height:410px;width:100%;"><div id="notchartContainerCount"></div></div>
	</div>
	</div>
	<div  class="panel panel-primary" style="height:470px;width:79%;margin-top:10px;">
	  <div class="panel-heading"> Notification Report - By Status</div>
	<div  class="panel-body" >
	  <div  class="col-lg-12" style="margin-top:10px;;height:400px;width:100%;"><div id="notchartContainerStatus" ></div></div>
	</div>
	</div>
	<div  class="row" >
	  <div  class="col-lg-12" ><!-- <h1 class="page-header"> Patching Report </h1> --> </div>
	</div>
	<div  class="panel panel-primary" style="height:480px;width:79%;margin-top:10px;">
	  <div class="panel-heading"> Patching Report - By Count</div>
	<div  class="panel-body" >
	  <div  class="col-lg-12" style="padding-top:10px;height:410px;width:100%;"><div id="chartContainerCount" ></div></div>
	</div>
	</div>
	<div  class="panel panel-primary" style="height:480px;width:79%;margin-top:10px;">
	  <div class="panel-heading"> Patching Report - By Status</div>
	<div  class="panel-body" >
	  <div  class="col-lg-12" style="padding-top:15px;height:410px;width:100%;"><div id="chartContainerStatus" ></div></div>
	</div>
	</div>
</div>
</body>
</html>

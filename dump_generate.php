
<?php 
include "header.php";
?>
<div class="container">
	<div class="panel panel-primary" style="padding-bottom:30px;">
	<div class="panel-heading">Download Report as CSV</div>
	<div class="panel-body">
<?php
if(count($_POST['category']) == 2 || $_POST['category'][0] == 1) {
	$select = implode(",",$_POST['notification']);
	$from = $_POST['from'];
	$to = $_POST['to'];
	$where = "";
	$where_d = "";
	$where_u = "";
	$where_w = "";
	if(count($_POST['team']) == 2) {
		if($_POST['team'][0] == 0 ) {
			#$where_d = " and hostname like 'dev%' ";
		}
		else if($_POST['team'][0] == 1  ) {
			#$where_u = " and hostname not like 'dev%' ";
		}
		else if($_POST['team'][0] == 2 ){
			$where_w = " and maintenance_registered_hosts.os_type='WINDOWS' ";
		}
		if((isset($_POST['team'][1]) && $_POST['team'][1] == 1)) {
			#$where_u .= " or hostname not like 'dev%' ";
		}
		else if((isset($_POST['team'][1]) && $_POST['team'][1] == 2)) {
			$where_w .= " or maintenance_registered_hosts.os_type='WINDOWS' ";
		}
	}
	else if(count($_POST['team']) == 1) {
		if($_POST['team'][0] == 0 ) {
			$where_d = " and hostname like 'dev%' ";
		}
		else if ($_POST['team'][0] == 1 ) {
			$where_u = "and hostname not like 'dev%'  and maintenance_registered_hosts.os_type !='WINDOWS'"; 
		}
		else if($_POST['team'][0] == 2 ) {
			$where_w = " and maintenance_registered_hosts.os_type='WINDOWS' ";
		}
	}
	$where = " $where_d $where_u $where_w ";
	include "vars.php";
	// Create connection
	try {
		$con=mysqli_connect("$servername","$username","$password","patch");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
	#print_r($_REQUEST);
	$select = preg_replace("/comment/","maintenance_requests.comment",$select);
	    $sql = "select $select from maintenance_requests,maintenance_registered_hosts where maintenance_registered_hosts.host = maintenance_requests.hostname $where and scheduled_date >= '$from 00:00:00' and scheduled_date <= '$to 23:59:00' order by scheduled_date";
$result = $con->query($sql);
#echo "$sql";
if (!$result) die('Couldn\'t fetch records');
$DATE = date("Ymd_His"); 
$num_fields = mysql_num_fields($result);

$filename = $_SERVER['REMOTE_USER']."_notification_report_dump_".$DATE.".csv";
?>
<div>		<form action="download.php" method="POST">
		<input type="hidden" name="filename" value="<?php echo "$filename";?>" />
		<button type="button" class="btn btn-info pull-right" style="width:300px;" onclick="this.parentNode.submit()">Download Notification report</button>
		</form>
</div>
<?php
$fp = fopen("/tmp/$filename", 'w');
if ($fp && $result) {
    fputcsv($fp,  array_values($_POST['notification']));
    #fputcsv($fp, $select);
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        fputcsv($fp, array_values($row));
    }
}
fclose($fp);
	    }
	catch(PDOException $e)
	    {
	    echo "Connection failed: " . $e->getMessage();
	    }
	$conn = null;

}
if(count($_POST['category']) == 2 || $_POST['category'][0] == 2) {
	$select = implode(",",$_POST['patching']);
	$from = $_POST['from'];
	$to = $_POST['to'];
	$where = "";
	$where_d = "";
	$where_u = "";
	$where_w = "";
	if(count($_POST['team']) == 2) {
		#$where = "";
		if($_POST['team'][0] == 0 ) {
			$where_d = " and ( itoc=0 ";
		}
		else if($_POST['team'][0] == 1  ) {
			$where_u = " and ( itoc=1 ";
		}
		else if($_POST['team'][0] == 2 ){
			$where_w = " and itoc=3 ";
		}
		if((isset($_POST['team'][1]) && $_POST['team'][1] == 1)) {
			$where_u .= " or itoc=1 ) ";
		}
		else if((isset($_POST['team'][1]) && $_POST['team'][1] == 2)) {
			$where_w .= " or itoc=3 ) ";
		}
	}
	else if(count($_POST['team']) == 1) {
		if($_POST['team'][0] == 0 ) {
			$where_d = " and itoc=0 ";
		}
		else if ($_POST['team'][0] == 1 ) {
			$where_u = "and itoc=2"; 
		}
		else if($_POST['team'][0] == 2 ) {
			$where_w = " and itoc=3 ";
		}
	}
	$where = " $where_d $where_u $where_w ";
	include "vars.php";
	// Create connection
	try {
		$con=mysqli_connect("$servername","$username","$password","patch");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
	#print_r($_REQUEST);
	$select2 = preg_replace("/host/","maintenance_requests_queue.host",$select);
	$select2 = preg_replace("/os_type/","maintenance_requests_queue.os_type",$select2);
	$select2 = preg_replace("/auto_patch/","maintenance_requests_queue.auto_patch",$select2);
	    $sql = "select $select2 from maintenance_requests_queue,maintenance_registered_hosts where maintenance_registered_hosts.host = maintenance_requests_queue.host $where and scheduled_date >= '$from' and scheduled_date <= '$to' order by scheduled_date";
#echo $sql;
$result = $con->query($sql);
if (!$result) die('Couldn\'t fetch records'.$sql);
$num_fields = mysql_num_fields($result);
$DATE = date("Ymd_His"); 
$filename = $_SERVER['REMOTE_USER']."_patch_report_dump_".$DATE.".csv";
?>
	<div>
		<form action="download.php" method="POST">
		<input type="hidden" name="filename" value="<?php echo "$filename";?>" />
		 <button type="button" class="btn btn-primary pull-right" style="width:300px;margin-right:15px;" onclick="this.parentNode.submit()">Download Patching report</button><br>
		</form>
	</div>
<?php
$fp = fopen("/tmp/$filename", 'w');
if ($fp && $result) {
    fputcsv($fp,  array_values($_POST['patching']));
    while ($row = $result->fetch_array(MYSQLI_NUM)) {
        fputcsv($fp, array_values($row));
    }
}
fclose($fp);
	    }
	catch(PDOException $e)
	    {
	    echo "Connection failed: " . $e->getMessage();
	    }
}
?>
</div>
</div>

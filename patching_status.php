<?php
include("header.php");
?>
<div class="container">
<div class="panel panel-primary">
<div class="panel-heading">Update</div>
<div class="panel-body">
<?php 
include("vars.php");
$start=0;
$end=0;
$url='';

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select mrq.coord_mail,mrq.id,mrq.host,mrq.status,mrq.updates_log from maintenance_requests_queue mrq where id=".$_REQUEST['id'];
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
if($_REQUEST['status'] == $result['status'] || (($result['status'] != 'FAILED' && $result['status'] != 'TIMEOUT') && $_REQUEST['status']=='RERUN')) {
echo "<h2>This host is already in <span class='label label-warning'>".$result['status']."</span> status <span class='label label-danger glyphicon glyphicon-remove'></span></h2>";
echo "<br><a class='btn btn-info' href='patching_update.php?id=".$_REQUEST['mid']."'> Back to previous page</a>";
exit;
}

    if($_REQUEST['status'] == 'COORD_MAIL') {
       if($result['coord_mail'] == 'N') {
         $command = "/usr/bin/perl /opt/EIS/CM/bin/update_patching_status.pl ".$_REQUEST['id']." ".$_REQUEST['status']." ".$_SERVER['REMOTE_USER'];
         $ret = exec($command);
         $sql = "update maintenance_requests_queue set coord_mail='Y' where id=".$result['id'];
         $conn->exec($sql);
         echo "<h3>Coordination email sent to user's</h3>";
       }
       else {
	 echo "<h3>Coordination email already sent to user's</h3>";
       }
    } else if($_REQUEST['status'] == 'RERUN' && ($result['status'] == 'FAILED' || $result['status'] = 'TIMEOUT')) {
         $sql = "update maintenance_requests_queue set status='QUEUED'";
         $sql .= ", updates_log='".$result['updates_log']."\rUser ".$_SERVER['REMOTE_USER']." updated status to RERUN'";
 	 $sql .= " where id=".$result['id'];
         $conn->exec($sql);
         echo "<h3>Patching has been marked to RERUN</h3>";
    }
    else {
    if($result['status'] == $_REQUEST['status']) {
	echo "Patching status already set to ".$_REQUEST['status'];
    } else {
      $hostdb = "";
      $sql = "update maintenance_requests_queue ";
      $sql .=" set status='".$_REQUEST['status']."'";
      if($_REQUEST['status'] == 'RUNNING') {
         $sql .= ", started_date=now() ";
         $sql .= ", updates_log='".$result['updates_log']."\rUser ".$_SERVER['REMOTE_USER']." updated status to RUNNING'";
	 $command="curl 'https://unix-access.imasoft.com/update/maint_start?author=".$_SERVER['REMOTE_USER']."&message=Maintenance+Inprogress&hosts=".$result['host']."' -k";
  	 $hostdb = exec($command);
	 $url="https://unix-access.imasoft.com/update/maint_start?author=".$_SERVER['REMOTE_USER']."&message=Maintenance+Inprogress&hosts=".$result['host'];
	 $start=1;
      } else if($_REQUEST['status'] == 'COMPLETED') {
         $sql .= ", completed_date=now() ";
         $sql .= ", updates_log='".$result['updates_log']."\rUser ".$_SERVER['REMOTE_USER']." updated status to COMPLETED'";
	 $command="curl 'https://unix-access.imasoft.com/update/maint_end?author=".$_SERVER['REMOTE_USER']."&message=Maintenance+Complete&hosts=".$result['host']."' -k";
  	 $hostdb = exec($command);
	 $url="https://unix-access.imasoft.com/update/maint_end?author=".$_SERVER['REMOTE_USER']."&message=Maintenance+Complete&hosts=".$result['host'];
	$end=1;
       }
       $sql .= " where id=".$_REQUEST['id'];
       $command = "/usr/bin/perl /opt/EIS/CM/bin/update_patching_status.pl ".$_REQUEST['id']." ".$_REQUEST['status']." ".$_SERVER['REMOTE_USER'];
#       echo "$command<br>$sql";
       $ret = exec($command);
       $conn->exec($sql);
       echo "Patching status set to ".$_REQUEST['status']." <br> Notification sent<br> $hostdb";
    }
#      echo "<br>$sql";
}
}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>
<br> click <a class="btn btn-danger" href='patching_update.php?id=<?php echo $_REQUEST['mid']; ?>'> here </a> to goto previous page
<?php if($start == 1) {
#echo "<br> click <a href='$url'> here </a> to mark host to 'IN Maintenance' in HostDB";
}
else if($end==1) {
#echo "<br> click <a href='$url'> here </a> to mark host to 'Live' in HostDB";
}?>
</div>
</div>
</div>

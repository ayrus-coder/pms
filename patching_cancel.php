<div>
<?php 
include("vars.php");
$start=0;
$end=0;
$url='';

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select mrq.id,mrq.auto_patch,mrq.host,mrq.status,mrq.updates_log from maintenance_requests_queue mrq where id=".$_REQUEST['id'];
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result['status'] =='QUEUED' ||  $result['status'] == 'RUNNING' || $result['status'] == 'FAILED') {
      $hostdb = "";
      $sql = "update maintenance_requests_queue ";
      $sql .=" set status='CANCELLED'";
         $sql .= ", updates_log='".$result['updates_log']."\rUser ".$_SERVER['REMOTE_USER']." set status to CANCELLED \r COMMENT:".$_REQUEST['comment']."'";
#	 $command="curl 'https://unix-access.imasoft.com/update/maint_end?author=".$_SERVER['REMOTE_USER']."&message=Maintenance+Complete&hosts=".$result['host']."' -k";
       $sql .= " where id=".$_REQUEST['id'];
       $notify = "/usr/bin/perl /opt/EIS/CM/lib/EIS/Patch/update_patching_status.pl ".$_REQUEST['id']." CANCELLED ".$_SERVER['REMOTE_USER'];
       $conn->exec($sql);
       echo "Patching has been cancelled for this host ".$result['host']."  <br> ";
      #echo "<br>$sql";
    }
    else {
	echo "Cancelling for this status [".$result['status']."] is not allowed";
    }
}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
?>
<br> click <a href='patching_update.php?id=<?php echo $_REQUEST['mid']; ?>'> here </a> to goto previous page
<br> click <a href='index.php'> here </a> to goto Home page
</div>

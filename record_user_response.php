<!DOCTYPE html>
<html>
<head>
  <title> EIS - Maintenance (Patching)</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<?php
include("vars.php");
error_reporting(0);
require("invite.php");
#print_r($_POST);
#exit;
?>
<?php 
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    for($i=1; $i<$_POST['no_of_rows']; $i++) {
      $sql = "update maintenance_requests set ";
       if($_POST['approve'.$i] === 'Approve') {
         $comment = preg_replace("/'/","\\'",$_POST['approve'.$i."_comment"]);
         $sql .= " approved='Y',approved_by='".$_SERVER['REMOTE_USER']."@imasoft.com', comment='$comment', approved_at=now(), status='APPROVED' ";
       } else if ($_POST['approve'.$i] === 'Postpone') {
         $comment = preg_replace("/'/","\\'",$_POST['approve'.$i."_comment"]);
         $sql .= "scheduled_date='".$_POST['approve'.$i."_postpone_date"]."', comment='$comment', status='APPROVED', approved='Y', postponed='Y', approved_by='".$_SERVER['REMOTE_USER']."@imasoft.com', approved_at=now() ";
       } else if ($_POST['approve'.$i] === 'User') {
         $sql .= " comment='User ".$_SERVER['REMOTE_USER']." has manually patched', status='USER_PATCHED', approved='Y', approved_by='".$_SERVER['REMOTE_USER']."@imasoft.com', approved_at=now() ";
       }
       $sql .= ", updated_date=now() ";
       $sql .= " where id=".$_POST['id_'.$i]." and approved='N' and approved_by is NULL";
#       echo $sql;
       $conn->exec($sql);
       if($_POST['approve'.$i] === 'Approve' || $_POST['approve'.$i] === 'Postpone') {
         $ret = send_invite($_POST['id_'.$i]);
         $ret = add_maintenance_requests_queue($_POST['id_'.$i]);
	 #echo "<br>Invite Sent";
       }
       $s_query = "select reminder_I_ack, reminder_II_ack, reminder_III_ack from maintenance_notification_reminder where mq_id=15706";
       $stmt = $conn->prepare($s_query);
       $stmt->execute();

       // set the resulting array to associative
       $result = $stmt->fetch(PDO::FETCH_ASSOC);
       #print_r($result);
       $column="";
       if(empty($result['reminder_I_ack'])) {
         $column="reminder_I_ack='Y'";
       }
       else if(empty($result['reminder_II_ack'])) {
         $column="reminder_II_ack='Y'";
       }
       else if(empty($result['reminder_III_ack'])) {
         $column="reminder_III_ack='Y'";
       }
       $u_query = "update maintenance_notification_reminder set $column where mq_id=".$_POST["id_$i"];
       $conn->exec($u_query);
      #echo "<br>$sql";
    }
$user = $_SERVER['REMOTE_USER'];
    $sql = "select id, hostname, maintenance_requests.to, cc, approved, status, postponed, updated_date, DATE_FORMAT(scheduled_date, '%Y/%c/%d %H:%m:%s') as scheduled_date, approved_by, approved_at, last_patched_date,comment from maintenance_requests where status='PENDING' and maintenance_requests.to like '%$user%'";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    // set the resulting array to associative
    $count = 0;
    $hosts = "";
    foreach(($stmt->fetchAll()) as $k=>$v) {
      $count++;
      $hosts .= "<tr><td>$count</td><td>".$v['hostname']."</td><td>".$v['scheduled_date']."</td><td><span class='label label-warning' style='color:black;'>Pending<span></td></tr>";
    }
    $conn=null;
    if($count > 0){
?>
<div class="panel panel-primary">
  <div class="panel-heading"> Approval Update</span></div>
  <div class="panel-body">
    <div class="text-center">
    <h1><span class='glyphicon glyphicon-ok' style='color:green;'></span><h2> Thank you!, Your response is recorded <span class='glyphicon glyphicon-'></span></h2> 
    </div>
    <hr>
    <div class="text-center">
    <h1><span class='glyphicon glyphicon-warning-sign' style='color:#f0ad4e;'></span></h1><h3> You have following hosts pending for approval. Please click Review to respond.</h3> 
    <hr>
   <div>
   <table class="table table-hover">
    <thead><th>#</th><th>Hostname</th><th>Scheduled Date</th><th>Status</th></thead>
    <tbody>
      <?php echo $hosts; ?>
    </tbody>
   </table>
   </div>
   <a class="btn btn-primary pull-right" href="user_approve_pending.php" type="button" >Review</a>
  </div>
  </div>
</div>
<?php
    }
    else {
?>
<div class="panel panel-primary">
  <div class="panel-heading"> Approval Update</span></div>
  <div class="panel-body">
    <div class="text-center">
    <h1><span class='glyphicon glyphicon-ok' style="color:green;padding:10px;"></span></h1> 
    <h2>Thank you!, Your response is recorded </h2> 
    <!-- <p><span class='glyphicon glyphicon-envelope'></span> Please contact <span class="label label-info"> suryap@imasoft.com</span> for any feedback/suggestion on this tool</p> -->
    </div>
</div
</div>
<?php
    }
}
catch(PDOException $e)
    {
#    echo $sql . "<br>" . $e->getMessage();
echo "There was an error with DB. Please contact ITOC";
    }

$conn = null;
?>
</div>
</body>
</html>

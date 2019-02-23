<!DOCTYPE html>
<html>
<?php 
include('header.php'); ?>
<script>
$(document).ready( function () {
    $('#current_list').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     false
    });
    $('#table_id').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     false
    });
} );
</script>
<div id="body" class="container" > 
<div id="NEW">
<table id="table_id" class="display" cellspacing="0" width="100%" data-page-length="5" data-order="[[ 1, &quot;asc&quot; ]]">
<thead>
 <tr><th colspan="10"> Maintenance Notification </th></tr>
<tr>
<th> Hostname </th> <th>Owner </th> <th>Stakeholder </th><th> Scheduled Date </th> <th>Approved By</th> <th>Approved At</th> <th>Approved</th> <th>Postponed</th> <th>Status</th> <th>Comment</th>
</tr>
</thead>
<tbody>
<?php
include "vars.php";
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select id,hostname, maintenance_requests.to, cc, scheduled_date, status,approved_by,approved_at,approved,postponed,comment from maintenance_requests where hostname='".$_POST['host']."'";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach(($stmt->fetchAll()) as $k=>$v) { 
      echo "<tr>";
      echo "<td>".$v['hostname']."</td><td>".$v['to']."</td><td>".$v['cc']."</td><td>".$v['scheduled_date']."</td><td> ".$v['approved_by']."</td><td>".$v['approved_at']."</td><td> ".$v['approved']."</td><td> ".$v['postponed']."</td><td> ".$v['status']."</td><td>".$v['comment']."</td> ";
      echo "</tr>";
    }
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;
?>
</tbody>
</table>
</div>
<div id="CURRENT" style="padding-top:20px;">
<table id="current_list" class="display" cellspacing="0" width="100%" data-page-length="5" data-order="[[ 1, &quot;asc&quot; ]]">
<thead>
 <tr><th colspan="6"> Patching Info </th></tr>
<tr>
<th> Hostname </th> <th>OS </th> <th>Scheduled At </th><th> Started Start </th> <th>Completed At</th> <th>Status</th>
</tr>
</thead>
<tbody>
<?php
include "vars.php";
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select id, host, status, scheduled_date, started_date, completed_date, os_type, logfile from maintenance_requests_queue where host='".$_POST['host']."' order by started_date DESC ";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach(($stmt->fetchAll()) as $k=>$v) { 
      echo "<tr>";
      echo "<td>".$v['host']."</td><td>".$v['os_type']."</td><td>".$v['scheduled_date']."</td><td>".$v['started_date']."</td><td>".$v['completed_date']."</td><td>";
      if($v['status'] == "QUEUED") {
	echo "QUEUED";
      } else {
         echo "<a class='".$v['status']."' href='patching_update.php?mid=".$v['id']."' target='_blank'>".$v['status']."</a>";
      }
      echo "</td></tr>";
    }
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;
?>
</tbody>
</table>
</div>

</div>
</body>
<div id="footer">
</div>
</body>
</html>


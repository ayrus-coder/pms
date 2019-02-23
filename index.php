<!DOCTYPE html>
<html>
<?php 
include('header.php'); ?>
<script>
</script>
<div class='container-fluid mt--7'> 
<div  class="table-responsive">
<h3> Maintenance Request QUEUE </h3>
<table class="table align-items-center table-flush">
<thead class="thead-light">
<tr>
    <th scope="col"> Hostname </th> 
    <th scope="col"> Owner </th> 
    <th scope="col"> Scheduled Date </th> 
    <th scope="col"> Duration</th> 
    <th scope="col"> Status</th>
</tr>
</thead>
<tbody>
<?php

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = 'select id,hostname, maintenance_requests.to, cc, scheduled_date, status from maintenance_requests order by scheduled_date DESC limit 1000;';
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach(($stmt->fetchAll()) as $k=>$v) { 
      echo "<tr>";
      echo "<td>".$v['hostname']."</td><td>".$v['to']."</td><td>".$v['scheduled_date']."</td><td> 180 mins</td><td><a href='/$serverpath/admin_approve.php?ids=".$v['id']."'>".$v['status']."</a></td>";
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
 <tr><th colspan="6"> Patching QUEUE </th></tr>
<tr>
<th> Hostname </th> <th>OS </th> <th>Scheduled At </th><th> Started Start </th> <th>Completed At</th> <th>Status</th>
</tr>
</thead>
<tbody>
<?php
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = 'select id, host, status, scheduled_date, started_date, completed_date, os_type, logfile from maintenance_requests_queue order by scheduled_date DESC limit 2000';
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


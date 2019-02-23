<html>
<?php 
include("vars.php");
include('header.php'); ?>

<script>
$(document).ready( function () {
    $('#current_list').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     false
    });
} );
</script>
<div id="CURRENT" style="padding-top:20px;" class="container">
<table id="current_list" class="display" cellspacing="0" width="100%" data-page-length="50" data-order="[[ 1, &quot;asc&quot; ]]">
<thead>
 <tr><th colspan="8"> Patching QUEUE </th></tr>
<tr>
<th> Hostname </th><th>SLA </th> <th>OS </th> <th>Scheduled At </th><th> Started Start </th> <th>Completed At</th> <th>Status</th><th>Action</th>
</tr>
</thead>
<tbody>
<?php
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $status = "  where status='".$_REQUEST['type']."' ";
    if($_REQUEST['type'] == 'ALL') {
      $status =  "";
    }
    $sql = 'select id, host, status, scheduled_date, started_date, completed_date, os_type, logfile,sla from maintenance_requests_queue '.$status.' order by scheduled_date DESC limit 5000';
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach(($stmt->fetchAll()) as $k=>$v) {
      echo "<tr>";
      echo "<td>".$v['host']."</td><td>".$v['sla']."</td><td>".$v['os_type']."</td><td>".$v['scheduled_date']."</td><td>".$v['started_date']."</td><td>".$v['completed_date']."</td><td>";
      if($v['status'] == "QUEUED") {
         echo "<a class='QUEUED' href='patching_update.php?mid=".$v['id']."' target='_blank'>QUEUED</a>";
      } else {
         echo "<a class='".$v['status']."' href='http://$gw_host/patch/show.php?log=".$v['logfile']."' target='_blank'>".$v['status']."</a>";
         #echo "<a class='".$v['status']."' href='/patch/show.php?log=".$v['logfile']."' target='_blank'>".$v['status']."</a>";
      }
      echo "</td><td>";
echo "<a alt='Takes to patching update page' href='https://$localhost/$serverpath/patching_update.php?mid=".$v['id']."' target='_blank'> <img src='update.png' height=22 width=22 alt='Takes to patching update page' /> </a>-";
      if($v['status'] == "QUEUED") {
         echo "<a class='QUEUED' href='patching_update.php?mid=".$v['id']."' target='_blank'><img src='log.png' height=22 width=22 alt='Takes to logfile' /></a>";
      } else {
echo "<a alt='Takes to logfile' href='http://$gw_host/patch/show.php?log=".$v['logfile']."' target='_blank'> <img src='log.png' height=22 width=22 alt='Takes to logfile' /> </a>-";
#echo "<a alt='Takes to logfile' href='/patch/show.php?log=".$v['logfile']."' target='_blank'> <img src='log.png' height=22 width=22 alt='Takes to logfile' /> </a>-";
}
echo "<a alt='Takes to HostDB for this host' href='https://hostdb.imasoft.com/hosts/show/".$v['host']."' target='_blank'> <img src='hostdb.png' height=22 width=22 alt='Takes to HostDB for this host' /> </a>";
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

</body>
</html>

<html>
<?php 
include("vars.php");
include('header.php'); ?>
<script>
$(document).ready( function () {
    $('#table_id').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     false
    });
} );
</script>

<div class="container" id="CURRENT" style="padding-top:20px;">
<table id="table_id" class="display" cellspacing="0" width="100%" data-page-length="50" data-order="[[ 1, &quot;asc&quot; ]]">
<thead>
 <tr><th colspan="7"> Maintenance Request Queue - <?php echo ucfirst($_REQUEST['type']); ?></th></tr>
<tr>
<th> Hostname </th> <th>Owner </th> <th>Created By </th><th> Scheduled Date </th><th>Approved By</th> <th>Duration</th> <th>Status</th>
</tr>
</thead>
<tbody>
<?php
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select id,hostname, maintenance_requests.to, requested_by, scheduled_date, approved_by, status from maintenance_requests where status='".$_REQUEST['type']."' order by scheduled_date DESC limit 3000";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach(($stmt->fetchAll()) as $k=>$v) {
      echo "<tr>";
      echo "<td>".$v['hostname']."</td><td>".$v['to']."</td><td>".$v['requested_by']."</td><td>".$v['scheduled_date']."</td><td>";
      if($v['approved_by']) {
	echo $v['approved_by'];
      }
      else {
        echo "Approve Pending";
      }
      echo "</td><td> 180 Mins </td>";
     echo "<td><a href='https://$localhost/$serverpath/admin_approve.php?ids=".$v['id']."' target='_blank'>".$v['status']."</a></td>";
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

</body>
</html>

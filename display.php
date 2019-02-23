<html>
<?php 
include("vars.php");
include('header.php'); 
?>
<script>
$(document).ready( function () {
    $('#current_list').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     false
    });
} );
</script>

<div class="container">
<table id="current_list" class="display" cellspacing="0" width="100%" data-order="[[ 1, &quot;asc&quot; ]]">
<thead>
 <tr><th colspan="6"> Servers  </th></tr>
<tr>
<th> Id </th> <th>Hostname </th><th>OS Type </th> <th>Auto Patch</th><th>Contact</th><th>Created At</th> <th>Last Updated</th><th> Status </th>
</tr>
</thead>
<tbody>
<?php
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $status = " where host='".$_REQUEST['host']."'";
    if($_REQUEST['host'] == 'ALL') {
      $status =  "";
    }
   
    $sql = 'select id, host, active,os_type,created_at, updated_at,auto_patch, contact,note from maintenance_registered_hosts'.$status;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach(($stmt->fetchAll()) as $k=>$v) {
      echo "<tr>";
      echo "<td>".$v['id']."</td><td>".$v['host']."</td><td>".$v['os_type']."</td><td>".$v['auto_patch']."</td><td>".$v['contact']."</td><td>".$v['created_at']."</td><td>".$v['updated_at']."</td><td>";
      if($v['active'] == true) {
        echo "ACTIVE ";
      } elseif($v['active'] == false) {
         echo "INACTIVE ".$v['active'];
      } else {
         echo "REGISTER PENDING ";
      }
      echo "<a href='edit.php?id=".$v['id']."'><span class='glyphicon glyphicon-pencil'> </span></a></td></tr>";
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

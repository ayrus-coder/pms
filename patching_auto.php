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
    if($_REQUEST['status'] =='QUEUED' && $result['status'] == $_REQUEST['status']) {
      $hostdb = "";
      $sql = "update maintenance_requests_queue ";
      $sql .=" set auto_patch='Y'";
         $sql .= ", updates_log='".$result['updates_log']."\rUser ".$_SERVER['REMOTE_USER']." set AUTO Patch to True'";
       $sql .= " where id=".$_REQUEST['id'];
       $conn->exec($sql);
       echo "Patching marked to AUTO Run  <br> ";
      #echo "<br>$sql";
    }
}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
$ID="id=".$_REQUEST['id'];
if(isset($_REQUEST['mid'])) {
  $ID = "mid=".$_REQUEST['mid'];
}
?>
<br> click <a href='patching_update.php?<?php echo $ID; ?>'> here </a> to goto previous page
<br> click <a href='index.php'> here </a> to goto Home page
</div>

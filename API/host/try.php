<?php
include("config.php");
?>
<?php
// Create connection
$results = array();
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select host, status, max(scheduled_date) as scheduled_date from maintenance_requests_queue where ADDDATE(now(), INTERVAL -6 MONTH) and status='COMPLETED' group by host";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
  foreach(($stmt->fetchAll()) as $k=>$v) {
	$results[$v['host']]['patched_date'] = $v['scheduled_date'];
	$results[$v['host']]['patched_status'] = $v['status'];
    }
echo "<pre>";
print_r($results);
exit;
    $sql = "select host, status, max(scheduled_date) as scheduled_date from maintenance_requests_queue where scheduled_date >= ADDDATE(now(), INTERVAL -6 MONTH) and status='QUEUED' group by host";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
  foreach(($stmt->fetchAll()) as $k=>$v) {
        $results[$v['host']]['next_patch_date'] = $v['scheduled_date'];
        $results[$v['host']]['next_patched_status'] = $v['status'];
    }
    $sql = "select  x.host, x.status, x.scheduled_date as scheduled_date from maintenance_requests_queue x inner join (select host, max(scheduled_date) as scheduled_date from maintenance_requests_queue y  where y.scheduled_date >=ADDDATE(now(), INTERVAL -6 MONTH) group by host) mrq on x.host = mrq.host and mrq.scheduled_date = x.scheduled_date";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
  foreach(($stmt->fetchAll()) as $k=>$v) {
	$results[$v['host']]['last_patched_date'] = $v['scheduled_date'];
	$results[$v['host']]['last_patched_status'] = $v['status'];
    }
 $json = json_encode($results);
$conn = null;
echo $json;
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

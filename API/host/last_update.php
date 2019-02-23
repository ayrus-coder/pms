<?php
include("config.php");
$result = exec("curl https://unix-access.imasoft.com/query/hosts.json -k");
$json = json_decode($result);
$hosts_active = array();
foreach($json as $k) {
 $hosts_active[$k] = 1;
}
$result = exec("curl https://unix-access.imasoft.com/query/pcihosts.json -k");
$json = json_decode($result);
$pci = array();
foreach($json as $k) {
 $pci[$k] = 1;
}
$result = exec("curl https://unix-access.imasoft.com/query/soxhosts.json -k");
$json = json_decode($result);
$sox = array();
foreach($json as $k) {
 $sox[$k] = 1;
}
#print_r($_POST);
?>
<?php
// Create connection
$results = array();
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select  x.hostname, x.status, x.scheduled_date as scheduled_date from maintenance_requests x inner join (select hostname, max(scheduled_date) as scheduled_date from maintenance_requests y  where y.scheduled_date >=ADDDATE(now(), INTERVAL -6 MONTH) group by hostname) mrq on x.hostname = mrq.hostname and mrq.scheduled_date = x.scheduled_date ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
  foreach(($stmt->fetchAll()) as $k=>$v) {
	if(isset($hosts_active[$v['hostname']])){
	$results[$v['hostname']]['name'] = $v['hostname'];
	$results[$v['hostname']]['notify_date'] = $v['scheduled_date'];
	$results[$v['hostname']]['notify_status'] = $v['status'];
	$results[$v['hostname']]['group'] = "NON-PROD";
 	if(isset($pci[$v['hostname']]) && isset($sox[$v['hostname']])){
		$results[$v['hostname']]['group'] = 'PCI/SOX';
	}
 	else if(isset($pci[$v['hostname']]) ){
		$results[$v['hostname']]['group'] = "PCI";
	}
 	else if(isset($sox[$v['hostname']])){
		$results[$v['hostname']]['group'] = "SOX";
	}
	else if(preg_match('/^(prod|dr)/i', $v['hostname'])){
		$results[$v['hostname']]['group'] = "PROD";
	}
	$results[$v['hostname']]['patched_date'] = "";
	$results[$v['hostname']]['patched_status'] = "";
        $results[$v['hostname']]['next_patch_date'] = "";
        $results[$v['hostname']]['next_patched_status'] = "";
	$results[$v['hostname']]['last_patched_date'] = "";
	$results[$v['hostname']]['last_patched_status'] = "";
 	}
  }
    $sql = "select host, status, max(scheduled_date) as scheduled_date from maintenance_requests_queue where scheduled_date >= ADDDATE(now(), INTERVAL -6 MONTH) and status='COMPLETED' group by host";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
  foreach(($stmt->fetchAll()) as $k=>$v) {
        if(isset($results[$v['host']])){
	  $results[$v['host']]['patched_date'] = $v['scheduled_date'];
	  $results[$v['host']]['patched_status'] = $v['status'];
	}
    }
    $sql = "select host, status, max(scheduled_date) as scheduled_date from maintenance_requests_queue where scheduled_date >= ADDDATE(now(), INTERVAL -6 MONTH) and status='QUEUED' group by host";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
  foreach(($stmt->fetchAll()) as $k=>$v) {
        if(isset($results[$v['host']])){
          $results[$v['host']]['next_patch_date'] = $v['scheduled_date'];
          $results[$v['host']]['next_patched_status'] = $v['status'];
  	}
    }
    $sql = "select  x.host, x.status, x.scheduled_date as scheduled_date from maintenance_requests_queue x inner join (select host, max(scheduled_date) as scheduled_date from maintenance_requests_queue y  where y.scheduled_date >=ADDDATE(now(), INTERVAL -6 MONTH) and status !='QUEUED' group by host) mrq on x.host = mrq.host and mrq.scheduled_date = x.scheduled_date";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
  foreach(($stmt->fetchAll()) as $k=>$v) {
        if(isset($results[$v['host']])){
	  $results[$v['host']]['last_patched_date'] = $v['scheduled_date'];
	  $results[$v['host']]['last_patched_status'] = $v['status'];
	}
    }
 $json = json_encode($results);
$conn = null;
echo $json;
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

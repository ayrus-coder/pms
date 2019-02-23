<?php
include("vars.php");
   $url = 'https://unix-access.imasoft.com/query/host_os/';
 $host_hash = array();
 $host_not_found = "";
#print_r($_POST['hosts']);
  $flag=0;
 if(isset($_POST['hosts']) & !empty($_POST['hosts'])) {
   $hosts = explode("\n",$_POST['hosts']); 
   foreach($hosts as $host) {
     #echo "<br>Trying host $host";
     $host=preg_replace("(\s+) ","", $host);
     $host_db = file_get_contents($url.$host);
     chop ($host_db);
     chop ($host_db);
     #echo $host_db.$url.$host."<br>";
     if(preg_match("(ubuntu|oracle|rhel|centos)", $host_db, $match)) {
        $host_hash[$host] = strtoupper($match[0]);
     }
     elseif(preg_match("/windows/i", $host_db, $match)) {
        $host_hash[$host] = 'WINDOWS';
     } else {
       $host_not_found .= $host . "<br>";
       $flag=1;
     }
   }
   if($flag == 1){
     echo "<br>Following hosts are not present in HOSTDB, kindly provide proper host names<br>";
     echo $host_not_found;
   }
#print_r($host_hash);
   if(count($host_hash)) {

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $hostStr = preg_replace("/\s+/", "','",$_POST['hosts']);
    $sql = "select host from maintenance_registered_hosts where host in ('$hostStr')";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $registered_host = "";
    $db_list;
    foreach($result as $row) {
       $db_list[$row['host']] = 1;
    }
    foreach($host_hash as $host => $key) {
      if(isset($db_list[$host])) {
	$registered_host .= "Host ".$host." already registered<br>";
	unset($host_hash[$host]);
      }
    }
    if(!empty($registered_host)) {
       echo "$registered_host";
    }
    foreach($host_hash as $key => $value) {
      $sql = "insert into maintenance_registered_hosts (host, os_type,created_at) values('$key','$value',now())";	
	$conn->exec($sql);
echo $sql;
      echo "<br>Host $key has been added into HOST LIST";
    } 
}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
   }
 }
#print_r($host_hash);
?>
<br>
Click <a href="index.php" >Here</a> to goto Home page

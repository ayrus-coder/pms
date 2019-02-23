<?php
include("vars.php");
print_r($_POST);

$hosts = preg_split("/[\s,]+/", trim($_POST['hosts']));
$from_name = "EISTOOL";
$from_address = "admin@imasoft.com";
$location = "";



// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
foreach($hosts as $host){
    $sql = "select count(*) as count from maintenance_requests where hostname='$host' and status in ('SCHEDULED') ";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
print_r($result);

if(isset($result['count']) && $result['count'] == 0 ) {
$os=$_POST['optradio'];
$to = $_POST['to'];
$cc = $_POST['cc'];
$out="";
if((isset($_POST['to']) && empty($_POST['to'])) || (isset($_POST['cc']) && empty($_POST['cc']))) {
  $output = shell_exec("curl -H 'accept:text/plain' https://unix-access.imasoft.com/query/host_contacts/$host -k");
  $out = split("\n",$output);
}
  if(isset($_POST['cc']) && empty($_POST['cc'])) {
    $out[3]=preg_replace('/"/','',$out[3]);
    $out[3]=preg_replace('/\]/','',$out[3]);
    $out[3]=preg_replace('/.*\[/','',$out[3]);
    $cc = $out[3];
  }
if(isset($_POST['to']) && empty($_POST['to'])) {
  $out[2]=preg_replace('/"/','',$out[2]);
  $out[2]=preg_replace('/\]/','',$out[2]);
  $out[2]=preg_replace('/.*\[/','',$out[2]);
  $to = $out[2];
}
if(isset($_POST['optradio']) && $_POST['optradio'] == 4) {
$os_type = shell_exec("curl -H 'accept:text/plain' https://unix-access.imasoft.com/query/maintcategory/$host -k");
if(preg_match("/^Response/", $os_type)) {
  $os=1;
}
elseif(preg_match("/^Co-ord/", $os_type)) {
  $os=2;
}
else {
 $os = 4;
}
}


$date=$_POST['scheduled_date'];
$requester = $_SERVER['REMOTE_USER'];
$i_query = "insert into maintenance_requests (hostname,status,maintenance_requests.to,cc,scheduled_date,notification_type,requested_by,custom) values('$host','SCHEDULED','$to','$cc','$date',$os,'$requester','Y')";
echo $i_query;
if($conn->exec($i_query) ) {
$sql = "select id from maintenance_requests where hostname='$host' and status in ('SCHEDULED') ";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
$content = preg_replace("/\n/", '<br>',$_POST['content']);
$content = preg_replace("/'/", "\\'", $content);

    if(isset($result['id'])) {
	$i_query = "insert into maintenance_custom_email (maintenance_request_id,send_to,send_cc,send_from,subject,body) values(".$result['id'].",'".$_POST['to']."','".$_POST['cc']."','".$_POST['from']."','".$_POST['subject']."','".$content."')";
echo $i_query;
	if($conn->exec($i_query) ) {
   	   echo "<br>Patching has been scheduled for $host";
  	}
    }
}
}
else {
   echo "<BR>Ignoring $host which is already SCHEDULED";
}
}
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;
?>
<br>
<br> click <a href='schedule.php'> here </a> to goto previous page
<br> click <a href='index.php'> here </a> to goto Home page


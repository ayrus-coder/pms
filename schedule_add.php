<?php
include("vars.php");
include("header.php");
?>
<div class="container">
<div class="panel panel-primary">
<div class="panel-heading">Updates</div>
<div class="panel-body">
<?php
#print_r($_POST);
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
#print_r($result);

if(isset($result['count']) && $result['count'] == 0 ) {
$output = shell_exec("curl -H 'accept:text/plain' https://unix-access.imasoft.com/query/host_contacts/$host -k");
$os_type = shell_exec("curl -H 'accept:text/plain' https://unix-access.imasoft.com/query/maintcategory/$host -k");
$os = 4;
if($_POST['optradio'] == 0) {
if(preg_match("/^Response/", $os_type)) {
  $os=1;
}
elseif(preg_match("/^Co-ord/", $os_type)) {
  $os=2;
}
}
else { }
$out = split("\n",$output);
$out[3]=preg_replace('/"/','',$out[3]);
$out[3]=preg_replace('/\]/','',$out[3]);
$out[3]=preg_replace('/.*\[/','',$out[3]);

$out[2]=preg_replace('/"/','',$out[2]);
$out[2]=preg_replace('/\]/','',$out[2]);
$out[2]=preg_replace('/.*\[/','',$out[2]);
$date=$_POST['scheduled_date'];
if($_POST['optradio'] != 4) {
  $os = $_POST['optradio'];
}
$requester = $_SERVER['REMOTE_USER'];
$i_query = "insert into maintenance_requests (hostname,status,maintenance_requests.to,cc,scheduled_date,notification_type,requested_by) values('$host','SCHEDULED','$out[2]','$out[3]','$date',$os,'$requester')";
if( $conn->exec($i_query)) {
   echo "<br><h4>Patching has been scheduled for <span class='label label-success'>$host</span> <span class='glyphicon glyphicon-ok'></span><h4>";
}
}
else {
   echo "<br><h4>There is patching already scheduled for this <span class='label label-warning'>$host</span><span class='glyphicon glyphicon-info-sign'></span> </h4>";
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
<br> <a class="pull-right btn btn-info" href='schedule.php'> Back to previous page</a>
</div>
</div>

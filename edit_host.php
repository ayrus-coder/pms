<?php
include("vars.php");
include("header.php");
?>
<div class="container">
<div class="panel panel-primary">
<div class="panel-heading">Updates</div>
<div class="panel-body">
<?php
if(isset($_POST['host'])) {
$flag = 1;
if(isset($_POST['auto_patch']) && $_POST['auto_patch'] == 'Y') {
	$curl = "curl -H 'accept: text/plain' https://unix-access.imasoft.com/query/maintcategory/".$_POST['host']." -k";
	$out = exec($curl);
	if(preg_match("/Co-ordination/", $out)) {
		echo "<h1 class='label label-warning'>Co-ordination required hosts can not be set Auto Patch</h1>";
		$flag = 0;
	} 
}
#print_r($_POST);
if($_POST['id'] && $flag == 1) {
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "update maintenance_registered_hosts set auto_patch='".$_POST['auto_patch']."',active='".$_POST['active']."',os_type='".$_POST['os_type']."',contact='".$_POST['contact']."',note='".$_POST['note']."' where id=".$_POST['id'];
   #echo $query;
   $conn->exec($query);
   echo "<h2 class='label-success'>Host information has been successfull updated</h2>";
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;
}
}
else {
	echo "<h1 class='label label-warning'>Id or Host is missing</h1>";
}
?>
<br>
<br> <a class="pull-right btn btn-info" href='display.php?host=ALL'> Back to previous page</a>
</div>
</div>

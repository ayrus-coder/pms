<?php
include("config.php");
#print_r($_POST);
?>
<?php
if(isset($_POST['auto_patch']) && isset($_POST['host']) && isset($_POST['user'])) {
$flag = 1;
if($_POST['auto_patch'] == 'Y') {
        $curl = "curl -H 'accept: text/plain' https://unix-access.imasoft.com/query/maintcategory/".$_POST['host']." -k";
        $out = exec($curl);
        if(preg_match("/Co-ordination/", $out)) {
                echo "Co-ordination required hosts can not be set Auto Patch";
                $flag = 0;
        } 
}
if($flag){
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select id,host,comment,auto_patch from maintenance_registered_hosts where host='".$_POST['host']."'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($result['id'] && $result['auto_patch'] != $_POST['auto_patch']) {
       $query = "update maintenance_registered_hosts set auto_patch='".$_POST['auto_patch']."', comment='".$result['comment']."\nUser ".$_POST['user']." updated auto_patch to ".$_POST['auto_patch']." on ".date("Y-m-d h:i:sa")."' where host='".$_POST['host']."'";
       #echo $query;
       $conn->exec($query);
       echo "Host information has been successfull updated";
    }
    else {
        if($result['auto_patch'] == $_POST['auto_patch']) {
	  echo "Host ".$_POST['host']." auto_patch is already set to '".$_POST['auto_patch']."'";
	}
	else {
	  echo "Host ".$_POST['host']." does not exist";
	}
    }
}
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;
}
}
else {
        echo "Auto Patch or Host or User is missing";
}
?>


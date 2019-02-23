<?php
include("vars.php");
$user = $_SERVER['REMOTE_USER'];
if(empty($user)) {
  $user='suryap';
}

$valid_user = 0;
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select users.id from users, roles, roles_users where users.username='".$user."' and users.id = roles_users.user_id and roles.id = roles_users.role_id and roles.id=2";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach(($stmt->fetchAll()) as $k=>$v) {
      if(isset($v['id'])) {
	$valid_user=1;
      } 
    }
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;
if($valid_user == 0) {	 echo "Your not authorised to access this page. Please contact admin@imasoft.com"; exit;}
?>

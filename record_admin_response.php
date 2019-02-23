
<?php
include("vars.php");
error_reporting(0);
require("invite.php");
?>
<table>
<?php 

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "update maintenance_requests set ";
         $comment = preg_replace("/'/","\\'",$_POST['approve_comment']);
       $comment = $_SERVER['REMOTE_USER']." : $comment";
         $sql .= "scheduled_date='".$_POST['approve_postpone_date']."', comment='$comment', approved_at=now() ";
       $sql .= " where id=".$_POST['id_1']." and approved='Y' and approved_by is not NULL";
      # echo $sql;
       $conn->exec($sql);
	$q_sql = "update maintenance_requests_queue set scheduled_date='".$_POST['approve_postpone_date']."' where maintenance_requests_id=".$_POST['id_1'];
      #echo "<br>$q_sql";
       $conn->exec($q_sql);
         $ret = send_invite($_POST['id_1']);
    echo "Thank you!, Your response is recorded"; 
}
catch(PDOException $e)
    {
#    echo $sql . "<br>" . $e->getMessage();
echo "There was an error with DB. Please contact ITOC";
    }

$conn = null;
?>
</table>

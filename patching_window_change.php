<!DOCTYPE html>
<html>
<?php
include("vars.php");
include("header.php");
error_reporting(0);

function display_approve_request ($ids){
include("vars.php");
    $count = 1;
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select id, hostname, maintenance_requests.to, cc, approved, status, postponed, updated_date, DATE_FORMAT(scheduled_date, '%Y/%c/%d %H:%m:%s') as scheduled_date, approved_by, approved_at, last_patched_date,comment from maintenance_requests where id = $ids";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    #print_r($result);
      if($result['approved'] == 'Y') {
?>
<div class="container">
<div class="panel panel-primary">
<div class="panel-heading">Maintenance Detail</div>
<div class="panel-body">
	<h5><?php #echo $count; ?> Host:<span class="label label-default">
	<?php echo $result['hostname']; ?></span></h5>
	<h5>Owner:<span class="label label-default">
	<?php echo $result['to']; ?></span></h5>
	<h5>Maintenance Date:<span class="label label-default">
	<?php echo $result['scheduled_date']; ?> EST</span></h5>
	<h5>Duration:<span class="label label-default">
	180 mins</span></h5>
	<h5>Last Patched:<span class="label label-default">
	<?php echo $result['last_patched_date']; ?></span></h5>
<h5>Status:<span class="label label-default">
<?php echo $result['status']; ?></span></h5>
<h5>Approved By:<span class="label label-default">
<?php echo $result['approved_by']; ?></span></h5>
<h5>Approved On:<span class="label label-default">
<?php echo $result['updated_date']; ?></span></h5>
<h5>Postponed:<span class="label label-default">
<?php echo $result['postponed']; ?> </span></h5>
<h5>Scheduled Date:<span class="label label-default">
<?php echo $result['scheduled_date']; ?> </span></h5>
<h5>Comment:<span class="label label-default">
<?php echo $result['comment']; ?> </span></h5>
<input type="hidden" name="scheduled_date_prev" value="<?php echo $result['scheduled_date']; ?>"/> <input type="hidden" name="id_1" value="<?php echo $result['id']; ?>"/>
<label for="datetimepicker"> *Pick Date (EST): </label><input class="form-control" id="datetimepicker" type="text" name="approve_postpone_date" /> 
<label for="approve_comment"> Comment : </label><textarea class="form-control" rows=3 cols=20 type="text" name="approve_comment" id="approve_comment" ></textarea>
<br>
<input class="btn btn-primary pull-right" type="button" value="Submit" onclick="javascript:validate();"/></span>

</div>
</div>
</div>
</div>
<?php
      }
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;
return $count;
}

  $ids = $_REQUEST['mid'];
  $user = $_SERVER['REMOTE_USER'];


?>
<body>
<link rel="stylesheet" type="text/css" href="../datetimepicker-master/jquery.datetimepicker.css"/ > 
<script src="../DataTables-1.10.6/media/js/jquery.js" charset="utf8" type="text/javascript"></script>
<script src="../DataTables-1.10.6/media/js/jquery.js" charset="utf8" type="text/javascript"></script>
<script src="../datetimepicker-master/jquery.datetimepicker.js"></script> 
<script type="text/javascript">
Date.prototype.addDays = function(days) {
    this.setDate(this.getDate() + (parseInt(days)*24*60*60*1000));
    return this;
};
function validate() {
var flag = true;
var msg = "";
    var selDate = new Date(new Date($("input[name=approve_postpone_date]").val()).getTime());
    var schedDate = new Date(new Date($("input[name=scheduled_date_prev").val()).getTime());
    var  oneday = 60 * 60 * 1000 * 24;
    var diffdays = Math.round((selDate.getTime() - schedDate.getTime())/oneday);
if(diffdays < 0) { 
  msg = msg + "\nPlease select the future date";
  flag = false;
} 
   if($("input[name=approve_postpone_date]").val().length == 0) {
     msg = msg + "\nPlease select new date";
     flag = false;
   }
   if(document.getElementById("approve_comment").value.length == 0) {
     msg = msg + "\nPlease provide comment";
     flag = false;
   }
if(flag == false) {
  alert("Please fix following errrors: \n"+msg);
}
else {
    if (confirm("Please confirm to proceed") == true) {
        $("form").submit();
    }
}
}
</script>
<form method="POST" action="record_admin_response.php">
<?php
$count = display_approve_request($_REQUEST['mid']);
?>
</form>
</div>
</div>
</body>
<script type="text/javascript">
$(document).ready(function(){
$('#datetimepicker').datetimepicker({format:'Y/m/d H:i:00',minDate:0});
});
</script>
</html>

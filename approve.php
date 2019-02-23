<!DOCTYPE html>
<html>
<head>
  <title> EIS - Maintenance (Patching)</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <h4 style="position:fixed;top: 10px;right:10px;"> Logged As: <?php echo $_SERVER['REMOTE_USER']; ?></h4>
</head>
<?php
include("vars.php");
error_reporting(0);
function validate_user ($user, $id){
return true;
include("vars.php");

// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select id from maintenance_requests where id in($id) and (maintenance_requests.to  like '%$user%' or cc like '%$user%')";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    #print_r($result);
    if($result['id'] == $id || $user == 'suryap') {
      $conn = null;
	return true;
      }
    }
catch(PDOException $e)
    {
#    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;
return false;
}

function display_approve_request ($ids){


include("vars.php");


    $count = 1;
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select id, hostname, maintenance_requests.to, cc, approved, status, postponed, updated_date, DATE_FORMAT(scheduled_date, '%Y/%c/%d %H:%m:%s') as scheduled_date, approved_by, approved_at, last_patched_date,comment from maintenance_requests where id in($ids)";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $edit = false;
    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    #print_r($result);
?>
<?php
      do{
      if($result['approved'] == 'Y') {
?>
<div class="panel panel-info">
        <div class="panel panel-heading"><span class="label label-default"><?php echo $count; ?></span> - For host <span class="label label-success" style="font-size:14px;"><?php echo $result['hostname']; ?> </span></div>
        <div class="panel panel-body">
        <div class="row">
        <div class="col-md-6">
                <div>
                        <label>Owner:</label>
                        <span class="label label-default"><?php echo $result['to']; ?></span>
                </div>
                <div>
                        <label>Maintenance Date:</label>
                        <span class="label label-default"><?php echo $result['scheduled_date']; ?></span>
                </div>
                <div>
                        <label>Duration:</label>
                        <span class="label label-default">180 Mins</span>
                </div>
                <div>
                        <label>Last Patched:</label>
                        <span class="label label-info"><?php echo $result['last_patched_date']; ?></span>
                </div>
        </div>
	<div class="col-md-6">
		<div>
			<label><b>Status:</b></label>
			<span class="label label-success"><?php echo $result['status']; ?></span>
		</div>
		<div>
			<label><b>Approved By:</b></label>
			<span class="label label-default"><?php echo $result['approved_by']; ?></span>
		</div>
		<div>
			<label><b>Approved On:</b></label>
			<span class="label label-default"><?php echo $result['updated_date']; ?></span>
		</div>
		<div>
			<label><b>Postponed:</b></label>
			<span class="label label-default"><?php echo $result['postponed']; ?> </span>
		</div>
		<div>
			<label><b>Scheduled Date:</b></label>
			<span class="label label-default"><?php echo $result['scheduled_date']; ?> </span>
		</div>
		<div>
			<label><b>Comment:</b></label>
			<span class="label label-default"><?php echo $result['comment']; ?> </span>
		</div>
	</div>
</div>
</div>
</div>
<hr>
<?php
      } else {
    $edit = true;
?>
<div class="panel panel-info">
	<div class="panel panel-heading"> <span class="label label-default"><?php echo $count; ?></span> - For host <span class="label label-warning" style="font-size:14px;"><?php echo $result['hostname']; ?> </span></div>
	<div class="panel panel-body">
 	<div class="row">
	<div class="col-md-6">
		<div>
			<label>Owner:</label>
			<span class="label label-default"><?php echo $result['to']; ?></span>
		</div>
		<div>
			<label>Maintenance Date:</label>
			<span class="label label-default"><?php echo $result['scheduled_date']; ?></span>
		</div>
		<div>
			<label>Duration:</label>
			<span class="label label-default">180 Mins</span>
		</div>
		<div>
			<label>Last Patched:</label>
			<span class="label label-info"><?php echo $result['last_patched_date']; ?></span>
		</div>
	</div>
	<div class="col-md-6">
	<div>
		  <input type="hidden" 
		       name="scheduled_date_<?php echo $count; ?>" 
		       value="<?php echo $result['scheduled_date']; ?>"/> 
		  <input type="hidden" 
		       name="id_<?php echo $count; ?>" 
		       value="<?php echo $result['id']; ?>"/>
		  <input type="radio" 
		       name="approve<?php echo $count; ?>" 
		       value="Approve" checked> Approve 
		<input type="radio" name="approve<?php echo $count; ?>" value="Postpone" /> Postpone 
		<input type="radio" name="approve<?php echo $count; ?>" value="User" /> I patched myself 
	</div>
		<div id="approve<?php echo $count; ?>_Postpone" style="display: none;">
		<input id="datetimepicker_<?php echo $count; ?>" 
			class="form-control"
			placeholder="Select Date (EST)" 
			type="text" 
			name="approve<?php echo $count; ?>_postpone_date" />  
		 <span class="help-block"><b>Note:</b> Max +15 days allowed to postpone</span>
		</div>
		<div class="form-group" >
		   <textarea style='margin-top:10px;' rows=3 cols=60 type="text" 
			class="form-control"
			placeholder="Enter comment here" 
			name="approve<?php echo $count; ?>_comment" 
			id="approve<?php echo $count; ?>_comment" ></textarea>
		</div>
	</div>
	</div>
</div>
</div>
<hr>
		<?php
	}
	  $count++;
        }while($result = $stmt->fetch(PDO::FETCH_ASSOC));
if($edit) {
?>
<input type="hidden" name="no_of_rows" id="no_of_rows" value="<?php echo $count; ?>" />
<div >
  <button type="button" class="btn btn-primary pull-right" onclick="validate();"/>Submit</button>
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

  $ids = explode(",",$_REQUEST['ids']);
  $user = $_SERVER['REMOTE_USER'];
  foreach($ids as $ID) {
  if(validate_user($user, $ID) == false) {
   echo "Your not authorised to access this page or no entry exist for this requested ID";
   exit;
  }
  }


?>
<body>
<div class="container">
<link rel="stylesheet" type="text/css" href="../datetimepicker-master/jquery.datetimepicker.css"/ > 
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
var no_of_rows = document.getElementById("no_of_rows").value;
//#function onChange(e) {
//}
for(var i=1; i<no_of_rows; i++) {
 var item = $("input[name=approve"+i+"]:checked").val();
 if(item == "Postpone") {
    //var myDate = new Date(new Date().getTime()+(5*24*60*60*1000));
    var selDate = new Date(new Date($("input[name=approve"+i+"_postpone_date]").val()).getTime());
    var schedDate = new Date(new Date($("input[name=scheduled_date_"+i+"]").val()).getTime());
//#alert($("input[name=approve"+i+"_postpone_date]").val());
//alert(new Date(Date.parse($("input[name=approve"+i+"_postpone_date]").val())));
    //var date = Date.parse($("input[name=approve"+i+"_postpone_date]").val()); //You might want to tweak this to as per your needs.
    //#date2 = new Date(myDate.getTime()+(15*24*60*60*1000));
//var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var  oneday = 60 * 60 * 1000 * 24;
    var diffdays = Math.round((selDate.getTime() - schedDate.getTime())/oneday);
if(diffdays > 15 || diffdays < 0) { 
  msg = msg + "\nHost "+i+" selected Postpone date is not within 15 days";
  falg = false;
} 
    //$('.new').val(new_date.toString('M/d/yyyy'); //You might want to tweak this as per your needs as well.
   if($("input[name=approve"+i+"_postpone_date]").val().length == 0) {
     msg = msg + "\nHost "+i+" postpone date not selected";
     flag = false;
   }
   if(document.getElementById("approve"+i+"_comment").value.length == 0) {
     msg = msg + "\nHost "+i+" for postpone option comment is mandatory";
     flag = false;
   }
 }
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
$(document).ready(function(){
$("input:radio").change(function() {
var called = $(this).val();
if(called == "Postpone") {
  var post = document.getElementById($(this).attr("name")+"_Postpone");
  $(post).css("display", "");
  var post_d = document.getElementsByName($(this).attr("name")+"_postpone_date");
  //var post_c = document.getElementById($(this).attr("name")+"_postpone_comment");
  $(post_d).prop("required", true);
  //$(post_c).prop("required", true);
}
else {
  var post = document.getElementById($(this).attr("name")+"_Postpone");
  $(post).css("display", "none");
  //alert("Hello"+$(this).attr("name")+"_Postpone");
  var post_d = document.getElementsByName($(this).attr("name")+"_postpone_date");
  //var post_c = document.getElementById($(this).attr("name")+"_postpone_comment");
  $(post_d).prop("required", false);
  //$(post_c).prop("required", false);
}
});
});
</script>
<form method="POST" action="record_user_response.php">
<div id="head">
<div class="panel panel-primary">
<div class="panel panel-heading">Maintenance Details</div>
<div class="panel panel-body">
<?php
$count = display_approve_request($_REQUEST['ids']);
?>
</div>
</div>
</form>
</div>
</body>
<script type="text/javascript">
$(document).ready(function(){
<?php 
for($i=1; $i < $count; $i++) {
?>
$('#datetimepicker_<?php echo $i; ?>').datetimepicker({format:'Y/m/d H:i:00',minDate:0});
<?php 
}
?>
});
</script>
</html>

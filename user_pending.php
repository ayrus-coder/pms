<!DOCTYPE html>
<html>
<head>
<h4 style="position:fixed;top: 10px;right:10px;"> Logged As: <?php echo $_SERVER['REMOTE_USER']; ?></h4>
</head>
<?php

function display_approve_request ($user){
include("vars.php");
    $count = 1;
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $sql = "select id, hostname, maintenance_requests.to, cc, approved, status, postponed, updated_date, DATE_FORMAT(scheduled_date, '%Y/%c/%d %H:%m:%s') as scheduled_date, approved_by, approved_at, last_patched_date,comment from maintenance_requests where status='PENDING' and maintenance_requests.to like '%$user%'";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    #print_r($result);
      if($result['approved'] == 'Y') {
?>
<?php
        do{
?>
<div id="left<?php echo $count; ?>" style="width: 390px;height:300px;float:left;padding-top:5px;padding-left:10px;background-color:#CCFFFF;border-radius: 0px 0px 0px 5px;border-bottom: 4px solid #8AC007;">
<table>
<thead><tr><th><b>Details:</b></th></tr></thead>
<tbody>
<tr><td><b><?php echo $count; ?> - Host:</b></td></tr>
<tr><td><?php echo $result['hostname']; ?></td></tr>
<tr><td><b>Owner:</b></td></tr>
<tr><td><?php echo $result['to']; ?></td></tr>
<tr><td><b>Maintenance Date:</b></td></tr>
<tr><td><?php echo $result['scheduled_date']; ?> EST</td></tr>
<tr><td><b>Duration:</b></td></tr>
<tr><td>180 mins</td></tr>
<tr><td><b>Last Patched:</b></td></tr>
<tr><td><?php echo $result['last_patched_date']; ?></td></tr>
</tbody>
</table>
</div>
<div id="right<?php echo $count; ?>" style="width: 400px;height:300px;margin:auto;float:left;padding-top:5px;background-color:#CCFFFF;border-radius: 0px 50px 30px 5px;border-bottom: 4px solid #8AC007;">

<table>
<thead>
<tr><th > Approved </th>
</thead>
<tbody>
<tr><td><b>Status:</b></td></tr>
<tr><td><?php echo $result['status']; ?></td></tr>
<tr><td><b>Approved By:</b></td></tr>
<tr><td><?php echo $result['approved_by']; ?></td></tr>
<tr><td><b>Approved On:</b></td></tr>
<tr><td><?php echo $result['updated_date']; ?></td></tr>
<tr><td><b>Postponed:</b></td></tr>
<tr><td><?php echo $result['postponed']; ?> </td></tr>
<tr><td><b>Scheduled Date:</b></td></tr>
<tr><td><?php echo $result['scheduled_date']; ?> </td></tr>
<tr><td><b>Comment:</b></td></tr>
<tr><td><?php echo $result['comment']; ?> </td></tr>
</tbody>
</table>
</div>
<?php
       }while($result = $stmt->fetch(PDO::FETCH_ASSOC));
      } else {
        do{
?>
<div id="left<?php echo $count; ?>" style="width: 390px;min-height:274px;float:left;padding-top:5px;padding-left:10px;background-color:#CCFFFF;border-radius: 0px 0px 0px 5px;border-bottom: 4px solid #8AC007;">
<table>
<thead><tr><th><b>Details:</b></th></tr></thead>
<tbody>
<tr><td><b><?php echo $count; ?> - Host:</b></td></tr>
<tr><td><?php echo $result['hostname']; ?></td></tr>
<tr><td><b>Owner:</b></td></tr>
<tr><td><?php echo $result['to']; ?></td></tr>
<tr><td><b>Maintenance Date:</b></td></tr>
<tr><td><?php echo $result['scheduled_date']; ?> EST</td></tr>
<tr><td><b>Duration:</b></td></tr>
<tr><td>180 mins</td></tr>
<tr><td><b>Last Patched:</b></td></tr>
<tr><td><?php echo $result['last_patched_date']; ?></td></tr>
</tbody>
</table>
</div>
<div id="right<?php echo $count; ?>" style="width: 400px;height:274px;margin:auto;float:left;padding-top:5px;background-color:#CCFFFF;border-radius: 0px 50px 30px 5px;border-bottom: 4px solid #8AC007;">

<table>
<thead>
<tr><th > Action </th>
</thead>
<tbody>
<tr><td><input type="hidden" name="scheduled_date_<?php echo $count; ?>" value="<?php echo $result['scheduled_date']; ?>"/> <input type="hidden" name="id_<?php echo $count; ?>" value="<?php echo $result['id']; ?>"/><input type="radio" name="approve<?php echo $count; ?>" value="Approve" checked>Approve
<tr><td>
<input type="radio" name="approve<?php echo $count; ?>" value="Postpone" />Postpone
</td></tr>
<tr><td>
<div id="approve<?php echo $count; ?>_Postpone" style="margin-left:50px;display: none;">
<table>
<tbody>
<tr>
<td>
<label for="postfone"> *Pick Date (EST): </label></td><td><input id="datetimepicker_<?php echo $count; ?>" type="text" name="approve<?php echo $count; ?>_postpone_date" />  </td> </tr>
<tr><td colspan=2><b>Note:</b> Max +15 days allowed to postpone</td></tr>
</tbody>
</table>
</div>
</td></tr>
<tr><td>
<input type="radio" name="approve<?php echo $count; ?>" value="User" />I patched myself 
</td></tr>
<tr><td><label for="postfone"> Comment : </label><textarea rows=3 cols=20 type="text" name="approve<?php echo $count; ?>_comment" id="approve<?php echo $count; ?>_comment" ></textarea></td> </tr>
</tbody>
</table>
</div>
		<?php
	  $count++;
        }while($result = $stmt->fetch(PDO::FETCH_ASSOC));
?>
<input type="hidden" name="no_of_rows" id="no_of_rows" value="<?php echo $count; ?>" />
<div id="tail" style="min-width: 800px;height: 30px; float:left;background-color:#cca37a;">
<span style="padding-left:705px;padding-top:10px;"><input type="button" value="Submit" onclick="validate();"/></span>
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

?>
<body>
<div style="width:800px;min-height:300px;margin:auto;">
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
<div id="head" style="min-width: 800px;height: 50px; float:left;background-color:#cca37a;">
<h2 align="center">Maintenance Details</h2>
</div>
<!--
-->
<?php
$count = display_approve_request($_SERVER['REMOTE_USER']);
?>
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

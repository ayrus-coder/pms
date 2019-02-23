<!DOCTYPE html>
<html>
<?php 
include("vars.php");
include('header.php'); ?>
<link rel="stylesheet" type="text/css" href="../datetimepicker-master/jquery.datetimepicker.css"/ >
<script src="../datetimepicker-master/jquery.datetimepicker.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

<div style="padding-top:25px;" class="container">
<form name="schedule" action="schedule_add.php" method="post">
<div class="panel panel-primary">
  <div class="panel-heading"> Hosts details - <span class="label label-warning" style="color:black;">Only for Linux<span></div>
  <div class="panel-body">
<div class="form-group">
 <label for="hosts">Enter host name(s)</label>
 <textarea id="hosts" name="hosts" rows="3" cols="30" required class="form-control" 
	placeholder="example1.kendall.com<?php echo "\n" ;?>xyz.kendall.imasoft.com<?php echo "\n" ;?>abc.bos1.imasoft.com"></textarea>
</div>
<div class="form-group">
<label for="datetimepicker_1">Scheduled Date (in EST)</label>
<input style="width:250px;" id="datetimepicker_1" required type="text" name="scheduled_date" class="form-control" placeholder="YYYY/MM/DD HH:MM:SS"/>
</div>
<div class="form-group">
<label for="radio">Notification Type</label>
<div id="radio">
<div class="radio">
<label><input type="radio" name="optradio" value="3" required>Notification Only</label>
</div>
<div class="radio">
      <label><input type="radio" name="optradio" value="1">Response Required</label>
    </div>
    <div class="radio">
      <label><input type="radio" name="optradio" value="2">Coordination Required</label>
    </div>
    <div class="radio">
      <label><input type="radio" name="optradio" value="4" required>Pick from HostDB</label>
    </div>
</div>
<button class="btn btn-primary pull-right" type="button" onclick="javascript:validate();"  >Schedule</button>
<input type="submit" style="display:none;" id="Submit" />
</div>
</div>
</form>
</div>
<script type="text/javascript">
function validate() {
    var isValidForm = document.forms['schedule'].checkValidity();
    if(isValidForm) {
    var selDate = new Date(new Date($("input[name=scheduled_date]").val()).getTime());
    var schedDate = new Date();
    var  oneday = 60 * 60 * 1000 * 24;
    var diffdays = Math.round((selDate.getTime() - schedDate.getTime())/oneday);
    //alert(diffdays);
    if ( diffdays >= 0 && confirm("Please confirm to proceed") == true) {
        $("form").submit();
    }
    else {
  	alert("Please provide only future date");
	return 0;
   }
  } 
  else {
     $('#Submit').click();
  }
}
$(document).ready(function(){
$('#datetimepicker_1').datetimepicker({format:'Y/m/d H:i:00',closeOnDateSelect:true});
});
</script>
</body>
</html>

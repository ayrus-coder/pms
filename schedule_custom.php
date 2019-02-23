<!DOCTYPE html>
<html>
<?php 
include("vars.php");
include('header.php'); ?>
<link rel="stylesheet" type="text/css" href="../datetimepicker-master/jquery.datetimepicker.css"/ >
<script src="../datetimepicker-master/jquery.datetimepicker.js"></script>

<div style="padding-top:25px;" class="container">
<form name="schedule" action="schedule_custom_add.php" method="post">
<div class="panel panel-primary">
	<div class="panel-heading"> Hosts details</div>
	<div class="panel-body">
		<div class="form-group">
		   <label for="hosts">Hostname(s)</label>
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
	</div>
</div>
</div>

<div class="panel panel-primary" style="">
	<div class="panel-heading">Email details</div>
	<div class="panel-body">
		<div class="form-group">
		<label for="to">To</label>
		<input type="text" name="to" id="to" style="width:250px;" placeholder="Owner email ids, separated by comma" class="form-control">
		</div>
		<div class="form-group">
		<label for="cc">CC</label>
		<input type="text" name="cc" id="cc" style="width:250px;" placeholder="CC list email ids, separated by comma" class="form-control">
		</div>
		<div class="form-group">
		<label for="from">From</label>
		<input type="hidden" name="from" value="<?php echo $_SERVER['REMOTE_USER']; ?>"> 
		<input type="text" style="width:250px;" id="from" value="<?php echo $_SERVER['REMOTE_USER']; ?>" class="form-control" disabled> 
		</div>
		<div class="form-group">
		  <label for="subject">Subject</label>
		  <input type="text" name="subject" id="subject" style="width:250px;" required class="form-control" placeholder="Enter subject here">
		</div>
		<div class="form-group">
		  <label for="content">Content</label>
		  <textarea name="content" id="content" rows="14" cols="30" required class="form-control" placeholder="Dear User,<?php echo "\n\n" ;?>This email is being sent to inform you of an upcoming Windows server patching for the below system(s) for which you are listed as responsible for.<?php echo "\n\n" ;?>We have a patching window for the below host(s) on 2016-05-26. This server patching will require 20 to 30 minutes for each host when the system will not be available.<?php echo "\n\n" ;?>{{HOST}}<?php echo "\n\n" ;?>Please confirm the date or time by clicking the link below. If there is a more convenient time you would like to propose, please do so by clicking the same link.<?php echo "\n\n" ;?>Please respond by visiting {{LINK}} and selecting the appropriate option.<?php echo "\n\n" ;?>Regards<?php echo "\n" ;?>ITOC Windows"></textarea>
		</div>
		<div class="form-group">
		<button class="btn btn-primary pull-right" type="button" onclick="javascript:validate();" value="Submit" />Schedule</button>
		</div>
		<input type="submit" style="display:none;" id="Submit" value="Submit"/>

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

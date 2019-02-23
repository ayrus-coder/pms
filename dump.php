<!DOCTYPE html>
<html>
<?php include('header.php'); ?>
<script type="text/javascript" src="../js/multiple-select.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.6/angular.min.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>

<link rel="stylesheet" href="../css/multiple-select.css" type="text/css"/>
  <script>
	$(document).ready(function(){
	$('#category').change(function() {
	//alert($("#category option:checked").length);
	//alert($(this).find(":checked").val());
  	if($("#category option:checked").length == 1) {
	   if($(this).find(":checked").val() == 1) {
	     $('#patch').hide();
             $('#patching').prop('required',false);
	     $('#notify').show();
	     $('#notification').prop('required',true);
	   }
	   else if($(this).find(":checked").val() == 2) {
		$('#notify').hide();
		$('#notification').prop('required',false);
		$('#patch').show();
		$('#patching').prop('required',true);
	     }
	}
	else {
	  $('#patch').show();
          $('#patching').prop('required',true);
	  $('#notify').show();
	  $('#notification').prop('required',true);
	   }
	});});
  </script>

<div class="container" ng-app="patching">
<form name="report" action="dump_generate.php" method="POST" role="form" >
<div class="panel panel-primary">
<div class="panel-heading">CSV Report Generation</div>
<div class="panel-body">
    <div class="form-group" ng-controller="unixPatching">
	<label for="team" > Team</label>
	<select id='team' name="team[]" class="form-control" required multiple size="3">
	<option ng-repeat="team in teams" value={{team.position}} >{{team.name}}</option>
	</select>
    </div>
    <div class="form-group" ng-controller="unixPatching">
	<label for="category" > Category </label>
	<select name="category[]" id="category" class="form-control" required multiple size="2">
	<option ng-repeat="category in categories" value={{category.position}} >{{category.name}}</option>
	</select>
    </div>
    <div class="form-group" id="notify" ng-controller="unixPatching">
	<label for="notification" >Notification (Choose Columns)</label>
	<select id="notification" name="notification[]" class="form-control" required multiple>
		<option ng-repeat="notify in notificationCols" value="{{notify.column}}">{{notify.name}}</option>
	</select>
    </div>
    <div class="form-group" id="patch" ng-controller="unixPatching">
	<label for="patching" >Patching (Choose Columns)</label>
	<select id='patching' name="patching[]" class="form-control" required multiple="true">
		<option ng-repeat="patching in patchingCols" value="{{patching.column}}">{{patching.name}}</option>
      	</select>
    </div>
    <div class="form-group">
	<label for="from" > Date From</label>
	<input type="date" id="from" name="from" class="form-control" /> 
	<label for="to" > Date To</label>
	<input type="date" id="to" name="to" class="form-control " /> 
    </div>
   <div class="form-group">
	<input type="submit" class="pull-right next btn btn-primary" value='Generate'>
   </div>
</div>
</div>
</form>
</div>
</body>
</html>

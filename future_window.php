<html>
<?php 
include("vars.php");
include('header.php'); 
$date = date("Y-m-d");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
 $("#start").change(function(){
	$("#end").attr({"min":$("#start").val()});
 });
});
</script>
<div class="container" ng-app="editApp">
<div class="panel panel-primary">
 <div class="panel-heading"><h4>Future maintenance List</h4></div>
 <div class="panel-body">
	<form action="get_list.php" method="POST" ng-contoller="editCtrl">
	 <div class="form-group">
	   <label for="start">Start </label>
	   <input id="start" type="date" name="start" class="form-control" required min="<?php echo $date; ?>">
	 </div>
	 <div class="form-group">
	   <label for="end">End </label>
	   <input type="date" id="end"  name="end" class="form-control" required min="<?php echo $date; ?>">
	 </div>
	 <div class="form-group pull-right">
	   <input class="btn btn-primary" type="submit">
	 </div>
	</form>
 </div>
</div>
</div>
</body>
</html>

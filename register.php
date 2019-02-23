<!DOCTYPE html>
<html>
<?php include('header.php'); ?>
<script>
  var app = angular.module("myApp", [])
		.controller("myCtrl", function($scope) {});
</script>
<div id="CURRENT" style="" class="container">
<form action="register_host.php" method="POST" name="register" ng-app="myApp" ng-controller="myCtrl">
<div class="panel panel-primary">
	<div class="panel-heading">Register Host </div>
	<div class="panel-body">
		<div class="form-group">
		<label for="hosts">Hostnames</label>
		<textarea  ng-model="hosts" name="hosts" id="hosts" placeholder="example1.kendall.com<?php echo "\n" ;?>xyz.kendall.imasoft.com<?php echo "\n" ;?>abc.bos1.imasoft.com" rows=10 cols=70 required class="form-control"></textarea>
		</div>
		<div class="form-group">
		<input ng-disabled="register.hosts.$dirty && register.hosts.$invalid" type="submit" class="btn btn-primary pull-right" value="Register" />
		</div>
	</div>
</div>
</form>
</div>

</body>
</html>

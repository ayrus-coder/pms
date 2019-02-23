<html>
<?php 
include("vars.php");
include('header.php'); 
?>
<style>
th {
    white-space: nowrap;
}
.prime{
background-color: #337ab7;
color:white;
}
.warning{
background-color: #f0ad4e;
color:white;
}

</style>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.15/angular-sanitize.min.js"></script>
<script src="js/ng-csv.js"></script>
<script>
var app = angular.module("lastUpdate", ["ngSanitize","ngCsv"])
	.filter("status", function(){
		return function(txtStatus){
			switch(txtStatus){
			  case 'APPROVED':
			  case 'COMPLETED':
				return "success";
			  case 'NO_RESPONSE':
			  case 'FAILED':
			  case 'TIMEOUT':
			  case 'AUTO_CANCE':
				return "danger";
			  case 'PENDING':
			  case 'RUNNING':
				return "warning";
			  case 'CANCELED':
				return "default";
			  case 'QUEUED':
				return "primary";
			  case 'NA':
				return "default";
			  default:
			   return "info";
			}
		};
	})
	.filter("schedule", function(){
		return function(txt){
			if(txt){
			  return 'SCHEDULED';
			} else {
			  return 'NA';
			}
		};
	})
	.filter("search", function(){
		return function(host, filter){
			var filteredHost = {};
			if(filter.group == "All" && filter.name === undefined){
				return host;
			}
			else if(filter.group !='All'){
				angular.forEach(host, function(val, key){
				  if(host[key].group == filter.group && (filter.name === undefined || key.search(filter.name) >=0)){
				    filteredHost[key] = val;
				  }
				});
			}	
			else {
				angular.forEach(host, function(val, key){
				  if(key.search(filter.name) >=0){
				    filteredHost[key] = val;
				  }
				});
			}
		return filteredHost;
		}
	})
	.filter("checkEmpty", function(){
		return function(txt){
		   if(!txt){
			return "   NA   ";
		   }
		   return txt;
		}
	})
	.controller("updateCtrl", function($scope, $http){
		var self = this;
		$scope.dataAvailable = false;
		$scope.grpFilter = "All";
		$scope.name = "";
		$scope.getArray = [{"name":"Name","notify_date":"Notify Date","notify_status":"Notify Status","group":"Group","patched_date":"Patched Date","patched_status":"Patched Status", "next_patch_date":"Next Patch Date","next_patched_status":"Next Patch Status", "last_patch_date":"Last Patched Date", "last_patched_status":"Last Patched Status"}];
		//$scope.getArray = [];
		$scope.filename = "last_patch_report";
		$http.get("https://<?php echo $localhost; ?>/maintenance/API/host/last_update.php").then(function(res){
			$scope.data = res.data;
			angular.forEach($scope.data, function(val, key){
			   $scope.getArray.push($scope.data[key]);
			});
			$scope.dataAvailable = true;
		}); 
		$scope.getHeader = function () {return ["name","notify_date","notify_status","group","patched_date","patched_status","last_patched_date","last_patched_status","next_patched_date","next_patched_status"];};
		
	});
</script>

<div ng-app="lastUpdate">
<div ng-controller="updateCtrl">
<div ng-hide="dataAvailable">
    <div class="container text-center"> 
	<h1 class="label label-warning"> Please wait while data being fetched ...</h1><br><br><br>
	<img src='200.gif' alt="Loading.."/>
    </div>
</div>
  <div ng-show='dataAvailable' >
  <button class="btn btn-primary pull-right"
              ng-csv="getArray" lazy-load="true" filename="{{filename}}.csv" field-separator=","
              >Export to CSV </button> 
  <div  class="container" style="padding-bottom:10px;">
    <div class="input-group">
      <span class="input-group-addon prime">Filter : </span>
      <span class="input-group-addon warning">Group</span>
	 <select id="grpFilter" class="form-control" name="grpFilter" ng-model="grpFilter" >
	<option>PCI/SOX</option>
	<option>PCI</option>
	<option>SOX</option>
	<option>PROD</option>
	<option>NON-PROD</option>
	<option>All</option>
  </select>
  <span class="input-group-addon warning">Host </span> <input type="text" ng-model="hstFilter" name="name" class="form-control">

  </div>
  </div>
<div class="container-fluid">
  <table ng-table="vm.tableParams" class="table" show-filter="true" ng-show='dataAvailable'>
    <tr><th>Name</th><th>Group</th><th>Notify Date</th><th>Notify Status</th><th>Patched Date</th><th>Patched Status</th><th>Last Patched Date</th><th>Last Patched Status</th><th>Next Patch Date</th><th>Next Patch Status</th></tr>
    <tr ng-repeat="host in data|search:{group: grpFilter, name: hstFilter}">
        <td >
            {{host.name}}</td>
        <td >
            {{host.group}}</td>
        <td >
            {{host.notify_date}}</td>
        <td >
            <span class="label label-{{host.notify_status|status}}">{{host.notify_status}}</span></td>
        <td >
            {{host.patched_date|checkEmpty}}</td>
        <td >
            <span class="label label-{{host.patched_status|status}}">{{host.patched_status | checkEmpty}}</span></td>
        <td >
            {{host.last_patched_date | checkEmpty}}</td>
        <td >
            <span class="label label-{{host.last_patched_status|status}}">{{host.last_patched_status | checkEmpty}}</span></td>
        <td >
            {{host.next_patch_date | checkEmpty}}</td>
        <td >
            <span class="label label-{{host.next_patch_date|status}}">{{host.next_patch_date | schedule}}</span></td>
    </tr>
</table>
</div>
</div>
</div>
</div>

</body>
</html>

var patching = angular.module("patching", [])
		.controller("unixPatching", function($scope) {
			$scope.teams = [
				{name:"ITOC", position: 0},
				{name:"Unix", position: 1},
				{name:"Windows", position: 2}
			];	
			$scope.categories = [
				{name:"Notification", position: 1},
				{name:"Patching", position: 2}
				];
			$scope.notificationCols = [
				{column:"hostname",name:"Host"},
				{column:"maintenance_requests.to",name:"Owner"},
				{column:"cc",name:"Stakeholder"},
				{column:"scheduled_date",name:"Scheduled Date"},
				{column:"status",name:"Status"},
				{column:"approved",name:"Approved"},
				{column:"approved_by",name:"Approved By"},
				{column:"approved_at",name:"Approved At"},
				{column:"notification_sent",name:"Notification Sent"},
				{column:"notification_type",name:"Notification Type"},
				{column:"postponed",name:"Postponed"},
				{column:"coordination",name:"Coordination"},
				{column:"comment",name:"Comment"}
			]; 
			$scope.patchingCols = [
				{column:"host", name:"Host"},
				{column:"status", name:"Status"},
				{column:"scheduled_date", name:"Scheduled Date"},
				{column:"started_date", name:"Started Date"},
				{column:"completed_date", name:"Completed Date"},
				{column:"os_type", name:"OS"},
				{column:"updates_log", name:"Comment"},
				{column:"sla", name:"SLA"},
				{column:"auto_patch", name:"Auto Patch"}
			];
		});

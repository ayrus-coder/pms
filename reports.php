<!DOCTYPE html>
<html>
<?php include('header.php'); ?>
  <script>
	$(document).ready(function(){
 	$('#team').change(function() {
	   if($(this).find(":checked").val() == 2) {
	     $("#auto option[value='1']").remove();
	     $("#auto option[value='2']").remove();
	   }
	   else if($('#auto option').size() < 2){
	     $("#auto").append('<option value="1">Auto</option>');
	     $("#auto").append('<option value="2">All</option>');
	   }
	});
	$('#type').change(function() {
	   if($(this).find(":checked").val() == 'daily') {
	     $('#monthyear').show();
	     $('#from').prop('required',false);
	     $('#to').prop('required',false);
	     $('#range').hide();
	   }
	   else {
	     $('#monthyear').hide();
	     $('#range').show();
	     $('#from').prop('required',true);
	     $('#to').prop('required',true);
	   }
	});});
  </script>
<div class="container">
<form name="report" action="report_generate.php" method="POST" role="form" >
<div class="panel panel-primary">

<div class="panel-heading">Report Generation</div>
<div class="panel-body">
    <div class="form-group">
	<label for="team"> Team</label>
	<select id='team' name="team" class="form-control" required>
	<option value="1">Unix</option>
	<option value="2">Windows</option>
	<option value="3">All</option>
	</select>
    </div>
    <div class="form-group">
	<label for="auto"> Patching </label>
	<select name="auto" id="auto" class="form-control" required>
	<option value="0">Manual</option>
	<option value="1">Auto</option>
	<option value="2">All</option>
	</select>
    </div>
    <div class="form-group">
	<label for="type"> Report Type</label>
	<select name="type" id="type" class="form-control" required>
	<option value="daily">Daily</option>
	<option value="weekly">Weekly</option>
	<option value="monthly">Monthly</option>
	</select>
    </div>
<div id="monthyear">
    <div class="form-group">
	<label for="year">Select Year</label>
	<select name="year" id="year" size="1" class="form-control">
 	<?php $c_year = date("Y"); for($year=2015; $year<= $c_year; $year++) { ?>
    		<option value="<?php echo $year;?>"><?php echo $year;?></option>
	<?php
	}
	?>
	</select>
    </div>
    <div class="form-group">
	<label for="month">Select Month </label>
	<select name="month" id="month" size="1" class="form-control">
    	    <option value="01">January</option>
	    <option value="02">February</option>
	    <option value="03">March</option>
	    <option value="04">April</option>
	    <option value="05">May</option>
	    <option value="06">June</option>
	    <option value="07">July</option>
	    <option value="08">August</option>
	    <option value="09">September</option>
	    <option value="10">October</option>
	    <option value="11">November</option>
	    <option value="12">December</option>
	</select>
    </div>
</div>
<div id="range" style="display:none">
    <div class="form-group">
	<label for="from"> Date From</label>
	<input type="date" id="from" name="from" class="form-control"> 
	<label for="to"> Date To</label>
	<input type="date" id="to" name="to" class="form-control"> 
    </div>
</div>
   <div class="form-group">
	<input type="submit" class=" pull-right btn btn-primary" value='Search'>
    </div>
</div>
</div>
</form>
</div>

</body>
</html>

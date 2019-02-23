<?php
include "header.php";
?>
<div class="container">
        <div class="panel panel-primary" style="padding-bottom:30px;">
        <div class="panel-heading">Download Report as CSV - From <span class="label label-warning"><?php echo $_POST['start']; ?></span> To <span class="label label-warning"><?php echo $_POST['end']; ?></span></div>
        <div class="panel-body">
<?php
$user = $_SERVER['REMOTE_USER'];
$start = $_POST['start'];
$end = $_POST['end'];
if(isset($_POST['start']) && isset($_POST['end'])) {
#print_r($_POST);
$result = exec("perl /opt/EIS/CM/lib/EIS/Patch/generate_future_patching_data.pl $user $start $end",$output);
?>
        <div>
                <form action="fd.php" method="POST">
                <input type="hidden" name="filename" value="<?php echo "$output[0]";?>" />
                 <button type="button" class="btn btn-primary pull-right" style="width:300px;margin-right:15px;" onclick="this.parentNode.submit()">Download PCI Hosts report</button><br>
                </form>
        </div>
	<br>
        <div>
                <form action="fd.php" method="POST">
                <input type="hidden" name="filename" value="<?php echo "$output[1]";?>" />
                 <button type="button" class="btn btn-primary pull-right" style="width:300px;margin-right:15px;" onclick="this.parentNode.submit()">Download SOX Hosts report</button><br>
                </form>
        </div>
	<br>
        <div>
                <form action="fd.php" method="POST">
                <input type="hidden" name="filename" value="<?php echo "$output[2]";?>" />
                 <button type="button" class="btn btn-primary pull-right" style="width:300px;margin-right:15px;" onclick="this.parentNode.submit()">Download Prod Hosts report</button><br>
                </form>
        </div>
	<br>
        <div>
                <form action="fd.php" method="POST">
                <input type="hidden" name="filename" value="<?php echo "$output[3]";?>" />
                 <button type="button" class="btn btn-primary pull-right" style="width:300px;margin-right:15px;" onclick="this.parentNode.submit()">Download Non Prod Hosts report</button><br>
                </form>
        </div>
<?php
}
else {
?>
<h1> No Start And/Or date selected </h1>
<?php
}
?>
</div>
</div>

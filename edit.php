<html>
<?php 
include("vars.php");
include('header.php'); 
?>

<div class="container" ng-app="editApp">
<div class="panel panel-primary">
 <div class="panel-heading"><h4>Edit host info</h4></div>
 <div class="panel-content">
 </div>
</div>
<?php
// Create connection
try {
    $conn = new PDO("mysql:host=$servername;dbname=patch", $username, $password);
    $status = " where host='".$_REQUEST['host']."'";
    if($_REQUEST['host'] == 'ALL') {
      $status =  "";
    }
   
    $sql = 'select id, host, active,os_type,created_at, updated_at,auto_patch,note,contact from maintenance_registered_hosts where id='.$_REQUEST['id'];
#echo $sql;
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
$curl = "curl -H 'accept: text/plain' https://unix-access.imasoft.com/query/maintcategory/".$result['host']." -k";
    #print_r($result); 
$out = exec($curl);
?>
<script>
</script>
<form action="edit_host.php" method="POST" ng-contoller="editCtrl">
<input type="hidden" name="id" value="<?php echo $result['id']; ?>">
<input type="hidden" name="host" value="<?php echo $result['host']; ?>">
 <div class="form-group">
   <label for="host">Host </label>
   <input type="text" name="host" value="<?php echo $result['host']; ?>" class="form-control" required disabled>
 </div>
 <div class="form-group">
   <label for="active" >Active</label>
   <select name="active" class="form-control">
     <option <?php if(preg_match('/Y/i',$result['active'])) { echo "selected"; } ?>>Y</option>
     <option <?php if(preg_match('/N/i',$result['active'])) { echo "selected"; } ?>>N</option>
   </select>
 </div>
 <div class="form-group">
   <label for="os_type">OS</label>
   <input type="text" name="os_type" value="<?php echo $result['os_type']; ?>" class="form-control" required>
 </div>
 <div class="form-group">
   <label for="auto_patch">Auto Patch</label>
   <select name="auto_patch" class="form-control" <?php if(preg_match("/Co-ordination/",$out)) { echo "disabled"; } ?> >
     <option <?php if(preg_match('/Y/i',$result['auto_patch'])) { echo "selected".$result['auto_patch']; } ?>>Y</option>
     <option <?php if(preg_match('/N/i',$result['auto_patch'])) { echo "selected"; } ?>>N</option>
   </select>
 </div>
 <div class="form-group">
   <label for="contact">Contact</label>
   <input type="text" name="contact" value="<?php echo $result['contact']; ?>" class="form-control">
 </div>
 <div class="form-group">
   <label for="note">Note</label>
   <textarea name="note" class="form-control"><?php echo $result['contact']; ?></textarea>
 </div>
 <div class="form-group pull-right">
   <input class="btn btn-primary" type="submit">
 </div>
</form>

<?php

    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }
$conn = null;
?>
</div>
</body>
</html>

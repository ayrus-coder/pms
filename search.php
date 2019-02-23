<!DOCTYPE html>
<html>
<?php include('header.php'); ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<div id="CURRENT" style="padding-top:20px;">
<form action="host_search.php" method="POST">
<table>
<tbody>
<thead>
<tr><th colspan=2>Search for Notification and Patching details of host </th>
</tr>
</thead>
<tr>
<td>Hostname</td>
<td><input type=text name="host" style='width:190px;' required></td>
<tr>
<td></td><td align="right"><input type="submit" value="Search" /></td>
</tr>
</tbody>
</table>
</form>
</div>

</body>
</html>

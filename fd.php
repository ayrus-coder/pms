<?php
$file = '/var/tmp/maintenance/'.$_POST['filename'];
#echo $file;
if (file_exists($file)) {
    header("Content-Length: " . filesize($file));
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$_POST['filename']);
    
    readfile($file);
}
?>

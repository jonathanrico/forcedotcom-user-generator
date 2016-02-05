<?php
header('Content-Type: image/jpeg');
echo file_get_contents("portraits/med/lego/" . mt_rand(1, 9) . ".jpg");
?>

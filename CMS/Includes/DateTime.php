<?php
date_default_timezone_set("Asia/Kolkata");
$CurrentTime=time();
$DateTime=strftime("%Y-%m-%d %H:%m:%S",$CurrentTime);
echo $DateTime;
?>
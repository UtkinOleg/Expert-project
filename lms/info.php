<?php  

$status = uploadprogress_get_info($_POST['uid']); 
 echo json_encode($status) ; 

?>
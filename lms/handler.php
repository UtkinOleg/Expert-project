<?php  
 function jsOnResponse($obj)  
 {  
 echo ' 
 <script type="text/javascript"> 
 window.parent.onResponse("'.$obj.'"); 
 </script> 
 ';  
 }  
  
 $dir = '/uploads'; // путь к каталогу загрузок на сервере  
 $name = basename($_FILES['loadfile']['name']);  
 $file = $dir . $name;  
  
 $success = move_uploaded_file($_FILES['loadfile']['tmp_name'], $file);  
 jsOnResponse("{'filename':'" . $name . "', 'success':'" . $success . "'}");  
  
?>
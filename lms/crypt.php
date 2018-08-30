<?php
 include "config.php";
 include "func.php";
 if(!defined("IN_ADMIN")) die;  

 $i=0;
  while($i<=13)
  {
   $i=$i+1;
   $lst2 = mysql_query("SELECT password FROM users WHERE id=".$i);
   if (!$lst2) puterror("Ошибка при обращении к базе данных");
   $list = mysql_fetch_array($lst2);
   $pass = $list['password'];
   $query = "UPDATE users SET password = '".md5($pass)."'
           WHERE id=".$i;
   if(!mysql_query($query))
    puterror("Ошибка при обращении к базе данных");

  }


?>
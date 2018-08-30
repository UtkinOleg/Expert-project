<?php
if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) {  
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";

  $id = $_GET["id"];
  $kid = $_GET["kid"];

  $tg = mysql_query("SELECT userid FROM questgroups WHERE id='".$id."' LIMIT 1;");
  if (!$tg) puterror("Ошибка при обращении к базе данных");
  $tgdata = mysql_fetch_array($tg);

  if ((defined("IN_SUPERVISOR") and $tgdata['userid'] == USER_ID) or defined("IN_ADMIN")) 
  {
  mysql_query("START TRANSACTION;");
  $qst = mysql_query("SELECT id FROM questions WHERE qgroupid='".$id."' ORDER BY id");
  while($member = mysql_fetch_array($qst))
       {
        $query = "DELETE FROM answers WHERE questionid=".$member['id'];
        if (!mysql_query($query))
           puterror("Ошибка при обращении к базе данных");
       }

  $query = "DELETE FROM questions WHERE qgroupid=".$id;
  if(mysql_query($query))
  {
      mysql_query("COMMIT");
      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=questgroups&kid=".$kid."'>\n";
      print "</HEAD></HTML>\n";
  }
  else 
   puterror("Ошибка при обращении к базе данных");
 }
 else die;  
} 
else 
 die;  
?>
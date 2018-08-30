<?php
if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) {
  include "config.php";
  include "func.php";
  $query = "DELETE FROM knowledge
            WHERE id=".$_GET["id"];
  if(mysql_query($query))
  {
      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=knows'>\n";
      print "</HEAD></HTML>\n";
  }
  else puterror("Ошибка при обращении к базе данных");
} else die;  
?>
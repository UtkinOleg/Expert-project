<?php
if(!defined("IN_ADMIN")) die;  
  include "config.php";
  include "func.php";
  $query = "DELETE FROM logs
            WHERE id=".$_GET["id"];
  if(mysql_query($query))
  {
      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?op=logs&start=".$_GET["start"]."'>\n";
      print "</HEAD></HTML>\n";
  }
  else puterror("Ошибка при обращении к базе данных");
?>
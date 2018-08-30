<?php
if(!defined("IN_ADMIN")) die;  
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";

  $query1 = mysql_query("SELECT * FROM shablondb WHERE id=".$_GET["id"]);
  if (!$query1) puterror("Ошибка при обращении к базе данных");
  $r1 = mysql_fetch_array($query1);

  $query4 = mysql_query("UPDATE projects SET maxball = '0', status = 'inprocess' WHERE id=".$r1['memberid']);
  if (!$query4) puterror("Ошибка при обращении к базе данных");
  
  $query = "DELETE FROM leafs
            WHERE shablondbid=".$_GET["id"];
  // Удаляем сообщение с первичным ключом $id
  if(mysql_query($query))
  {
   $query2 = "DELETE FROM shablondb
            WHERE id=".$_GET["id"];
   if(mysql_query($query2))
   {
      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=''>\n";
      print "</HEAD></HTML>\n";
   }
   else puterror("Ошибка при обращении к базе данных (Удаление шаблона)");
  }
  else puterror("Ошибка при обращении к базе данных (Удаление ветвей)");



?>
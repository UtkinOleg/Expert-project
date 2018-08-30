<?php
  if(!defined("IN_ADMIN")) die;  
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";
  $title=$titlepage="Параметры";
  
  $helppage='';
  // Выводим шапку страницы
  include "topadmin.php";

  echo"<a class=link href='index.php?op=grades'>Оценки</a>";
  echo"<br><a class=link href='index.php?op=groups'>Группы шаблона</a>";
  echo"<br><a class=link href='index.php?op=shablon'>Шаблон экспертного листа</a>";


  include "bottomadmin.php";
?>
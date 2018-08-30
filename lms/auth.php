<?php 

// Данный файл всегда будит "включаться" в другие файлы 
// директивой include поэтому следует запретить его самостоятельный вызов 
// из строки запроса путём указания его имени 
// Если не определена константа IN_ADMIN – завершаем работу скрипта 
if(!defined("IN_ADMIN")) die; 

include "config.php";

// Начинаем сессию 
session_start();     


// Проверям были ли посланы данные 
if(!empty($_POST['enter'])) 
{ 

$login = $_POST['login']; 
$passw = $_POST['passw']; 


$query = "select id from users where username='$login' and password='$passw'";

$r = $result = mysql_query($query);
if(!$r)exit(mysql_error());
$number = mysql_num_rows($result);

if ($number != 0)
{
  $_SESSION['login'] = $_POST['login']; 
  $_SESSION['passw'] = $_POST['passw']; 
} 

}
// Если ввода не было, или они не верны 
// просим их ввести 
if(empty($_SESSION['login'])) 
{ 
   
    echo $query;
?> 
     <form action=index.php method=post> 
     Логин <input type="text" name=login value=""> 
     Пароль <input type="password" name=passw value=""> 
     <input type=hidden name=enter value=yes> 
     <input class=button type=submit value="Вход"> 
   <?php 
   die; 
} 

?> 
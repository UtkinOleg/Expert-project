<?php 

// ������ ���� ������ ����� "����������" � ������ ����� 
// ���������� include ������� ������� ��������� ��� ��������������� ����� 
// �� ������ ������� ���� �������� ��� ����� 
// ���� �� ���������� ��������� IN_ADMIN � ��������� ������ ������� 
if(!defined("IN_ADMIN")) die; 

include "config.php";

// �������� ������ 
session_start();     


// �������� ���� �� ������� ������ 
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
// ���� ����� �� ����, ��� ��� �� ����� 
// ������ �� ������ 
if(empty($_SESSION['login'])) 
{ 
   
    echo $query;
?> 
     <form action=index.php method=post> 
     ����� <input type="text" name=login value=""> 
     ������ <input type="password" name=passw value=""> 
     <input type=hidden name=enter value=yes> 
     <input class=button type=submit value="����"> 
   <?php 
   die; 
} 

?> 
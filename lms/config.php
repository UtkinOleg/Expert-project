<?php

  $dblocation = "localhost";
  $dbname = "";
  $dbuser = "";
  $dbpasswd = "";
  $pnumber = 30;
  $sendmail = false;
  $valmail = "";
  $valmail2 = "";
  $version = "1.0";
  $site = "";

  // �������� - ��������� ����������� SQL ��������
  $enable_cache = false;
  
  // ������ �����
  $max_file_size = 3096576; 
  $max_file_size_str = "3";
  $file_types_array=array("txt","doc","lts","xls","qg2","mbk","docx","xlsx","pdf","ppt","pptx","rar","zip");
  $upload_dir="uploads/";
  $xmlupload_dir="xmluploads/";
  
  // ������ ����������
  $photo_max_file_size = 1048576; 
  $photo_max_file_size_str = "1";
  $photo_file_types_array=array("jpeg","jpg","gif","png");
  $photo_upload_dir="uploads/avatars/";
  $pa_upload_dir="uploads/pavatars/";

  $resizing = 100;
  // ���������� ������� ��� �������� ������ � �����
  $monthtoarch = 3;
  
  ini_set('display_errors', 0);
  error_reporting(); // E_ALL
  
  date_default_timezone_set('Asia/Irkutsk');
    
  // ����������� � �������� ���� ������
  define("MYSQLND_QC_ENABLE_SWITCH", "qc=on");
  
  $mysqli = mysqli_connect($dblocation,$dbuser,$dbpasswd,$dbname);
  if (!$mysqli) {
  ?>
   "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head><body>
<P>� ��������� ������ ������ ���� ������ �� ��������.</P></body></html>
  <?
    exit();
  }
  
  $dbcnx = @mysql_connect($dblocation,$dbuser,$dbpasswd);
  if (!$dbcnx)
  {
  ?>
   "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head><body>
<P>� ��������� ������ ������ ���� ������ �� ��������.</P></body></html>
  <?
    exit();
  }
  // �������� ���� ������
  if (! @mysql_select_db($dbname,$dbcnx) )
  {
  ?>
   "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head><body>
<P>� ��������� ������ ���� ������ �� ��������.</P></body></html>
  <?
    exit();
  }
  
 mysql_query("SET time_zone = '+08:00';");  
 mysqli_query($mysqli,"SET time_zone = '+08:00';");  
?>
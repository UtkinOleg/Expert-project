<?php
  if(defined("IN_ADMIN")) 
  {
 
// Устанавливаем соединение с базой данных
include "config.php";
include "func.php";
$error = "";

// Возвращаем значение переменной action
$action = $_POST["action"];
$nomenu = $_GET["nomenu"];

if (empty($action)) 
 $mode = $_GET["mode"];
else
 $mode = $_POST["mode"];

if (empty($action)) 
{
 $multiid = $_GET["multi"];
 if (empty($multiid))
  $multiid = 0;
}
else
 $multiid = $_POST["multi"];

if (!empty($nomenu)) 
 $title=$titlepage="";
else
{
 if ($mode=='add')
  $title=$titlepage="Добавление проекта";
 else
  $title=$titlepage="Редактирование проекта";
}

if (empty($nomenu)) 
 include "topadmin2.php";
else 
 include "topadmin5.php";

// Если оно не пусто - добавляем сообщение в базу данных
if (!empty($action)) 
{

  $userid = USER_ID;
  $paid = $_POST["paid"];

  if (empty($_POST["info"])) 
  {
    $action = ""; 
    $error = "<LI>Вы не указали наименование проекта.\n";
  }

  $res10=mysql_query("SELECT * FROM poptions WHERE proarrid='".$paid."' AND multiid='".$multiid."' ORDER BY id");
  while($mb10 = mysql_fetch_array($res10))
  {
    $optionsid = $mb10['id'];
    $filedata = $_FILES["file".$optionsid]["name"]; 

    if($_FILES["file".$optionsid]["name"]!=""){ 
          $origfilename = $_FILES["file".$optionsid]["name"]; 
          $filename = explode(".", $_FILES["file".$optionsid]["name"]); 
          $filenameext = $filename[count($filename)-1]; 
          unset($filename[count($filename)-1]); 
          $filename = implode(".", $filename); 
          $filename = substr($filename, 0, 15).".".$filenameext; 
          $file_ext_allow = FALSE; 

          if ($mb10['filetype']=="file") {
           for($x=0;$x<count($file_types_array);$x++){ 
             if(strtolower($filenameext)==$file_types_array[$x]){ 
              $file_ext_allow = TRUE; 
             } 
            }
           }
           
          if ($mb10['filetype']=="foto") {
           for($x=0;$x<count($photo_file_types_array);$x++){ 
             if(strtolower($filenameext)==$photo_file_types_array[$x]){ 
              $file_ext_allow = TRUE; 
             } 
            }
           }

          if($file_ext_allow){ 
            if($_FILES["file".$optionsid]["size"]>$max_file_size){ 
              $error=$error.$origfilename." превышает размер ".$max_file_size." байт<br>"; 
            } 
          }else{ 
            $error=$error.$origfilename." не поддерживается.<br>"; 
          } 
    } 

  }

   if (!empty($error)) 
  {
    print "<P>Во время изменения проекта произошли следующие ошибки:</p>\n";
    print "<UL>\n";
    print $error;
    print "</UL>\n";
    print "<input type='button' name='close' value='Назад' onclick='history.back()'>"; 
    exit();
  }

  if ($mode=='add')
  {
   $userid = USER_ID;
   $info = $_POST["info"];
   mysql_query("LOCK TABLES projects WRITE");
   mysql_query("SET AUTOCOMMIT = 0");
   $query = "INSERT INTO projects VALUES (0,$userid,'$info',NOW(),'created',0,$paid,'',0,0,$multiid)";
  
   if(!mysql_query($query)) {
      puterror("Ошибка 1 при добавлении проекта.");
   }

   $id = mysql_insert_id();
   define('PROJECT_ID',mysql_query("SELECT LAST_INSERT_ID()"));
   mysql_query("COMMIT");
   mysql_query("UNLOCK TABLES");
   writelog("Добавлен проект №".$id." (".$info.").");
  }
  else
  {
   $info = $_POST["info"];
   $id = $_POST["id"];
   $query = "UPDATE projects SET info = '".$info."'
           WHERE id=".$id;

   if(!mysql_query($query)) {
      puterror("Ошибка 1 при изменении проекта.");
   }
   writelog("Проект изменен №".$id." (".$info.").");
  }
  
  require_once ('lib/transliteration.inc');
 
  $res10=mysql_query("SELECT * FROM poptions WHERE proarrid='".$paid."' AND multiid='".$multiid."' ORDER BY id");
  while($mb10 = mysql_fetch_array($res10))
  {
    $optionsid = $mb10['id'];
    $contentdata = htmlspecialchars($_POST["content".$optionsid], ENT_QUOTES); 

    if ($mode=='add')
    {

     if($_FILES["file".$optionsid]["name"]!=""){ 
      $filedata = $_FILES["file".$optionsid]["name"]; 
      $realfiledata = transliteration_clean_filename($_FILES["file".$optionsid]["name"],"ru");
      $filesize = $_FILES["file".$optionsid]["size"]; 
     }
     else
     {
      $filedata = ""; 
      $realfiledata = "";
      $filesize = 0; 
     }
    
     mysql_query("LOCK TABLES projectdata WRITE");
     mysql_query("SET AUTOCOMMIT = 0");
     $secure = md5($id.$realfiledata);
    
     $query2 = "INSERT INTO projectdata VALUES (0, 
     $id, 
     $optionsid, 
     '$contentdata', 
     '$filedata',
     '$realfiledata',
     $filesize, 
     $paid,
     '$secure')";
    
     if(!mysql_query($query2))
     {
      puterror("Ошибка 2 при добавлении проекта.\n".$query2);
      break; 
     }
     mysql_query("COMMIT");
     mysql_query("UNLOCK TABLES");
    
    }

    $res3=mysql_query("SELECT * FROM projectdata WHERE projectid='".$id."' AND optionsid=".$optionsid);
    if(!$res3) puterror("Ошибка 3 при изменении проекта.");
    $param = mysql_fetch_array($res3);

    if($_FILES["file".$optionsid]["name"]!="")
    { 
     $filedata = $_FILES["file".$optionsid]["name"]; 
     $realfiledata = transliteration_clean_filename($_FILES["file".$optionsid]["name"],"ru");
     $filesize = $_FILES["file".$optionsid]["size"]; 

     // Удалим файл - есди пользователь заменил его
     if (!empty($param['filename'])) { 
      unlink($upload_dir.$id.$param['realfilename']);
     }
     
    }
    else
    {
     // Удалим файл - есди пользователь хочет удалить
     if (!empty($_POST['cb'.$optionsid])) { 
      unlink($upload_dir.$id.$param['realfilename']);
     }
     
     $filedata = ""; 
     $realfiledata = "";
     $filesize = 0; 
    }

    $secure = md5($id.$realfiledata);

    if (!empty($_POST['vb'.$optionsid])) {
     $contentdata = "";
    } 
    
    if (!empty($param['filename']) && !empty($_FILES["file".$optionsid]["name"])) { 
     $query2 = "UPDATE projectdata SET content = '".$contentdata."'
            , filename = '".$filedata."' 
            , realfilename = '".$realfiledata."' 
            , filesize = ".$filesize." 
            , secure = '".$secure."' 
           WHERE projectid=".$id." and optionsid=".$optionsid;
     } else {
    if (empty($param['filename']) && !empty($_FILES["file".$optionsid]["name"])) { 
     $query2 = "UPDATE projectdata SET content = '".$contentdata."'
            , filename = '".$filedata."' 
            , realfilename = '".$realfiledata."' 
            , filesize = ".$filesize." 
            , secure = '".$secure."' 
           WHERE projectid=".$id." and optionsid=".$optionsid;
     } else {
      if (!empty($_POST['cb'.$optionsid])) { 
      $query2 = "UPDATE projectdata SET content = '".$contentdata."'
            , filename = '' 
            , realfilename = '' 
            , filesize = 0 
            , secure = '' 
           WHERE projectid=".$id." and optionsid=".$optionsid;
      } else {
     $query2 = "UPDATE projectdata SET content = '".$contentdata."'
           WHERE projectid=".$id." and optionsid=".$optionsid;
     }
     }
    }
    if(!mysql_query($query2))
    {
      puterror("Ошибка 2 при изменении проекта.\n".$query2);
      break; 
    }


    if($_FILES["file".$optionsid]["name"]!=""){ 
          $origfilename = $_FILES["file".$optionsid]["name"]; 
          $filename = explode(".", $_FILES["file".$optionsid]["name"]); 
          $filenameext = $filename[count($filename)-1]; 
          unset($filename[count($filename)-1]); 
          $filename = implode(".", $filename); 
          $filename = substr($filename, 0, 15).".".$filenameext; 
          $file_ext_allow = FALSE; 

          if ($mb10['filetype']=="file") {
           for($x=0;$x<count($file_types_array);$x++){ 
             if(strtolower($filenameext)==$file_types_array[$x]){ 
              $file_ext_allow = TRUE; 
             } 
            }
           }
           
          if ($mb10['filetype']=="foto") {
           for($x=0;$x<count($photo_file_types_array);$x++){ 
             if(strtolower($filenameext)==$photo_file_types_array[$x]){ 
              $file_ext_allow = TRUE; 
             } 
            }
           }

          if($file_ext_allow){ 
            if($_FILES["file".$optionsid]["size"]<$max_file_size){ 
              if(move_uploaded_file($_FILES["file".$optionsid]["tmp_name"], $upload_dir.$id.$realfiledata)){ 
                echo("Файл успешно загружен. - <a href='".$upload_dir.$id.$realfiledata."' target='_blank'>".$filedata."</a><br />"); 
              }else{ 
                $error = $error.$origfilename." не был загружен в каталог сервера."; 
              } 
            }else{ 
              $error=$error.$origfilename." превышает установленный размер."; 
            } 
          }else{ 
            $error=$error.$origfilename." не поддерживается."; 
          } 
    } 

   
  }


  // Отправим сообщение экспертам, которые могут оставлять замечания
  $res3=mysql_query("SELECT * FROM projectarray WHERE id='".$paid."'");
  if(!$res3) puterror("Ошибка 3 при изменении данных.");
  $projectarray = mysql_fetch_array($res3);
  if ($projectarray['addcomment']==1 and $projectarray['expertmailer']==1) 
  {

  $gst4 = mysql_query("SELECT * FROM proexperts WHERE proarrid='".$paid."' ORDER BY id");
  if (!$gst4) puterror("Ошибка при обращении к базе данных");
  while($member4 = mysql_fetch_array($gst4))
  {
    // Обновим статусы просмотров проектов экспертом
    // Отправим уведомление экспертам по почте
    
     $res5=mysql_query("SELECT * FROM users WHERE id='".$member4['expertid']."'");
     if(!$res5) puterror("Ошибка 3 при изменении данных.");
     $expert = mysql_fetch_array($res5);

     $toemail = $expert['email'];

     if ($mode=='add')
     {
     $title = "Создан новый проект";
     $body = "Здравствуйте ".$expert['userfio']."!\n\nВ экспертной системе ".$site." появился новый проект.\n
     Создатель нового проекта - ".USER_FIO."\n
     Наименование нового проекта - ".$info."\n
     Требуется проверка данного проекта. (Главное меню - Проекты участников)\n
     С уважением, Экспертная система (".$site.")";
     }
     else
     {
     $title = "Изменение проекта";
     $body = "Здравствуйте ".$expert['userfio']."!\n\nВ экспертной системе ".$site." изменился проект.\n
     Создатель проекта - ".USER_FIO."\n
     Наименование проекта - ".$info."\n
     Требуется проверка данного проекта. (Главное меню - Проекты участников)\n
     С уважением, Экспертная система (".$site.")";
     }
     require_once('lib/unicode.inc');

     $mimeheaders = array();
     $mimeheaders[] = 'Content-type: '. mime_header_encode('text/plain; charset=UTF-8; format=flowed; delsp=yes');
     $mimeheaders[] = 'Content-Transfer-Encoding: '. mime_header_encode('8Bit');
     $mimeheaders[] = 'X-Mailer: '. mime_header_encode('ExpertSystem');
     $mimeheaders[] = 'From: '. mime_header_encode(USER_FIO.' <'.USER_EMAIL.'>');

     if (!empty($toemail))
     {
     if (!mail(
       $toemail,
       mime_header_encode($title),
       str_replace("\r", '', $body),
       join("\n", $mimeheaders)
      )) {
       puterror("Ошибка при отправке сообщения.");
      } 
     } 
   } 
  }  

      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=projects'>\n";
      print "</HEAD></HTML>\n";
      exit();
  
}

if (empty($action)) 
{

  if (defined("IN_ADMIN")) 
   $gst3 = mysql_query("SELECT * FROM projects WHERE id='".$_GET['id']."'");
  else
  if (defined("IN_USER")) 
   $gst3 = mysql_query("SELECT * FROM projects WHERE id='".$_GET['id']."' AND userid='".USER_ID."'");
   
  if (!$gst3) puterror("Ошибка при обращении к базе данных");
  $project = mysql_fetch_array($gst3);

?>

<script type="text/javascript"> 

 $(document).ready(function() {
		$('.fancybox').fancybox();
 });
 
 $(function() {
    $( "#accordion" ).accordion({
      heightStyle: "content",
      active: false,
      collapsible: true
    });
 });

 function startStatus(total) {
  var i=1;
	for ( ; i < total+1; i++ ) {
    $("#form_upload"+i).fadeOut(); 
  }
  $("#progress_bar").fadeIn(); 
 }  

 $(document).ready(function(){

    var i = $('input').size() + 1;

    $('#add').click(function() {
        $('<div><input type="file" class="field" name="dynamic[]" value="' + i + '" /></div>').fadeIn('slow').appendTo('.inputfiles');
        i++;
    });

    $('#remove').click(function() {
    if(i > 1) {
        $('.field:last').remove();
        i--;
    }
    });

 });
</script> 

<style type="text/css"> 
#progress_bar{ 
    position:relative; 
    width:300px; 
    display: none; 
    margin:15px 0 0 0px; 
} 
#bg{ 
    width:300px; 
    border:1px solid black; 
    height:10px; 
    display:block; 
    background-image:url(/img/progress.gif); 
    background-repeat: repeat-x; 
} 
.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
}
</style>  		

<?
   if ($mode=='add')
   {
    $proarrid  = $_GET["paid"];
    $res1=mysql_query("SELECT count(*) FROM poptions WHERE proarrid='".$proarrid."' AND multiid='".$multiid."' ORDER BY id");
    $total = mysql_fetch_array($res1);
   }
   else
   {
    $multiid = $project['multiid'];
    $res1=mysql_query("SELECT count(*) FROM poptions WHERE proarrid='".$project['proarrid']."' AND multiid='".$multiid."' ORDER BY id");
    $total = mysql_fetch_array($res1);
   }

?>

<form action="editproject" method="post" enctype="multipart/form-data" onsubmit="startStatus(<? echo $total['count(*)']; ?>);">
<input type=hidden name=multi value='<? echo $multiid; ?>'>
<input type=hidden name=mode value='<? echo $mode; ?>'>
<input type=hidden name=action value=post>
<input type=hidden name=id value='<? echo $project['id']; ?>'>
<?
 if ($mode=='add')
  echo "<input type=hidden name=paid value='".$proarrid."'>";
 else 
  echo "<input type=hidden name=paid value='".$project['proarrid']."'>";
?>

<p align='center'>
<table border="0" cellpadding=2 cellspacing=2 bordercolorlight=gray bordercolordark=white>
    <tr>
        <td><p class=ptd><b><em class=em>Наименование проекта *:</em></b></td>
    </tr><tr>
        <td><input type=text name='info' value='<? echo $project['info']; ?>' size=100></td>
    </tr>

<?    
   
   if ($mode=='add')
    $res3=mysql_query("SELECT * FROM poptions WHERE proarrid='".$proarrid."' AND multiid='".$multiid."' ORDER BY id");
   else
    $res3=mysql_query("SELECT * FROM poptions WHERE proarrid='".$project['proarrid']."' AND multiid='".$multiid."' ORDER BY id");
   
   $cnt=0;

    while($param = mysql_fetch_array($res3))
   { 

    if ($mode!='add')
    {
     $res4=mysql_query("SELECT * FROM projectdata WHERE projectid='".$project['id']."' AND optionsid='".$param['id']."'");
     if (!$res4) puterror("Ошибка при обращении к базе данных");
     $param4 = mysql_fetch_array($res4);
    }
    
    if ($param['content']=="yes" and $param['youtube']=="no" and $param['link']=="no") 
    {
     echo "<tr><td>";
     if ($param['name'] != "[empty]")
      echo "<p class=ptd><b>".$param['name'].":</b></p>";
     else
      echo "<p class=ptd><b>Пояснение или комментарий:</b></p>";
     if (!empty($param['doptext']))
      echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";

     echo "</td></tr>";

     if ($param['typetext']=="textarea") {
      echo "<tr><td><textarea name='content".$param['id']."' style='width:100%' rows='20'>".$param4['content']."</textarea></td></tr>";
     } 
     elseif ($param['typetext']=="text") {
      echo "<tr><td><input type=text name='content".$param['id']."' size=100 value='".$param4['content']."'></td></tr>";
     }
    }
    else
    if ($param['youtube']=="yes") 
    {
     if (!empty($param4['content']))
       echo "<tr><td>
       <input type='hidden' name='content".$param['id']."' size='100' value='".$param4['content']."'>
       <iframe width='320' height='180' src='http://www.youtube.com/embed/".$param4['content']."?feature=player_detailpage' frameborder='0' allowfullscreen></iframe>
       &nbsp;<label><input type='checkbox' name='vb".$param['id']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить видеоролик</font></label></p>
       </td></tr>";
     else
     {
      // if (!$lastvideo) {
        echo "<tr><td><p class=ptd><b>".$param['name'].":</b></p>";
        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";
        echo "</td></tr>";  
        echo "<tr><td><input type='text' name='content".$param['id']."' size='100' value='".$param4['content']."'></td></tr>";
      //  $lastvideo = true;
      // }
     }
    }  
    else
    if ($param['link']=="yes") 
    {
     if (!empty($param4['content']))
     {
       echo "<tr><td>
       <input type='hidden' name='content".$param['id']."' size='100' value='".$param4['content']."'>
       ".$param['name'];
       if (isUrl($param4['content'])) 
        echo " <a href='".$param4['content']."' target='_blank'>".$param4['content']."</a>";
       else
        echo " <a href='http://".$param4['content']."' target='_blank'>".$param4['content']."</a>";
       echo "&nbsp;<label><input type='checkbox' name='vb".$param['id']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить ссылку</font></label></p>
       </td></tr>";
     }
     else
     {
       echo "<tr><td><p class=ptd><b>".$param['name'].":</b></p>";
       if (!empty($param['doptext']))
         echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";
       echo "</td></tr>";  
       echo "<tr><td><input type='text' name='content".$param['id']."' size='100' value='".$param4['content']."'></td></tr>";
     }
    }  
    else
    if ($param['files']=="yes") {
     $cnt=$cnt+1;
     
     if ($param['fileformat']=="ajax") {
      echo "<tr><td>";

      if (!empty($param4['filename'])) { 
       $kb = round($param4['filesize']/1024,2);
       echo "<p>
       <a target='_blank' title='Просмотр через Google Docs' href='http://docs.google.com/viewer?url=http%3A%2F%2Fexpert03.ru%2Ffile.php%3Fid%3D".$param4['secure']."'>Просмотр ".$param4['filename']."</a>
       <a class='menu' href='file.php?id=".$param4['secure']."'
       target='_blank' title='Загрузить прикрепленный файл ".$param4['filename']." (".$kb." кб)'><img src='img/f32.jpg' alt='Загрузить ".$param4['filename']." (".$kb."кб)'></a>&nbsp;<label><input type='checkbox' name='cb".$param['id']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить файл</font></label></p>";
      }
      
      if ($param['filetype']=="file") {  
        if (!$lastfiles) {

        echo"<p class=ptd><b>Прикрепить новые файлы (не более 5) к разделу '".$param['name']."'.</b> Размер нового файла не должен превышать 1Мб.
        Допустимые расширения: ";
        for($x=0;$x<count($file_types_array);$x++) echo $file_types_array[$x].",";
        echo "</p>";

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";

        echo"<input type='file' name='file".$param['id']."' id='file".$param['id']."'/>";
        echo"</td></tr>";
        echo"<tr><td>";
        echo"<p><div class='inputfiles'></div></p>";
        echo"<p align='left'><a href='#' id='add'>Добавить</a> | <a href='#' id='remove'>Удалить</a></p>"; 
        echo"</td></tr>";
        $lastfiles=true;
        }
      }
      else
      if ($param['filetype']=="foto") {
        echo "<p class=ptd><b>Прикрепить новые фотографии (не более 5) к разделу '".$param['name']."'.</b> Размер нового файла не должен превышать 1Мб.
         Допустипые расширения: ";
         for($x=0;$x<count($photo_file_types_array);$x++) echo $photo_file_types_array[$x].",";
        echo "</p>";

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";

        echo"<input type='file' name='file".$param['id']."' id='file".$param['id']."'/>";
        echo"</td></tr>";
      } 


     }
     else 
     if ($param['fileformat']=="simple") {
      echo "<tr><td>";
   
      if (!empty($param4['filename'])) { 

       if ($param['filetype']=="file") { 

        $kb = round($param4['filesize']/1024,2);
        echo "<p>".$param['name'].":
        <a target='_blank' title='Просмотр через сервис Google Docs' href='http://docs.google.com/viewer?url=http%3A%2F%2Fexpert03.ru%2Ffile.php%3Fid%3D".$param4['secure']."'>Просмотр ".$param4['filename']."</a>
        <a class='menu' href='file.php?id=".$param4['secure']."'
        target='_blank' title='Загрузить прикрепленный файл ".$param4['filename']." (".$kb." кб)'>
        <img src='img/f32.jpg' alt='Загрузить ".$param4['filename']." (".$kb." кб)'></a>&nbsp;<label><input type='checkbox' name='cb".$param['id']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить файл</font></label></p>";

       } 

      else
       if ($param['filetype']=="foto") {
     

        list($width, $height, $type, $attr)= getimagesize($upload_dir.$param4['projectid'].$param4['realfilename']);


         if ($width>$resizing) {
          $resize = round(($resizing*100)/$width);
          $new_width = round((($resize/100)*$width));
          $new_height = round((($resize/100)*$height));
         } else
         {
          $new_width = $width;
          $new_height = $height;
         }


    ?>
    
<script type="text/javascript">
		$(document).ready(function() {

      $("#fancybox-manual-<?php echo $param4['id']; ?>").click(function() {
				$.fancybox.open([
        <?php
      echo "{ href : '".$upload_dir.$param4['projectid'].$param4['realfilename']."' }";
        ?>
          
				], {
					helpers : {
						thumbs : {
							width: 75,
							height: 50
						}
					}
				});
			});      
      
		});
</script>
<?
      echo "<a title='".$param4['filename']."' id='fancybox-manual-".$param4['id']."' href='javascript:;'><img src='file.php?id=".$param4['secure']."' width='".$new_width."' height='".$new_height."'></a>&nbsp;<label>
      <input type='checkbox' name='cb".$param['id']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить файл</font></label></p>";
     }

//      echo"<p class=ptd><b>Прикрепить новый файл к разделу '".$param['name']."'. Существующий файл будет удален.</b> Размер нового файла не должен превышать 1Мб.</p>";
//      } else {
//       echo"<p class=ptd><b>Прикрепить новый файл к разделу '".$param['name']."'.</b> Размер нового файла не должен превышать 1Мб.</p>";
//      }
//      echo"<input type='file' name='file".$param['id']."' id='file".$param['id']."'/>";

      echo"</td></tr>";
     }
     else
     {
      if ($param['filetype']=="file") { 
      // if (!$lastfile) {
        echo "<tr><td>";
        echo"<p class=ptd>Новый файл <b>".$param['name']."</b> Размер нового файла не должен превышать 1Мб.
        Допустимые расширения: ";
        for($x=0;$x<count($file_types_array);$x++) echo $file_types_array[$x].",";
        echo "</p>";

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";

        echo"<input type='file' name='file".$param['id']."' id='file".$param['id']."'/>";
        echo"</td></tr>";
       // $lastfile = false;
       //}
      }
      else
      if ($param['filetype']=="foto") {
       if (!$lastfoto) {
        echo "<tr><td>";
        echo "<p class=ptd>Новая фотография (картинка) <b>".$param['name']."</b> Размер нового файла не должен превышать 1Мб.
         Допустипые расширения: ";
         for($x=0;$x<count($photo_file_types_array);$x++) echo $photo_file_types_array[$x].",";
        echo "</p>";

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";

        echo"<input type='file' name='file".$param['id']."' id='file".$param['id']."'/>";
        echo"</td></tr>";
        $lastfoto = true;
       }
      } 
     
    }
    }
   } 
  }


if (empty($nomenu)) 
{
?> 

    <tr align="center">
        <td colspan="3">
          <div id="progress_bar"> 
            <div id="bg"></div> 
          </div></p> 
        </td>
    </tr>           
    <tr align="center">
        <td colspan="3">
            <input type="submit" value="<? if ($mode=='add') echo "Добавить"; else echo "Изменить"?>">&nbsp;
            <input type="button" name="close" value="Назад" onclick="history.back()"> 
        </td>
    </tr>           

    <?
}
  

  $tot = mysql_query("SELECT count(*) FROM comments WHERE projectid='".$project['id']."'");
  if (!$tot) puterror("Ошибка при обращении к базе данных");
  $total = mysql_fetch_array($tot);
  $count = $total['count(*)'];
  if ($count>0) 
  {

  echo "<tr class='tableheader'><td><p class=help>Замечания и комментарии к проекту</p></td></tr>";   
  
  $res3=mysql_query("SELECT * FROM comments WHERE projectid='".$project['id']."' ORDER BY cdate DESC");
  while($param3 = mysql_fetch_array($res3))
   { 
      $res4=mysql_query("SELECT userfio FROM users WHERE id='".$param3['expertid']."'");
      $param4 = mysql_fetch_array($res4);
      
      echo "<tr><td witdh='300'><hr><p><b>Эксперт ".$param4['userfio']." от ".data_convert ($param3['cdate'], 1, 1, 0)." оставил комментарий (замечание):</b></p>
      <p>".$param3['content']."</p></td></tr>";   
      
      // Установим статус- комментарий прочтен
      if ($param3['readed']==0) {
        $query = "UPDATE projects SET readed='1' WHERE id=".$param3["id"];
        mysql_query($query);
      }
   } 
  }
    ?>
    
</table></div></p>
</form>

<?php
if (!empty($nomenu)) 
 echo "</body></html>";
else
 include "bottomadmin.php";

}
}
else die;
?>

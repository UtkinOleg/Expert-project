<?php
  if(defined("IN_USER")) 
  {

include "config.php";
include "func.php";
$error = "";

// Возвращаем значение переменной action
$action = $_POST["action"];
$nomenu = $_GET["nomenu"];
$paid = $_GET["paid"];

if (empty($action)) 
 $mode = $_GET["mode"];
else
 $mode = $_POST["mode"];

 $pa = mysqli_query($mysqli,"SELECT * FROM projectarray WHERE id='".$paid."' LIMIT 1");
 $paa = mysqli_fetch_array($pa);
 $pan = $paa['name'];
 $pap = $paa['photoname'];
 $df = $paa['defaultshablon'];
 $payment = $paa['payment'];
 $paysumma = $paa['paysumma'];

if (!empty($nomenu)) 
 $title=$titlepage="";
else
{

 
 if (empty($df))
  $df = 'Раздел по умолчанию';
  
      if (!empty($pap))
       {      
       if (stristr($pap,'http') === FALSE)
           $pic = "<div class='menu_glide_img'><img class='leftimg' style='margin: -5px 5px 5px 0;' src='".$pa_upload_dir.$paid.$pap."' height='32'></div> "; 
          else
           $pic = "<div class='menu_glide_img'><img class='leftimg' style='margin: -5px 5px 5px 0;' src='".$pap."' height='32'></div> "; 
       }

 if ($mode=='add')
  $title=$titlepage = $pic."Новый проект - ".$pan;
 else
  $title=$titlepage = $pic."Редактирование проекта";
}

if (empty($nomenu)) 
 include "topadmin2.php";
else 
 include "topadmin5.php";
 
if (!empty($action)) 
{

  $paid = $_POST["paid"];
  $userid = USER_ID;
  $error = "";
  $info = $_POST["info"];
  if (strlen(trim($info))==0) 
  {
    $action = ""; 
    $error = "<p>Вы не указали наименование проекта.</p>";
  }

  $res10=mysqli_query($mysqli,"SELECT * FROM poptions WHERE proarrid='".$paid."' ORDER BY id");
  while($mb10 = mysqli_fetch_array($res10))
  {
    $optionsid = $mb10['id'];
    
    if ($mb10['fileformat']=="ajax")
    {
     
     for ($i=$_POST["multifilemin".$optionsid]; $i<=$_POST["multifilemax".$optionsid]; $i++) {
      
      $filedata = $_FILES["multifile".$optionsid."-".$i]["name"]; 
      $filesize = $_FILES["multifile".$optionsid."-".$i]["size"]; 
      
      if(!empty($filedata))
      { 
          $origfilename = $filedata; 
          $filename = explode(".", $filedata); 
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

          if ($file_ext_allow) { 

            if ($mb10['filetype']=="file") { 
             if($filesize>$max_file_size){ 
              $action = ""; 
              $error=$error."<p>Файл <b>".$origfilename."</b> превышает размер ".$max_file_size_str." Мб.</p>"; 
             } 
            }
            else
            if ($mb10['filetype']=="foto") {
             if($filesize>$photo_max_file_size){ 
              $action = ""; 
              $error=$error."<p>Файл <b>".$origfilename."</b> превышает размер ".$photo_max_file_size_str." Мб.</p>"; 
             } 
            }

          } 
          else { 
            $action = ""; 
            $error=$error."<p>Расширение ".strtolower($filenameext)." файла <b>".$origfilename."</b> не поддерживается.</p>"; 
          } 
           
      }
     }
     
    
    }
    else
    {
     $filedata = $_FILES["file".$optionsid]["name"]; 
     $filesize = $_FILES["file".$optionsid]["size"]; 
    
     if($filedata!="")
     { 
          $origfilename = $filedata; 
          $filename = explode(".", $filedata); 
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

          if ($file_ext_allow) { 

            if ($mb10['filetype']=="file") { 
             if($filesize>$max_file_size){ 
              $action = ""; 
              $error=$error."<p>Файл <b>".$origfilename."</b> превышает размер ".$max_file_size_str." Мб.</p>"; 
             } 
            }
            else
            if ($mb10['filetype']=="foto") {
             if($filesize>$photo_max_file_size){ 
              $action = ""; 
              $error=$error."<p>Файл <b>".$origfilename."</b> превышает размер ".$photo_max_file_size_str." Мб.</p>"; 
             } 
            }

          } 
          else { 
            $action = ""; 
            $error=$error."<p>Расширение ".strtolower($filenameext)." файла <b>".$origfilename."</b> не поддерживается.</p>"; 
          } 
     }
    } 

  }

  if (empty($action)) 
  {
    $id = $_POST["id"];
    ?>
<script>
$(function() {
    $( "#error-message" ).dialog({
      width: 500,
      modal: true,
      buttons: {
        Ok: function() {
          $( this ).dialog( "close" );
        }
      },
      close: function() {
          <? if ($mode=='add') { ?> 
            document.location.href = "editproject&mode=add&paid=<? echo $paid ?>";
          <?} else {?>
            document.location.href = "editproject&id=<? echo $id ?>";
          <? } ?>
      }      
    });
  });
  </script>
  
    <div id="error-message" title="Ошибки">
     <? echo $error ?>
   </div>
   
    <?
   
/*    print "<HTML><HEAD>\n";
    if ($mode=='add')
     print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=editproject&mode=add&paid=".$paid."'>\n";
    else
     print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=editproject&id=".$_POST["id"]."'>\n";
    print "</HEAD></HTML>\n";
*/    
    exit();
  }
  mysqli_query($mysqli,"START TRANSACTION;");
  if ($mode=='add')
  {
   $userid = USER_ID;
   $info = $_POST["info"];
   $query = "INSERT INTO projects VALUES (0,$userid,'$info',NOW(),'created',0,$paid,'',0,0,0)";
  
   if(!mysqli_query($mysqli,$query)) {
      puterror("Ошибка 1 при добавлении проекта.");
   }

   $id = mysqli_insert_id($mysqli);
   define('PROJECT_ID',mysqli_query($mysqli,"SELECT LAST_INSERT_ID()"));
   writelog("Добавлен проект №".$id." (".$info.").");
  }
  else
  {
   $info = $_POST["info"];
   $id = $_POST["id"];

   
   $query = "UPDATE projects SET info = '".$info."'
           WHERE id=".$id;

   if(!mysqli_query($mysqli,$query)) {
      puterror("Ошибка 1 при изменении проекта.");
   }

   writelog("Проект изменен №".$id." (".$info.").");
  }
  mysqli_query($mysqli,"COMMIT");
  
  require_once ('lib/transliteration.inc');
 
  $res10 = mysqli_query($mysqli,"SELECT * FROM poptions WHERE proarrid='".$paid."' ORDER BY id");
  mysqli_query($mysqli,"START TRANSACTION;");
  
  while($mb10 = mysqli_fetch_array($res10))
  {
    $optionsid = $mb10['id'];
    $contentdata = htmlspecialchars($_POST["content".$optionsid], ENT_QUOTES); 

    if ($mode=='add')
    {

    if ($mb10['fileformat']=="ajax")
    {
     for ($i=1; $i<=5; $i++) {
      
      $filedata = $_FILES["multifile".$optionsid."-".$i]["name"]; 
      $filesize = $_FILES["multifile".$optionsid."-".$i]["size"]; 
      $realfiledata = transliteration_clean_filename($filedata,"ru");
      if (empty($filesize)) $filesize=0;
      
      $secure = md5($id.$realfiledata);
      $query2 = "INSERT INTO multiprojectdata VALUES (0, 
      $id,
      $optionsid, 
      $i, 
      '$contentdata', 
      '$filedata',
      '$realfiledata',
      $filesize, 
      $paid,
      '$secure')";
      if(!mysqli_query($mysqli,$query2))
       puterror("Ошибка 2 при добавлении проекта.\n".$query2);

     }
      mysqli_query($mysqli,"COMMIT");
    } 
    else
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
     if(!mysqli_query($mysqli,$query2))
     {
      puterror("Ошибка 2 при добавлении проекта.\n".$query2);
     }
     
     
    }
    
    }


    if ($mb10['fileformat']=="ajax")
    {

     for ($i=1; $i<=5; $i++) {
      
      // Удалим файл - если пользователь хочет удалить
      if (!empty($_POST['cb'.$optionsid.'-'.$i]))
      { 
       $dres3=mysqli_query($mysqli,"SELECT * FROM multiprojectdata WHERE paramid='".$i."' AND projectid='".$id."' AND optionsid=".$optionsid);
       if(!$dres3) puterror("Ошибка 3 при изменении проекта.");
       $dparam = mysqli_fetch_array($dres3);
      
       unlink($upload_dir.$id.$dparam['realfilename']);

       $query2 = "UPDATE multiprojectdata SET content = ''
            , filename = '' 
            , realfilename = '' 
            , filesize = 0 
            , secure = '' 
           WHERE paramid=".$i." and projectid=".$id." and optionsid=".$optionsid;
       if(!mysqli_query($mysqli,$query2))
        puterror("Ошибка 2 при изменении проекта.\n".$query2);
 
      }
     }
     
      // Проверим остальные элементы +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

     for ($i=$_POST["multifilemin".$optionsid]; $i<=$_POST["multifilemax".$optionsid]; $i++) {
      
      $filedata = $_FILES["multifile".$optionsid."-".$i]["name"]; 
      $filesize = $_FILES["multifile".$optionsid."-".$i]["size"]; 
      $realfiledata = transliteration_clean_filename($filedata,"ru");
      $tmpfiledata = $_FILES["multifile".$optionsid."-".$i]["tmp_name"]; 
      if (empty($filesize)) $filesize=0;
      
      if ($filedata!="")
      {

    $res3=mysqli_query($mysqli,"SELECT * FROM multiprojectdata WHERE paramid='".$i."' AND projectid='".$id."' AND optionsid=".$optionsid);
    if(!$res3) puterror("Ошибка 3 при изменении проекта.");
    $param = mysqli_fetch_array($res3);

    $secure = md5($id.$realfiledata);

    if (!empty($_POST['vb'.$optionsid])) {
     $contentdata = "";
    } 
    
    if (!empty($param['filename']) && !empty($filedata)) { 
     $query2 = "UPDATE multiprojectdata SET content = '".$contentdata."'
            , filename = '".$filedata."' 
            , realfilename = '".$realfiledata."' 
            , filesize = ".$filesize." 
            , secure = '".$secure."' 
           WHERE paramid=".$i." and projectid=".$id." and optionsid=".$optionsid;
     } else {
    if (empty($param['filename']) && !empty($filedata)) { 
     $query2 = "UPDATE multiprojectdata SET content = '".$contentdata."'
            , filename = '".$filedata."' 
            , realfilename = '".$realfiledata."' 
            , filesize = ".$filesize." 
            , secure = '".$secure."' 
           WHERE paramid=".$i." and projectid=".$id." and optionsid=".$optionsid;
     } 
    }
    
    if(!mysqli_query($mysqli,$query2))
      puterror("Ошибка 2 при изменении проекта.\n".$query2);

    // Загрузка файла
    if ($filedata!="")
    {
    $origfilename = $filedata; 
    $filename = explode(".", $filedata); 
    $filenameext = $filename[count($filename)-1]; 
    unset($filename[count($filename)-1]); 
    $filename = implode(".", $filename); 
    $filename = substr($filename, 0, 15).".".$filenameext; 
    if(!move_uploaded_file($tmpfiledata, $upload_dir.$id.$realfiledata))
     puterror("Ошибка при загрузке файла на сервер.");
    }
    
    }

     }
    }
    else
    {  

    $res3=mysqli_query($mysqli,"SELECT * FROM projectdata WHERE projectid='".$id."' AND optionsid=".$optionsid);
    if(!$res3) puterror("Ошибка 3 при изменении проекта.");
    $param = mysqli_fetch_array($res3);

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
    
    if(!mysqli_query($mysqli,$query2))
      puterror("Ошибка 2 при изменении проекта.\n".$query2);

    // Загрузка файла
    if($_FILES["file".$optionsid]["name"]!="")
    { 
          $origfilename = $_FILES["file".$optionsid]["name"]; 
          $filename = explode(".", $_FILES["file".$optionsid]["name"]); 
          $filenameext = $filename[count($filename)-1]; 
          unset($filename[count($filename)-1]); 
          $filename = implode(".", $filename); 
          $filename = substr($filename, 0, 15).".".$filenameext; 
          if(!move_uploaded_file($_FILES["file".$optionsid]["tmp_name"], $upload_dir.$id.$realfiledata))
           puterror("Ошибка при загрузке файла на сервер.");
     } 

    }
  }
  mysqli_query($mysqli,"COMMIT");


  // Отправим сообщение экспертам, которые могут оставлять замечания
  $res3=mysqli_query($mysqli,"SELECT * FROM projectarray WHERE id='".$paid."' LIMIT 1");
  if(!$res3) puterror("Ошибка 3 при изменении данных.");
  $projectarray = mysqli_fetch_array($res3);
  if ($projectarray['addcomment']==1 and $projectarray['expertmailer']==1) 
  {

  $gst4 = mysqli_query($mysqli,"SELECT * FROM proexperts WHERE proarrid='".$paid."' ORDER BY id");
  if (!$gst4) puterror("Ошибка при обращении к базе данных");
  while($member4 = mysqli_fetch_array($gst4))
  {
    // Обновим статусы просмотров проектов экспертом
    // Отправим уведомление экспертам по почте
    
     $res5=mysqli_query($mysqli,"SELECT * FROM users WHERE id='".$member4['expertid']."' LIMIT 1");
     if(!$res5) puterror("Ошибка 3 при изменении данных.");
     $expert = mysqli_fetch_array($res5);

     $toemail = $expert['email'];

     if ($mode=='add')
     {
      $title = 'Создан новый проект '.$info.' в системе expert03.ru';
      $body = msghead($expert['userfio'], $site);
      $body.='<p>Создан новый проект <strong>'.$info.'</strong> (автор '.USER_FIO.')</p>';
      $body.='<p>Требуется Ваша оценка проекта. Вы можете оставить также комментарий к проекту <strong>'.$info.'</strong> в личном кабинете (экспертиза - проекты). Автор проекта увидит Ваш комментарий и получит уведомление.</p>';
      $body .= msgtail($site);
     }
     else
     {
      $title = 'Изменился проект '.$info.' в системе expert03.ru';
      $body = msghead($expert['userfio'], $site);
      $body.='<p>Изменился проект <strong>'.$info.'</strong> (автор '.USER_FIO.')</p>';
      $body.='<p>Требуется Ваша оценка изменений в проекте. Вы можете также оставить комментарий к проекту <strong>'.$info.'</strong> в личном кабинете (экспертиза - проекты). Автор проекта увидит Ваш комментарий и получит уведомление.</p>';
      $body .= msgtail($site);
     }
     require_once('lib/unicode.inc');

     $mimeheaders = array();
     $mimeheaders[] = 'Content-type: '. mime_header_encode('text/html; charset=UTF-8; format=flowed; delsp=yes');
     $mimeheaders[] = 'Content-Transfer-Encoding: '. mime_header_encode('8Bit');
     $mimeheaders[] = 'X-Mailer: '. mime_header_encode('ExpertSystem');
     $mimeheaders[] = 'From: '. mime_header_encode('info@expert03.ru <info@expert03.ru>');

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
  
  if (empty($_GET['mode']) and empty($_GET['id']))
   die;

  if ($mode=='add')
  {
  // Проверим на оплату
  if ($payment>0) 
  {
      // Проверим на оплату
      $sum1 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM money WHERE proarrid='".$paid."'");
      if (!$sum1) puterror("Ошибка при обращении к базе данных");
      $usumma = 0; 
      while ($s1 = mysqli_fetch_array($sum1))
      {
       $order1 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM orders WHERE id='".$s1['orderid']."' LIMIT 1");
       if (!$order1) puterror("Ошибка при обращении к базе данных");
       $o1 = mysqli_fetch_array($order1);
       if ($o1['userid']==USER_ID) 
        $usumma += $s1['summa'];
      }
      if ($usumma < $paysumma)
      {
       echo '<script language="javascript">';
       if ($usumma==0) 
        echo 'alert("Размещение проекта не оплачено!");';
       else
        echo 'alert("Размещение проекта оплачено не полностью!");';
       echo '</script>';
       die;
      } 
  }

  // Проверим на дату 
  $date3 = date("d.m.Y");
  preg_match_all("/(\d{1,2})\.(\d{1,2})\.(\d{4})/",$date3,$ik);
  $day=$ik[1][0];
  $month=$ik[2][0];
  $year=$ik[3][0];
  $timestamp3 = (mktime(0, 0, 0, $month, $day, $year));

  $date1 = $paa['startdate'];
  $date2 = $paa['checkdate1'];
  $arr1 = explode(" ", $date1);
  $arr2 = explode(" ", $date2);  
  $arrdate1 = explode("-", $arr1[0]);
  $arrdate2 = explode("-", $arr2[0]);
  $timestamp2 = (mktime(0, 0, 0, $arrdate2[1],  $arrdate2[2],  $arrdate2[0]));
  $timestamp1 = (mktime(0, 0, 0, $arrdate1[1],  $arrdate1[2],  $arrdate1[0]));
  if ($timestamp3 >= $timestamp1 and $timestamp3 <= $timestamp2)
  {} 
  else
  {      
  ?>
            <div class="ui-widget">
	            <div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em;">
               <p align="center">Размещение проекта запрещено!</p>
            	</div>
            </div><p></p>
   <?
       die;
  }
  }

  if ($mode=='add')
  {
     $btot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM blockcontentnames WHERE proarrid='".$_GET["paid"]."'");
     if (!$btot) puterror("Ошибка при обращении к базе данных");
     $totbcnt = mysqli_fetch_array($btot);
     $countb = $totbcnt['count(*)'];
  }               
  else
  {
   if (defined("IN_ADMIN")) 
    $gst3 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM projects WHERE id='".$_GET['id']."' LIMIT 1");
   else
    $gst3 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM projects WHERE id='".$_GET['id']."' AND userid='".USER_ID."' LIMIT 1");

   if (!$gst3) puterror("Ошибка при обращении к базе данных");

   $project = mysqli_fetch_array($gst3);
  
   if (!defined("IN_ADMIN") and $project['status']!='created')
   {
  ?>
            <div class="ui-widget">
	            <div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em;">
               <p align="center">Изменение проекта запрещено!</p>
            	</div>
            </div><p></p>
   <?
       die;
   }
   $pa1 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT defaultshablon FROM projectarray WHERE id='".$project["proarrid"]."' LIMIT 1");
   $paa1 = mysqli_fetch_array($pa1);
   $daf = $paa1['defaultshablon'];

   $btot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM blockcontentnames WHERE proarrid='".$project["proarrid"]."'");
   if (!$btot) puterror("Ошибка при обращении к базе данных");
   $totbcnt = mysqli_fetch_array($btot);
   $countb = $totbcnt['count(*)'];
     
  }
?>

<script type="text/javascript"> 

$(document).ready(function(){
    $("input[type=submit]").button();
    $("input[type=button]").button();
    $("input[type=file]").button();
    
    $('form').submit(function(){
     $('input[type=submit]', $(this)).attr('disabled', 'disabled');
     $('input[type=button]', $(this)).attr('disabled', 'disabled');
});   

});

 function startStatus() {
   $("#spinner").fadeIn("slow");
 }  
 
</script> 

<?
/*   if ($mode=='add')
   {
    $proarrid  = $_GET["paid"];
    $res1=mysqli_query($mysqli,"SELECT count(*) FROM poptions WHERE proarrid='".$proarrid."' AND multiid='".$multiid."' ORDER BY id");
    $total = mysqli_fetch_array($res1);
   }
   else
   {
    $multiid = $project['multiid'];
    $res1=mysqli_query($mysqli,"SELECT count(*) FROM poptions WHERE proarrid='".$project['proarrid']."' AND multiid='".$multiid."' ORDER BY id");
    $total = mysqli_fetch_array($res1);
   } */
?>
<? if (empty($nomenu)) {?>
<script type="text/javascript">
		$(document).ready(function() {
			$('.fancybox').fancybox();
		});
</script>
<? } ?>

<style type="text/css">
.z1 {
 text-align : left;
}
.fancybox-custom .fancybox-skin {
 box-shadow: 0 0 50px #222;
}
.ui-tabs .ui-tabs-panel {
 padding: 0px;
}
input.text, input[type=text] { 
 font-size: 100%; margin-top: 0px; border: 1px solid #393939; padding: 1px; background: #fafafa; color: #404040; }
</style>

<script type="text/javascript">  
 function onResponse(d) {  
 eval('var obj = ' + d + ';');  
 alert('Файл ' + obj.filename + (obj.success ? " " : " НЕ ") +  
    "загружен.");  
 }  

 $(function() {
  $( "#tabs" ).tabs({
<?
 if ($mode=='add')
  { 
?>      
   active: <? if (empty($_GET['multi'])) echo '0'; else echo $_GET['multi']; ?>
<? } else { ?>
   active: 0
<? } ?>
    });
 });
</script>

<form action="editproject" method="post" enctype="multipart/form-data" onsubmit="startStatus();">


<input type=hidden name=mode value='<? echo $mode; ?>'>
<input type=hidden name=action value=post>
<input type=hidden name=id value='<? echo $project['id']; ?>'>
                                                                                 
<div id='menu_glide' class='ui-widget-content ui-corner-all'>
<h3 class='ui-widget-header ui-corner-all' style='font-size: 14px; text-align: left; background: #497787 url("scripts/jquery-ui/images/ui-bg_inset-soft_75_497787_1x100.png") 50% 50% repeat-x;'>Наименование проекта *</h3>
<p align="center"><input type=text name='info' value='<? echo $project['info']; ?>' style="width:98%;"></p>
</div><p></p>

<?
 if ($mode=='add')
  echo "<input type='hidden' name='paid' value='".$paid."'>";
 else 
  echo "<input type='hidden' name='paid' value='".$project['proarrid']."'>";
?>

<div id="tabs">

<?
 if ($countb>0)
 {
  if ($mode=='add')
   $bgst = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM blockcontentnames WHERE proarrid='".$paid."' ORDER BY id");
  else
   $bgst = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM blockcontentnames WHERE proarrid='".$project['proarrid']."' ORDER BY id");
  if (!$bgst) puterror("Ошибка при обращении к базе данных");
  $bi=1;
  if (!empty($nomenu))
   echo "<ul><li><a href='#tabs-".$bi."'>По умолчанию</a></li>";
  else
  {
  if ($mode=='add')
   echo "<ul><li><a href='#tabs-".$bi."'>".$df."</a></li>";
  else
   echo "<ul><li><a href='#tabs-".$bi."'>".$daf."</a></li>";
  }
  while($block = mysqli_fetch_array($bgst))
   { 
     echo "<li><a href='#tabs-".++$bi."' title='".$block['info']."'>".$block['name']."</a></li>";
   }
  echo "</ul>"; 
 }
/////////////////////////////////////////// ++++++++++++++++++++++++++++++++++++++++++++++++
   
   $multiid = 0;
   
   if ($mode=='add')
    $res3=mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM poptions WHERE proarrid='".$paid."' AND multiid='".$multiid."' ORDER BY id");
   else
    $res3=mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM poptions WHERE proarrid='".$project['proarrid']."' AND multiid='".$multiid."' ORDER BY id");
  
   echo "<div id='tabs-1'>"
?>
<p align='center'>
<table border='0' cellpadding=2 cellspacing=2 width='100%' bordercolordark=white>

<?    
   
 
   $cnt=0;

    while($param = mysqli_fetch_array($res3))
   { 

    if ($mode!='add')
    {
     $res4=mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM projectdata WHERE projectid='".$project['id']."' AND optionsid='".$param['id']."'");
     if (!$res4) puterror("Ошибка при обращении к базе данных");
     $param4 = mysqli_fetch_array($res4);
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
      echo "<tr><td><textarea name='content".$param['id']."' style='width:100%' rows='30'>".$param4['content']."</textarea></td></tr>";
     } 
     elseif ($param['typetext']=="text") {
      echo "<tr><td><input type=text name='content".$param['id']."' style='width:100%' value='".$param4['content']."'></td></tr>";
     }
    }
    else
    if ($param['youtube']=="yes") 
    {
     if (!empty($param4['content']))
     {
     if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $param4['content'], $matches)) 
     {
       echo "<tr><td>
       <input type='hidden' name='content".$param['id']."' size='120' value='".$param4['content']."'>
       <iframe width='320' height='180' src='http://www.youtube.com/embed/".$matches[1]."?feature=player_detailpage' frameborder='0' allowfullscreen></iframe>
       &nbsp;<label><input type='checkbox' name='vb".$param['id']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить видеоролик</font></label></p>
       </td></tr>";
     }
     else
       echo "<tr><td>
       <input type='hidden' name='content".$param['id']."' size='120' value='".$param4['content']."'>
       <iframe width='320' height='180' src='http://www.youtube.com/embed/".$param4['content']."?feature=player_detailpage' frameborder='0' allowfullscreen></iframe>
       &nbsp;<label><input type='checkbox' name='vb".$param['id']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить видеоролик</font></label></p>
       </td></tr>";
     }
     else
     {
      // if (!$lastvideo) {
        echo "<tr><td><p class=ptd><b>".$param['name'].":</b></p>";
        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";
        echo "</td></tr>";  
        echo "<tr><td><input type='text' name='content".$param['id']."' style='width:100%' value='".$param4['content']."'></td></tr>";
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
       <input type='hidden' name='content".$param['id']."' size='120' value='".$param4['content']."'>
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
       echo "<tr><td><input type='text' name='content".$param['id']."' style='width:100%' value='".$param4['content']."'></td></tr>";
     }
    }  
    else
    if ($param['files']=="yes") {
     $cnt=$cnt+1;
     
     if ($param['fileformat']=="ajax") {
      echo "<tr><td>";
      echo"<p class='ptd'><b>".$param['name'].":</b></p>";


      $mres4=mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM multiprojectdata WHERE projectid='".$project['id']."' AND optionsid='".$param['id']."'");
      if (!$mres4) puterror("Ошибка при обращении к базе данных");
      $realcnt=0;
      while($mparam4 = mysqli_fetch_array($mres4))
      {
       if (!empty($mparam4['filename'])) 
       { 
        $realcnt++;
        $kb = round($mparam4['filesize']/1024,2);

      if ($param['filetype']=="file") { 
        
        echo "<p><a target='_blank' title='Просмотр через Google Docs' href='http://docs.google.com/viewer?url=http%3A%2F%2Fexpert03.ru%2Ffile.php%3Fid%3D".$mparam4['secure']."'>Просмотр ".$mparam4['filename']."</a>
        <a class='menu' href='file.php?id=".$mparam4['secure']."'
        target='_blank' title='Загрузить прикрепленный файл ".$mparam4['filename']." (".$kb." кб)'><img src='img/f32.jpg' alt='Загрузить ".$mparam4['filename']." (".$kb."кб)'></a>&nbsp;<label><input type='checkbox' name='cb".$param['id']."-".$mparam4['paramid']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить файл</font></label></p>";
       
      }
      else
      if ($param['filetype']=="foto") 
      {

        list($width, $height, $type, $attr)= getimagesize($upload_dir.$mparam4['projectid'].$mparam4['realfilename']);


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

      $("#viewpic-<?php echo $mparam4['id']; ?>").click(function() {
				$.fancybox.open([
        <?php
      echo "{ href : '".$upload_dir.$mparam4['projectid'].$mparam4['realfilename']."' }";
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


      echo "<a title='Просмотр ".$mparam4['filename']."' id='viewpic-".$mparam4['id']."' href='javascript:;'>
      <img src='file_thumb.php?id=".$mparam4['secure']."&w=".$new_width."&h=".$new_height."' width='".$new_width."' height='".$new_height."'></a>&nbsp;<label>
      <input type='checkbox' name='cb".$param['id']."-".$mparam4['paramid']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить файл</font></label></p>";

      }
       
       
       }
      }
      $rc = 5 - $realcnt;
      $realcnt++;
      if ($param['filetype']=="file") { 
        
       if ($rc>0) {
        echo"<p class=ptd><b>Прикрепить новые файлы (не более ".$rc.") к разделу '".$param['name']."'.</b> Размер нового файла не должен превышать ".$max_file_size_str." Мб.
        Допустимые расширения: ";
        for($x=0;$x<count($file_types_array);$x++) echo $file_types_array[$x].",";
        echo "</p>";

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";
       }
//        echo"<input type='file' class='multi max-5' name='file".$param['id']."' id='file".$param['id']."'/>";
?>

<div id="parentId<?echo $param['id']?>">
  <div>
    <? if ($rc>0) { ?><p><input name="multifile<?echo $param['id']?>-<? echo $realcnt ?>" type="file"/></p><? } ?>
    <input id="multifilemin<?echo $param['id']?>" name="multifilemin<?echo $param['id']?>" type="hidden" value="<? echo $realcnt ?>">
    <input id="multifilemax<?echo $param['id']?>" name="multifilemax<?echo $param['id']?>" type="hidden" value="<? echo $realcnt ?>">
    <? if ($rc>1) { ?><p><input type="button" onclick="return addField<?echo $param['id']?>()" value="Добавить файл"></p><? } ?>
  </div>
</div>

<script>
var countOfFields<?echo $param['id']?> = <? echo $realcnt ?>; 
var curFieldNameId<?echo $param['id']?> = <? echo $realcnt ?>; 
var maxFieldLimit = <?echo $rc ?>; 
function deleteField<?echo $param['id']?>(a) {
  if (countOfFields<?echo $param['id']?> > <? echo $realcnt ?>)
  {
 
 var contDiv = a.parentNode;
 contDiv.parentNode.removeChild(contDiv);
 countOfFields<?echo $param['id']?>--;
 }
 return false;
}
function addField<?echo $param['id']?>() {
 if (countOfFields<?echo $param['id']?> >= maxFieldLimit) {
  return false;
 }
 countOfFields<?echo $param['id']?>++;
 curFieldNameId<?echo $param['id']?>++;
 $( "#multifilemax<?echo $param['id']?>" ).val(curFieldNameId<?echo $param['id']?>);
 var div = document.createElement("div");
 div.innerHTML = "<p><input name=\"multifile<?echo $param['id']?>-" + curFieldNameId<?echo $param['id']?> + "\" type=\"file\" /> <input type=\"button\" onclick=\"return deleteField<?echo $param['id']?>(this)\" value=\"-\"></p>";
 document.getElementById("parentId<?echo $param['id']?>").appendChild(div);
 return false;
}
</script>

<?

        echo"</td></tr><tr><td><hr></td></tr>";
      }
      else
      if ($param['filetype']=="foto") {

       if ($rc>0) {
        echo "<p class=ptd><b>Прикрепить новые фотографии (не более ".$rc.") к разделу '".$param['name']."'.</b> Размер нового файла не должен превышать ".$photo_max_file_size_str." Мб.
         Допустипые расширения: ";
         for($x=0;$x<count($photo_file_types_array);$x++) echo $photo_file_types_array[$x].",";
        echo "</p>";

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";
       }

?>

<div id="parentId<?echo $param['id']?>">
  <div>
    <? if ($rc>0) { ?><p><input name="multifile<?echo $param['id']?>-<? echo $realcnt ?>" type="file"/></p><? } ?>
    <input id="multifilemin<?echo $param['id']?>" name="multifilemin<?echo $param['id']?>" type="hidden" value="<? echo $realcnt ?>">
    <input id="multifilemax<?echo $param['id']?>" name="multifilemax<?echo $param['id']?>" type="hidden" value="<? echo $realcnt ?>">
    <? if ($rc>1) { ?><p><input type="button" onclick="return addField<?echo $param['id']?>()" value="Добавить файл"></p> <? } ?>
  </div>
</div>

<script>
var countOfFields<?echo $param['id']?> = <? echo $realcnt ?>; 
var curFieldNameId<?echo $param['id']?> = <? echo $realcnt ?>; 
var maxFieldLimit = <?echo $rc ?>; 
function deleteField<?echo $param['id']?>(a) {
  if (countOfFields<?echo $param['id']?> > <? echo $realcnt ?>)
  {
 
 var contDiv = a.parentNode;
 contDiv.parentNode.removeChild(contDiv);
 countOfFields<?echo $param['id']?>--;
 }
 return false;
}
function addField<?echo $param['id']?>() {
 if (countOfFields<?echo $param['id']?> >= maxFieldLimit) {
  return false;
 }
 countOfFields<?echo $param['id']?>++;
 curFieldNameId<?echo $param['id']?>++;
 $( "#multifilemax<?echo $param['id']?>" ).val(curFieldNameId<?echo $param['id']?>);
 var div = document.createElement("div");
 div.innerHTML = "<p><input name=\"multifile<?echo $param['id']?>-" + curFieldNameId<?echo $param['id']?> + "\" type=\"file\" /> <input type=\"button\" onclick=\"return deleteField<?echo $param['id']?>(this)\" value=\"-\"></p>";
 document.getElementById("parentId<?echo $param['id']?>").appendChild(div);
 return false;
}
</script>

<?

        echo"</td></tr><tr><td><hr></td></tr>";
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


      echo "<a title='".$param4['filename']."' id='fancybox-manual-".$param4['id']."' href='javascript:;'><img src='file_thumb.php?id=".$param4['secure']."&w=".$new_width."&h=".$new_height."' width='".$new_width."' height='".$new_height."'></a>&nbsp;<label>
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
        echo"<p class='ptd'><b>".$param['name'].":</b></p>";

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-1'>".$param['doptext']."</font></p>";
        
        echo"<p><font face='Tahoma,Arial' size='-2'>Размер нового файла не должен превышать ".$max_file_size_str." Мб. Допустимые расширения: ";
        for($x=0;$x<count($file_types_array);$x++) echo $file_types_array[$x].",";
        echo "</font></p>";


        echo"<input type='file' name='file".$param['id']."' id='file".$param['id']."'/>";
        echo"</td></tr><tr><td><hr></td></tr>";
       // $lastfile = false;
       //}
      }
      else
      if ($param['filetype']=="foto") {
       //if (!$lastfoto) {
        echo "<tr><td>";
        echo "<p class=ptd>Новая фотография (картинка) <b>".$param['name'].":</b></p>";

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";
        
        echo "<p><font face='Tahoma,Arial' size='-2'>Размер фотографии (картинки) не должен превышать ".$photo_max_file_size_str." Мб.
         Допустипые расширения: ";
         for($x=0;$x<count($photo_file_types_array);$x++) echo $photo_file_types_array[$x].",";
        echo "</font></p>";

        echo"<input type='file' name='file".$param['id']."' id='file".$param['id']."'/>";
        echo"</td></tr><tr><td><hr></td></tr>";
//        $lastfoto = true;
//       }
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
            <input type="submit" value="<? if ($mode=='add') echo "Сохранить проект"; else echo "Изменить проект"?>">&nbsp;
        </td>
    </tr>           
    <?
}
    ?>
    
</table></p>
<?
   echo "</div>";

/////////////////////////////////////////// --------------------------------------------------------------------
 if ($countb>0)
 {
  if ($mode=='add')
   $bgst2 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM blockcontentnames WHERE proarrid='".$paid."' ORDER BY id");
  else
   $bgst2 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM blockcontentnames WHERE proarrid='".$project['proarrid']."' ORDER BY id");
  if (!$bgst2) puterror("Ошибка при обращении к базе данных");
  $bi2=1;
  while($block2 = mysqli_fetch_array($bgst2))
  { 
   $multiid = $block2['id'];
   
   if ($mode=='add')
    $res3=mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM poptions WHERE proarrid='".$paid."' AND multiid='".$multiid."' ORDER BY id");
   else
    $res3=mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM poptions WHERE proarrid='".$project['proarrid']."' AND multiid='".$multiid."' ORDER BY id");
  
   echo "<div id='tabs-".++$bi2."'>"
?>
<p align='center'>
<table border='0' cellpadding=2 cellspacing=2 width='100%' bordercolordark=white>

<?    
   
 
   $cnt=0;

    while($param = mysqli_fetch_array($res3))
   { 

    if ($mode!='add')
    {
     $res4=mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM projectdata WHERE projectid='".$project['id']."' AND optionsid='".$param['id']."'");
     if (!$res4) puterror("Ошибка при обращении к базе данных");
     $param4 = mysqli_fetch_array($res4);
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
      echo "<tr><td><textarea name='content".$param['id']."' style='width:100%' rows='30'>".$param4['content']."</textarea></td></tr>";
     } 
     elseif ($param['typetext']=="text") {
      echo "<tr><td><input type=text name='content".$param['id']."' style='width:100%' value='".$param4['content']."'></td></tr>";
     }
    }
    else
    if ($param['youtube']=="yes") 
    {
     if (!empty($param4['content']))
     {
     if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $param4['content'], $matches)) 
     {
       echo "<tr><td>
       <input type='hidden' name='content".$param['id']."' size='120' value='".$param4['content']."'>
       <iframe width='320' height='180' src='http://www.youtube.com/embed/".$matches[1]."?feature=player_detailpage' frameborder='0' allowfullscreen></iframe>
       &nbsp;<label><input type='checkbox' name='vb".$param['id']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить видеоролик</font></label></p>
       </td></tr>";
     }
     else
       echo "<tr><td>
       <input type='hidden' name='content".$param['id']."' size='120' value='".$param4['content']."'>
       <iframe width='320' height='180' src='http://www.youtube.com/embed/".$param4['content']."?feature=player_detailpage' frameborder='0' allowfullscreen></iframe>
       &nbsp;<label><input type='checkbox' name='vb".$param['id']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить видеоролик</font></label></p>
       </td></tr>";
     }
     else
     {
      // if (!$lastvideo) {
        echo "<tr><td><p class=ptd><b>".$param['name'].":</b></p>";
        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";
        echo "</td></tr>";  
        echo "<tr><td><input type='text' name='content".$param['id']."' style='width:100%' value='".$param4['content']."'></td></tr>";
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
       <input type='hidden' name='content".$param['id']."' size='120' value='".$param4['content']."'>
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
       echo "<tr><td><input type='text' name='content".$param['id']."' style='width:100%' value='".$param4['content']."'></td></tr>";
     }
    }  
    else
    if ($param['files']=="yes") {
     $cnt=$cnt+1;
     
     if ($param['fileformat']=="ajax") {
      echo "<tr><td>";
      echo"<p class='ptd'><b>".$param['name'].":</b></p>";

      $mres4=mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM multiprojectdata WHERE projectid='".$project['id']."' AND optionsid='".$param['id']."'");
      if (!$mres4) puterror("Ошибка при обращении к базе данных");
      $realcnt=0;
      while($mparam4 = mysqli_fetch_array($mres4))
      {
       if (!empty($mparam4['filename'])) 
       { 
        $realcnt++;
        $kb = round($mparam4['filesize']/1024,2);

      if ($param['filetype']=="file") { 
        
        echo "<p><a target='_blank' title='Просмотр через Google Docs' href='http://docs.google.com/viewer?url=http%3A%2F%2Fexpert03.ru%2Ffile.php%3Fid%3D".$mparam4['secure']."'>Просмотр ".$mparam4['filename']."</a>
        <a class='menu' href='file.php?id=".$mparam4['secure']."'
        target='_blank' title='Загрузить прикрепленный файл ".$mparam4['filename']." (".$kb." кб)'><img src='img/f32.jpg' alt='Загрузить ".$mparam4['filename']." (".$kb."кб)'></a>&nbsp;<label><input type='checkbox' name='cb".$param['id']."-".$mparam4['paramid']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить файл</font></label></p>";
       
      }
      else
      if ($param['filetype']=="foto") 
      {

        list($width, $height, $type, $attr)= getimagesize($upload_dir.$mparam4['projectid'].$mparam4['realfilename']);


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

      $("#viewpic-<?php echo $mparam4['id']; ?>").click(function() {
				$.fancybox.open([
        <?php
      echo "{ href : '".$upload_dir.$mparam4['projectid'].$mparam4['realfilename']."' }";
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


      echo "<a title='Просмотр ".$mparam4['filename']."' id='viewpic-".$mparam4['id']."' href='javascript:;'>
      <img src='file_thumb.php?id=".$mparam4['secure']."&w=".$new_width."&h=".$new_height."' width='".$new_width."' height='".$new_height."'></a>&nbsp;<label>
      <input type='checkbox' name='cb".$param['id']."-".$mparam4['paramid']."' value='1'><font face='Tahoma, Arial' size='-1'>Удалить файл</font></label></p>";

      }


       }
      }
      $rc = 5 - $realcnt;
      $realcnt++;

      if ($param['filetype']=="file") { 
        
        if ($rc>0) {
        echo"<p class=ptd><b>Прикрепить новые файлы (не более ".$rc.") к разделу '".$param['name']."'.</b> Размер нового файла не должен превышать ".$max_file_size_str." Мб.
        Допустимые расширения: ";
        for($x=0;$x<count($file_types_array);$x++) echo $file_types_array[$x].",";
        echo "</p>";

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";
        }

?>

<div id="parentId<?echo $param['id']?>">
  <div>
    <? if ($rc>0) { ?><p><input name="multifile<?echo $param['id']?>-<? echo $realcnt ?>" type="file"/></p><? } ?>
    <input id="multifilemin<?echo $param['id']?>" name="multifilemin<?echo $param['id']?>" type="hidden" value="<? echo $realcnt ?>">
    <input id="multifilemax<?echo $param['id']?>" name="multifilemax<?echo $param['id']?>" type="hidden" value="<? echo $realcnt ?>">
    <? if ($rc>1) { ?><p><input type="button" onclick="return addField<?echo $param['id']?>()" value="Добавить файл"></p> <? } ?>
  </div>
</div>

<script>
var countOfFields<?echo $param['id']?> = <? echo $realcnt ?>; 
var curFieldNameId<?echo $param['id']?> = <? echo $realcnt ?>; 
var maxFieldLimit = <?echo $rc ?>; 
function deleteField<?echo $param['id']?>(a) {
  if (countOfFields<?echo $param['id']?> > <? echo $realcnt ?>)
  {
 
 var contDiv = a.parentNode;
 contDiv.parentNode.removeChild(contDiv);
 countOfFields<?echo $param['id']?>--;
 }
 return false;
}
function addField<?echo $param['id']?>() {
 if (countOfFields<?echo $param['id']?> >= maxFieldLimit) {
  return false;
 }
 countOfFields<?echo $param['id']?>++;
 curFieldNameId<?echo $param['id']?>++;
 $( "#multifilemax<?echo $param['id']?>" ).val(curFieldNameId<?echo $param['id']?>);
 var div = document.createElement("div");
 div.innerHTML = "<p><input name=\"multifile<?echo $param['id']?>-" + curFieldNameId<?echo $param['id']?> + "\" type=\"file\" /> <input type=\"button\" onclick=\"return deleteField<?echo $param['id']?>(this)\" value=\"-\"></p>";
 document.getElementById("parentId<?echo $param['id']?>").appendChild(div);
 return false;
}
</script>

<?
        echo"</td></tr><tr><td><hr></td></tr>";
      }
      else
      if ($param['filetype']=="foto") {

       if ($rc>0) {
        echo "<p class=ptd><b>Прикрепить новые фотографии (не более ".$rc.") к разделу '".$param['name']."'.</b> Размер нового файла не должен превышать ".$photo_max_file_size_str." Мб.
         Допустипые расширения: ";
         for($x=0;$x<count($photo_file_types_array);$x++) echo $photo_file_types_array[$x].",";
        echo "</p>";

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";
        }

?>

<div id="parentId<?echo $param['id']?>">
  <div>
    <? if ($rc>0) { ?><p><input name="multifile<?echo $param['id']?>-<? echo $realcnt ?>" type="file"/></p><? } ?>
    <input id="multifilemin<?echo $param['id']?>" name="multifilemin<?echo $param['id']?>" type="hidden" value="<? echo $realcnt ?>">
    <input id="multifilemax<?echo $param['id']?>" name="multifilemax<?echo $param['id']?>" type="hidden" value="<? echo $realcnt ?>">
    <? if ($rc>1) { ?><p><input type="button" onclick="return addField<?echo $param['id']?>()" value="Добавить файл"></p> <? } ?>
  </div>
</div>

<script>
var countOfFields<?echo $param['id']?> = <? echo $realcnt ?>; 
var curFieldNameId<?echo $param['id']?> = <? echo $realcnt ?>; 
var maxFieldLimit = <?echo $rc ?>; 
function deleteField<?echo $param['id']?>(a) {
  if (countOfFields<?echo $param['id']?> > <? echo $realcnt ?>)
  {
 
 var contDiv = a.parentNode;
 contDiv.parentNode.removeChild(contDiv);
 countOfFields<?echo $param['id']?>--;
 }
 return false;
}
function addField<?echo $param['id']?>() {
 if (countOfFields<?echo $param['id']?> >= maxFieldLimit) {
  return false;
 }
 countOfFields<?echo $param['id']?>++;
 curFieldNameId<?echo $param['id']?>++;
 $( "#multifilemax<?echo $param['id']?>" ).val(curFieldNameId<?echo $param['id']?>);
 var div = document.createElement("div");
 div.innerHTML = "<p><input name=\"multifile<?echo $param['id']?>-" + curFieldNameId<?echo $param['id']?> + "\" type=\"file\" /> <input type=\"button\" onclick=\"return deleteField<?echo $param['id']?>(this)\" value=\"-\"></p>";
 document.getElementById("parentId<?echo $param['id']?>").appendChild(div);
 return false;
}
</script>

<?

        echo"</td></tr><tr><td><hr></td></tr>";
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
      echo "<a title='".$param4['filename']."' id='fancybox-manual-".$param4['id']."' href='javascript:;'><img src='file_thumb.php?id=".$param4['secure']."&w=".$new_width."&h=".$new_height."' width='".$new_width."' height='".$new_height."'></a>&nbsp;<label>
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
        echo"<p class=ptd>Новый файл <b>".$param['name'].":</b></p>"; 

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-1'>".$param['doptext']."</font></p>";
        
        echo"<p><font face='Tahoma,Arial' size='-2'>Размер нового файла не должен превышать ".$max_file_size_str." Мб. Допустимые расширения: ";
        for($x=0;$x<count($file_types_array);$x++) echo $file_types_array[$x].",";
        echo "</font></p>";


        echo"<input type='file' name='file".$param['id']."' id='file".$param['id']."'/>";
        echo"</td></tr><tr><td><hr></td></tr>";
       // $lastfile = false;
       //}
      }
      else
      if ($param['filetype']=="foto") {
//       if (!$lastfoto) {
        echo "<tr><td>";
        echo "<p class=ptd>Новая фотография (картинка) <b>".$param['name'].":</b></p>";

        if (!empty($param['doptext']))
          echo "<p><font face='Tahoma,Arial' size='-2'>".$param['doptext']."</font></p>";
        
        echo "<p><font face='Tahoma,Arial' size='-2'>Размер фотографии (картинки) не должен превышать ".$photo_max_file_size_str." Мб.
         Допустипые расширения: ";
         for($x=0;$x<count($photo_file_types_array);$x++) echo $photo_file_types_array[$x].",";
        echo "</font></p>";

        echo"<input type='file' name='file".$param['id']."' id='file".$param['id']."'/>";
        echo"</td></tr><tr><td><hr></td></tr>";
//        $lastfoto = true;
//       }
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
            <input type="submit" value="<? if ($mode=='add') echo "Сохранить проект"; else echo "Изменить проект"?>">&nbsp;
        </td>
    </tr>           
    <?
}
  
  echo "</table></p></div></div></form>";

  $tot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM comments WHERE projectid='".$project['id']."'");
  if (!$tot) puterror("Ошибка при обращении к базе данных");
  $total = mysqli_fetch_array($tot);
  $count = $total['count(*)'];
  if ($count>0) 
  {
  ?>
<p></p>
<div id='menu_glide' class='ui-widget-content ui-corner-all'>
<h3 class='ui-widget-header ui-corner-all' style='font-size: 14px; text-align: left; background: #497787 url("scripts/jquery-ui/images/ui-bg_inset-soft_75_497787_1x100.png") 50% 50% repeat-x;'>Замечания и комментарии к проекту</h3>
<table border='0' cellpadding=2 cellspacing=2 width='100%' bordercolordark=white>
  <?
  
  $res3=mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM comments WHERE projectid='".$project['id']."' ORDER BY cdate DESC");
  while($param3 = mysqli_fetch_array($res3))
   { 
      $res4=mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT userfio FROM users WHERE id='".$param3['expertid']."'");
      $param4 = mysqli_fetch_array($res4);
      echo "<tr><td witdh='300'><hr><p><b>Эксперт ".$param4['userfio']." от ".data_convert ($param3['cdate'], 1, 1, 0)." оставил комментарий (замечание):</b></p>
      <p>".$param3['content']."</p></td></tr>";   
   } 
   echo "</table></div>";
  }
  } 
  }
?>

<div id="spinner"></div>

<?php

include "bottomadmin.php";
}
} else die;
?>

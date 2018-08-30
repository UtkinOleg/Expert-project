<?php
 if(defined("IN_USER") or defined("IN_ADMIN") or defined("IN_SUPERVISOR")) {  
 
 include "config.php";
 include "func.php";


 $title = "Изменение статуса проекта";
 $titlepage=$title;  

 include "topadmin.php";

 $action = $_POST["action"];

if (!empty($action)) 
{

  $paid = $_POST["paid"];
  $topage = $_POST["topage"];
  $paname = $_POST["paname"];

  // Сразу обновим статус проекта
  $query = "UPDATE projects SET status = '".$_POST["status"]."'
           WHERE id=".$_POST["id"];

   if(mysql_query($query))
   {
      writelog("Изменен статус проекта №".$_POST['id']." - ".$_POST["status"].".");
      
      require_once('lib/unicode.inc');
      
      // Отправка на экспертизу - модератору шаблона на проверку
      if ($_POST["status"]=="accepted") {


       $gst2 = mysql_query("SELECT * FROM projectarray WHERE id='".$paid."'");
       if (!$gst2) puterror("Ошибка при обращении к базе данных");
       $owner = mysql_fetch_array($gst2);

       $gst4 = mysql_query("SELECT * FROM users WHERE id='".$owner['ownerid']."'");
       $admin = mysql_fetch_array($gst4);
      
       $toid = $admin['id'];
       $toemail = $admin['email'];

       $title = "Проект №".$_POST["id"]." подготовлен к экспертизе.";
       $body = "Здравствуйте модератор шаблона проекта ".$owner['name']."!\n\n
       Проект №".$_POST["id"]." подготовлен к экспертизе. Требуется Ваша проверка проекта.\n
       После проверки необходимо у проекта изменить статус - проходит экспертизу.\n
       Если Вы не хотите проверять проект перед экспертизой - необходимо отключить отправку проверки проекта в настройках шаблона.\n
       С уважением, Экспертная система (".$site.")";
       $fromid = USER_ID;

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
       )) puterror("Ошибка при отправке сообщения.");
       
       }
      
       $query = "INSERT INTO msgs VALUES (0,
                                        $toid,
                                        $fromid,
                                        '$title',
                                        '$body',0,NOW());";
       if(!mysql_query($query))
       puterror("Ошибка при обращении к базе данных.");

      }
      else
      // Проект проверен - отправим сообщение экспертам
      if ($_POST["status"]=="inprocess") {

      $gst = mysql_query("SELECT * FROM proexperts WHERE proarrid='".$paid."' ORDER BY id");
      if (!$gst) puterror("Ошибка при обращении к базе данных");
      
      while($member = mysql_fetch_array($gst))
      {

      $gst2 = mysql_query("SELECT * FROM users WHERE id='".$member['expertid']."'");
      $user = mysql_fetch_array($gst2);

      $toid = $user["id"];
      $toemail = $user["email"];
      $title = "Проект подготовлен к экспертизе.";
      $body = "Здравствуйте ".$user["userfio"]."!\n\n
      Проект №".$_POST["id"]." подготовлен к экспертизе. Требуется экспертная оценка проекта.\n
      Для этого необходимо зайти на сайт экспертной системы и выбрать пункт меню <b>Экспертные листы</b>.\n
      Далее - Добавить новый экспертный лист, проверить проект и установить оценки по критериям или написать рецензию.\n
      После проверки проекта выбрать - сохранить экспертный лист.\n
      Экспертный лист или рецензия на проект сохранятеся только один раз!\n
      С уважением, Экспертная система (".$site.")";
      $fromid = USER_ID;


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
          )) puterror("Ошибка при отправке сообщения.");

        $query = "INSERT INTO msgs VALUES (0,
                                        $toid,
                                        $fromid,
                                        '$title',
                                        '$body',0,NOW());";
        if(!mysql_query($query))
         puterror("Ошибка при обращении к базе данных.");
   
       }
  
      }
      }
       
     if (!empty($topage))
     {
      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?op=".$topage."&paname=".$paname."&paid=".$paid."'>\n";
      print "</HEAD></HTML>\n";
     } 
     else
     { 
      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?op=projects'>\n";
      print "</HEAD></HTML>\n";
     }
   } else  
    puterror("Ошибка при обращении к базе данных");
}

if (empty($action)) 
{
  
  $topage = $_GET['to'];
  $paname = $_GET['paname'];
  
  $id = $_GET['id'];

  // Получим ИД шаблона

  $gst = mysql_query("SELECT * FROM projects WHERE id='".$id."'");
  if (!$gst) puterror("Ошибка при обращении к базе данных");
  
  $tableheader = "class=tableheader";
  $showhide = "";
  $tableheader = "class=tableheaderhide";
  $member = mysql_fetch_array($gst);
  $paid = $member['proarrid'];
  
?>

<form action=index.php?op=chsproject method=post>
<input type=hidden name=action value=post>
<?   if (!empty($topage)) {
?>
 <input type=hidden name="topage" value="<? echo $topage; ?>">
 <input type=hidden name="paname" value="<? echo $paname; ?>">
<? } ?> 
<p align='center'>
<div id="menu_glide" class="menu_glide">
<table class=bodytable border="0" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
    <tr>
        <td><p class=ptd><b>Идентификационный номер проекта:</b></p></td><td><p class=ptd><? echo $member['id']; ?></p></td>
    </tr>
    <tr>
        <td><p class=ptd><b>Наименование:</b></p></td><td><p class=ptd><? echo $member['info']; ?></p></td>
    </tr>
    <tr>
        <td><p class=ptd><b>Изменить статус проекта *:</b></p></td>
        
        <?
        echo"<td><select name='status'>";

        if ($member['status']=='created') 
        {
         // В зависимости от настроек шаблона - либо отправляем на проверку модератору, либо сразу экспертам
         $gst2 = mysql_query("SELECT * FROM projectarray WHERE id='".$paid."'");
         if (!$gst2) puterror("Ошибка при обращении к базе данных");
         $owner = mysql_fetch_array($gst2);
         if ($owner['moderatorverify']==1) {
          echo"<option value='accepted'>Подготовлен к экспертизе</option>";
         } 
         else {
          echo"<option value='inprocess'>Подготовлен к экспертизе</option>";
         }
        } 

        if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) 
        {
         if ($member['status']=='accepted')
          echo"<option value='inprocess'>Проходит экспертизу</option>";
        }
        
        if ($member['status']=='finalized') {
         if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) 
         {
          echo"<option value='inprocess'>Проходит экспертизу</option>";
          echo"<option value='published'>Публикация проекта</option>";
         } else {
          echo"<option value='published'>Публикация проекта</option>";
         }
        }
        
        if ($member['status']=='published') {
          echo"<option value='finalized'>Отменить публикацию проекта</option>";
        }
        
        echo"</select></td>";   
        ?>
        
    </tr><tr>
        <td><p class=ptd>Внимание! Изменение статуса проекта приведет к невозможности его изменения и удаления!</p></td>
    </tr><tr align="center">
        <td colspan="3">
            <input type="submit" value="Изменить статус">&nbsp;
            <input type="button" name="close" value="Отмена" onclick="history.back()"> 
        </td>
    </tr>           
</table></div></p>
<input type=hidden name=id value=<?php echo $id; ?>>
<input type=hidden name=paid value=<?php echo $paid; ?>>
</form>

<?
}
include "bottomadmin.php";
}
else die;  
?>
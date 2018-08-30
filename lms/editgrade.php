<?php
 if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) {  

 include "config.php";
 include "func.php";

 $title = "Редактирование оценки";
 $titlepage=$title;  

 include "topadmin.php";

 $action = $_POST["action"];

if (!empty($action)) 
{
  $paid = $_POST["paid"];

  $gst3 = mysql_query("SELECT ownerid FROM projectarray WHERE id='".$paid."'");
  if (!$gst3) puterror("Ошибка при обращении к базе данных");
  $projectarray = mysql_fetch_array($gst3);

   if ((defined("IN_SUPERVISOR") and $projectarray['ownerid'] == USER_ID) or defined("IN_ADMIN")) 
   {

  // Проверяем правильность ввода информации в поля формы
  if (empty($_POST["name"])) 
  {
    $action = ""; 
    $error = $error."<LI>Вы не ввели Оценку.\n";
  }

  if (!empty($action)) 
  {
  $query = "UPDATE grades SET ball = '".$_POST["name"]."' WHERE id=".$_POST["id"];
   if(mysql_query($query))
   {

      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?op=grades&paid=".$paid."'>\n";
      print "</HEAD></HTML>\n";

   } else  
    puterror("Ошибка при обращении к базе данных");
  }  
}
}

if (empty($action)) 
{
  $id = $_GET['id'];
  $start = $_GET['start'];
  $paid = $_GET["paid"];

  $gst3 = mysql_query("SELECT ownerid FROM projectarray WHERE id='".$paid."'");
  if (!$gst3) puterror("Ошибка при обращении к базе данных");
  $projectarray = mysql_fetch_array($gst3);

   if ((defined("IN_SUPERVISOR") and $projectarray['ownerid'] == USER_ID) or defined("IN_ADMIN")) 
   {

  $query = "SELECT * FROM grades WHERE id = $id";
  $gst = mysql_query($query);
  if ($gst)
  {
    $member = mysql_fetch_array($gst);
  }
  else puterror("Ошибка при обращении к базе данных");

  
?>


<form action=index.php?op=editgrade method=post>
<input type=hidden name=action value=post>
<input type=hidden name=paid value=<? echo $paid; ?>>
<p align='center'><table class=bodytable border="0" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
    <tr>
        <td><p class=ptd><b>Значение *</b></p></td>
        <td><input type=text name=name size=25 value='<? echo $member['ball'] ?>'></td>
    </tr>
    <tr>
        <td colspan="3">
            <input type="submit" value="Изменить">&nbsp;&nbsp;&nbsp;
            <input type="button" name="close" value="Назад" onclick="history.back()"> 
        </td>
    </tr>           
</table></p>
<input type=hidden name=id value=<?php echo $id; ?>>
<input type=hidden name=start value=<?php echo $start; ?>>
</form>

<?
include "bottomadmin.php";
}
}
} else die;
?>
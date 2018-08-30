<?php
if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) {  
// Устанавливаем соединение с базой данных
  include "config.php";
  $title=$titlepage="Добавить оценку";
  include "topadmin.php";

$error = "";
$action = "";
// Возвращаем значение переменной action, переданной в урле
$action = $_POST["action"];
// Если оно не пусто - добавляем сообщение в базу данных
if (!empty($action)) 
{
  // Проверяем совпадает ли идентификатор сессии с
  // переданным в форме - защита а авто-постинга
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
    $error = $error."<LI>Вы не ввели оценку\n";
  }

    $name = $_POST["name"];
   
    // Запрос к базе данных на добавление сообщения
    $query = "INSERT INTO grades VALUES (0, $name, $paid);";
    if(mysql_query($query))
    {
      // Возвращаемся на главную страницу если всё прошло удачно
      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?op=grades&paid=".$paid."'>\n";
      print "</HEAD></HTML>\n";
      exit();
    }
    else
    {
      // Выводим сообщение об ошибке в случае неудачи
      echo "<a href='index.php?op=grades&paid=".$paid."'>Вернуться</a>";
      echo("<P> Ошибка при добавлении оценки</P>");
      echo("<P> $query</P>");
      exit();
    }
    
  
}
}

if (empty($action)) 
{
  $paid = $_GET["paid"];
  $gst3 = mysql_query("SELECT ownerid FROM projectarray WHERE id='".$paid."'");
  if (!$gst3) puterror("Ошибка при обращении к базе данных");
  $projectarray = mysql_fetch_array($gst3);

   if ((defined("IN_SUPERVISOR") and $projectarray['ownerid'] == USER_ID) or defined("IN_ADMIN")) 
   {
?>

<form action=index.php?op=addgrade method=post>
<input type=hidden name=sid_add_theme value='<?php echo $sid_add_theme; ?>'>
<input type=hidden name=action value=post>
<input type=hidden name=paid value=<? echo $paid; ?>>
<table><tr valign="top"><td width="25%">&nbsp;</td><td>
<table border="0" align="left" cellpadding="6" cellspacing="0">
    <tr>
        <td width="250"><p class=ptd><b><em class=em>Значение *</em></b></td>
        <td><input type=text name=name maxlength=32 size=25 value='<? echo $name; ?>'></td>
    </tr>
    <tr>
        <td colspan="3">
            <input type="submit" value="Добавить">&nbsp;&nbsp;&nbsp;
            <input type="button" name="close" value="Назад" onclick="history.back()"> 
        </td>
    </tr>           
</table>
</td></tr></table>
</form>
<?php

  include "bottomadmin.php";

  if (!empty($error)) 
  {
    print "<P><font color=green>Во время добавления записи произошли следующие ошибки: </font></P>\n";
    print "<UL>\n";
    print $error;
    print "</UL>\n";
  }
}

}} else die;
?>

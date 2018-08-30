<?php
if(!defined("IN_ADMIN")) die;  
// Устанавливаем соединение с базой данных
include "config.php";

$error = "";
$action = "";

$title=$titlepage="Новая область знаний";

include "topadmin.php";

// Возвращаем значение переменной action, переданной в урле
$action = $_POST["action"];
// Если оно не пусто - добавляем сообщение в базу данных
if (!empty($action)) 
{
  
    $name = $_POST["name"];
    $content = $_POST["content"];

    // Запрос к базе данных на добавление сообщения
    $query = "INSERT INTO knowledge VALUES (0,
                                        '$name',
                                        '$content');";
    if(mysql_query($query))
    {


      // Возвращаемся на главную страницу если всё прошло удачно
      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?op=knows'>\n";
      print "</HEAD></HTML>\n";
      exit();
    }
    else
    {
      // Выводим сообщение об ошибке в случае неудачи
      echo "<a href='index.php?op=knows'>Вернуться</a>";
      echo("<P> Ошибка при добавлении области</P>");
      echo("<P> $query</P>");
      exit();
    }
    
  
}

if (empty($action)) 
{


?>
<form action=index.php?op=addknows method=post>
<input type=hidden name=action value=post>
<p align='center'>
<table  width="700" border="0" cellpadding=0 cellspacing=0 bordercolorlight=gray bordercolordark=white>
<tr><td>
<div id="menu_glide" class="menu_glide">
<table width="700" class=bodytable border="0" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
    <tr>
        <td width="250"><p class=ptd><b><em class=em>Наименование:</em></b></td>
    </tr>
    <tr>
        <td><input type='text' name='name' size=45></td>
    </tr>

<tr><td>
    
    <table  width="700" class=bodytable border="0" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
    <tr><td><p class=ptd>Пояснение:</p></td></tr>
    <tr><td><textarea name=content style='width:100%' rows='10'></textarea></td>
    </tr>
    </table>
    </td></tr>

    <tr>
        <td colspan="3">
            <input type="submit" value="Добавить">&nbsp;&nbsp;
            <input type="button" name="close" value="Назад" onclick="history.back()"> 
        </td>
    </tr>           
</table></div>
</td></tr></table>
</form>
<?php
include "bottomadmin.php";
}
?>

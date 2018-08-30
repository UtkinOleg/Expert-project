<?php
if(!defined("IN_ADMIN")) die;  
// Устанавливаем соединение с базой данных
include "config.php";

$error = "";
$action = "";
$title=$titlepage="Новый эксперт";

include "topadmin.php";

// Возвращаем значение переменной action, переданной в урле
$action = $_POST["action"];
// Если оно не пусто - добавляем сообщение в базу данных
if (!empty($action)) 
{
  
  // Проверяем правильность ввода информации в поля формы
  if (empty($_POST["fio"])) 
  {
    $action = ""; 
    $error = $error."<LI>Вы не ввели Фамилию Имя Отчество.\n";
  }
  if (empty($_POST["login"])) 
  {
    $action = ""; 
    $error = $error."<LI>Вы не ввели логин.\n";
  }

  if (empty($_POST["pass"])) 
  {
    $action = ""; 
    $error = $error."<LI>Вы не ввели пароль.\n";
  }

  if (!empty($action)) 
  {
    $usertype = $_POST["usertype"];
    $email = $_POST["email"];
    $job = $_POST["job"];
    $login = $_POST["login"];
    $pass = md5($_POST["pass"]);
    $fio = $_POST["fio"];
    // Запрос к базе данных на добавление сообщения
    $query = "INSERT INTO users VALUES (0,
                                        '$login',
                                        '$pass',
                                        '$fio',
                                        '$usertype',NOW(),'$email',0,'','','$job',0);";
    if(mysql_query($query))
    {
   
  $toemail = $email;
  $title = "Добро пожаловать в экспертную систему!";
  $body = "Здравствуйте ".$fio."!\nДобро пожаловать в экспертную систему ".$site."!\n
  Для входа в систему: перейдите по адресу - ".$site.", введите логин - \n".$login."\nзатем пароль - ".$_POST["pass"]."\n\n
  Вам присвоен статус ЭКСПЕРТА. На Ваш электронный адрес в период работы системы будет приходить почта c запросами оценки проектов участников системы.\n
  Ваша задача - зайти на сайт и оценить проект каждого участника.\n\nПо всем вопросам можете обращаться siberia-soft@yandex.ru";
  $fromid = USER_ID;

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
      // Возвращаемся на главную страницу если всё прошло удачно
      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?op=experts'>\n";
      print "</HEAD></HTML>\n";
      exit();
      
      
    }
    else
    {
      // Выводим сообщение об ошибке в случае неудачи
      echo "<a href='index.php?op=experts'>Вернуться</a>";
      echo("<P> Ошибка при добавлении эксперта</P>");
      echo("<P> $query</P>");
      exit();
    }
  }  
  
}

if (empty($action)) 
{

  $tableheader = "class=tableheader";
  $showhide = "";
  $tableheader = "class=tableheaderhide";
?>
<form action=index.php?op=addexpert method=post>
<input type=hidden name=action value=post>
<p align='center'><table class=bodytable border="0" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
    <tr>
        <td witdh='400'><p class=ptd><b><em class=em>Фамилия Имя Отчество *</em></b></td>
        <td witdh='400'><input type=text name=fio size=35></td>
    </tr><tr>
        <td witdh='400'><p class=ptd><b><em class=em>Логин *</em></b></td>
        <td witdh='400'><input type=text name=login size=25></td>
    </tr><tr>
        <td witdh='400'><p class=ptd><b><em class=em>Пароль *</em></b></td>
        <td witdh='400'><input type=text name=pass size=25></td>
    </tr><tr>
        <td><p class=ptd>Электронная почта</p></td>
        <td><input type=text name=email size=25></td>
    </tr><tr>
        <td><p class=ptd>Место работы</p></td>
        <td><input type=text name=job size=25></td>
    </tr><tr>
        <td><p class=ptd>Тип эксперта</p></td>
        <td><select name='usertype'><option value='expert'>Эксперт</option><option value='mainexpert'>Главный эксперт</option></select>
    </tr><tr>
        <td colspan="3" witdh='400'>
            <input type="submit" value="Добавить">&nbsp;&nbsp;&nbsp;
            <input type="button" name="close" value="Назад" onclick="history.back()"> 
        </td>
    </tr>           
</table>
</form>

<?
  include "bottomadmin.php";
  // Выводим сообщение об ошибке
  if (!empty($error)) 
  {
    print "<P><font color=green>Во время добавления записи произошли следующие ошибки: </font></P>\n";
    print "<UL>\n";
    print $error;
    print "</UL>\n";
  }
}
?>

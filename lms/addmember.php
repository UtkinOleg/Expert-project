<?php
if(!defined("IN_ADMIN")) die;  
// Устанавливаем соединение с базой данных
include "config.php";

$error = "";
$action = "";
$title=$titlepage="Новый участник";

include "topadmin.php";

// Возвращаем значение переменной action, переданной в урле
$action = $_POST["action"];
// Если оно не пусто - добавляем сообщение в базу данных
if (!empty($action)) 
{
  // Проверяем совпадает ли идентификатор сессии с
  // переданным в форме - защита а авто-постинга
  // Проверяем сообщение на слишком длинные слова
  $lenmsg = strlen($msg);
  $templen = 0;
  $temp = strtok($msg, " ");
  if (strlen($msg)>60)
  {
    while ($templen < $lenmsg)
    { 
      if (strlen($temp)>60)
      {
        $action = ""; 
        $error = $error."<LI>Текст содержит слишком много символов без пробелов\n";
        break;
      }
      else
      {
        $templen = $templen + strlen($temp) + 1;
      }
      $temp = strtok(" ");            
    }       
  }
  
  // Проверяем правильность ввода информации в поля формы
  if (empty($_POST["name"])) 
  {
    $action = ""; 
    $error = $error."<LI>Вы не ввели Фамилию Имя Отчество\n";
  }
  if (empty($_POST["login"])) 
  {
    $action = ""; 
    $error = $error."<LI>Вы не ввели Логин\n";
  }
  if (empty($_POST["password"])) 
  {
    $action = ""; 
    $error = $error."<LI>Вы не ввели Пароль\n";
  }

    $name = $_POST["name"];
    $ou = $_POST["ou"];
    $email = $_POST["email"];
    $login = $_POST["login"];
    $password = md5($_POST["password"]);

    // Запрос к базе данных на добавление сообщения
    $query = "INSERT INTO users VALUES (0,
                                        '$login',
                                        '$password',
                                        '$name',
                                        'user',
                                        NOW(),
                                        '$email',
                                        0,'',NOW(),'$ou',0,'','','','','');";
    if(mysql_query($query))
    {


  $toemail = $email;
  $title = "Добро пожаловать в экспертную систему!";
      $body = "Здравствуйте ".$name."!\nДобро пожаловать в экспертную систему ".$site."!\n
      Для входа в систему: перейдите по адресу - ".$site.", введите логин - \n".$login."\nзатем пароль - ".$_POST["password"]."\n\n
      Вам присвоен статус УЧАСТНИКА. Статус УЧАСТНИКА позволяет создавать проекты. В дальнейшем все созданные проекты, после прохождения проверки, будут отправлены на экспертную оценку.\n
      Ваша задача - зайти на сайт и создать один или несколько проектов (к проекту можно прикреплять различные файлы).\n\nПо всем вопросам Вы можете обращаться к администратору экспертной системы (siberia-soft@yandex.ru).</a>";
  $fromid = USER_ID;

  require_once('lib/unicode.inc');

  $mimeheaders = array();
  $mimeheaders[] = 'Content-type: '. mime_header_encode('text/plain; charset=UTF-8; format=flowed; delsp=yes');
  $mimeheaders[] = 'Content-Transfer-Encoding: '. mime_header_encode('8Bit');
  $mimeheaders[] = 'X-Mailer: '. mime_header_encode('ExpertSystem');
  $mimeheaders[] = 'From: '. mime_header_encode(admin.' <'.$valmail.'>');

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
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?op=members'>\n";
      print "</HEAD></HTML>\n";
      exit();
    }
    else
    {
      // Выводим сообщение об ошибке в случае неудачи
      echo "<a href='index.php?op=members'>Вернуться</a>";
      echo("<P> Ошибка при добавлении участника</P>");
      echo("<P> $query</P>");
      exit();
    }
    
  
}

if (empty($action)) 
{
?>
<center><br>

<form action=index.php?op=addmember method=post>
<input type=hidden name=action value=post>
<table><tr valign="top"><td width="25%">&nbsp;</td><td>
<table border="0" align="left" cellpadding="6" cellspacing="0">
    <tr>
        <td width="250"><p class=ptd><b><em class=em>Фамилия Имя Отчество *</em></b></td>
    </tr>
    <tr>
        <td><input type=text name=name size=45></td>
    </tr>
    <tr>
        <td width="250"><p class=ptd><b><em class=em>Логин *</em></b></td>
    </tr>
    <tr>
        <td><input type=text name=login size=45></td>
    </tr>
    <tr>
        <td width="250"><p class=ptd><b><em class=em>Пароль *</em></b></td>
    </tr>
    <tr>
        <td><input type=text name=password size=45></td>
    </tr>
    <tr>
        <td><p class=ptd>Место работы</td>
    </tr>
    <tr>
        <td><input type=text name=ou size=45></td>
    </tr>
    <tr>
        <td><p class=ptd>E-mail</td>
    </tr>
    <tr>
        <td><input type=text name=email size=45></td>
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
</body>
</html>
<?php
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

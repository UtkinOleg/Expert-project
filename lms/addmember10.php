<?php

include "config.php";

$error = "";
$action = "";
$title=$titlepage="Новые участники";

include "topadmin.php";
include "func.php";

function addmember($name, $ou, $email)
{
    if ($name!="")
    {

    $tot = mysql_query("SELECT max(id) FROM users ORDER BY id;");
    $total = mysql_fetch_array($tot);
    $count = $total['max(id)']+1;

    $login = 'user'.$count;
    $password = generate_password(7);
    $mdpass = md5($password);

    // Запрос к базе данных на добавление сообщения
    $query = "INSERT INTO users VALUES (0,
                                        '$login',
                                        '$mdpass',
                                        '$name',
                                        'user',
                                        NOW(),
                                        '$email',
                                        0,'',NOW(),'$ou',0,'','','','');";
     if(!mysql_query($query))
     {
      echo "<a href='index.php?op=members'>Вернуться</a>";
      echo("<P> Ошибка при добавлении участника</P>");
      echo("<P> $query</P>");
      exit();
     } 
     else
     {
      echo "<p>Новый участник:</p>";
      echo "<p>".$name." - логин: ".$login.", пароль: ".$password."</p>";
      
      $toemail = $email;
      $title = "Добро пожаловать в экспертную систему!";
      $body = "Здравствуйте ".$name."!\nДобро пожаловать в экспертную систему ".$site."!\n
      Для входа в систему: перейдите по адресу - ".$site.", введите логин - \n".$login."\nзатем пароль - ".$password."\n\n
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
        )) puterror("Ошибка при отправке сообщения.");
       }     
     }

    }
}

if(!defined("IN_ADMIN")) die;  

$action = $_POST["action"];
// Если оно не пусто - добавляем сообщение в базу данных
if (!empty($action)) 
{
    $name1 = $_POST["name1"];
    $name2 = $_POST["name2"];
    $name3 = $_POST["name3"];
    $name4 = $_POST["name4"];
    $name5 = $_POST["name5"];
    $name6 = $_POST["name6"];
    $name7 = $_POST["name7"];
    $name8 = $_POST["name8"];
    $name9 = $_POST["name9"];
    $name10 = $_POST["name10"];
    
    $ou1 = $_POST["ou1"];
    $ou2 = $_POST["ou2"];
    $ou3 = $_POST["ou3"];
    $ou4 = $_POST["ou4"];
    $ou5 = $_POST["ou5"];
    $ou6 = $_POST["ou6"];
    $ou7 = $_POST["ou7"];
    $ou8 = $_POST["ou8"];
    $ou9 = $_POST["ou9"];
    $ou10 = $_POST["ou10"];

    $email1 = $_POST["email1"];
    $email2 = $_POST["email2"];
    $email3 = $_POST["email3"];
    $email4 = $_POST["email4"];
    $email5 = $_POST["email5"];
    $email6 = $_POST["email6"];
    $email7 = $_POST["email7"];
    $email8 = $_POST["email8"];
    $email9 = $_POST["email9"];
    $email10 = $_POST["email10"];

    addmember($name1,$ou1,$email1);
    addmember($name2,$ou2,$email2);
    addmember($name3,$ou3,$email3);
    addmember($name4,$ou4,$email4);
    addmember($name5,$ou5,$email5);
    addmember($name6,$ou6,$email6);
    addmember($name7,$ou7,$email7);
    addmember($name8,$ou8,$email8);
    addmember($name9,$ou9,$email9);
    addmember($name10,$ou10,$email10);


      echo "<form action=index.php?op=members method=post>";
      echo "<input type='submit' name='close' value='Вернуться к списку участников'></form>"; 

//    print "<HTML><HEAD>\n";
//    print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php?op=members'>\n";
//    print "</HEAD></HTML>\n";
    exit();
  
}

if (empty($action)) 
{
?>

<form action=index.php?op=addmember10 method=post>
<input type=hidden name=action value=post>
<table><tr valign="top"><td width="25%">&nbsp;</td><td>
<table border="0" align="left" cellpadding="6" cellspacing="0">
    <tr>
        <td width="200"><p class=ptd><b>ФИО №1:</b></td>
        <td><input type=text name=name1 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Место работы:</b></td>
        <td><input type=text name=ou1 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Email:</b></td>
        <td><input type=text name=email1 size=40</td>
        <td></td>
    </tr>
    <tr>
        <td width="200"><p class=ptd><b>ФИО №2:</b></td>
        <td><input type=text name=name2 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Место работы:</b></td>
        <td><input type=text name=ou2 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Email:</b></td>
        <td><input type=text name=email2 size=40</td>
        <td></td>
    </tr>
    <tr>
        <td width="200"><p class=ptd><b>ФИО №3:</b></td>
        <td><input type=text name=name3 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Место работы:</b></td>
        <td><input type=text name=ou3 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Email:</b></td>
        <td><input type=text name=email3 size=40</td>
        <td></td>
    </tr>
    <tr>
        <td width="200"><p class=ptd><b>ФИО №4:</b></td>
        <td><input type=text name=name4 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Место работы:</b></td>
        <td><input type=text name=ou4 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Email:</b></td>
        <td><input type=text name=email4 size=40</td>
        <td></td>
    </tr>
    <tr>
        <td width="200"><p class=ptd><b>ФИО №5:</b></td>
        <td><input type=text name=name5 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Место работы:</b></td>
        <td><input type=text name=ou5 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Email:</b></td>
        <td><input type=text name=email5 size=40</td>
        <td></td>
    </tr>
    <tr>
        <td width="200"><p class=ptd><b>ФИО №6:</b></td>
        <td><input type=text name=name6 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Место работы:</b></td>
        <td><input type=text name=ou6 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Email:</b></td>
        <td><input type=text name=email6 size=40</td>
        <td></td>
    </tr>
    <tr>
        <td width="200"><p class=ptd><b>ФИО №7:</b></td>
        <td><input type=text name=name7 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Место работы:</b></td>
        <td><input type=text name=ou7 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Email:</b></td>
        <td><input type=text name=email7 size=40</td>
        <td></td>
    </tr>
    <tr>
        <td width="200"><p class=ptd><b>ФИО №8:</b></td>
        <td><input type=text name=name8 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Место работы:</b></td>
        <td><input type=text name=ou8 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Email:</b></td>
        <td><input type=text name=email8 size=40</td>
        <td></td>
    </tr>
    <tr>
        <td width="200"><p class=ptd><b>ФИО №9:</b></td>
        <td><input type=text name=name9 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Место работы:</b></td>
        <td><input type=text name=ou9 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Email:</b></td>
        <td><input type=text name=email9 size=40</td>
        <td></td>
    </tr>
    <tr>
        <td width="200"><p class=ptd><b>ФИО №10:</b></td>
        <td><input type=text name=name10 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Место работы:</b></td>
        <td><input type=text name=ou10 size=40</td>
        <td></td>
        <td width="200"><p class=ptd><b>Email:</b></td>
        <td><input type=text name=email10 size=40</td>
        <td></td>
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

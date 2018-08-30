<?

require_once('lib/recaptchalib.php');

$error = "";
// Get a key from https://www.google.com/recaptcha/admin/create
$publickey = "6LfYu78SAAAAAEPsziEdDFvkV06loBUAwa44-JhI";
$privatekey = "6LfYu78SAAAAABVIuDTGQFe76fcXW3XGu-HqRrHp";

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;


if(!isset($_POST['ok'])) {
// если форма не заполнена, то выводим ее
  include "config.php";
  include "func.php";

  $title = "Восстановление пароля";
  $titlepage=$title;  
  include "topadmin.php";
  ?>
<script type='text/javascript'>
 var RecaptchaOptions = {
                lang : 'ru',     theme : 'white'
 };
</script>
  
<script type="text/javascript">
$(document).ready(function() {
 
    $('#submit').click(function() { 
 
        $(".iferror").hide();
        var hasError = false;
 
        var regVal2 = $("#email").val();
        if(regVal2 == '') {
            $("#email").after('<span class="iferror">Вы не ввели адрес электронной почты!</span>');
            hasError = true;
        }
 
        if(hasError == true) { return false; }
 
    });
});
</script>

  <form method='post' action='forgot'>
	<table width="100%" height="100%">
  <input type="hidden" name="action" value="post">
	<tr><td align=center>

  <?
  
	echo"
  <div id='menu_glide' class='menu_glide'>
	<table border='0' align='center' class='bodytable' border='0' cellpadding=2 cellspacing=2>
	<tr><td>
	<table>
	<tr><td><font face='Tahoma, Arial' size='-1'>Введите адрес Вашей электронной почты:</font></td>
  <td> "; ?>
  <input type="text" name="email" id="email" size="35">
  </td></tr>
	<?
  echo"</table></td></tr>
	<tr><td><font face='Tahoma, Arial' size='-1'>На этот адрес будет отправлено сообщение со ссылкой на изменение пароля.</font></td><tr><td>";
  echo recaptcha_get_html($publickey,$error); 
	
  echo "</td></tr><tr><td align=center><input id='submit' type='submit' name='ok'
        value='Отправить'>&nbsp;<input type='button' name='close' value='Отмена' onclick='history.back()'></td></tr>
	</table></div></td></tr></table>
	</form>";
  include "bottomadmin.php";
}
else
{	
  include "config.php";
  include "func.php";

  $email = strtolower(trim($_POST["email"]));
  
  if (empty($_POST["email"])) 
  {
    $action = ""; 
    $error1 = $error1."<LI>Вы не ввели адрес электронной почты\n";
  }

  $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

  if (!$resp->is_valid) {
                   $error1 = $error1."<li>Неверно введен код CAPTCHA.".$resp->error;
  }

  if (!empty($error1)) 
  {
  $title = "Восстановление пароля";
  $titlepage=$title;  
  include "topadmin.php";
	echo"
	<p align=center>
  <div id='menu_glide' class='menu_glide'>
	<table border='0' align='center' class='bodytable' border='0' cellpadding=2 cellspacing=2>
	<tr><td>";
  echo"<ul><font face='Tahoma, Arial' size='-1'>".$error1."</font></ul></td></tr>";
  echo"<tr><td align='center'><input type='button' name='close' value='Назад' onclick='history.back()'>
  </td></tr></table></div></p>"; 
  include "bottomadmin.php";

  exit();
  }
  
  
  $query = mysql_query("SELECT * FROM users WHERE email='".$email."'");
  if($query)
   {
      $user = mysql_fetch_array($query);
      
      $title = "Воосстановление пароля";
      
      $ls = ceil(strlen($user['password'])/3);
      $s1 = substr($user['password'], 0, $ls);
      $s2 = substr($user['password'], $ls, $ls);
      $s3 = substr($user['password'], $ls+$ls, $ls);

      $cs = md5($user['email']);
      $ls = ceil(strlen($cs)/3);
      $e1 = substr($cs, 0, $ls);
      $e2 = substr($cs, $ls, $ls);
      $e3 = substr($cs, $ls+$ls, $ls);

      writelog("Запрос на изменение пароля для ".$user['userfio']." (".$email.").");

      $body = "Здравствуйте, ".$user['userfio']."!\nВы запросили изменение пароля в Экспертной системе оценки проектов.\n
      Для изменения пароля перейдите по адресу - ".$site."/?ps1=".$s1."&ps2=".$s2."&ps3=".$s3."&e1=".$e1."&e2=".$e2."&e3=".$e3."
      \nС уважением, Экспертная система оценки проектов.";

      /*Ваш логин в системе - ".$user["username"].".\n*/
      
      require_once('lib/unicode.inc');
      
      $mimeheaders = array();
      $mimeheaders[] = 'Content-type: '. mime_header_encode('text/plain; charset=UTF-8; format=flowed; delsp=yes');
      $mimeheaders[] = 'Content-Transfer-Encoding: '. mime_header_encode('8Bit');
      $mimeheaders[] = 'X-Mailer: '. mime_header_encode('ExpertSystem');
      $mimeheaders[] = 'From: '. mime_header_encode('info@expert03.ru <info@expert03.ru>');

      if (!empty($email))
      {
      if (!mail(
      $email,
      mime_header_encode($title),
      str_replace("\r", '', $body),
      join("\n", $mimeheaders)
      )) puterror("Ошибка при отправке сообщения.");
      
      }
      
     // $to = $email;      
     // $subject = $title; 
     // $message = $body;             
     // $headers = "From: observer@expert03.ru\r\n" . 
     //   'X-Mailer: PHP/' . phpversion() . "\r\n" . 
     //   "MIME-Version: 1.0\r\n" . 
     //   "Content-Type: text/html; charset=utf-8\r\n" . 
     //   "Content-Transfer-Encoding: 8bit\r\n\r\n"; 
     // mail($to, $subject, $message, $headers); 

      
     // Возвращаемся на страницу логина если всё прошло удачно
      include "topadmin.php";
    	echo"
    	<p align=center>
      <div id='menu_glide' class='menu_glide'>
    	<table border='0' align='center' class='bodytable' border='0' cellpadding=2 cellspacing=2>
    	<tr><td>";
      echo"<font face='Tahoma, Arial' size='-1'>На Ваш электронный адрес выслано письмо со ссылкой на изменение пароля.</font></td></tr>";
      echo"<tr><td></td></tr><tr><td align='center'><form method='POST' action='news'>
      <input type='submit' name='close' value='Продолжить'></form></td></tr></table></div></p>"; 
      include "bottomadmin.php";
      exit();
      
	 } else puterror("Ошибка при обращении к базе данных");
	
  mysql_close();
}


?>
<?
session_start(); //инициализирум механизм сесссий

include "config.php";
require_once 'lib/LoginzaAPI.class.php';
require_once 'lib/LoginzaUserProfile.class.php';
require_once('func.php');
$LoginzaAPI = new LoginzaAPI();

  // Проверим на авторизацию чере соцсети
 if (!empty($_POST['token'])) {
	// получаем профиль авторизованного пользователя
	$UserProfile = $LoginzaAPI->getAuthInfo($_POST['token']);
	

	// проверка на ошибки
	if (!empty($UserProfile->error_type)) {
		// есть ошибки, выводим их
		// в рабочем примере данные ошибки не следует выводить пользователю, так как они несут информационный характер только для разработчика
		//echo $UserProfile->error_type.": ".$UserProfile->error_message;
 		$error1 = $error1."<li>Временная ошибка</li>";
	
  } elseif (empty($UserProfile)) {
		// прочие ошибки
		$error1 = $error1."<li>Временная ошибка</li>";
    
	} else {
  
     // объект генерации недостаюих полей (если требуется)
   	 $LoginzaProfile = new LoginzaUserProfile($UserProfile);
     $login = $LoginzaProfile->genNickname();
     $password = generate_password(7);
     $cpassword = md5($password);
     $fio = $LoginzaProfile->genFullName();
     $email1 = $UserProfile->email;
     $socialid = $UserProfile->identity;
     $socialpage = $LoginzaProfile->genUserSite();
     $provider = $UserProfile->provider;
     $dob = date('Y-m-d', strtotime($UserProfile->dob));
     $avatar = $UserProfile->photo;

  	 $res2 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM users WHERE social_id='".$socialid."'");
  	 if(mysqli_num_rows($res2)==0)
     {	
     //такого пользователя нет - запишем нового
     mysqli_query($mysqli,"START TRANSACTION;");
     $query = "INSERT INTO users VALUES (0,
                                        '$login',
                                        '$cpassword',
                                        '$fio',
                                        'user',
                                        NOW(),
                                        '$email1',
                                        0,
                                        '',
                                        NOW(),
                                        '',
                                        0,
                                        '',
                                        '$avatar',
                                        '',
                                        '',
                                        '',
                                        'offline',
                                        0,
                                        '',
                                        '',
                                        0,0,0,0,0,0,0,
                                        '$socialid','$socialpage','male','{$dob}','$provider',0);";
      if(mysqli_query($mysqli, $query))
      {
       mysqli_query($mysqli,"COMMIT");
       
       writelog("Зарегистрирован новый пользователь (сеть) ".$login." (".$fio.").");    		
        
       $toemail = $valmail;
       $title = "Зарегистрирован новый пользователь";
       $body = "Зарегистрирован новый пользователь - ФИО: ".$fio."\n
логин - ".$login."\n
пароль - ".$password."\n
id соц сети - ".$socialid."\n
страница - ".$socialpage."\n
email - ".$email1."\n";

       require_once('lib/unicode.inc');

       $mimeheaders = array();
       $mimeheaders[] = 'Content-type: '. mime_header_encode('text/plain; charset=UTF-8; format=flowed; delsp=yes');
       $mimeheaders[] = 'Content-Transfer-Encoding: '. mime_header_encode('8Bit');
       $mimeheaders[] = 'X-Mailer: '. mime_header_encode('ExpertSystem');
       $mimeheaders[] = 'From: '. mime_header_encode(admin.' <'.$valmail2.'>');

       mail($toemail,
       mime_header_encode($title),
       str_replace("\r", '', $body),
       join("\n", $mimeheaders));  

       if (!empty($email1))
       {

       }
             
       $_SESSION['login'] = $login;	//устанавливаем login & pass
 	     $_SESSION['pass'] = $cpassword;
       $token = $_POST['token'];
       setcookie('token', $token, time() + 60 * 60 * 24 * 14);
       mysqli_query($mysqli,"START TRANSACTION;");
       mysqli_query($mysqli,"UPDATE users SET token='$token' WHERE username='".$login."'AND password='".$cpassword."'");
       mysqli_query($mysqli,"COMMIT;");

       if ($enable_cache) update_cache('SELECT id,userfio,email,usertype FROM users ORDER BY userfio');

       // Проверка пользователя на участие в проектах и экспертизах
       check_user_in_system($mysqli, $email1);

    	 Header("Location: projects");
       
      } 
      else
      {
       mysqli_query($mysqli,"COMMIT");
    	 Header("Location: news");	
      }
    }
    else
    {
      $res22 = mysqli_fetch_array($res2);
      writelog("Вход в систему ".$res22['username']);
    	$_SESSION['login'] = $res22['username'];	//устанавливаем login & pass
 	    $_SESSION['pass'] = $res22['password'];
      $token = $_POST['token'];
      setcookie('token', $token, time() + 60 * 60 * 24 * 14);
      mysqli_query($mysqli,"UPDATE users SET token='$token' WHERE username='".$res22['username']."'AND password='".$res22['password']."'");
    	
      // Проверка пользователя на участие в проектах и экспертизах
      check_user_in_system($mysqli, $res22['email']);
      
      Header("Location: projects");	
    }
          
	}       
 }  
 else


if(!isset($_POST['ok'])) {
 // если форма не заполнена, то выводим ошибку
 echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>";
 echo '<script language="javascript">';
 echo 'alert("Для входа в систему используйте ВХОД");';
 echo "</script><META HTTP-EQUIV='Refresh' CONTENT='0; URL=news'></head></html>";
}
else
{	

	//проверяем есть ли пользователь с таким login'ом и password'ом
	$res = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT id, username, email FROM users WHERE username='".$_POST['login']."' AND password='".md5($_POST['pass'])."' LIMIT 1;");
  $e = strtolower(trim($_POST['login']));
	$res2 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT id, username, email FROM users WHERE email='".$e."' AND password='".md5($_POST['pass'])."' LIMIT 1;");

	if((mysqli_num_rows($res)==0) and (mysqli_num_rows($res2)==0)){	//такого пользователя нет

   echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>";
   echo '<script language="javascript">';
   echo 'alert("Для входа в систему используйте ВХОД");';
   echo "</script><META HTTP-EQUIV='Refresh' CONTENT='0; URL=news'></head></html>";
   
   writelog("Логин (email) или пароль неверные для ".$_POST['login']." : ".$_POST['pass'].".");

	}
	else 
  
  // Стандартный путь авторизации
  // ______________________________
	if(mysqli_num_rows($res)!=0) {	// есть login
  $res11 = mysqli_fetch_array($res);
	
  $token = md5(time().$_POST['login']);
  if ($_POST['saveme']) {
    setcookie('token', $token, time() + 60 * 60 * 24 * 14);
    mysqli_query($mysqli,"UPDATE users SET token='$token' WHERE username='".$_POST['login']."'AND password='".md5($_POST['pass'])."'");
  }
  // mysql_query("UPDATE users SET status='online' WHERE username='".$_POST['login']."'AND password='".md5($_POST['pass'])."'");
  
    writelog("Вход в систему ".$_POST['login']);
		$_SESSION['login'] = $_POST['login'];	//устанавливаем login & pass
		$_SESSION['pass'] = md5($_POST['pass']);
    
    // Проверка пользователя на участие в проектах и экспертизах
    check_user_in_system($mysqli, $res11['email']);
     
		Header("Location: projects");	// перенаправляем на index.php страница welcome

	} else
	if(mysqli_num_rows($res2)!=0) {	//есть email
  $res22 = mysqli_fetch_array($res2);
  
  $token = md5(time().$res22['username']);
  if ($_POST['saveme']) {
    setcookie('token', $token, time() + 60 * 60 * 24 * 14);
    mysqli_query($mysqli,"UPDATE users SET token='$token' WHERE username='".$res22['username']."'AND password='".md5($_POST['pass'])."'");
  }
  // mysql_query("UPDATE users SET status='online' WHERE username='".$res22['username']."'AND password='".md5($_POST['pass'])."'");
  
    writelog("Вход в систему ".$res22['username']);
		$_SESSION['login'] = $res22['username'];	//устанавливаем login & pass
		$_SESSION['pass'] = md5($_POST['pass']);

    // Проверка пользователя на участие в проектах и экспертизах
    check_user_in_system($mysqli, $res22['email']);

		Header("Location: projects");	// перенаправляем на index.php страница welcome
  
  }
//	mysql_close();
}
?>
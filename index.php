<?php 
          
  session_start(); //инициализирум механизм сесссий

  ini_set('display_errors', 0);
  error_reporting(); // E_ALL

  //начинаем проверку логина и пароля
  $dblocation1 = "localhost";      
  $dbname1 = "";
  $dbuser1 = "";
  $dbpasswd1 = "";
  $mysqlic = mysqli_connect($dblocation1,$dbuser1,$dbpasswd1,$dbname1);
  if (!$mysqlic) {
  ?>
   "<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head><body>
<P>В настоящий момент сервер базы данных не доступен.</P></body></html>
  <?
    exit();
  }

 $usernames = filter_var($_SESSION['login'], FILTER_SANITIZE_STRING);
 $passwords = filter_var($_SESSION['pass'], FILTER_SANITIZE_STRING);
 
 $usernames = mysqli_real_escape_string($mysqlic,$usernames);
 $passwords = mysqli_real_escape_string($mysqlic,$passwords);

 if (isset($_COOKIE['token']))
 {
  $token = htmlspecialchars($_COOKIE['token']); // на всякий сл.
  $res = mysqli_query($mysqlic,sprintf("SELECT * FROM users WHERE token='%s' LIMIT 1;",$token));
 }
 else
  $res = mysqli_query($mysqlic,sprintf("SELECT * FROM users WHERE username='%s' AND password='%s' LIMIT 1;",$usernames,$passwords));
 $r = mysqli_fetch_array($res);

 $pass1 = $_GET['restoreme'];

if (empty($r['id']))
{	
   //такого пользователя нет
   setcookie('token', '');

if ($pass1!="")
{
  $pass1 = filter_var($pass1, FILTER_SANITIZE_STRING);
  $pass1 = mysqli_real_escape_string($mysqlic,$pass1);
  $query = mysqli_query($mysqlic,sprintf("SELECT * FROM users WHERE md5(email)='%s' LIMIT 1;",$pass1));
  if($query) {
     $f=0;
     $user = mysqli_fetch_array($query);
      if (md5($user['email'])==$pass1) {
       	$f=1;
        $token = md5(time().$user['username']);
        setcookie('token', $token, time() + 60 * 60 * 24 * 14);
        mysqli_query($mysqlic,"UPDATE users SET token='$token' WHERE id='".$user['id']."'");
        mysqli_query($mysqlic,"COMMIT");
        Header("Location: lms/changepass.php?token=".$token);	//перенаправляем на изменение пароля	
     }
     if ($f==0)
   	  Header("Location: bootstrap.php");	//перенаправляем на news
   } else 
   	  Header("Location: bootstrap.php");	//перенаправляем на news
  exit();
}

 $op = $_GET['op']; 
 mysqli_close($mysqlic);

 // Выбираем нужное нам действие 
 switch ($op) 
       { 
       // case 'login' : {include "lms/login.php"; break;} 
        default :  include "bootstrap.php"; // default page 
       }
}
else
{	
  
  $_SESSION['login']=$r['login'];	//устанавливаем login & pass
  $_SESSION['pass']=$r['pass'];
  
  //пользователь найден, можем выводить все что нам надо
  if ($r['usertype'] == 'admin') {
   define("IN_ADMIN", TRUE); 
   define("IN_SUPERVISOR", TRUE); 
   define("IN_EXPERT", TRUE); 
   define("IN_USER", TRUE); 
   define("USER_STATUS", "администратор"); 
   define("LOWSUPERVISOR", FALSE);
  }
  else
  if ($r['usertype'] == 'supervisor') {
   define("IN_SUPERVISOR", TRUE); 
   define("IN_EXPERT", TRUE); 
   define("IN_USER", TRUE); 
   if ($r['qcount'] > 0) 
   {
    define("LOWSUPERVISOR", TRUE);
    define("USER_STATUS", "супервизор"); 
   } 
   else
   {
    define("USER_STATUS", "супервизор"); 
    define("LOWSUPERVISOR", FALSE);
   } 
  }
  else
  if ($r['usertype'] == 'expert') { 
   define("IN_EXPERT", TRUE); 
   define("IN_USER", TRUE); 
   define("USER_STATUS", "эксперт"); 
  } 
  else
  if ($r['usertype'] == 'user') {
   define("IN_USER", TRUE); 
   define("USER_STATUS", "участник"); 
  }

  if ($r['passchanged'] == 0) define("PASS_NOT_CHANGED", TRUE);

  define("USER_FIO", $r['userfio']); 
  define("USER_ID", $r['id']); 
  define("USER_EMAIL", $r['email']); 

  if(defined("IN_USER") or defined("IN_ADMIN") or defined("IN_EXPERT") or defined("IN_SUPERVISOR")) { 
   define("USER_REGISTERED", TRUE); 
  } 

 define("USER_PICT", $r['photoname']); 
 define("HELP_ENABLED", FALSE); 

 mysqli_close($mysqlic);
 // Получаем параметр op из URL 
 $op = $_GET['op']; 

 // Выбираем нужное нам действие 
 switch ($op) 
 { 
        case 'logout' : {include "lms/logout.php"; break;} 
        case 'selector' : {include "lms/selector.php"; break;} 

        case 'report2' : {include "lms/report2-3.php"; break;} 
        case 'report2w' : {include "lms/report2w.php"; break;} 
        case 'report2-2' : {include "lms/report2-2.php"; break;} 
        case 'report2-3' : {include "lms/report2-3.php"; break;} 
        case 'report3' : {include "lms/report3.php"; break;} 
        case 'report4' : {include "lms/report4.php"; break;} 
        case 'stat' : {include "lms/stat.php"; break;} 
        case 'chpass' : {include "lms/changepass.php"; break;} 
        case 'forgot' : {include "lms/forgot.php"; break;} 

        case 'listsall' : {include "lms/listsall.php"; break;} 
        case 'print' : {include "lms/print.php"; break;} 
        case 'lists' : {include "lms/lists.php"; break;} 
        case 'addlist' : {include "lms/addlist.php"; break;} 
        case 'dellist' : {include "lms/dellist.php"; break;} 
        case 'viewlist' : {include "lms/viewlist.php"; break;} 

        case 'params' : {include "lms/params.php"; break;} 

        case 'members' : {include "lms/main.php"; break;} 
        case 'addmember' : {include "lms/addmember.php"; break;} 
        case 'addmember10' : {include "lms/addmember10.php"; break;} 
        case 'delmember' : {include "lms/delpost.php"; break;} 

        case 'experts' : {include "lms/experts.php"; break;} 
        case 'addexpert' : {include "lms/addexpert.php"; break;} 
        case 'delexpert' : {include "lms/delexpert.php"; break;} 

        case 'edituser' : {include "lms/edituser.php"; break;} 

        case 'addkgroup' : {include "lms/addkgroup.php"; break;} 
        case 'delkgroup' : {include "lms/delkgroup.php"; break;} 
        case 'editkgroup' : {include "lms/editkgroup.php"; break;} 

        case 'addexgroup' : {include "lms/addexgroup.php"; break;} 
        case 'delexgroup' : {include "lms/delexgroup.php"; break;} 
        case 'editexgroup' : {include "lms/editexgroup.php"; break;} 

        case 'addmulti' : {include "lms/addmulti.php"; break;} 
        case 'delmulti' : {include "lms/delmulti.php"; break;} 
        case 'editmulti' : {include "lms/editmulti.php"; break;} 
        
        // Блок тестирования
        case 'testoptions' : {include "lms/testoptions.php"; break;} 
        case 'addtest' : {include "lms/addtest.php"; break;} 
        case 'deltest' : {include "lms/deltest.php"; break;} 
        case 'edittest' : {include "lms/edittest.php"; break;} 
        case 'questgroups' : {include "lms/questgroups.php"; break;} 
        case 'addquestgroup' : {include "lms/addquestgroup.php"; break;} 
        case 'delquestgroup' : {include "lms/delquestgroup.php"; break;} 
        case 'editquestgroup' : {include "lms/editquestgroup.php"; break;} 
        case 'addquestfromfile' : {include "lms/addquestfromfile.php"; break;} 
        case 'listquestions' : {include "lms/listquestions.php"; break;} 
        case 'addgrouptotest' : {include "lms/addgrouptotest.php"; break;} 
        case 'deltestdata' : {include "lms/deltestdata.php"; break;} 
        case 'editgroupintest' : {include "lms/editgroupintest.php"; break;} 
        case 'viewtest' : {include "lms/viewtest.php"; break;} 
        case 'testresults': {include "lms/results.php"; break;}
        case 'delquestions' : {include "lms/delquestions.php"; break;} 
        case 'viewtestresults': {include "lms/testresults.php"; break;}
        case 'deltestresult' : {include "lms/deltestresult.php"; break;} 
        case 'searchresult' : {include "lms/searchresult.php"; break;} 
        case 'resultprotocol' : {include "lms/testprotocol.php"; break;} 
        case 'createtest' : {include "lms/createtest.php"; break;} 
        case 'createshablon' : {include "lms/createshablon.php"; break;} 
        case 'deltestquestgroup' : {include "lms/deltestquestgroup.php"; break;} 

        case 'shablons' : {include "lms/shablons.php"; break;} 
        case 'addshablon' : {include "lms/addshablon.php"; break;} 
        case 'delshablon' : {include "lms/delshablon.php"; break;} 
        case 'delallshablon' : {include "lms/delallshablon.php"; break;} 
        case 'editshablon' : {include "lms/editshablon.php"; break;} 
        case 'viewshablon' : {include "lms/viewshablon.php"; break;} 

        case 'admshab' : {include "lms/admshab.php"; break;} 
        case 'delshab' : {include "lms/delshab.php"; break;} 
        case 'editshab' : {include "lms/editshab.php"; break;} 

        case 'admlimit' : {include "lms/admlimit.php"; break;} 
        case 'dellimit' : {include "lms/dellimit.php"; break;} 
        case 'editlimit' : {include "lms/editlimit.php"; break;} 
        
        // Комплексный критерий
        case 'edcomplex' : {include "lms/edcomplex.php"; break;} 
        case 'addcomp' : {include "lms/addcomp.php"; break;} 
        case 'editcomp' : {include "lms/editcomp.php"; break;} 
        case 'delcomp' : {include "lms/delcomp.php"; break;} 

        case 'grades' : {include "lms/grades.php"; break;} 
        case 'addgrade' : {include "lms/addgrade.php"; break;} 
        case 'delgrade' : {include "lms/delgrade.php"; break;} 
        case 'editgrade' : {include "lms/editgrade.php"; break;} 

        case 'poptions' : {include "lms/poptions.php"; break;} 
        case 'addpoptionfromfile' : {include "lms/addpoptionfromfile.php"; break;} 
        case 'addpoption2' : {include "lms/addpoption2.php"; break;} 
        case 'delpoption' : {include "lms/delpoption.php"; break;} 
        case 'delpoptions' : {include "lms/delpoptions.php"; break;} 
        case 'editpoption' : {include "lms/editpoption.php"; break;} 

        case 'pindicator' : {include "lms/pindicator.php"; break;} 
        case 'addindicator' : {include "lms/addindicator.php"; break;} 
        case 'delindicator' : {include "lms/delindicator.php"; break;} 
        case 'editindicator' : {include "lms/editindicator.php"; break;} 

        case 'logout' : {include "lms/logout.php"; break;} 
        case 'welcome' : {include "lms/welcome.php"; break;} 
        case 'msg' : {include "lms/msg.php"; break;} 
        case 'msgs' : {include "lms/msgs.php"; break;} 
        case 'viewmsg' : {include "lms/viewmsg.php"; break;} 
        case 'logs' : {include "lms/logs.php"; break;} 
        case 'dellog' : {include "lms/dellog.php"; break;} 
        case 'logmsgs' : {include "lms/logmsgs.php"; break;} 

        case 'projects' : {include "lms/projects.php"; break;} 
        case 'projects2' : {include "lms/projects2.php"; break;} 
        case 'addproject' : {include "lms/addproject.php"; break;} 
        case 'viewproject3' : {include "lms/viewproject3.php"; break;} 
        case 'viewlink3' : {include "lms/viewlink3.php"; break;} 
        case 'viewlist3' : {include "lms/viewlist3.php"; break;} 
        case 'editproject' : {include "lms/editproject.php"; break;} 
        case 'editproject2' : {include "lms/editproject2.php"; break;} 
        case 'delproject' : {include "lms/delproject.php"; break;} 
        case 'chsproject' : {include "lms/chsproject.php"; break;} 
        case 'chsproject2' : {include "lms/chsproject2.php"; break;} 
        case 'viewcomment3' : {include "lms/viewcomment3.php"; break;} 

        case 'parray' : {include "lms/projectarray.php"; break;} 
        case 'addproarr' : {include "lms/addproarr.php"; break;} 
        case 'editproarr' : {include "lms/editproarr.php"; break;} 
        case 'delproarr' : {include "lms/delproarr.php"; break;} 

        case 'pay' : {include "lms/payment.php"; break;} 
        case 'bank' : {include "lms/bank.php"; break;} 

        case 'proexperts' : {include "lms/proexperts.php"; break;} 
        case 'addsvproexpert' : {include "lms/addsvproexpert.php"; break;} 
        case 'addproexpert' : {include "lms/addproexpert.php"; break;} 
        case 'delproexpert' : {include "lms/delproexpert.php"; break;} 

        case 'prousers' : {include "lms/prousers.php"; break;} 
        case 'addprouser' : {include "lms/addprouser.php"; break;} 
        case 'delprouser' : {include "lms/delprouser.php"; break;} 

        case 'oplata' : {include "lms/oplata.php"; break;} 
        case 'addoplata' : {include "lms/addoplata.php"; break;} 
        case 'deloplata' : {include "lms/deloplata.php"; break;} 

        case 'userprojects' : {include "lms/userprojects.php"; break;} 
        case 'userkeys' : {include "lms/userkeys.php"; break;} 
        case 'addcomment' : {include "lms/addcomment.php"; break;} 
        case 'inst1' : {include "lms/inst1.php"; break;} 
        case 'newprojects' : {include "lms/newprojects.php"; break;} 

        case 'statistic' : {include "lms/static.php"; break;} 
        case 'public' : {include "lms/public.php"; break;} 
        case 'tops' : {include "lms/top2.php"; break;} 
        case 'inet' : {include "lms/inet.php"; break;} 
        case 'dovote' : {include "lms/dovote.php"; break;} 
        case 'votes' : {include "lms/votes.php"; break;} 
        case 'voteres' : {include "lms/voteres.php"; break;} 
        case 'help' : {include "lms/help.php"; break;} 
        case 'helpcontent' : {include "lms/help.php"; break;} 
        case 'helpabout' : {include "lms/help.php"; break;} 

        case 'newses' : {include "lms/newses.php"; break;} 
        case 'addnews' : {include "lms/addnews.php"; break;} 
        case 'delnews' : {include "lms/delnews.php"; break;} 
        case 'editnews' : {include "lms/editnews.php"; break;} 
        case 'moderatornewsjson' : {include "lms/moderatornewsjson.php"; break;}

        case 'knows' : {include "lms/knows.php"; break;} 
        case 'delknows' : {include "lms/delknows.php"; break;} 
        case 'editknows' : {include "lms/editknows.php"; break;} 

        case 'iwant' : {include "lms/iwant.php"; break;} 
        case 'query' : {include "lms/query.php"; break;} 
        case 'page' : {include "lms/pageview.php"; break;} 
        case 'page_fb' : {include "lms/page_fb.php"; break;} 
        case 'viewuser' : {include "lms/viewuser.php"; break;} 
        case 'usermsgs' : {include "lms/usermsgs.php"; break;} 
        case 'getmsgjson' : {include "lms/getmsgjson.php"; break;}
        case 'getansjson' : {include "lms/getansjson.php"; break;}
        case 'pdfprotocol' : {include "lms/pdfprotocol.php"; break;}
        case 'opened' : {include "lms/opened.php"; break;} 

        case 'yamoney' : {include "lms/yamoney.php"; break;} 
        case 'robokassa' : {include "lms/robokassa.php"; break;} 
        case 'robokassa2' : {include "lms/robokassa2.php"; break;} 
        case 'robokassa3' : {include "lms/robokassa3.php"; break;}  
        case 'robokassa4' : {include "lms/robokassa4.php"; break;} 

        default :  include "lms/projects.php"; 
 
}
}
mysql_close();
mysqli_close($mysqli);
?> 
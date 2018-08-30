<?
include "config.php";

// mysql_query("UPDATE users SET status='offline' WHERE userid='".USER_ID."'");

// session_start();	//инициализируем механизм сессий
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_unset();
session_destroy();	//удаляем текущую сессию
$_SESSION = array();
setcookie('token', '');

define("USER_REGISTERED", FALSE); 
define("IN_ADMIN", FALSE); 
define("IN_SUPERVISOR", FALSE); 
define("IN_EXPERT", FALSE); 
define("IN_USER", FALSE); 
define("USER_STATUS", ""); 
define("USER_FIO", ""); 
define("USER_ID", ""); 
define("USER_EMAIL", ""); 


Header("Location: ".$site);	
?>
   

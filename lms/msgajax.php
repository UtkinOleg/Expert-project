<?
  include "config.php";

  $body = $_POST["body"];
  $email = $_POST["email"];   
  $email = strtolower(trim($email));
  $fio = $_POST['name'];

  $title = $_POST["title"];
  if (empty($title))
   $title = "Cообщение";

  require_once('lib/unicode.inc');
  $mimeheaders = array();
  $mimeheaders[] = 'Content-type: '. mime_header_encode('text/plain; charset=UTF-8; format=flowed; delsp=yes');
  $mimeheaders[] = 'Content-Transfer-Encoding: '. mime_header_encode('8Bit');
  $mimeheaders[] = 'X-Mailer: '. mime_header_encode('ExpertSystem');
  $mimeheaders[] = 'From: '. mime_header_encode('info@expert03.ru');
  $json['ok'] = '0';

 if (!empty($email))
 {
  if (mail($valmail2,
      mime_header_encode($title),
      str_replace("\r", '', $fio.' ('.$email.') сообщает: '.$body),
      join("\n", $mimeheaders))) 
    $json['ok'] = '1';
 } 
 echo json_encode($json);  
  
?>
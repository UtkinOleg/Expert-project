<?
include "config.php";
include "func.php";
// регистрационная информация (пароль #1)
// registration info (password #1)
$mrh_pass1 = "jgkfnf1";

// чтение параметров
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];

$tm=getdate(time()+9*3600);
$date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";


$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass1:Shp_item=$shp_item"));

// проверка корректности подписи
// check signature
if ($my_crc != $crc)
{
  echo "bad sign\n";
  exit();
}

if ($shp_item==0)

// новый конкурс

{

}
else
{

// признак успешно проведенной операции

      // Проверим на оплату
      $sum1 = mysql_query("SELECT * FROM money WHERE proarrid='".$shp_item."'");
      if (!$sum1) puterror("Ошибка при обращении к базе данных");
      $usumma = 0; 
      while ($s1 = mysql_fetch_array($sum1))
      {
       $order1 = mysql_query("SELECT * FROM orders WHERE id='".$inv_id."' LIMIT 1");
       if (!$order1) puterror("Ошибка при обращении к базе данных");
       $o1 = mysql_fetch_array($order1);
       if ($o1['userid']==USER_ID) 
        $usumma += $s1['summa'];
      }

      if ($usumma < $out_summ)
      {
         if(!mysql_query("UPDATE orders SET paid = 1 WHERE id='".$inv_id."'")) {
          puterror("Ошибка 1");
         }
      
        mysql_query("LOCK TABLES money WRITE");
        mysql_query("SET AUTOCOMMIT = 0");
        $query = "INSERT INTO money VALUES (0,
                                        $inv_id,
                                        $shp_item,
                                        $out_summ, 
                                        '$date');";
        if (!mysql_query($query)) 
        {
         echo "bad query\n";
         exit();
        }
        mysql_query("COMMIT");
        mysql_query("UNLOCK TABLES");
       }
    
       $order1 = mysql_query("SELECT userid FROM orders WHERE id='".$inv_id."' LIMIT 1");
       if (!$order1) puterror("Ошибка при обращении к базе данных");
       $o1 = mysql_fetch_array($order1);
       if ($o1['userid']==USER_ID) 
       {
       $gst3 = mysql_query("SELECT * FROM users WHERE id='".$o1['userid']."' LIMIT 1");
       if (!$gst3) puterror("Ошибка при обращении к базе данных");
       $user = mysql_fetch_array($gst3);

       $toemail = $user['email'];
       if (!empty($toemail))
       {
        $pa = mysql_query("SELECT name FROM projectarray WHERE id='".$shp_item."' LIMIT 1");
        if (!$pa) puterror("Ошибка при обращении к базе данных");
        $pa1 = mysql_fetch_array($pa);


        $title = "Оплата услуги по размещению проекта на сайте expert03.ru";

        $body = msghead($user['userfio'], $site);
        $body.='<p>Вами произведена оплата в размере '.$sum.' руб. за размещение проекта <strong>'.$pa1['name'].'</strong></p>';
        $body.='<p>В личном кабинете можно начинать создание проекта. В зависимости от настроек системы, Вам может быть предложено пройти входное тестирование перед созданием проекта.</p>';
        $body.= msgtail($site);

        require_once('lib/unicode.inc');
  
        $mimeheaders = array();
        $mimeheaders[] = 'Content-type: '. mime_header_encode('text/html; charset=UTF-8; format=flowed; delsp=yes');
        $mimeheaders[] = 'Content-Transfer-Encoding: '. mime_header_encode('8Bit');
        $mimeheaders[] = 'X-Mailer: '. mime_header_encode('ExpertSystem');
        $mimeheaders[] = 'From: '. mime_header_encode('info@expert03.ru <info@expert03.ru>');


        if (!empty($toemail))
        {
         mail($toemail,
         mime_header_encode($title),
         str_replace("\r", '', $body),
         join("\n", $mimeheaders));
        }     
       }
      } 
 }     
      print "<HTML><HEAD>\n";
      print "<META HTTP-EQUIV='Refresh' CONTENT='0; URL=newprojects'>\n";
      print "</HEAD></HTML>\n";
       

?>



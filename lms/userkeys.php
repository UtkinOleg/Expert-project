<?php
if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) {  
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";

  $title=$titlepage="Секретные ключи участников проекта";
  $helppage='';

  $paid = $_GET["paid"];

  $gst3 = mysql_query("SELECT ownerid FROM projectarray WHERE id='".$paid."'");
  if (!$gst3) puterror("Ошибка при обращении к базе данных");
  $projectarray = mysql_fetch_array($gst3);

   if ((defined("IN_SUPERVISOR") and $projectarray['ownerid'] == USER_ID) or defined("IN_ADMIN")) 
   {
  // Выводим шапку страницы
  include "topadmin.php";

  $gst = mysql_query("SELECT * FROM requests WHERE proarrid='".$paid."' ORDER BY userid");
   
  if (!$gst) puterror("Ошибка при обращении к базе данных");
  $tableheader = "class=tableheaderhide";
    ?>
     <div id='menu_glide' class='menu_glide'>
      <table class=bodytable border="0" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
          <tr <? echo $tableheader ?> >
              <td witdh='100'><p class=help>№</p></td>
  
              <td witdh='60%'><p class=help>Ф.И.О. участника</p></td>
              <td witdh='100'><p class=help>Секретный ключ</p></td>
          </tr>   
     <?         

  $i=0;
  while($member = mysql_fetch_array($gst))
  {
 
    $i=$i+1;
    $ii = $i/2;
    $k = substr($ii, strpos($ii,'.')+1);
    if (empty($k))
     echo "<tr bgcolor='#FFFFFF'>";
    else
     echo "<tr>";
    
    echo "<td witdh='100'><p class=help>".$i."</p></td>";
    
    $from = mysql_query("SELECT * FROM users WHERE id='".$member['userid']."'");
    $fromuser = mysql_fetch_array($from);
    echo "<td width='300'><p>".$fromuser['userfio']."</p>
    <p>Место работы - ".$fromuser['job']."</p><p><a href='mailto:".$fromuser['email']."'>".$fromuser['email']."</a>
    &nbsp;<a href='index.php?op=msg&title=Секретный ключ&content=Ваш секретный ключ ".$member['secretkey']."&id=".$fromuser['id']."' title='Отправить сообщение'><img src='img/forum-default.png' width='16' height='16'></a></p></td>";

    echo "<td width='100'><p class=zag2>".$member['secretkey']."</p></td>";

    echo "</tr>"; 
  }
    echo "</table></div></p>";
    ?>
    <p align="center"><input type="button" name="close" value="Вернуться к шаблонам" onclick="document.location='<? echo $site; ?>/index.php?op=parray'"></p>
    <?    
  include "bottomadmin.php";
}} else die;  
?>
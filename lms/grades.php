<?php
if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) {  
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";
  $title=$titlepage="Список оценок";
  $helppage='';
  include "topadmin.php";

  $paid = $_GET["paid"];

  $gst3 = mysql_query("SELECT ownerid FROM projectarray WHERE id='".$paid."'");
  if (!$gst3) puterror("Ошибка при обращении к базе данных");
  $projectarray = mysql_fetch_array($gst3);

   if ((defined("IN_SUPERVISOR") and $projectarray['ownerid'] == USER_ID) or defined("IN_ADMIN")) 
   {

  echo"<p align='center'><a class=link href='index.php?op=addgrade&paid=".$paid."'>Добавить новую оценку</a>
  </p><p align='center'>";
  // Стартовая точка
  $start = $_GET["start"];
  if (empty($start)) $start = 0;
  if ($start < 0) $start = 0;

  $tot = mysql_query("SELECT count(*) FROM grades WHERE proarrid='".$paid."'");
  $gst = mysql_query("SELECT * FROM grades WHERE proarrid='".$paid."' ORDER BY id");
  if (!$gst || !$tot) puterror("Ошибка при обращении к базе данных");
  $tableheader = "class=tableheaderhide";
    ?>
           <div id='menu_glide' class='menu_glide'>
   <table class=bodytable border="0" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
          <tr <? echo $tableheader ?> >
              <td witdh='100'><p class=help>№</p></td>
              <td witdh='100'><p class=help>Значение</p></td>
          </tr>   
     <?         

  $i=0;
  while($member = mysql_fetch_array($gst))
  {
    $i=$i+1;
    echo "<tr><td witdh='100'><p class=help>".$i."</p></td>";
    echo "<td width='100'><a class='menu' href=index.php?op=editgrade&paid=".$paid."&id=".$member['id']."&start=$start title='Редактировать'><p class=zag2>".$member['ball']."</a>";
    ?>
    &nbsp;
    <a href="#" onClick="DelWindowPaid(<? echo $member['id'];?> ,<? echo $paid;?>,'delgrade','grades','оценку')" title="Удалить оценку"><img src="img/b_drop.png" width="16" height="16"></a></p>
    <?
  }
  echo "</table></div></p>";
    ?>
    <p align="center"><input type="button" name="close" value="Вернуться к шаблонам" onclick="document.location='<? echo $site; ?>/index.php?op=parray'"></p>
    <?    
 } 
  include "bottomadmin.php";
} else die;  
?>
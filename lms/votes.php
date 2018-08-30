<?php
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";

  $selpaid = $_GET["paid"];
  if (empty($selpaid)) die;

  $vote = $_GET["vote"];
  $ip = getenv ("REMOTE_ADDR");

  if ($vote== 0)
   $title=$titlepage="Итоги голосования";
  else
   $title=$titlepage="Итоги голосования (Голосование с адреса ".$ip." уже производилось)";

  // Выводим шапку страницы
  include "topadmin.php";

  $gst = mysql_query("SELECT * FROM projects WHERE proarrid='".$selpaid."' ORDER BY id ASC");
  if (!$gst) puterror("Ошибка при обращении к базе данных");
  $tableheader = "class=tableheaderhide";
    ?>


<div id="menu_glide" class="menu_glide">
      <table class=bodytable border="0" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
          <tr <? echo $tableheader ?> >
              <td witdh='100' align='center'><p>№</p></td>
              <td witdh='400' align='center'><p>Наименование</p></td>
              <td witdh='200' align='center'><p>Голосование</p></td>
              <td witdh='100' align='center'><p>Дата создания</p></td>
              <td witdh='100' align='center'><p>Статус</p></td>
          </tr>   
     <?         

  $i=$start;
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
    
    echo "<td width='400' align='center'>";
    ?>
     <input type="button" name="close" value="<?echo $member['info'];?>" 
     onclick="window.open('viewproject3&id=<? echo $member['id'] ?>','<? echo $member['info']; ?>','height=600,width=820,top=20,left=20,status=no,toolbar=no,menubar=no,scrollbars=yes,resizable');">
    <?

    
    echo "<td width='200' align='center'>";
        
    echo "</td><td width='100' align='center'><p>".data_convert ($member['regdate'], 1, 0, 0)."</p></td>";
    echo "<td width='100'  align='center'><p>";
    if ($member['status']=='created') 
    {
     echo "Создание";
    }
    else
    if ($member['status']=='accepted') 
    {
     echo "Подготовлен";
    }
    else
    if ($member['status']=='inprocess') 
    {
     echo "Проходит экспертизу";
    }
    else
    if ($member['status']=='finalized') 
    {
     echo "Экспертиза завершена";
    }
    else
    if ($member['status']=='published') 
     echo "Опубликован";

   echo "</p></td></tr>"; 
  }
  
  echo "</table></div>
  </p>";

include "bottomadmin.php";
?>
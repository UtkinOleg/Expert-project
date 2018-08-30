<?php
  if(defined("IN_EXPERT") or defined("IN_SUPERVISOR") or defined("IN_ADMIN")) {  
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";

  $paname = $_GET["paname"];

  $selpaid = $_GET["paid"];
  if (empty($selpaid)) die;

  $title=$titlepage="Просмотр всех проектов &#8220;".$paname."&#8221;";

  // Выводим шапку страницы
  include "topadmin.php";

  $sort = $_GET["sort"];
  if (empty($sort)) $sort='id';
   

  $gst = mysql_query("SELECT p.* FROM projects as p, proexperts as e WHERE p.proarrid=e.proarrid AND e.expertid='".USER_ID."' ORDER BY p.".$sort." ASC");
  if (!$gst) puterror("Ошибка при обращении к базе данных");

  $tableheader = "class=tableheaderhide";
    ?>
<div id="menu_glide" class="menu_glide">
      <table class=bodytable border="0" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
          <tr <? echo $tableheader ?> >
              <td witdh='100'><p><a class=link href='index.php?op=userprojects&sort=id'>№</a></p></td>
  
              <td witdh='300'><p>Ф.И.О. участника</p></td>
              <td witdh='300'><p><a class=link href='index.php?op=userprojects&sort=info'>Наименование проекта</a></p></td>
              <td></td><td></td>
              <td witdh='400'><p>Прикрепленные файлы</p></td>
              <td witdh='100'><p><a class=link href='index.php?op=userprojects&sort=regdate'>Дата создания</a></p></td>
              <td witdh='100'><p><a class=link href='index.php?op=userprojects&sort=status'>Статус проекта</a></p></td>
              <td></td>
          </tr>   
     <?         

  $i=$start;
  while($member = mysql_fetch_array($gst))
  {
  // Просмотр только доступных проектов
  if ($selpaid == $member['proarrid']) {
  // Просмотр возможен только у проектов доступных для коммента
  $res4=mysql_query("SELECT * FROM projectarray WHERE id='".$member['proarrid']."'");
  if(!$res4) puterror("Ошибка 3 при изменении данных.");

  $projectarray = mysql_fetch_array($res4);
  if ($projectarray['addcomment']==1) {
  
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
    echo "<td width='300'><a class='menu' href='index.php?op=viewuser&id=".$member['userid']."' title='Данные участника'>
    <p class=zag2>".$fromuser['userfio']."</a>&nbsp;
    <a href='index.php?op=msg&id=".$fromuser['id']."' title='Отправить сообщение'>
    <img src='img/forum-default.png' width='16' height='16'></a></p>
    </td>";

//    $from2 = mysql_query("SELECT name FROM projectarray WHERE id='".$member['proarrid']."'");
//    $projectarray = mysql_fetch_array($from2);
//    echo "<td width='100'><p class=zag2>".$projectarray['name']."</p></a></td>";

    echo "<td width='300'><p align='center'><a href='index.php?op=viewproject&back=userprojects&id=".$member['id']."' title='Просмотр проекта'><b>".$member['info']."</a></p>";

     ?>
     <p align="center"><input type="button" name="viewproject" value="Просмотр проекта" onclick="document.location='<? echo $site; ?>/?op=viewproject&back=userprojects&id=<? echo $member['id']; ?>'"></p>
     <?

    echo "</td><td></td><td></td>"; 
    
    echo "<td width='400'>";
    $res3=mysql_query("SELECT * FROM projectdata WHERE projectid='".$member['id']."' ORDER BY id");
    $iscreate = 0;
    while($param = mysql_fetch_array($res3))
    { 
     if (!empty($param['filename'])) { 
     $kb = round($param['filesize']/1024,2);
     echo "<a class='menu' href='file.php?id=".$param['secure']."'
      target='_blank'>".$param['filename']."</a> (".$kb." кб)<br>";
     } 
    }
    echo "</td><td width='100'><p class=zag2>".data_convert ($member['regdate'], 1, 0, 0)."</p></td>";
    if ($member['status']=='created') 
    {
     $iscreate = 1;
     echo "</td><td width='100'><p class=zag2>Создание</p></td>";
     echo "<td></td>";
    }
    else
    if ($member['status']=='accepted') 
    {
     echo "</td><td width='100'><p class=zag2>Подготовлен</p></td>";
     if(defined("IN_SUPERVISOR"))
      echo "<td><a href='index.php?op=chsproject&to=userprojects&paname=".$paname."&id=".$member['id']."' title='Изменить статус'><img src='img/s_process.png' width='16' height='16'></a></p></td>";
     else 
      echo "<td></td>";
    }
    else
    if ($member['status']=='inprocess') 
    {
     echo "</td><td width='100'><p class=zag2>Проходит экспертизу</p></td>";
     echo "<td></td>";
    }
    else
    if ($member['status']=='finalized') 
    {
     echo "</td><td width='100'><p class=zag2>Экспертиза завершена</p><p class=zag2>Итоговый балл: ".round($member['maxball'],2)."</p></td>";
     echo "<td></td>";
    }
    else
    if ($member['status']=='published') 
     echo "</td><td width='100'><p class=zag2>Опубликован</p></td><td></td>";
   echo "</tr>"; 
   }
   }
  }
  
  echo "</table></div></p>";
  include "bottomadmin.php";
} else die;  
?>
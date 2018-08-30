<?php
if(defined("IN_ADMIN") or defined("IN_SUPERVISOR") or defined("IN_EXPERT"))
{
// Устанавливаем соединение с базой данных
include "config.php";
include "func.php";
$title=$titlepage="Просмотр экспертного листа";
include "topadmin.php";
?>

<table class=bodytable width="90%" border="0" cellpadding=5 cellspacing=5 bordercolorlight=gray bordercolordark=white>

<?    
   $query1 = mysql_query("SELECT * FROM shablondb WHERE id=".$_GET["id"]);
   if (!$query1) puterror("Ошибка при обращении к базе данных");
   $r1 = mysql_fetch_array($query1);

   $query2 = mysql_query("SELECT * FROM projects WHERE id=".$r1['memberid']);
   if (!$query2) puterror("Ошибка при обращении к базе данных");
   $r2 = mysql_fetch_array($query2);

   $query22 = mysql_query("SELECT * FROM users WHERE id=".$r2['userid']);
   if (!$query22) puterror("Ошибка при обращении к базе данных");
   $r22 = mysql_fetch_array($query22);

   $query3 = mysql_query("SELECT * FROM users WHERE id=".$r1['userid']);
   if (!$query3) puterror("Ошибка при обращении к базе данных");
   $r3 = mysql_fetch_array($query3);
?>
    <tr>
        <td><font face='Tahoma,Arial' size='-1'>Проект - <? echo "<a class='menu' href='index.php?op=viewproject&id=".$r2['id']."'>№".$r2['id']." (".$r2['info'].")</a>"; ?></font></td><td></td>
    </tr>
    <tr>
        <td><font face='Tahoma,Arial' size='-1'>Автор проекта - <? echo $r22['userfio']; ?></font></td><td></td>
    </tr>
    <tr>    
        <td><font face='Tahoma,Arial' size='-1'>Эксперт - <? echo $r3['userfio']; ?></font></td><td></td>
    </tr>
    <tr>    
        <td><font face='Tahoma,Arial' size='-1'>Дата экспертизы - <? echo data_convert ($r1['puttime'], 1, 1, 0); ?></font></td><td></td>
    </tr>

<?    
 $res2=mysql_query("SELECT * FROM shablongroups WHERE proarrid='".$_GET["paid"]."' ORDER BY id");
  while($group = mysql_fetch_array($res2))
  { 
    echo"<tr class=tableheader valign='center'>";
        echo"<td colspan='3' height='20'>";
            echo"<p><b>".$group['name']."</b></p></td>";
    echo"</tr>";
    
   $subtotal = 0; 
   $res3=mysql_query("SELECT * FROM shablon WHERE groupid='".$group['id']."' AND proarrid='".$_GET["paid"]."' ORDER BY id");
    while($param = mysql_fetch_array($res3))
   { 
    echo"<tr>";
    $query4=mysql_query("SELECT * FROM leafs WHERE shablonid='".$param['id']."' AND shablondbid='".$r1['id']."'");
    $r4 = mysql_fetch_array($query4);
    echo"<td><font face='Tahoma,Arial' size='-1'>".$param['name']."</font></td>";
    echo"<td><font face='Tahoma,Arial' size='-1'>".$r4['ball']."</font></td>";
    echo"</tr>";
    $subtotal = $subtotal + $r4['ball']; 
   
   } 
    echo"<tr><td><p><b>Итого по разделу:</b></p></td>";
    echo"<td><p><b>".$subtotal."</b></p></td>";
    echo"</tr>";
   
  }  

    echo"<tr class=tableheader><td><p><b>Итого:</b></p></td>";
    echo"<td><p><b>".$r1['ball']."</b></p></td>";
    echo"</tr>";

    echo"<tr><td>".$r1['info']."</td><td></td></tr>";
       
?> 
    <tr align="center">
        <td colspan="3">
            <input type="button" name="close" value="Назад" onclick="history.back()"> 
        </td>
    </tr>           
</table>
<?php
 include "bottomadmin.php";
}
else die;
?>

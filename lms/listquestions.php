<?php
if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) {  
// Устанавливаем соединение с базой данных
include "config.php";
require_once "header.php"; 
$grid = $_GET['id'];
?>
</head><body>
<table border=0 cellpadding=0 cellspacing=0 width='100%' height='100%' bgcolor='#ffffff'><tr><td>
<h3 class='ui-widget-header ui-corner-all' align="center"><p>Список вопросов</p></h3>
<p align='center'>
     <div id="menu_glide" class="menu_glide">
      <table width='95%' align='center' class=bodytable border="0" cellpadding=3 cellspacing=0 bordercolorlight=gray bordercolordark=white>
          <tr class=tableheaderhide>
              <td align='center' witdh='50'><p class=help>№</p></td>
              <td align='center' witdh='500'><p class=help>Наименование вопроса</p></td>
              <td align='center' witdh='400'><p class=help>Ответы</p></td>
              <td align='center' witdh='50'><p class=help>Тип ответа</p></td>
          </tr>
      <? 
       $qst = mysql_query("SELECT * FROM questions WHERE qgroupid='".$grid."' ORDER BY id");
       $i=0;
       while($member = mysql_fetch_array($qst))
       {
        echo "<tr><td witdh='50'><p>".++$i." [".$member['id']."]</p></td>";
        echo "<td width='500'><p class=zag2>".$member['name']."</p><p><font size='-1'>".$member['content']."</font></td>";

        echo "<td witdh='400'>";
        $ans = mysql_query("SELECT * FROM answers WHERE questionid='".$member['id']."' ORDER BY id");
         while($answer = mysql_fetch_array($ans))
         {
           if ($answer['ball']>0)
            echo "<p><b>".$answer['name']."</b></p>";
           else
            echo "<p>".$answer['name']."</p>";
         }
        mysql_free_result($ans); 
        echo "</td>";
        if ($member['qtype']=='multichoice')
         $s='Закрытый';
        else 
        if ($member['qtype']=='shortanswer')
         $s='Открытый';
        echo "<td witdh='50'><p>".$s."</p></td></tr>";
       }
      mysql_free_result($gst); 
      ?>
             
      </table></div>

</p>
</td></tr></table>
</body></html>
<?
} else die;
?>
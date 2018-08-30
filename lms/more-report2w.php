<?php
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";
  

  $selpaid = $_GET["paid"];
  if (empty($selpaid)) die;

  // Стартовая точка
  $offset = intval($_POST['offset']); 
  
  // Запрашиваем общее число участников
  $sql = mysql_query("SELECT * FROM projects WHERE proarrid='".$selpaid."' AND (status='inprocess' OR status='finalized' OR status='published') ORDER BY maxball DESC LIMIT $offset, $pnumber;");
  if (!$sql) puterror("Ошибка при обращении к базе данных");
  $n=$start;
  if(mysql_num_rows($sql)>0) { 
        while($post = mysql_fetch_assoc($sql)){  
            foreach($post AS $n=>$m){  
                $post[$n] = $m; 
            }  
            $json['more'][] = $post; # чтобы было легче до 
        }   
         if(count($json['more']))  { 
             $json['ok'] = '1';  
         } else {  
             $json['ok'] = '0'; 
         }      
        } else { 
           $json['ok']='3'; 
        }     
    
 echo json_encode($json);  


  while($list = mysql_fetch_array($sql))
  {
    $n=$n+1;
    if ($n<11)
     echo "<tr bgcolor='#FFFFFF'>";
    else
     echo "<tr>";
    echo "<td align='center'><p class=zag2>".$n."</p></td>";
    if(defined("IN_ADMIN") or defined("IN_SUPERVISOR"))
     echo "<td width='400'><p class=zag2>№".$list['id']." (".$list['info'].")</p></td>";
    else 
    {
     $res3cnt=mysql_query("SELECT count(*) FROM shablondb WHERE memberid='".$list['id']."' AND LENGTH(info)>0");
     $param3cnt = mysql_fetch_array($res3cnt);
     
     if ($param3cnt['count(*)']>0)
     {

?> 
<script type="text/javascript">
		$(document).ready(function() {
    	$("#fancybox<?php echo $list['id']; ?>").click(function() {
				$.fancybox.open({
					href : 'viewcomment3&id=<? echo $list['id'] ?>',
					type : 'iframe',
					padding : 5
				});
			});
		});
</script>
<?
      
      $commstr = "<font size='-2'>
      <a title='Комментарии экспертов' id='fancybox".$list['id']."' href='javascript:;'>Комментарии экспертов (".$param3cnt['count(*)'].")</a>
      </font>";
     } 
     else 
      $commstr = "";
      
     if (isUrl($list['info']))
     {
      if (preg_match("/http:/i", $list['info'])>0)
       echo "<td width='400'><p class=zag2><a href='".$list['info']."' target='_blank'>Проект №".$list['id'].". ".$list['info']."</a></p>".$commstr."</td>";
      else
       echo "<td width='400'><p class=zag2><a href='http://".$list['info']."' target='_blank'>Проект №".$list['id'].". ".$list['info']."</a></p>".$commstr."</td>";
     }
 
   else
     echo "<td width='400'><p class=zag2>Проект №".$list['id'].". ".$list['info']."</p>".$commstr."</td>";
    
    }
    
  
    $lst3 = mysql_query("SELECT expertid FROM proexperts WHERE proarrid='".$selpaid."' ORDER BY expertid");
    if (!$lst3) puterror("Ошибка при обращении к базе данных");
    $i=0;
    $newprcent = 0;
    
    while($list3 = mysql_fetch_array($lst3))
     {
      $lst4 = mysql_query("SELECT * FROM shablondb WHERE userid='".$list3['expertid']."' AND memberid='".$list['id']."'");
      if (!$lst4) puterror("Ошибка при обращении к базе данных");
      $list4 = mysql_fetch_array($lst4);
      if ($list4['maxball']!=0) 
      {
       $percent = ($list4['ball'] / $list4['maxball']) * $ocenka;  
       $i=$i+1;
      }
      else
       $percent = 0;
      
      $newprcent = $newprcent + $percent; 
      if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) 
      {
       if ($mode==1) 
       {
       if ($percent == 0) 
        echo "<td align='center' width='150'>-</td>";
       else 
        echo "<td align='center' width='150'><p class=zag2>".$list4['ball']." из ".$list4['maxball']." (".round($percent,2).")</p></td>";
       }
      } 
     }
    
    if ($list['maxball']>0)
     echo "<td align='center' width='100'><p class=zag2>".round($list['maxball'],2)."</p></td>";
    elseif ($newprcent>0)
     echo "<td align='center' width='100'><p class=zag2>".round($newprcent,2)."</p></td>";
    else
     echo "<td align='center' width='100'><p class=zag2>-</p></td>";

    if ($i>0) 
    {
     if ($list['maxball']>0)
      $aball = $list['maxball'] / $i;
     elseif ($newprcent>0)
      $aball = $newprcent / $i;
     else 
      $aball = 0;
    }
    else
     $aball = 0;

    if ($aball>0)
     echo "<td align='center' width='100'><p class=zag2>".round($aball,2)."</p></td>";
    else  
     echo "<td align='center' width='100'>-</td>";

    if ($aball>0)
     echo "<td align='center' width='100'><p class=zag2>".$i."</p></td>";
    else  
     echo "<td align='center' width='100'>-</td>";
    
    echo "</tr>";
  }
?>


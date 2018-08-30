<?php
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";

  $paname = $_GET["paname"];
  $selpaid = $_GET["paid"];

  if (empty($selpaid)) die;

  $title=$titlepage="Голосование по проекту &#8220;".$paname."&#8221;";

  // Выводим шапку страницы
  include "topadmin.php";

  $gst = mysql_query("SELECT * FROM projectarray WHERE id='".$selpaid."'");
  $member = mysql_fetch_array($gst);

    // Начнем сравнение дат
    $date1 = $member['startdate'];
    $date2 = $member['stopdate'];
    $date3 = date("d.m.Y");
    preg_match_all("/(\d{1,2})\.(\d{1,2})\.(\d{4})/",$date3,$i);
    $day=$i[1][0];
    $month=$i[2][0];
    $year=$i[3][0];
    
    $arr1 = explode(" ", $date1);
    $arr2 = explode(" ", $date2);  

    $arrdate1 = explode("-", $arr1[0]);
    $arrdate2 = explode("-", $arr2[0]);


    $timestamp3 = (mktime(0, 0, 0, $month, $day, $year));
    $timestamp2 = (mktime(0, 0, 0, $arrdate2[1],  $arrdate2[2],  $arrdate2[0]));
    $timestamp1 = (mktime(0, 0, 0, $arrdate1[1],  $arrdate1[2],  $arrdate1[0]));

     
    if ($timestamp3 >= $timestamp1 and $timestamp3 <= $timestamp2) 
    {

  $gst = mysql_query("SELECT * FROM projects WHERE proarrid='".$selpaid."' AND status!='created' ORDER BY up-down DESC");
  if (!$gst) puterror("Ошибка при обращении к базе данных");
  $tableheader = "class=tableheaderhide";
    ?>

<script type="text/javascript">
$(function() {

$(".vote").click(function() 
{

var id = $(this).attr("id");
var name = $(this).attr("name");
var dataString = 'id='+ id ;
var parent = $(this);


if(name=='up')
{

$(this).fadeIn(200).html('<img src="img/dot.gif" align="absmiddle">');
$.ajax({
   type: "POST",
   url: "up_vote.php",
   data: dataString,
   cache: false,

   success: function(html)
   {
    parent.html(html);
  
  }  });
  
}
else
{

$(this).fadeIn(200).html('<img src="img/dot.gif" align="absmiddle">');
$.ajax({
   type: "POST",
   url: "down_vote.php",
   data: dataString,
   cache: false,

   success: function(html)
   {
       parent.html(html);
  }
   
 });


}

return false;
	});

});
</script>


<script type="text/javascript">
		$(document).ready(function() {
			$('.fancybox').fancybox();
		});
</script>

<style type="text/css">
		.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
		}
</style>

<form action=dovote method=post>
<input type=hidden name=action value=post>
<input type="hidden" name="paid" value="<? echo $selpaid; ?>">

<div id="menu_glide" class="menu_glide">
      <table class=bodytable border="0" align="center" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
          <tr <? echo $tableheader ?> >
              <td witdh='100' align='center'><p>№</p></td>
              <td witdh='400' align='center'><p>Наименование</p></td>
              <td witdh='200' align='center'><p>Голосование</p></td>
              <td witdh='100' align='center'><p>Дата создания</p></td>
          </tr>   
     <?         

  $i=$start;
  while($member = mysql_fetch_array($gst))
  {
   // Голосование только для готовых проектов
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
    
<script type="text/javascript">
		$(document).ready(function() {
    	$("#fancybox<?php echo $member['id']; ?>").click(function() {
				$.fancybox.open({
					href : 'viewproject3&id=<? echo $member['id'] ?>',
					type : 'iframe',
					padding : 5
				});
			});

      $("#fancybox-manual-<?php echo $member['id']; ?>").click(function() {
				$.fancybox.open([
        <?php
  $res3=mysql_query("SELECT * FROM poptions WHERE proarrid='".$member['proarrid']."' ORDER BY id");
  while($param = mysql_fetch_array($res3))
   { 
    $res4=mysql_query("SELECT * FROM projectdata WHERE projectid='".$member['id']."' AND optionsid='".$param['id']."'");
    $param4 = mysql_fetch_array($res4);
    if ($param['files']=="yes") {
     if (!empty($param4['filename'])) { 
     if ($param['filetype']=="foto") {
      echo "{ href : '".$upload_dir.$param4['projectid'].$param4['realfilename']."' },";
     }
     }  
    } 
    }
        ?>
          
				], {
					helpers : {
						thumbs : {
							width: 75,
							height: 50
						}
					}
				});
			});      
      
		});
</script>
    <font size='+1'>
    <a title="Промотр проекта" id="fancybox<? echo $member['id'] ?>" href="javascript:;"><? echo $member['info']; ?></a>
    </font> 

<?

  $res3=mysql_query("SELECT * FROM poptions WHERE proarrid='".$member['proarrid']."' ORDER BY id");
  if (!$res3) puterror("Ошибка при обращении к базе данных");
  while($param = mysql_fetch_array($res3))
   { 
    $res4=mysql_query("SELECT * FROM projectdata WHERE projectid='".$member['id']."' AND optionsid='".$param['id']."'");
    $param4 = mysql_fetch_array($res4);

    if ($param['files']=="yes") {
     if (!empty($param4['filename'])) { 
     $kb = round($param4['filesize']/1024,2);
     if ($param['filetype']=="file") { 
      echo "<a class='menu' href='file.php?id=".$param4['secure']."'
      target='_blank'>".$param4['filename']."</a> (".$kb." кб)<br>";
     } 
     else
     if ($param['filetype']=="foto") {
     

      list($width, $height, $type, $attr)= getimagesize($upload_dir.$param4['projectid'].$param4['realfilename']);


         if ($width>$resizing) {
          $resize = round(($resizing*100)/$width);
          $new_width = round((($resize/100)*$width));
          $new_height = round((($resize/100)*$height));
         } else
         {
          $new_width = $width;
          $new_height = $height;
         }

      echo "<br><a title='Фотогалерея' id='fancybox-manual-".$member['id']."' href='javascript:;'><img src='file.php?id=".$param4['secure']."' width='".$new_width."' height='".$new_height."'></a>&nbsp;";
      break;
     }
     }  
    } 
    }

?>    
    <td width='200' align='center'>

    <div class="box1">
    <div class='up'><a href="" class="vote" id="<?php echo $member['id']; ?>" name="up">Проект нравится (<?php echo $member['up']; ?>)</a>
    </div>
    <div class='down'><a href="" class="vote" id="<?php echo $member['id']; ?>" name="down">Проект не нравится (<?php echo $member['down']; ?>)</a></div>
    </div>

    <?

//    echo "<option value='1'>Нравится</option>";
//    echo "<option value='-1'>Не нравится</option>";
//    echo "<option value='0' selected>Затрудняюсь ответить</option>";
//    echo "</select>";

        
    echo "</td><td width='100' align='center'><p>".data_convert ($member['regdate'], 1, 0, 0)."</p></td>";
/*    echo "<td width='100'  align='center'><p>";
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

   echo "</p></td>"; */
   echo "</tr>"; 
  }
  echo "</table></div>";
 // echo "<p align='center'><input type='button' name='close' value='Назад' onclick='history.back()'></p>";
  
}
include "bottomadmin.php";
?>
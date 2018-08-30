<?php
if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) {  
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";
  $title=$titlepage="Список областей знаний";
  include "topadmin.php";

    ?>
<script type="text/javascript">

	$(document).ready(function() {
      $( "#addknow" ).button();
			$('.fancybox').fancybox();
    	$("#addknow").click(function() {
				$.fancybox.open({
					href : 'editknows&mode=add',
					type : 'iframe',
          width : 500,
          height : 360,
          fitToView : true,
          autoSize : false,
					padding : 5
				});
			});
	});
  
  function closeFancybox(){
    $.fancybox.close();
   }    
</script>    

<div class="ui-widget">
	<div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<strong>Для сведения!</strong> Группы вопросов, сформированные в системе, можно относить к определенной области знаний. Условно, области знаний можно представить в виде разделов, в которых можно хранить группы вопросов.</p>
  </div>
</div>

<?
  echo"<p align='center'><a style='font-size:1em;' id='addknow' href='javascript:;'><i class='fa fa-cloud fa-lg'></i>&nbsp;Добавить область
  </a></p><p align='center'>";

  
  if (defined("IN_ADMIN"))
   $gst = mysqli_query($mysqli,"SELECT * FROM knowledge ORDER BY id;");
  else
   $gst = mysqli_query($mysqli,"SELECT * FROM knowledge WHERE userid='".USER_ID."' ORDER BY id;");

  if (defined("IN_ADMIN"))
   $tot = mysqli_query($mysqli,"SELECT count(*) FROM knowledge ORDER BY id;");
  else
   $tot = mysqli_query($mysqli,"SELECT count(*) FROM knowledge WHERE userid='".USER_ID."' ORDER BY id;");
   
  if (!$gst) 
   puterror("Ошибка при обращении к базе данных");
  if (!$tot) 
   puterror("Ошибка при обращении к базе данных");
  $totc = mysqli_fetch_array($tot);
  $ctotal = $totc['count(*)'];
  mysqli_free_result($tot);
  if ($ctotal>0)
  {

  $tableheader = "class=tableheaderhide";
    ?>
     <div id='menu_glide' class='menu_glide'>
      <table class=bodytable style="background-color: #FFFFFF;" align="center" width="90%" border="0" cellpadding=3 cellspacing=0 bordercolorlight=gray bordercolordark=white>
          <tr <? echo $tableheader ?> >
              <td witdh='50'><p class=help>№</p></td>
              <td><p class=help>Наименование</p></td>
              <td><p class=help>Пояснение</p></td>
          </tr>   
     <?         

  $i=0;
  while($member = mysqli_fetch_array($gst))
  {

    ?>
<script type="text/javascript">

	$(document).ready(function() {
    	$("#editknow<? echo $member['id'] ?>").click(function() {
				$.fancybox.open({
					href : 'editknows&mode=edit&id=<? echo $member['id'] ?>',
					type : 'iframe',
          width : 500,
          height : 360,
          fitToView : true,
          autoSize : false,
					padding : 5
				});
			});
	});
  
</script>    
<?

    echo "<tr><td align='center'><p>".++$i."</p></td>";
    echo "<td><p>".$member['name']." <a id='editknow".$member['id']."' href='javascript:;'><i class='fa fa-pencil fa-lg'></i></a>";
    $groups = mysqli_query($mysqli,"SELECT count(*) FROM questgroups WHERE knowsid='".$member['id']."'");
    $groupsd = mysqli_fetch_array($groups);
    $cnt = $groupsd['count(*)'];
    mysqli_free_result($groups);
    if ($cnt==0){
    ?>
    <a href="#" onClick="DelWindow(<? echo $member['id'];?> ,0,'delknows','knows','область знаний')" title="Удалить"><i class='fa fa-trash fa-lg'></i></a></p>
    <?
    }
    echo "<td><p>".$member['content']."</p></td></tr>";
  }
  mysqli_free_result($gst);
  echo "</table></div></p>";
  }
  include "bottomadmin.php";
} else die;  
?>
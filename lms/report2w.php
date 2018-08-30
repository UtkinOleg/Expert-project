<?php
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";
  
  $paname = $_GET["paname"];
  $mode = $_GET["mode"];

  $selpaid = $_GET["paid"];
  if (empty($selpaid)) die;

  // Найдем оценку проекта
  $res5=mysql_query("SELECT openproject, ocenka FROM projectarray WHERE id='".$selpaid."'");
  if(!$res5) puterror("Ошибка 3 при получении данных.");
  $proarray = mysql_fetch_array($res5);
  $openproject = $proarray['openproject'];
  $ocenka = $proarray['ocenka'];
  
  // Посмотрим открытый ли проект
  if ($openproject==1 || defined("IN_ADMIN") || defined("IN_SUPERVISOR")) {

  $title=$titlepage="Итоговый рейтинг проекта &#8220;".$paname."&#8221; по всем участникам";

  include "topadmin.php";

  echo "<p><b>Пояснения к таблице итогового рейтинга.</b></p>";
  echo "<p>Итоговый рейтинг формируется в режиме реального времени в процессе оценки проектов экспертами. По мере того, как эксперты 
  заполняют экспертные листы, рейтинг автоматически пересчитывается. На оценку всех проектов отводится определенный срок. 
  После экспертизы всех проектов сформируется окончательный рейтинг.</p>";
  echo "<p>Итоговый рейтинг формируется по показателю <b>Сумма средних баллов</b>, который вычисляется на основании количества проведенных 
  экспертиз по данному проекту, полученному среднему баллу (обычно по стобалльной системе) по каждой экспертизе и суммарному итогу всех средних баллов.</p>";
  echo "<p>Показатель <b>Средний балл по рейтингу</b> вычисляется как отношение суммы средних баллов к количеству проведенных экспертиз.</p>"; 
  ?>
  
  <script type="text/javascript"> 
  jQuery(document).ready(function() {  
         query();  
  }); 
  var n; 
  var str;
   
  function query(){     
  if(n==undefined) { 
     n=0; 
  } else { 
     n=n+<? echo $pnumber; ?>; 
  } 
  $.post('more-report2w.php?paid=<? echo $selpaid; ?>',{offset:n}, 
    function(data){  
      eval('var obj='+data);         
      if(obj.ok=='1'){ 
        if(n==0){
            str = '<div id=menu_glide class=menu_glide>'
             +'<table class=bodytable border=0 cellpadding=5 cellspacing=5 bordercolorlight=gray bordercolordark=white align=center>'
             +'<tr class=tableheader>'
             +'<td align=center><p>Место в рейтинге</p></td>'
             +'<td width=400 align=center><p>Наименование проекта</p></td>'
             +'<td width=100 align=center><p>Сумма средних баллов (рейтинг)</p></td>'
             +'<td width=100 align=center><p>Средний балл по рейтингу</p></td>'
             +'<td width=100 align=center><p>Проведено экспертиз</p></td></tr>';
        }   
        for(var i = 0; i <= obj.more.length; i++){   
           k = i+n+1;  
           str = str + '<tr><td>' +k+ '</td><td><p class=zag2>№'
           + obj.more[i].id + ' (' + obj.more[i].info + ')</p></td>' + 
           '<td></td><td></td><td></td></tr>';        
        }   
        $('#result').append(str);
      } 
      else 
      if(obj.ok=='3') { 
      $('#button').addClass('button_disabled').attr('disabled', true); 
      }                
    });     
  }       
</script> 

<div id="result">
</div> 
<p align = "center">
<button onclick="query();" id="button">Показать еще</button></p> 
<?
 include "bottomadmin.php";
 } 
?>


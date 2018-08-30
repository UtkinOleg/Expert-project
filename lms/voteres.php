<?php
  // Получаем соединение с базой данных
  include "config.php";
  include "func.php";
  
  $selpaid = $_GET["paid"];

  if (empty($selpaid)) die;

  $tot = mysql_query("SELECT count(*) FROM projects WHERE proarrid='".$selpaid."' AND status!='created'");
  $tot2cnt = mysql_fetch_array($tot);
  $count = $tot2cnt['count(*)'] * 60;
  
  $gst = mysql_query("SELECT * FROM projects WHERE proarrid='".$selpaid."' AND status!='created' ORDER BY up-down DESC");
  if (!$gst) puterror("Ошибка при обращении к базе данных");

  echo "<html>
  <head>                 
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <meta name='description' content='Экспертная оценка проектов: независимая оценка проектов по заданным критериям, формирование сообщества экспертов, экспертиза проектов' /> 
    <meta name='keywords' content='эксперты, оценка прокетов, проведение любых конкурсов, разработка критериев оценки, критерии оценки проектов, экспертная оценка проектов, конференции, семинары, экспертиза проектов' /> 
    <script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type='text/javascript'>

      google.load('visualization', '1.0', {'packages':['corechart']});

      google.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Голосов ЗА');
        data.addColumn('number', 'Голосов ПРОТИВ');
        data.addRows([ ";
      
  while($member = mysql_fetch_array($gst))
  {
    echo "['";
    echo $member['info'];
    echo "',".$member['up'].",".$member['down'];
//    $cnt = $member['up'] - $member['down'];
//    echo $cnt;
    
    echo "],";
  }    

 $title = 'Результаты голосования';
  echo "        ]);

        var options = {'title':'".$title."',
                       'width':700,
                       'height':".$count.",
                       'legend':'top',
                       'isStacked':false};

        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>

  <body>
    <!--Div that will hold the pie chart-->
    <div id='chart_div'></div>
  </body>
</html>";


?>
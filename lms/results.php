<?
if(!defined("USER_REGISTERED")) die;  

include "config.php";
include "func.php";


$sign = $_GET['sign']; // Сигнатура ответов
$sid = $_GET['tid']; // Сигнатура Теста
$resultid = $_GET['id']; // ID Результата
$reloadurl = $_GET['url'];

$rtf = $_GET['rtf']; // Печать
if (empty($rtf)) $rtf = 0;

$log=0;

if ($rtf == 0)
 require_once "header.php"; 

if (empty($resultid))
{
 $query = "SELECT * FROM testgroups WHERE signature='".$sid."' LIMIT 1;";
 $tst = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . $query);
 if ($tst) 
  $test = mysqli_fetch_array($tst);
 else 
  puterror("Ошибка при обращении к базе данных");
 $testname = $test['name'];
 $tid = $test['id'];
 mysqli_free_result($tst); 
}
else
{
 $query = "SELECT * FROM singleresult WHERE id='".$resultid."' LIMIT 1;";
 $res = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . $query);
 if ($res) 
  $result = mysqli_fetch_array($res);
 else 
  puterror("Ошибка при обращении к базе данных");
 $tid = $result['testid'];
 $userid = $result['userid'];
 $sign = $result['signature'];
 $itog_rightsumma = $result['rightball'];
 $itog_nonsumma = $result['allball'];
 $itog_sumball = $result['rightq'];
 $itog_allq = $result['allq'];
 $testdate = data_convert ($result['resdate'], 1, 0, 0);
 mysql_free_result($res); 

 $from = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT userfio FROM users WHERE id='".$userid."' LIMIT 1;");
 $fromuser = mysqli_fetch_array($from);
 $userfio = $fromuser['userfio'];
 mysqli_free_result($from);
 
 $query = "SELECT * FROM testgroups WHERE id=".$tid." LIMIT 1;";
 $tst = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . $query);
 if ($tst) 
  $test = mysqli_fetch_array($tst);
 else 
  puterror("Ошибка при обращении к базе данных");
 $testname = $test['name'];
 mysqli_free_result($tst); 

}      

if ($rtf == 0)
{
?>
<link rel="stylesheet" href="scripts/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="scripts/jquery.fancybox.pack.js?v=2.1.5"></script>
<script type="text/javascript" src="scripts/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
<link rel="stylesheet" type="text/css" href="scripts/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
<script type="text/javascript" src="scripts/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script src="scripts/jquery.easypiechart.min.js"></script>
<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
<style type="text/css">
#buttonset { 
display:block;  font-family: 'Helvetica', 'Arial';  text-align: center;   width: 600px;   height: 40px;   left: 50%;  bottom : 0px;  position: absolute;  margin-left: -300px; } 
#buttonsetm { 
display:block;  font-family: 'Helvetica', 'Arial';  width: 100%; top: 60px; bottom : 50px;  position: absolute; overflow: auto;} 
.ui-widget { font-family: Verdana,Arial,sans-serif; font-size: 0.7em;}
p {
  font: 16px / 1.4 'Helvetica', 'Arial', sans-serif;
}
.chart {
  position: relative;
  display: inline-block;
  width: 100px;
  height: 100px;
  margin-top: 0px;
  margin-bottom: 0px;
  text-align: center;
  font: 18px / 1.4 'Helvetica', 'Arial', sans-serif;
}
.chart canvas {
  position: absolute;
  top: 0;
  left: 0;
}
.percent {
  display: inline-block;
  line-height: 100px;
  z-index: 2;
}
.percent:after {
  content: '%';
  margin-left: 0.1em;
  font-size: .8em;
}
.charti {
  position: relative;
  display: inline-block;
  width: 100px;
  height: 100px;
  margin-top: 0px;
  margin-bottom: 0px;
  text-align: center;
  font: 18px / 1.4 'Helvetica', 'Arial', sans-serif;
}
.charti canvas {
  position: absolute;
  top: 0;
  left: 0;
}
.percenti {
  display: inline-block;
  line-height: 100px;
  z-index: 2;
}
.percenti:after {
  content: '%';
  margin-left: 0.1em;
  font-size: .8em;
}
</style>
<script>
  $(function() {
    $( "#viewprotocol" ).button();
    $( "#close" ).button();
    $( "#print" ).button();
    $('.chart').easyPieChart({
      size : 100,
      easing: 'easeOutBounce',
      onStep: function(from, to, percent) {
				$(this.el).find('.percent').text(Math.round(percent));
			}		
    });
    $('.charti').easyPieChart({
      size : 100,
      easing: 'easeOutBounce',
      onStep: function(from, to, percent) {
				$(this.el).find('.percenti').text(Math.round(percent));
			}		
    });
  });
</script>
</head><body>
<h3 class='ui-widget-header ui-corner-all' style="margin-top:0px;" align="center"><p>Результаты тестирования по тесту "<? echo $testname; ?>"<? if (!empty($resultid)) echo ', '.$userfio; ?></p></h3>

<div id="buttonsetm">
<table width="100%" border="0" cellpadding=0 cellspacing=0 bordercolorlight=white bordercolordark=white>
    <tr><td>
      <div id="menu_glide" class="menu_glide">
      <table align='center' width='90%' class=bodytable border="0" cellpadding=2 cellspacing=0 bordercolorlight=gray bordercolordark=white>
          <tr class=tableheaderhide>
              <td align='center' witdh='30'><p class=help>№</p></td>
              <td align='left' witdh='400'><p class=help>Наименование группы</p></td>
              <td align='center' witdh='50'><p class=help>Вопросов всего</p></td>
              <td align='center' witdh='50'><p class=help>Правильно отвечено</p></td>
              <td align='center' witdh='50'><p class=help>Неправильно отвечено</p></td>
              <td align='center' witdh='50'><p class=help>Набрано баллов</p></td>
              <td align='center' witdh='50'></td>
          </tr>   

          
<?         
}
else
{

//ini_set('display_errors', 1);
//error_reporting(E_ALL); // E_ALL

$dir = dirname(__FILE__);
require_once $dir . '/lib/Classes/PHPRtfLite/lib/PHPRtfLite.php';

$rowCount = 1;
$rowHeight = 1;
$columnCount = 7;
$columnWidth = 3;

PHPRtfLite::registerAutoloader();

$rtfs = new PHPRtfLite();


$sect = $rtfs->addSection();

$sect->writeText(' ', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER));
$sect->addImage($dir . '/img/logoexpert.png', null );
$sect->writeText('<i>Результаты тестирования по тесту <b>'.$testname.'</b></i>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER));
$sect->writeText('<i>Имя: <b>'.$userfio.'</b></i>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER));
$sect->writeText('<i>Дата тестирования: <b>'.$testdate.'</b></i>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER));
$sect->writeText(' ', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER));

$table = $sect->addTable();
$table->addRows(1);
$table->addColumnsList(array(1,4,2,2,2,2,2));

$cell = $table->getCell(1,1);
$cell->writeText("№");
$cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
$cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

$cell = $table->getCell(1,2);
$cell->writeText("Наименование группы");
$cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
$cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

$cell = $table->getCell(1,3);
$cell->writeText("Вопросов всего");
$cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
$cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

$cell = $table->getCell(1,4);
$cell->writeText("Правильно отвечено");
$cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
$cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

$cell = $table->getCell(1,5);
$cell->writeText("Неправильно отвечено");
$cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
$cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

$cell = $table->getCell(1,6);
$cell->writeText("Набрано баллов");
$cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
$cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

$cell = $table->getCell(1,7);
$cell->writeText("Процент набранных баллов");
$cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
$cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

$borderTop = new PHPRtfLite_Border($rtfs);
$borderTop->setBorderTop(new PHPRtfLite_Border_Format(1, '#000'));
$table->setBorderForCellRange($borderTop, 1, 1, 1, $columnCount);

$borderBottom = new PHPRtfLite_Border($rtfs);
$borderBottom->setBorderBottom(new PHPRtfLite_Border_Format(1, '#000'));
$table->setBorderForCellRange($borderBottom, $rowCount, 1, $rowCount, $columnCount);

}

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
$showprotocol = true;

if (empty($resultid))  // Процесс сканирования результатов и записи
{

      $showprotocol = false;

      $td = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM tmptest WHERE signature='".$sign."' ORDER BY id");
      $ctd = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM tmptest WHERE signature='".$sign."' LIMIT 1;");
      if (!$td) puterror("Ошибка при обращении к базе данных");
      if (!$ctd) puterror("Ошибка при обращении к базе данных");
      $cnttd = mysqli_fetch_array($ctd);
      $qc=0;
      $aqc=0;
      $tt=0;
      $sumball=0;  // Количество правильных ответов по группе
      $rightsumma=0; // Сумма правильно набранных баллов по группе
      $nonsumma=0; // Сумма всех баллов по группе
      $itog_sumball=0;  // Количество правильных ответов по тесту
      $itog_rightsumma=0; // Сумма правильно набранных баллов по тесту
      $itog_nonsumma=0; // Сумма неправильно набранных баллов по тесту
      $i=0;                      
      $userid = USER_ID;
      
      while($testdata = mysqli_fetch_array($td))
      {
       if ($i==0)
       {
        $qg = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questgroups WHERE id='".$testdata['qgroupid']."' LIMIT 1");
        $questgroup = mysqli_fetch_array($qg);
        $qc++;
        echo "<tr><td align='center'><p>".++$i."</p></td><td><p>".$questgroup['name']."</p>";
        if (!empty($questgroup['comment'])) echo "<p style='font-size:0.7em;'>".$questgroup['comment']."</p>";
        echo"</td>";
        $lastid = $testdata['qgroupid'];
        mysqli_free_result($qg);
       }
       else
        $qc++;
        
       $aqc++; 

       if ($lastid != $testdata['qgroupid'])      // Новая группа !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
       {
        $qc--;
        $nball = $qc-$sumball;
        
        ?>
<style type="text/css">
.chart<? echo $i?> {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 60px;
  margin-top: 0px;
  margin-bottom: 0px;
  text-align: center;
}
.chart<? echo $i?> canvas {
  position: absolute;
  top: 0;
  left: 0;
}
.percent<? echo $i?> {
  display: inline-block;
  line-height: 60px;
  z-index: 2;
  font-size: .8em;
}
.percent<? echo $i?>:after {
  content: '%';
  margin-left: 0.1em;
  font-size: .8em;
}
</style>
<script>
  $(function() {
    $('.chart<? echo $i?>').easyPieChart({
      size : 60,
      easing: 'easeOutBounce',
      onStep: function(from, to, percent) {
				$(this.el).find('.percent<? echo $i?>').text(Math.round(percent));
			}		
    });
  });
</script>
        <?
        echo "<td align='center'><p>".$qc."</p></td><td align='center'><p>".$sumball."</p></td>
        <td align='center'><p>".$nball."</p></td><td align='center'><p>".$rightsumma." из ".$nonsumma."</p></td>";
        $percent = (int) floor($rightsumma / $nonsumma * 100);
        echo "<td align='center'>
        <div class='chart".$i."' data-percent='".$percent."' data-scale-color='#ffb400'><span class='percent".$i."'></span></div>
        </td></tr>";
        $itog_sumball += $sumball;
        $itog_rightsumma += $rightsumma;
        $itog_nonsumma += $nonsumma;
        
        // Запишем результат +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // Проверка на существование результата 
        if ($log>0) echo " Группа записи = ".$lastid."<br>";
        $tmpres = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM testresults WHERE testid='".$tid."' AND qgroupid='".$lastid."' AND signature='".$sign."' ORDER BY id;");
        $totalres = mysqli_fetch_array($tmpres);
        $allrescount = $totalres['count(*)'];
        if ($allrescount==0)
        {
         if ($log>0) echo " Группа ".$lastid." записана.<br>";
         mysqli_query($mysqli,"START TRANSACTION;");
         $userid = USER_ID;
         mysqli_query($mysqli,"INSERT INTO testresults VALUES (0,
                                        $tid,
                                        $lastid,
                                        $userid,
                                        $qc,
                                        $sumball,
                                        $nonsumma,
                                        $rightsumma,
                                        '$sign',
                                        NOW());");
         mysqli_query($mysqli,"COMMIT");
        }
        mysqli_free_result($tmpres);
        // Запишем результат +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        
        $sumball = 0;
        $rightsumma = 0;
        $nonsumma = 0;
        $qc=1;
        $qg = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questgroups WHERE id='".$testdata['qgroupid']."' LIMIT 1");
        $questgroup = mysqli_fetch_array($qg);
        echo "<tr><td align='center'><p>".++$i."</p></td><td><p>".$questgroup['name']."</p></td>";
        $lastid = $testdata['qgroupid'];
        mysqli_free_result($qg);

       if ($aqc==$cnttd['count(*)'])
       {
        
        ?>
<style type="text/css">
.chart<? echo $i?> {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 60px;
  margin-top: 0px;
  margin-bottom: 0px;
  text-align: center;
}
.chart<? echo $i?> canvas {
  position: absolute;
  top: 0;
  left: 0;
}
.percent<? echo $i?> {
  display: inline-block;
  line-height: 60px;
  z-index: 2;
  font-size: .8em;
}
.percent<? echo $i?>:after {
  content: '%';
  margin-left: 0.1em;
  font-size: .8em;
}
</style>
<script>
  $(function() {
    $('.chart<? echo $i?>').easyPieChart({
      size : 60,
      easing: 'easeOutBounce',
      onStep: function(from, to, percent) {
				$(this.el).find('.percent<? echo $i?>').text(Math.round(percent));
			}		
    });
  });
</script>
        <?
        
       // Найдем балл за правильный ответ на последний вопрос 
       // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
       $rtball=1; // По умолчанию - 1
       $qgp = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questgroups WHERE id='".$testdata['qgroupid']."' LIMIT 1;");
       if ($qgp)
       {
         $questgp = mysqli_fetch_array($qgp);
         $rtball = $questgp['singleball'];
       } 
       else if ($log>0) echo "err ";
       mysqli_free_result($qgp); 
       
       $qq = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questions WHERE id='".$testdata['questionid']."' ORDER BY id LIMIT 1;");
       $quest = mysqli_fetch_array($qq);
       $questtype = $quest['qtype'];
       mysqli_free_result($qq); 
       
       // Поищем правильные ответы
       $ans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM answers WHERE questionid='".$testdata['questionid']."' ORDER BY id");
       $ball=0;
       $kball=0;
       while($answer = mysqli_fetch_array($ans))
       {
        if ($log>0) echo "<br>";
        if ($questtype=='multichoice')
        {
         $userans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM tmpmultianswer WHERE questionid='".$testdata['questionid']."' AND answerid='".$answer['id']."' AND signature='".$sign."' LIMIT 1;");
         if ($userans)
         {
          if ($answer['ball']>0) 
           $kball++;
          $useranswer = mysqli_fetch_array($userans);
          $ku = $useranswer['value'];
          if (empty($ku))
           $ku = 0;
          if ($log>0) echo "u=".$ku." a=".$answer['ball'];
          if ($ku and $answer['ball']>0) 
           $ball++;
          if ($ku and $answer['ball']==0) 
           $ball--;
         }
         mysqli_free_result($userans);   
        }
        else
        if ($questtype=='shortanswer')
        {
         $userans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT value FROM tmpshortanswer WHERE questionid='".$testdata['questionid']."' AND signature='".$sign."' ORDER BY id LIMIT 1;");
         if ($userans)
         {
          if ($answer['ball']>0) 
           $kball++;
          $useranswer = mysqli_fetch_array($userans);
          $kustr = trim(mb_strtolower($useranswer['value'],'UTF-8'));
          $ansk = trim(mb_strtolower($answer['name'],'UTF-8'));
          if ($log>0) echo "u=".$kustr." a=".$ansk." sign=".$sign." id=".$testdata['questionid'];
          if ($kustr == $ansk) 
           $ball++;
         }
         mysqli_free_result($userans);   
        }
       }
       mysqli_free_result($ans); 
       
       if ($questtype=='multichoice')
       {
        if ($ball==$kball and $ball>0) 
        { 
        // Ну вот и правильный ответ
        $sumball++;
        $rightsumma += $rtball;
        }
       }
       else
       if ($questtype=='shortanswer')
       {
        if ($ball>0) 
        { 
        // Ну вот и правильный ответ
        $sumball++;
        $rightsumma += $rtball;
        }
       }
       
       $nonsumma += $rtball;
       // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $nball = $qc-$sumball;
        echo "<td align='center'><p>".$qc."</p></td><td align='center'><p>".$sumball."</p></td>
        <td align='center'><p>".$nball."</p></td><td align='center'><p>".$rightsumma." из ".$nonsumma."</p></td>";
        $percent = (int) floor($rightsumma / $nonsumma * 100);
        echo "<td align='center'>
        <div class='chart".$i."' data-percent='".$percent."' data-scale-color='#ffb400'><span class='percent".$i."'></span></div>
        </td></tr>";
        $itog_sumball += $sumball;
        $itog_rightsumma += $rightsumma;
        $itog_nonsumma += $nonsumma;
        
        // Запишем результат +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // Проверка на существование результата 
        if ($log>0) echo " Группа записи = ".$lastid."<br>";
        $tmpres = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM testresults WHERE testid='".$tid."' AND qgroupid='".$lastid."' AND signature='".$sign."' ORDER BY id;");
        $totalres = mysqli_fetch_array($tmpres);
        $allrescount = $totalres['count(*)'];
        if ($allrescount==0)
        {
         if ($log>0) echo " Группа ".$lastid." записана.<br>";
         mysqli_query($mysqli,"START TRANSACTION;");
         $userid = USER_ID;
         mysqli_query($mysqli,"INSERT INTO testresults VALUES (0,
                                        $tid,
                                        $lastid,
                                        $userid,
                                        $qc,
                                        $sumball,
                                        $nonsumma,
                                        $rightsumma,
                                        '$sign',
                                        NOW());");
         mysqli_query($mysqli,"COMMIT");
        }
        mysqli_free_result($tmpres);
        // Запишем результат +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        
        break;
       }

        
       }
       else
       if ($aqc==$cnttd['count(*)'])
       {
        
        ?>
<style type="text/css">
.chart<? echo $i?> {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 60px;
  margin-top: 0px;
  margin-bottom: 0px;
  text-align: center;
}
.chart<? echo $i?> canvas {
  position: absolute;
  top: 0;
  left: 0;
}
.percent<? echo $i?> {
  display: inline-block;
  line-height: 60px;
  z-index: 2;
  font-size: .8em;
}
.percent<? echo $i?>:after {
  content: '%';
  margin-left: 0.1em;
  font-size: .8em;
}
</style>
<script>
  $(function() {
    $('.chart<? echo $i?>').easyPieChart({
      size : 60,
      easing: 'easeOutBounce',
      onStep: function(from, to, percent) {
				$(this.el).find('.percent<? echo $i?>').text(Math.round(percent));
			}		
    });
  });
</script>
        <?
        
       // Найдем балл за правильный ответ на последний вопрос 
       // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
       $rtball=1; // По умолчанию - 1
       $qgp = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questgroups WHERE id='".$testdata['qgroupid']."' LIMIT 1;");
       if ($qgp)
       {
         $questgp = mysqli_fetch_array($qgp);
         $rtball = $questgp['singleball'];
       } 
       else if ($log>0) echo "err ";
       mysqli_free_result($qgp); 
       
       $qq = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questions WHERE id='".$testdata['questionid']."' ORDER BY id LIMIT 1;");
       $quest = mysqli_fetch_array($qq);
       $questtype = $quest['qtype'];
       mysqli_free_result($qq); 
       
       // Поищем правильные ответы
       $ans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM answers WHERE questionid='".$testdata['questionid']."' ORDER BY id");
       $ball=0;
       $kball=0;
       while($answer = mysqli_fetch_array($ans))
       {
        if ($log>0) echo "<br>";
        if ($questtype=='multichoice')
        {
         $userans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM tmpmultianswer WHERE questionid='".$testdata['questionid']."' AND answerid='".$answer['id']."' AND signature='".$sign."' LIMIT 1;");
         if ($userans)
         {
          if ($answer['ball']>0) 
           $kball++;
          $useranswer = mysqli_fetch_array($userans);
          $ku = $useranswer['value'];
          if (empty($ku))
           $ku = 0;
          if ($log>0) echo "u=".$ku." a=".$answer['ball'];
          if ($ku and $answer['ball']>0) 
           $ball++;
          if ($ku and $answer['ball']==0) 
           $ball--;
         }
         mysqli_free_result($userans);   
        }
        else
        if ($questtype=='shortanswer')
        {
         $userans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT value FROM tmpshortanswer WHERE questionid='".$testdata['questionid']."' AND signature='".$sign."' ORDER BY id LIMIT 1;");
         if ($userans)
         {
          if ($answer['ball']>0) 
           $kball++;
          $useranswer = mysqli_fetch_array($userans);
          $kustr = trim(mb_strtolower($useranswer['value'],'UTF-8'));
          $ansk = trim(mb_strtolower($answer['name'],'UTF-8'));
          if ($log>0) echo "u=".$kustr." a=".$ansk." sign=".$sign." id=".$testdata['questionid'];
          if ($kustr == $ansk) 
           $ball++;
         }
         mysqli_free_result($userans);   
        }
       }
       mysqli_free_result($ans); 
       
       if ($questtype=='multichoice')
       {
        if ($ball==$kball and $ball>0) 
        { 
        // Ну вот и правильный ответ
        $sumball++;
        $rightsumma += $rtball;
        }
       }
       else
       if ($questtype=='shortanswer')
       {
        if ($ball>0) 
        { 
        // Ну вот и правильный ответ
        $sumball++;
        $rightsumma += $rtball;
        }
       }
       $nonsumma += $rtball;
       // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $nball = $qc-$sumball;
        echo "<td align='center'><p>".$qc."</p></td><td align='center'><p>".$sumball."</p></td>
        <td align='center'><p>".$nball."</p></td><td align='center'><p>".$rightsumma." из ".$nonsumma."</p></td>";
        $percent = (int) floor($rightsumma / $nonsumma * 100);
        echo "<td align='center'>
        <div class='chart".$i."' data-percent='".$percent."' data-scale-color='#ffb400'><span class='percent".$i."'></span></div>
        </td></tr>";
        $itog_sumball += $sumball;
        $itog_rightsumma += $rightsumma;
        $itog_nonsumma += $nonsumma;
        
        // Запишем результат +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // Проверка на существование результата 
        if ($log>0) echo " Группа записи = ".$lastid."<br>";
        $tmpres = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM testresults WHERE testid='".$tid."' AND qgroupid='".$lastid."' AND signature='".$sign."' ORDER BY id;");
        $totalres = mysqli_fetch_array($tmpres);
        $allrescount = $totalres['count(*)'];
        if ($allrescount==0)
        {
         if ($log>0) echo " Группа ".$lastid." записана.<br>";
         mysqli_query($mysqli,"START TRANSACTION;");
         $userid = USER_ID;
         mysqli_query($mysqli,"INSERT INTO testresults VALUES (0,
                                        $tid,
                                        $lastid,
                                        $userid,
                                        $qc,
                                        $sumball,
                                        $nonsumma,
                                        $rightsumma,
                                        '$sign',
                                        NOW());");
         mysqli_query($mysqli,"COMMIT");
        }
        mysqli_free_result($tmpres);
        // Запишем результат +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        
        break;
       }
       
       // Найдем балл за правильный ответ
       // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
       $rtball=1; // По умолчанию - 1
       $qgp = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questgroups WHERE id='".$testdata['qgroupid']."' LIMIT 1;");
       if ($qgp)
       {
         $questgp = mysqli_fetch_array($qgp);
         $rtball = $questgp['singleball'];
       } 
       else if ($log>0) echo "err ";
       mysqli_free_result($qgp); 
       
       $qq = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questions WHERE id='".$testdata['questionid']."' ORDER BY id LIMIT 1;");
       $quest = mysqli_fetch_array($qq);
       $questtype = $quest['qtype'];
       mysqli_free_result($qq); 
       
       // Поищем правильные ответы
       $ans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM answers WHERE questionid='".$testdata['questionid']."' ORDER BY id");
       $ball=0;
       $kball=0;
       while($answer = mysqli_fetch_array($ans))
       {
        if ($log>0) echo "<br>";
        if ($questtype=='multichoice')
        {
         $userans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM tmpmultianswer WHERE questionid='".$testdata['questionid']."' AND answerid='".$answer['id']."' AND signature='".$sign."' LIMIT 1;");
         if ($userans)
         {
          if ($answer['ball']>0) 
           $kball++;
          $useranswer = mysqli_fetch_array($userans);
          $ku = $useranswer['value'];
          if (empty($ku))
           $ku = 0;
          if ($log>0) echo "u=".$ku." a=".$answer['ball'];
          if ($ku and $answer['ball']>0) 
           $ball++;
          if ($ku and $answer['ball']==0) 
           $ball--;
         }
         mysqli_free_result($userans);   
        }
        else
        if ($questtype=='shortanswer')
        {
         $userans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM tmpshortanswer WHERE questionid='".$testdata['questionid']."' AND signature='".$sign."' LIMIT 1;");
         if ($userans)
         {
          if ($answer['ball']>0) 
           $kball++;
          $useranswer = mysqli_fetch_array($userans);
          $kustr = trim(mb_strtolower($useranswer['value'],'UTF-8'));
          $ansk = trim(mb_strtolower($answer['name'],'UTF-8'));
          if ($log>0) echo "u=".$kustr." a=".$ansk." sign=".$sign." id=".$testdata['questionid'];
          if ($kustr == $ansk) 
           $ball++;
         }
         mysqli_free_result($userans);   
        }
       }
       mysqli_free_result($ans); 
       
       if ($questtype=='multichoice')
       {
        if ($ball==$kball and $ball>0) 
        { 
        // Ну вот и правильный ответ
        $sumball++;
        $rightsumma += $rtball;
        }
       }
       else
       if ($questtype=='shortanswer')
       {
        if ($ball>0) 
        { 
        // Ну вот и правильный ответ
        $sumball++;
        $rightsumma += $rtball;
        }
       }
       $nonsumma += $rtball;
       // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
       
      }
      $itog_allq = $cnttd['count(*)'];
      mysql_free_result($ctd);
      mysql_free_result($td);
      
      echo "</table></div></td></tr><tr><td align='center'>";
      
        // Запишем итоговый результат +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // Проверка на существование итогов результата 
        $tmpres = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM singleresult WHERE testid='".$tid."' AND signature='".$sign."' ORDER BY id;");
        $totalres = mysqli_fetch_array($tmpres);
        $allrescount = $totalres['count(*)'];
        if ($allrescount==0)
        {
         mysqli_query($mysqli,"START TRANSACTION;");
         $userid = USER_ID;
         mysqli_query($mysqli,"INSERT INTO singleresult VALUES (0,
                                        $tid,
                                        $userid,
                                        $itog_allq,
                                        $itog_sumball,
                                        $itog_nonsumma,
                                        $itog_rightsumma,
                                        '$sign',
                                        NOW());");
         $resultid = mysqli_insert_id($mysqli);                               
         mysqli_query($mysqli,"COMMIT");
        }
        mysqli_free_result($tmpres);
        
        
        // Запишем результат +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        
}
else  // Покажем записанные результаты
{
      $td = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM testresults WHERE signature='".$sign."' AND testid='".$tid."' AND userid='".$userid."' ORDER BY id");
      $i=0;
      while($member = mysqli_fetch_array($td))
      {
        $i++;
        
if ($rtf==0) {        
?>
<style type="text/css">
.chart<? echo $i?> {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 60px;
  margin-top: 0px;
  margin-bottom: 0px;
  text-align: center;
}
.chart<? echo $i?> canvas {
  position: absolute;
  top: 0;
  left: 0;
}
.percent<? echo $i?> {
  display: inline-block;
  line-height: 60px;
  z-index: 2;
  font-size: .8em;
}
.percent<? echo $i?>:after {
  content: '%';
  margin-left: 0.1em;
  font-size: .8em;
}
</style>
<script>
  $(function() {
    $('.chart<? echo $i?>').easyPieChart({
      size : 60,
      easing: 'easeOutBounce',
      onStep: function(from, to, percent) {
				$(this.el).find('.percent<? echo $i?>').text(Math.round(percent));
			}		
    });
  });
</script>
<?  
}    
        $qg = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questgroups WHERE id='".$member['qgroupid']."' LIMIT 1");
        $questgroup = mysqli_fetch_array($qg);
        
        if ($rtf==0)
         echo "<tr><td align='center'><p>".$i."</p></td><td><p>".$questgroup['name']."</p>";
        else
        {
        
         $table->addRow(1);
         $cell = $table->getCell($i+1, 1);
         $cell->writeText($i);
         $cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
         $cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

         $cell = $table->getCell($i+1, 2);
         $cell->writeText($questgroup['name']);
         $cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_LEFT);
         $cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

        }
        if (!empty($questgroup['comment'])) 
         if ($rtf==0)
          echo "<p style='font-size:0.7em;'>".$questgroup['comment']."</p>";
                if ($rtf==0)
         echo"</td>";
        
        //$lastid = $testdata['qgroupid'];
        mysqli_free_result($qg);
        
        $nball = $member['allq'] - $member['rightq'];
        if ($rtf==0)
        {
         echo "<td align='center'><p>".$member['allq']."</p></td><td align='center'><p>".$member['rightq']."</p></td>
         <td align='center'><p>".$nball."</p></td><td align='center'><p>".$member['rightball']." из ".$member['allball']."</p></td>";
        }
        else
        {
         $cell = $table->getCell($i+1, 3);
         $cell->writeText($member['allq']);
         $cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
         $cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

         $cell = $table->getCell($i+1, 4);
         $cell->writeText($member['rightq']);
         $cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
         $cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

         $cell = $table->getCell($i+1, 5);
         $cell->writeText($nball);
         $cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
         $cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);

         $cell = $table->getCell($i+1, 6);
         $cell->writeText($member['rightball']." из ".$member['allball']);
         $cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
         $cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);
        }
        $percent = (int) floor($member['rightball'] / $member['allball'] * 100);
         if ($rtf==0)
         {
          echo "<td align='center'>
          <div class='chart".$i."' data-percent='".$percent."' data-scale-color='#ffb400'><span class='percent".$i."'></span></div>
          </td></tr>";
         }
         else
         {
          $cell = $table->getCell($i+1, 7);
          $cell->writeText($percent."%");
          $cell->setTextAlignment(PHPRtfLite_Table_Cell::TEXT_ALIGN_CENTER);
          $cell->setVerticalAlignment(PHPRtfLite_Table_Cell::VERTICAL_ALIGN_CENTER);
         }
      }
      mysqli_free_result($td);

      if ($rtf==0)
       echo "</table></div></td></tr><tr><td align='center'>";
if ($rtf==0)
{
  if ($showprotocol)
  {
    ?>
<script>
  function closeFancybox(){
    $.fancybox.close();
   }
  $(document).ready(function() {
    	$("#viewprotocol").click(function() {
				$.fancybox.open({
					href : 'resultprotocol&id=<? echo $resultid ?>',
					type : 'iframe',
          width : document.documentElement.clientWidth,
          height : document.documentElement.clientHeight,
          fitToView : true,
          autoSize : false,
          modal : true,
          showCloseButton : false,
					padding : 5
				});
			});
  });
</script>
<?
 }
}
      
}
      
if ($rtf==0)
{
?>    
<p><table width="600" border="0" cellpadding=3 cellspacing=0 bordercolorlight=white bordercolordark=white>
    <tr align='center'>
     <td align='center'>
      <div id="menu_glide" class="menu_glide">
      <table align='center' width='280' class=bodytable border="0" cellpadding=2 cellspacing=0 bordercolorlight=gray bordercolordark=white>
      <tr><td align='center'>
<?

      $percent = (int) floor($itog_rightsumma / $itog_nonsumma * 100);
      echo "<p align='center'>Итоговый балл: ".$itog_rightsumma." из ".$itog_nonsumma."</p>";
      echo "</p>";
      echo "<div class='chart' data-percent='".$percent."' data-scale-color='#ffb400'><span class='percent'></span></div>";
?>    
      </td></tr>
      </table>
      </div>
     </td>  
     <td align='center'>
      <div id="menu_glide" class="menu_glide">
      <table align='center' width='280' class=bodytable border="0" cellpadding=2 cellspacing=0 bordercolorlight=gray bordercolordark=white>
      <tr><td align='center'>
<?
      $percent = (int) floor($itog_sumball / $itog_allq * 100);
      echo "<p align='center'>Правильно отвечено: ".$itog_sumball." из ".$itog_allq."</p>";
      echo "</p>";
      echo "<div class='charti' data-percent='".$percent."' data-scale-color='#ffb400'><span class='percenti'></span></div>";
?>    
      </td></tr>
      </table>
      </div>
     </td>  
    </tr> 
</table></p>    

    </td></tr>
  </table>  
  </div>  
  <div id="buttonset"> 
     <a href="testresults&rtf=1&id=<? echo $resultid; ?>" style="font-size: 1em;" id="print"><i class='fa fa-print fa-lg'></i> Печать</a>&nbsp; 
    <?
     if ($showprotocol)
     {?>
     <button style="font-size: 1em;" id="viewprotocol"><i class='fa fa-search fa-lg'></i> Протокол тестирования</button>&nbsp; 
    <?}?>
    <?
     if (!empty($reloadurl))
     {
     $reloadurl = str_replace("|", "&", $reloadurl);
    ?>
    <button style="font-size: 1em;" id="close" onclick="parent.closeFancyboxAndRedirectToUrl('<? echo $site."/".$reloadurl ?>');"><i class='fa fa-check fa-lg'></i> Закрыть</button> 
    <? } else {?>
    <button style="font-size: 1em;" id="close" onclick="parent.closeFancybox();"><i class='fa fa-check fa-lg'></i> Закрыть</button> 
    <?}?>
    </div>
</body></html>
<?
}
else
{

$percent = (int) floor($itog_rightsumma / $itog_nonsumma * 100);
$sect->writeText('<i>Итоговый балл: <b>'.$itog_rightsumma.' из '.$itog_nonsumma.'</i> ('.$percent.'%) </b>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
$sect->writeText(' ', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
$percent = (int) floor($itog_sumball / $itog_allq * 100);
$sect->writeText('<i>Правильно отвечено: <b>'.$itog_sumball.' из '.$itog_allq.'</i> ('.$percent.'%) </b>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
$sect->writeText(' ', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));

require_once ('lib/transliteration.inc');
header('Content-Type: application/octet-stream');
$filename = transliteration_clean_filename("result_".$testname."_".$userfio.".rtf","ru");
header('Content-Disposition: attachment;filename="'.$filename.'"');
header('Cache-Control: max-age=0');
header('Content-Transfer-Encoding: binary');

header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

// save rtf document
$rtfs->save('php://output');
}
?>

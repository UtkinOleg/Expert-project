<?
if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) 
{  

 include "config.php";
 include "func.php";

 $resultid = $_GET['id']; // ID Результата
 $rtf = $_GET['rtf']; // ID Результата
 if (empty($rtf)) $rtf = 0;

 $log=0;

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
 mysqli_free_result($res); 
 
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

if ($rtf==0) 
{
require_once "header.php"; 
?>
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
</style>
<script>
  $(function() {
    $("#close").button();
    $("#print").button();
    $("a").button();
  });
  
  function getans(id) {
   $.post('getansjson',{qid:id},  
    function(data){  
      eval('var obj='+data);         
      if(obj.ok=='1')
      {
       $('#lnk'+id).empty();        
       $('#ans'+id).empty();        
       $('#ans'+id).append(obj.content);        
      } 
      else 
       alert("Ошибка при получении данных.");
    }); 
  } 
</script>
</head><body>
<h3 class='ui-widget-header ui-corner-all' style="margin-top:0px;" align="center"><p>Протокол тестирования по тесту "<? echo $testname; ?>", <? echo $userfio; ?></p></h3>
<div id="buttonsetm">
<?
}
else
{
$dir = dirname(__FILE__);
require_once $dir . '/lib/Classes/PHPRtfLite/lib/PHPRtfLite.php';

// register PHPRtfLite class loader
PHPRtfLite::registerAutoloader();

$rtfs = new PHPRtfLite();

$sect = $rtfs->addSection();

$sect->writeText(' ', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER));
$sect->addImage($dir . '/img/logoexpert.png', null );
$sect->writeText('<i>Протокол тестирования по тесту <b>'.$testname.'</b></i>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER));
$sect->writeText('<i>Имя: <b>'.$userfio.'</b></i>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER));
$sect->writeText('<i>Дата тестирования: <b>'.$testdate.'</b></i>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER));
$sect->writeText(' ', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_CENTER));

}

  $td = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM tmptest WHERE testid='".$tid."' AND userid='".$userid."' AND signature='".$sign."' ORDER BY id;");
  $i=0;
  while($testdata = mysqli_fetch_array($td))
  {
       $qq = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questions WHERE id='".$testdata['questionid']."' ORDER BY id LIMIT 1;");
       $quest = mysqli_fetch_array($qq);
       $questname = $quest['content'];
       $questtype = $quest['qtype'];
       mysqli_free_result($qq); 

       $rtball=1; // По умолчанию - 1
       $qgp = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questgroups WHERE id='".$testdata['qgroupid']."' LIMIT 1;");
       if ($qgp)
       {
         $questgp = mysqli_fetch_array($qgp);
         $rtball = $questgp['singleball'];
       } 
       else 
        if ($log>0 and $rtf==0) echo "err ";
       mysqli_free_result($qgp);
              
       // Поищем правильные ответы
       $strans="";
       $ans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM answers WHERE questionid='".$testdata['questionid']."' ORDER BY id");
       $ball=0;
       $kball=0;
       $ansshow=0;
       while($answer = mysqli_fetch_array($ans))
       {
        if ($log>0 and $rtf==0) echo "<br>";
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
          if ($ku>0)
          {
           if ($rtf==0)
            $strans .= "<p><strong>".$answer['name']."</strong></p>";
           else
            $strans .= '<b>'.$answer['name'].'</b>';
          }
          if ($log>0 and $rtf==0) 
           echo "u=".$ku." a=".$answer['ball'];
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
          if ($ansshow==0) {
           if ($rtf==0)
            $strans .= "<p><strong>".$useranswer['value']."</strong></p>";
           else
            $strans .= 'Ответ: <b>'.$useranswer['value'].'</b>';
           $ansshow++;
          }
          if ($log>0 and $rtf==0) 
            echo "u=".$kustr." a=".$ansk." sign=".$sign." id=".$testdata['questionid'];
          if ($kustr == $ansk) 
           $ball++;
         }
         mysqli_free_result($userans);   
        }
       }
       mysqli_free_result($ans); 
       
       if (empty($strans))
       {
        if ($rtf==0)
          $lnk = "<p>Ответа нет</p>";
         else
          $lnk = "<b>Ответа нет</b>";
        if ($rtf==0)
        {
         $lnk .= "<div id='lnk".$testdata['questionid']."'><a href='javascript:;' onclick=\"getans(".$testdata['questionid'].");\" title='Посмотреть правильный ответ на вопрос'><i class='fa fa-sort-alpha-asc'></i> Правильный ответ</a></div>";
         $lnk .= "<div id='ans".$testdata['questionid']."'></div>";
        }
       }
       else
       {
        $lnk = $strans;
        if ($ball!=$kball or $ball==0)
        {
         if ($rtf==0)
         {
          $lnk .= "<div id='lnk".$testdata['questionid']."'><a href='javascript:;' onclick=\"getans(".$testdata['questionid'].");\" title='Посмотреть правильный ответ на вопрос'><i class='fa fa-sort-alpha-asc'></i> Правильный ответ </a></div>";
          $lnk .= "<div id='ans".$testdata['questionid']."'></div>";
         }
        }
       }
       
       if ($questtype=='multichoice')
       {
        if ($ball==$kball and $ball>0) 
        { 
         // Ну вот и правильный ответ
         if ($rtf==0)
         {
          echo "<p align='center'><strong>Вопрос №".++$i."</strong></p><div id='menu_glide' style='padding:10px; background: #B2F5B6;' class='ui-widget-content ui-corner-all'>".$questname."</p>";
          echo $lnk;
          echo "<p>Баллов получено:".$rtball."</p>";
         }
         else
         {
          $sect->writeText('<i><b>Вопрос №'.++$i.'</b>: '.$questname.'</i>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText($lnk, new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText('Баллов получено: <b>'.$rtball.'</b>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText(' ', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
         }
        }
        else
        {
         if ($rtf==0)
         {
          echo "<p align='center'><strong>Вопрос №".++$i."</strong></p><div id='menu_glide' style='padding:10px; background: #FCC0C0;' class='ui-widget-content ui-corner-all'><tr style='background-color:#FCC0C0;'><p>".$questname."</p>";
          echo $lnk;
         }
         else
         {
          $sect->writeText('<b>Вопрос №'.++$i.'</b>: '.$questname.'', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText($lnk, new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText('Баллов получено: <b>0</b>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText(' ', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
         }
        }
       }
       else
       if ($questtype=='shortanswer')
       {
        if ($ball>0) 
        { 
         // Ну вот и правильный ответ
         if ($rtf==0)
         {
          echo "<p align='center'><strong>Вопрос №".++$i."</strong></p><div id='menu_glide' style='padding:10px; background: #B2F5B6;' class='ui-widget-content ui-corner-all'><p>".$questname."</p>";
          echo $lnk;
          echo "<p>Баллов получено:".$rtball."</p>";
         }
         else
         {
          $sect->writeText('<i><b>Вопрос №'.++$i.'</b>: '.$questname.'</i>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText($lnk, new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText('Баллов получено: <b>'.$rtball.'</b>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText(' ', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
         }
        }
        else
        {
         if ($rtf==0)
         {
          echo "<p align='center'><strong>Вопрос №".++$i."</strong></p><div id='menu_glide' style='padding:10px; background: #FCC0C0;' class='ui-widget-content ui-corner-all'><tr style='background-color:#FCC0C0;'><p>".$questname."</p>";
          echo $lnk;
         }
         else
         {
          $sect->writeText('<i><b>Вопрос №'.++$i.'</b>: '.$questname.'</i>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText($lnk, new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText('Баллов получено: <b>0</b>', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
          $sect->writeText(' ', new PHPRtfLite_Font(12), new PHPRtfLite_ParFormat(PHPRtfLite_ParFormat::TEXT_ALIGN_LEFT));
         }
        }
       }
      if ($rtf==0)
       echo "</div>";
  }
  mysqli_free_result($td); 
if ($rtf==0)
{
?>      
  </div> 
  <div id="buttonset"> 
     <a href="resultprotocol&rtf=1&id=<? echo $resultid; ?>" style="font-size: 1em;" id="print"><i class='fa fa-print fa-lg'></i> Печать</a>&nbsp; 
     <button style="font-size: 1em;" id="close" onclick="parent.closeFancybox();"><i class='fa fa-check fa-lg'></i> Закрыть</button> 
  </div>
</body></html>
<? 
}
else
{
require_once ('lib/transliteration.inc');
header('Content-Type: application/octet-stream');
$filename = transliteration_clean_filename("protocol_".$testname."_".$userfio.".rtf","ru");
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

} else die;
?>
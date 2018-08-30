<?php
if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) {  
// Устанавливаем соединение с базой данных
include "config.php";
include "func.php";
require_once "header.php"; 
?>
<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
<style type="text/css">
.ui-widget { font-family: Verdana,Arial,sans-serif; font-size: 0.7em;}
.button_disabled { background: #D1D4D8;  }.button_enabled {  } 
p {   font: 16px / 1.4 'Helvetica', 'Arial', sans-serif; } 
#spinner {   display: none;   position: fixed; 	top: 50%; 	left: 50%; 	margin-top: -22px; 	margin-left: -22px; 	background-position: 0 -108px; 	opacity: 0.8; 	cursor: pointer; 	z-index: 8060;   width: 44px; 	height: 44px; 	background: #000 url('scripts/fancybox_loading.gif') center center no-repeat;   border-radius:7px; } 
#buttonset {   display:block;  font-family:Arial;  text-align: center;   width: 600px;   height: 40px;   left: 50%;  bottom : 0px;  position: absolute;  margin-left: -300px; } 
</style>
<?

$action = "";
$action = $_POST["action"];


if ($action=='stepfive')
{
    $paid = $_POST["paid"];
    $groupid = $_POST["qgid"];
    $testid = $_POST["testid"];
    mysqli_query($mysqli,"START TRANSACTION;");
    $totq = mysqli_query($mysqli,"SELECT count(*) FROM questions WHERE qgroupid='".$groupid."'");
    $totalq = mysqli_fetch_array($totq);
    if ($totalq['count(*)']>0)
    {
     $questcount = $_POST["qg".$groupid];
     $rnd = $_POST["rnd".$groupid];
     if ($questcount>0)
     {
      $query = "INSERT INTO testdata VALUES (0,
                                        $testid,
                                        $questcount,
                                        $rnd, 
                                        $groupid);";
      mysqli_query($mysqli,$query);
     }
    } 
    mysqli_query($mysqli,"COMMIT");
    echo '<script language="javascript">';
    echo 'parent.closeFancyboxAndRedirectToUrl("'.$site.'/testoptions&paid='.$paid.'");';
    echo '</script>';
    exit();
}
else
if ($action=='stepfour')
{
    $paid = $_POST["paid"];
    $groupid = $_POST["qgid"];
    $name = $_POST["name"];
    if (empty($maxball)) $maxball=0;
    $testfor = $_POST["testfor"];
    if (empty($testfor)) $testfor='';
    $attempt = $_POST["attempt"];
    if (empty($attempt)) $attempt=0;
    $userid = USER_ID;

    $token = md5(time().$userid.$name);  // Уникальная сигнатура теста
    mysqli_query($mysqli,"START TRANSACTION;");
    $query = "INSERT INTO testgroups VALUES (0,
                                        '$name',
                                        $maxball,
                                        $paid, 
                                        '$testfor', 
                                        0, 
                                        $attempt,
                                        $userid,
                                        NOW(),
                                        '$token');";
    if (mysqli_query($mysqli,$query))
     $testid = mysqli_insert_id($mysqli);
    else
     $testid = 0;
    mysqli_query($mysqli,"COMMIT");
?>
<link rel="stylesheet" href="scripts/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="scripts/jquery.fancybox.pack.js?v=2.1.5"></script>
<script type="text/javascript" src="scripts/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>
<link rel="stylesheet" type="text/css" href="scripts/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
<script type="text/javascript" src="scripts/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>
<script>
  $(function() {
    $(".ui-state-error").hide();
    $("#spinner").fadeOut("slow");
    $( "#next" ).button();
    $( "#close" ).button();
  });
 $(document).ready(function(){
    $('form').submit(function(){
     $('#next', $(this)).attr('disabled', 'disabled');
     $("#spinner").fadeIn("slow");
    });   
  });
</script>
</head>
<body>
  <form id="step_five" action="createtest" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="stepfive">
    <input type="hidden" name="paid" value="<? echo $paid; ?>">
    <div id="spinner">
    </div>
    <h3 class='ui-widget-header ui-corner-all' align="center">
      <p>Создание теста - Шаг 5 - Установка параметров выборки вопросов
      </p></h3>
    <div class="ui-widget">	
      <div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em; font: 14px / 1.4 'Helvetica', 'Arial', sans-serif;">		
        <p>
          <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;">
          </span> Остался последний шаг - установить параметры выборки вопросов из группы. После нажатия на кнопку "Готово", тест будет создан. Вернуться к редактированю теста и группы вопросов можно пока по тесту не проведено ни одного сеанса тестирования. Для того чтобы тест стал доступен для участников или экспертов его необходимо "включить". "Включение" теста устанавливается в параметрах.
        </p>	
      </div>
    </div>
    <div class="ui-widget">	
      <div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em; font: 14px / 1.4 'Helvetica', 'Arial', sans-serif;">		
        <p>
          <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;">
          </span>	При помощи 'слайдера' укажите количество вопросов, которое будет использоваться в тесте. Можно также установить параметр случайной выборки вопросов из группы. Вопросы из группы не будут использоваться (добавляться в тест), если установленное количество равно нулю.
        </p>	
      </div>
    </div>
    <table border=0 cellpadding=0 cellspacing=0 width='100%' height='100%' bgcolor='#ffffff'>
      <tr><td>
          <p align='center'>
            <div id="menu_glide" class="menu_glide" style="margin-top:40px;">
              <table class=bodytable border="0" width='95%' height='100%' align='center' cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
<?
  if ($testid != 0)
  {
                ?>    
                <input type="hidden" name="qgid" value="<? echo $groupid; ?>">    
                <input type="hidden" name="testid" value="<? echo $testid; ?>">    
                <tr class=tableheaderhide>              
                  <td align='center' witdh='200'>
                    <p class=help>Наименование группы
                    </p></td>              
                  <td align='center' witdh='30'>
                    <p class=help>Балльная стоимость
                    </p></td>              
                  <td align='center' witdh='30'>
                    <p class=help>Время ответа (мин)
                    </p></td>              
                  <td align='center' witdh='50'>
                    <p class=help>Дата создания
                    </p></td>              
                  <td align='center' witdh='300'>
                    <p class=help>Параметры
                    </p></td>              
                  <td align='center' witdh='70'>
                    <p class=help>Случайно
                    </p></td>    
                </tr>     
<?
    $gst = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questgroups WHERE userid='".USER_ID."' AND id='".$groupid."' LIMIT 1;");
    if (!$gst) puterror("Ошибка при обращении к базе данных");
    $member = mysqli_fetch_array($gst);
  
    $td = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM testdata WHERE groupid='".$member['id']."' AND testid='".$testid."' LIMIT 1;");
    $totaltd = mysqli_fetch_array($td);
    $grintest = $totaltd['count(*)'];
    mysqli_free_result($td);
  
    $totq = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM questions WHERE qgroupid='".$member['id']."'");
    $total = mysqli_fetch_array($totq);
    if ($total['count(*)']>0 and $grintest==0)
    {
     echo "<tr>";
     echo "<td width='200'>
     <p class=zag2><a title='Посмотреть список вопросов' id='listquestions".$member['id']."' href='javascript:;'><i class='fa fa-search fa-lg'></i> ".$member['name']."</a></p></td>";
     echo "<td align='center'><p class=zag2>".$member['singleball']."</p></td>";
     echo "<td align='center'><p class=zag2>".$member['singletime']."</p></td>";
     echo "<td><p class=zag2>".data_convert ($member['regdate'], 1, 0, 0)."</p></td>";
     echo "<td width='300' align='center'>";
     echo "<p><div style='margin: 3px;' id='slideru".$member['id']."'></div>
     <label for='qg".$member['id']."' id='lqg".$member['id']."'>Вопросов: 0</label>
     <input type='hidden' id='qg".$member['id']."' name='qg".$member['id']."'/></p>";
                     ?>        
<script>
        $(function() {
          $( "#slideru<? echo $member['id'];?>" ).slider({
           range: "min", value:0, min: 0, max: <? echo $total['count(*)']; ?>, step: 1,
           slide: function( event, ui ) {
           $( "#qg<? echo $member['id'];?>" ).val(ui.value);
           $( "#lqg<? echo $member['id'];?>" ).text('Вопросов: ' + ui.value);
           }
          });
        });
    	  $(document).ready(function() {
         $("#rndp<?php echo $member['id']; ?>").buttonset();
      	 $("#listquestions<?php echo $member['id']; ?>").click(function() {
	  			$.fancybox.open({
					href : 'listquestions&id=<? echo $member['id'] ?>',
					type : 'iframe',
          width : 900,
          height : 600,
          fitToView : false,
          autoSize : false,          
					padding : 5
				 });
			  });
        });
     </script>          </td><td align="center">         
          <div id="rndp<?echo $member['id'];?>">          
            <input type="radio" value='1' id="closed1_<?echo $member['id'];?>" name="rnd<?echo $member['id'];?>" checked="checked">
            <label for="closed1_<?echo $member['id'];?>">Да
            </label>                  
            <input type="radio" value='0' id="closed2_<?echo $member['id'];?>" name="rnd<?echo $member['id'];?>">
            <label for="closed2_<?echo $member['id'];?>">Нет
            </label>                 
          </div>         </td>         
<?         
    }
    mysqli_free_result($gst);
  }
  else
  {
   echo "<tr><td align='center'><p>Ошибка сохранения параметров теста.</p></td></tr>";
  } 
        ?>
    </table>
    </div>
    </p></td>
    </tr>
    </table>
    <div id="buttonset">  
      <? if ($groupid != 0 and $testid != 0) {?>  
      <button style="font-size: 1em;" id="next" onclick="$('#step_five').submit();">
        <i class='fa fa-check fa-lg'></i> Готово
      </button>    
      <button style="font-size: 1em;" id="close" onclick="parent.closeFancyboxAndRedirectToUrl('<? echo $site."/deltestquestgroup&paid=".$paid."&tid=".$testid."&qgid=".$groupid; ?>');">
        <i class='fa fa-times fa-lg'></i> Отмена
      </button>  
      <?} else 
      if ($testid == 0) {?>   
      <button style="font-size: 1em;" id="close" onclick="parent.closeFancyboxAndRedirectToUrl('<? echo $site."/delquestgroup&id=".$groupid; ?>');">
        <i class='fa fa-times fa-lg'></i> Отмена
      </button>  
      <?}?>
    </div>
  </form>
</body>
</html>
<?
}
else
if ($action=='stepthree')
{
    $paid = $_POST["paid"];
    $groupid = $_POST["qgid"];
    if (!empty($_FILES["textfile"]))
    {
     $origfilename = $_FILES["textfile"]["name"]; 
     $filename = explode(".", $origfilename); 
     $filenameext = $filename[count($filename)-1]; 
     if ($filenameext=='xml')
      $error = ImportXML($mysqli, $_FILES["textfile"], $groupid, $xmlupload_dir);
     else 
     if ($filenameext=='txt')
      $error = ImportTXT($mysqli, $_FILES["textfile"], $groupid, $xmlupload_dir);
/*     else 
     if ($filenameext=='doc')
     {
      $s = read_doc_file($_FILES["textfile"], $groupid, $xmlupload_dir);
      $error = ImportTXTContent($mysqli, $s, $groupid);
     }
     else
     if ($filenameext=='docx')
     {
      $s = read_docx_file($_FILES["textfile"], $groupid, $xmlupload_dir);
      $error = ImportTXTContent($mysqli, $s, $groupid);
     }  */
     else
      $error = " Расширение файла не поддерживается."; 
    }
    if (!empty($error))
     $groupid = 0;
?>
<script>
  $(function() {
    var paid = <? echo $paid?>;
    
    $(".ui-state-error").hide();
    $("#spinner").fadeOut("slow");
    $( "#next" ).button();
    $( "#close" ).button();

     if (paid==0) {
     $( "#testfor" ).selectmenu({ disabled: true });
     $( "#attempt" ).selectmenu({ disabled: true });
     $( "#slideru" ).slider({
           disabled: true,
           range: "min", value: 50, min: 0, max: 100, step: 1,
           slide: function( event, ui ) {
             $( "#maxball" ).val(ui.value);
             $( "#mb" ).text(ui.value + '%');
            }
          });
     }
     else
     {
     $( "#testfor" ).selectmenu();
     $( "#attempt" ).selectmenu();
     $( "#slideru" ).slider({
           range: "min", value: 50, min: 0, max: 100, step: 1,
           slide: function( event, ui ) {
             $( "#maxball" ).val(ui.value);
             $( "#mb" ).text(ui.value + '%');
            }
          });
     }     
         
  });
 $(document).ready(function(){
    $('form').submit(function(){
     $(".ui-state-error").hide();
     $("#err2").empty();
     var hasError = false;
     if($("#name").val()=='') {
            $("#name").focus();
            $("#err2").append('Укажите наименование теста.');
            hasError = true;
     }
     if($("#maxball").val()==0) {
            $("#maxball").focus();
            $("#err2").append('Минимальный порог для прохождения теста не должен быть равен нулю.');
            hasError = true;
     }
     if(hasError == true) {     
       $(".ui-state-error").show();
       return false; 
     }
     $('#next', $(this)).attr('disabled', 'disabled');
     $("#spinner").fadeIn("slow");
    });   
  });
</script>
</head>
<body>
  <form id="step_four" action="createtest" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="stepfour">
    <input type="hidden" name="paid" value="<? echo $paid; ?>">
    <div id="spinner">
    </div>
    <h3 class='ui-widget-header ui-corner-all' align="center">
      <p>Создание теста - Шаг 3 - Установка параметров теста
      </p></h3>           
<?
  if ($groupid != 0) {
?>
    <div class="ui-widget">	
      <div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em; font: 14px / 1.4 'Helvetica', 'Arial', sans-serif;">		
        <p>
          <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;">
          </span> Вопросы импортированы. Устанавливаем параметры нового теста.
        </p>	
      </div>
    </div>
<?}?>
    <p></p>
    <div id="err1" class="ui-widget">            	
      <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; font: 14px / 1.4 'Helvetica', 'Arial', sans-serif;">               
        <p>
          <div id="err2">
          </div>
        </p>            	
      </div>           
    </div>   
    <table border=0 cellpadding=0 cellspacing=0 width='100%' height='100%' bgcolor='#ffffff'>
      <tr><td>
          <p align='center'>   
<?
  if ($groupid != 0)
  {
    $totq = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM questions WHERE qgroupid='".$groupid."' LIMIT 1;");
    $total = mysqli_fetch_array($totq);
    $totalq = $total['count(*)'];
    echo "В группу импортировано вопросов: ".$totalq;
    mysql_free_result($totq);
   }             
?>
          </p>     
          <p align='center'>
            <div id="menu_glide" class="menu_glide" style="margin-top:40px;">
              <table class=bodytable border="0" width='95%' height='100%' align='center' cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>    
<?
  $goahead=1;
  if (defined("IN_SUPERVISOR") and !defined("IN_ADMIN"))
  {
    $tot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM testgroups WHERE ownerid='".USER_ID."' ORDER BY id");
    $totalt = mysqli_fetch_array($tot);
    if (LOWSUPERVISOR and $totalt['count(*)']>0)
     $goahead = 0;
    mysqli_free_result($tot);
  }
  
  if ($groupid != 0 and $totalq > 0 and $goahead == 1)
  {
?>
                <input type="hidden" name="qgid" value="<? echo $groupid; ?>">    
                <tr>        
                  <td witdh='300'>
                    <p>Наименование теста
                    </p></td>        <td>
                    <input style="width:100%" type='text' id='name' name='name'></td>    
                </tr>
                <tr>        
                  <td witdh='300'>         
                    <p>Минимальный порог в процентах для прохождения теста
                    </p>        </td>        
                  <td align="center">
                    <div style='margin: 3px;' id='slideru'>
                    </div>         
                    <label for='maxball' id='mb'>50%
                    </label>         
                    <input type='hidden' id='maxball' name='maxball' value="50"/>        </td>    
                </tr>
                <tr>        
                  <td witdh='300'>         
                    <p>Тест используется для
                    </p></td>        <td>         
                    <select id="testfor" name="testfor">          
                      <option value='member' selected>участников (для входного контроля)
                      </option>";           
                      <option value='expert'>экспертов (для оценки уровня знаний)
                      </option>";          
                    </select>        </td>    
                </tr>
                <tr>        
                  <td witdh='300'>         
                    <p>Количество попыток тестирования
                    </p>        </td>        <td>        
                    <select id="attempt" name="attempt">         
                      <option value='0' selected>Без ограничений
                      </option>";          
                      <option value='1'>Одна
                      </option>";          
                      <option value='2'>Две
                      </option>";          
                      <option value='3'>Три
                      </option>";          
                      <option value='5'>Пять
                      </option>";         
                    </select>        </td>    
                </tr>   
<?
  }
  else
  {
   if ($goahead == 0)
    echo "<tr><td align='center'><p>Превышено количество доступных тестов. Создание теста запрещено.</p></td></tr>";
   else
   {
   if ($totalq==0) 
    echo "<tr><td align='center'><p>Ошибка импорта вопросов из файла: Вопросы в файле не найдены. Проверьте файл вопросов.</p></td></tr>";
   else
    echo "<tr><td align='center'><p>Ошибка импорта вопросов из файла: ".$error."</p></td></tr>";
   }
  } 
                ?>
              </table>
            </div>
          </p></td>
      </tr>
    </table>
    <div id="buttonset">  
      <? if ($groupid != 0 and $totalq > 0 and $goahead == 1) {?>  
      <button style="font-size: 1em;" id="next" onclick="$('#step_four').submit();">
        <i class='fa fa-arrow-right fa-lg'></i> Далее
      </button>    
      <?}
      if ($groupid != 0) {?>   
      <button style="font-size: 1em;" id="close" onclick="parent.closeFancyboxAndRedirectToUrl('<? echo $site."/delquestgroup&id=".$groupid; ?>');">
        <i class='fa fa-times fa-lg'></i> Отмена
      </button>  
      <?} else {?>
      <button style="font-size: 1em;" id="close" onclick="parent.closeFancybox();">
        <i class='fa fa-times fa-lg'></i> Отмена
      </button>  
      <?}?>
    </div>
  </form>
</body>
</html>
<?
}
else
if ($action=='steptwo')
{
    $paid = $_POST["paid"];
    $name = $_POST["name"];
    $singleball = $_POST["singleball"];
    $singletime = $_POST["singletime"];
    $author = USER_ID;
    $comment = htmlspecialchars($_POST["comment"], ENT_QUOTES); 
    $knowsid = $_POST["knowsid"];
    mysqli_query($mysqli,"START TRANSACTION;");
    $query = "INSERT INTO questgroups VALUES (0,
                                        '$name',
                                        $singleball,
                                        $singletime,
                                        NOW(),
                                        $author,
                                        '$comment', 
                                        $knowsid);";
    if (mysqli_query($mysqli,$query))
     $groupid = mysqli_insert_id($mysqli);
    else
     $groupid = 0;
    mysqli_query($mysqli,"COMMIT");
?>
<script>
  $(function() {
    $(".ui-state-error").hide();
    $("#spinner").fadeOut("slow");
    $( "#next" ).button();
    $( "#close" ).button();
  });
 $(document).ready(function(){
    $('form').submit(function(){
     $(".ui-state-error").hide();
     $("#err2").empty();
     var hasError = false;
     if($("#textfile").val()=='') {
            $("#textfile").focus();
            $("#err2").append('Необходимо выбрать XML или TXT файл для импорта.');
            hasError = true;
     }
     if (hasError == false)
     {
     var f2 = $("#textfile").val().search(/^.*\.(?:xml|txt)\s*$/ig);
     if(f2!=0){
            $("#textfile").focus();
            $("#tabs").tabs("option", "active", 0);
            $("#tabs").tabs("refresh");
            $("#err2").append(' Поддерживаются файлы только с расширением XML или TXT.');
            hasError = true;
     }
     }
     if(hasError == true) {     
       $(".ui-state-error").show();
       return false; 
     }
     $('#next', $(this)).attr('disabled', 'disabled');
     $("#spinner").fadeIn("slow");
    });   
  });
</script>
</head>
<body>
  <form id="step_three" action="createtest" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="stepthree">
    <input type="hidden" name="paid" value="<? echo $paid; ?>">
    <div id="spinner">
    </div>
    <h3 class='ui-widget-header ui-corner-all' align="center">
      <p>Создание теста - Шаг 2 - Импорт вопросов из файла
      </p></h3>           
    <div class="ui-widget">	
      <div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em; font: 14px / 1.4 'Helvetica', 'Arial', sans-serif;">		
        <p>
          <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;">
          </span> Теперь необходимо указать подготовленный текстовый файл в формате XML или TXT с готовыми вопросами.
        </p>	
      </div>
    </div>
    <p></p>
    <div id="err1" class="ui-widget">            	
      <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; font: 14px / 1.4 'Helvetica', 'Arial', sans-serif;">               
        <p>
          <div id="err2">
          </div>
        </p>            	
      </div>           
    </div>   
    <table border=0 cellpadding=0 cellspacing=0 width='100%' height='100%' bgcolor='#ffffff'>
      <tr><td>
          <p align='center'>
            <div id="menu_glide" class="menu_glide" style="margin-top:40px;">
              <table class=bodytable border="0" width='95%' height='100%' align='center' cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
<?
  if ($groupid != 0)
  {
                    ?>    
                <input type="hidden" name="qgid" value="<? echo $groupid; ?>">    
                <tr><td>        
                    <p>Загрузить вопросы из текстового файла. Выберите файл с расширением XML или TXT. Размер файла не должен превышать 1Мб.
                    </p>        
                    <input type='file' id='textfile' name='textfile'/>    </td>
                </tr>   
<?
  }
  else
  {
   echo "<tr><td align='center'><p>Ошибка записи группы вопросов.</p></td></tr>";
  } 
                ?>
              </table>
            </div>
          </p></td>
      </tr>
    </table>
    <div id="buttonset">  
      <? if ($groupid != 0) {?>  
      <button style="font-size: 1em;" id="next" onclick="$('#step_three').submit();">
        <i class='fa fa-arrow-right fa-lg'></i> Далее
      </button>    
      <button style="font-size: 1em;" id="close" onclick="parent.closeFancyboxAndRedirectToUrl('<? echo $site."/delquestgroup&id=".$groupid; ?>');">
        <i class='fa fa-times fa-lg'></i> Отмена
      </button>  
      <?} else {?>
      <button style="font-size: 1em;" id="close" onclick="parent.closeFancybox();">
        <i class='fa fa-times fa-lg'></i> Отмена
      </button>  
      <?}?>
    </div>
  </form>
</body>
</html>
<?
}
else
if ($action=='stepone') 
{
  $paid = $_POST["paid"];
?>
<script>
  $(function() {
    $(".ui-state-error").hide();
    $("#spinner").fadeOut("slow");
    $( "#next" ).button();
    $( "#close" ).button();
    $( "#knowsid" ).selectmenu();
    $( "#singleball" ).spinner({ min: 1 });
    $( "#singletime" ).spinner({ min: 1 });
 });
 $(document).ready(function(){
    $('form').submit(function()
    {
     $(".ui-state-error").hide();
     $("#err2").empty();
     var hasError = false;
     if($("#name").val()=='') {
            $("#name").focus();
            $("#err2").append('Укажите наименование группы вопросов!');
            hasError = true;
     }
     if($("#singletime").val()=='0') {
            $("#singletime").focus();
            $("#err2").append(' Время ответа на вопрос не должно быть равно нулю!');
            hasError = true;
     }
     if($("#singleball").val()=='0') {
            $("#singleball").focus();
            $("#err2").append(' Балл за вопрос не должен быть равен нулю!');
            hasError = true;
     }
     if(hasError == true) {     
       $(".ui-state-error").show();
       return false; 
     }
     
     $('#next', $(this)).attr('disabled', 'disabled');
     $("#spinner").fadeIn("slow");
    });   
  });
</script>
</head>
<body>
  <form id="step_two" action="createtest" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="steptwo">
    <input type="hidden" name="paid" value="<? echo $paid ?>">
    <div id="spinner">
    </div>
    <h3 class='ui-widget-header ui-corner-all' align="center">
      <p>Создание теста - Шаг 1 - Создание группы вопросов
      </p></h3>
    <div class="ui-widget">	
      <div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em; font: 14px / 1.4 'Helvetica', 'Arial', sans-serif;">		
        <p>
          <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;">
          </span> Установите параметры группы вопросов: наименование, балльную стоимость одного вопроса и время ответа на один вопрос.
        </p>	
      </div>
    </div>
    <p></p>
    <div id="err1" class="ui-widget">            	   
      <div class="ui-state-error ui-corner-all" style="padding: 0 .7em; font: 14px / 1.4 'Helvetica', 'Arial', sans-serif;">                    
        <p>      
          <div id="err2">      
          </div>    
        </p>            	   
      </div>
    </div>  
    <table border=0 cellpadding=0 cellspacing=0 width='100%' height='100%' bgcolor='#ffffff'>
      <tr><td>
          <p align='center'>
            <div id="menu_glide" class="menu_glide" style="margin-top:40px;">
              <table class=bodytable border="0" width='95%' height='100%' align='center' cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
<?
  $goahead = 1;
  if (defined("IN_SUPERVISOR") and !defined("IN_ADMIN"))
  {
    $tot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM questgroups WHERE userid='".USER_ID."' ORDER BY id");
    $totalqg = mysqli_fetch_array($tot);
    if (LOWSUPERVISOR and $totalqg['count(*)']>0)
     $goahead = 0;
    mysqli_free_result($tot);
  }
  
  if ($goahead==1) {
                   ?>    
                <tr>        
                  <td width="300">
                    <p>Наименование группы вопросов
                    </p></td>        <td>
                    <input style='width:100%' type=text name="name" id="name" value="Новая группа"></td>    
                </tr>
                <tr>        
                  <td width="300">
                    <p>Балльная стоимость одного вопроса (баллы)
                    </p></td>        <td>
                    <input name="singleball" id="singleball" size=5 value=1></td>    
                </tr>
                <tr>        
                  <td width="300">
                    <p>Время ответа на один вопрос (минут)
                    </p></td>        <td>
                    <input name="singletime" id="singletime" size=5 value=1></td>    
                </tr>    
                <tr>        
                  <td width="300">
                    <p>Область знаний для группы
                    </p></td><td>        
                    <select name="knowsid" id="knowsid" title="Область знаний для группы">
                     <option value='0'>Нет</option>        
<? 
          $know = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM knowledge WHERE userid='".USER_ID."' ORDER BY name");
          while($knowmember = mysqli_fetch_array($know))
            echo "<option value='".$knowmember['id']."'>".$knowmember['name']."</option>";
          mysqli_free_result($know);
                              ?>        
                    </select></td>    
                </tr>         
                <tr>
                  <td width="300">     
                    <p>Дополнительная информация о группе вопросов
                    </p></td>    <td>
<textarea name=comment style='width:100%' rows='5'></textarea>    </td>    
                </tr>   
<?
  }
  else
  {
   echo "<tr><td align='center'><p>Превышено количество групп вопросов. Создание теста запрещено.</p></td></tr>";
  } 
                ?>
              </table>
            </div>
          </p></td>
      </tr>
    </table>
    <div id="buttonset">  
      <? if ($goahead==1) {?>  
      <button style="font-size: 1em;" id="next" onclick="$('#step_two').submit();">
        <i class='fa fa-arrow-right fa-lg'></i> Далее
      </button>    
      <?}?>   
      <button style="font-size: 1em;" id="close" onclick="parent.closeFancybox();">
        <i class='fa fa-times fa-lg'></i> Отмена
      </button>  
    </div>
  </form>
</body>
</html>
<?
}
else
if (empty($action)) 
{
  $paid = $_GET["paid"];
?>
<script>
  $(function() {
    $( "#next" ).button();
    $( "#close" ).button();
  });
 $(document).ready(function(){
    $('form').submit(function(){
     $('#next', $(this)).attr('disabled', 'disabled');
     $("#spinner").fadeIn("slow");
    });   
  });
</script>
</head>
<body>
  <form id="step_one" action="createtest" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="stepone">
    <input type="hidden" name="paid" value="<? echo $paid ?>">
    <div id="spinner">
    </div>
    <h3 class='ui-widget-header ui-corner-all' align="center">
      <p>Создание теста
      </p></h3>
    <? if ($paid==0) {?>
    <div class="ui-widget">	
      <div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em; font: 14px / 1.4 'Helvetica', 'Arial', sans-serif;">		
        <p>
          <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;">
          </span>		Создаваемый тест может использоваться только для самостоятельного пробного тестирования.
        </p>	
      </div>
    </div>
    <?} else {?>
    <div class="ui-widget">	
      <div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em; font: 14px / 1.4 'Helvetica', 'Arial', sans-serif;">		
        <p>
          <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;">
          </span>		Создаваемый тест может использоваться как для входного тестирования участников перед размещением готовых проектов, так и для тестирования экспертов на предмет знаний в различных областях перед началом проведения экспертизы.
        </p>	
      </div>
    </div>
    <?}?>
    <table border=0 cellpadding=0 cellspacing=0 width='100%' height='100%' bgcolor='#ffffff'>
      <tr><td>
          <p align='center'>
            <div id="menu_glide" class="menu_glide" style="margin-top:40px;">
              <table class=bodytable border="0" width='95%' height='100%' align='center' cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
                <tr><td>
                    <p>Перед тем как создавать новый тест, необходимо подготовить файл вопросов. Файл можно сделать двумя способами:
                    </p>
                    <p>Первый - экспортировать готовые вопросы из системы <a href='http://moodle.org' target='_blank'>Moodle</a> в формате <strong>XML</strong>. (подробнее 
                      <a href="page&id=55" target="_blank">здесь</a> и в 
                      <a href="https://docs.moodle.org/23/en/Moodle_XML_format" target="_blank">документации Moodle</a>)
                    </p>
                    <p>Второй - подготовить текстовый файл (расширение файла должно быть <strong>TXT)</strong> в формате <a href='http://siberia-soft.ru' target='_blank'>СТ М-Тест</a> (
                      <a href="page&id=54" target="_blank">подробнее о формате</a>). Этот формат позволяет также переносить в вопрос картинки.
                    </p>
                    <p>
                    </p>
                    <p>Системой поддерживаются две формы ответов: закрытая (выбор одного или нескольких вариантов) и открытая (ввод ответа с клавиатуры).
                    </p>
                    <p>
                    </p>
                    <p>Если файл подготовлен - можно нажимать кнопку "
                      <a href="javascript:;" onclick="$('#step_one').submit();">Далее</a>".
                    </p></td>
                </tr>
              </table>
            </div>
          </p></td>
      </tr>
    </table>
    <div id="buttonset">  
      <button style="font-size: 1em;" id="next" onclick="$('#step_one').submit();">
        <i class='fa fa-arrow-right fa-lg'></i> Далее
      </button>    
      <button style="font-size: 1em;" id="close" onclick="parent.closeFancybox();">
        <i class='fa fa-times fa-lg'></i> Отмена
      </button>  
    </div>
  </form>
</body>
</html>
<?
}
} else die;
?>
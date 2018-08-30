<?php
if(defined("IN_ADMIN") or defined("IN_SUPERVISOR")) {  
// Устанавливаем соединение с базой данных
include "config.php";
include "func.php";

$error = "";
$action = $_POST["action"];

if ($action=='file') 
{
    $groupid = $_POST["qgid"];
    $kid = $_POST["kid"];
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
     } */
     else
      $error = " Расширение файла не поддерживается."; 
    }
    if (!empty($error))
     $groupid = 0;

    if (!empty($error)) 
    {
      echo '<script language="javascript">';
      echo 'alert("Ошибки:'.$error.'");
      parent.closeFancybox();';
      echo '</script>';
      exit();
    }
    else   
    {
      echo '<script language="javascript">';
      echo 'parent.closeFancyboxAndRedirectToUrl("'.$site.'/questgroups&kid='.$kid.'");';
      echo '</script>';
      exit();
    }  
}
else
if (empty($action)) 
{
   if (defined("IN_SUPERVISOR") or defined("IN_ADMIN")) 
   {

  $qgid = $_GET["id"];
  $kid = $_GET["kid"];
  

require_once "header.php"; 
?>
<script>
  $(function() {
    $("#spinner").fadeOut("slow");
    $( "#ok" ).button();
  });
 $(document).ready(function(){
    $('form').submit(function(){
     var hasError = false;
     if($("#textfile").val()=='') {
            $("#info2").empty();
            $("#info2").append('Необходимо выбрать XML или TXT файл для импорта.');
            hasError = true;
     }  
     if (hasError == false)
     {
     var f2 = $("#textfile").val().search(/^.*\.(?:xml|txt)\s*$/ig);
     if(f2!=0){
            $("#info2").empty();
            $("#info2").append(' Поддерживаются файлы только с расширением XML или TXT.');
            hasError = true;
     }
     }
     if(hasError == true) {     
       $("#info1").removeClass("ui-state-highlight");
       $("#info1").addClass("ui-state-error");
       $("#textfile").focus();
       return false; 
     }
     $('#ok', $(this)).attr('disabled', 'disabled');
     $("#spinner").fadeIn("slow");
    });   
  });
</script>
<style type="text/css"> 
.ui-widget { font-family: Verdana,Arial,sans-serif; font-size: 0.7em;}
#spinner {   display: none;   position: fixed; 	top: 50%; 	left: 50%; 	margin-top: -22px; 	margin-left: -22px; 	background-position: 0 -108px; 	opacity: 0.8; 	cursor: pointer; 	z-index: 8060;   width: 44px; 	height: 44px; 	background: #000 url('scripts/fancybox_loading.gif') center center no-repeat;   border-radius:7px; } 
</style>  		
</head><body>
<table border=0 cellpadding=0 cellspacing=0 width='100%' height='100%' bgcolor='#ffffff'><tr><td align="center">
    <div class="ui-widget">            	   
      <div id='info1' class="ui-state-highlight ui-corner-all" style="padding: 0 .7em; font: 14px / 1.4 'Helvetica', 'Arial', sans-serif;">                    
        <p>      
          <div id="info2">Добавить вопросы из текстового файла.</div>    
        </p>            	   
      </div>
    </div>
    <p></p>  
<p align='center'>
<div id="menu_glide" class="menu_glide">
<table class=bodytable border="0" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
<form action="addquestfromfile" method="post" enctype="multipart/form-data">
<input type="hidden" name="action" value="file">
<input type="hidden" name="qgid" value="<? echo $qgid; ?>">
<input type="hidden" name="kid" value="<? echo $kid; ?>">
    <tr><td>
        <p class=ptd><b>Загрузить вопросы из файла XML (LMS Moodle) или TXT</b> 
        Размер файла не должен превышать 1Мб.</p>
        <input type='file' id='textfile' name='textfile'/>
    </td></tr>

    <tr>
        <td colspan="3" witdh='400' align='center'>
            <input id='ok' type="submit" value="Загрузить файл"> 
        </td>
    </tr>           
</form>
</table>
</div>
</p>

</td></tr></table>
<div id="spinner"></div
</body></html>
<?
}}} else die;
?>

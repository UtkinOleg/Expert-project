<?php
if(defined("IN_USER")) 
{

  echo "<font align='center' face='Tahoma,Arial' size='+1'>".$proarrname."</font>
  <br><font align='center' face='Tahoma,Arial' size='-1'>".$proarrcomm."</font>"; 

?>

<script type="text/javascript"> 

function startStatus(total) {
var i=1;
	for ( ; i < total+1; i++ ) {
    $("#form_upload"+i).fadeOut(); 
  }
  $("#progress_bar").fadeIn(); 
}  
  
</script> 
<style type="text/css"> 
#progress_bar{ 
    position:relative; 
    width:300px; 
    display: none; 
    margin:15px 0 0 0px; 
} 
#bg{ 
    width:300px; 
    border:1px solid black; 
    height:10px; 
    display:block; 
    background-image:url(/img/progress.gif); 
    background-repeat: repeat-x; 
} 
</style>  		

<?
   $res1=mysql_query("SELECT count(*) FROM poptions WHERE proarrid='".$proarrid."' ORDER BY id");
   $total = mysql_fetch_array($res1);
?>

<form action="index.php?op=addproject" method="post" enctype="multipart/form-data"  onsubmit="startStatus(<? echo $total['count(*)']; ?>);">
<input type=hidden name=action value=post>
<input type=hidden name=paid value=<? echo $proarrid; ?>>
<input type=hidden name=secret value=<? echo $skey; ?>>
<p align='center'><br>
<div id="menu_glide" class="menu_glide">
<table class=bodytable border="0" cellpadding=3 cellspacing=3 bordercolorlight=gray bordercolordark=white>
    <tr>
        <td><p class=ptd><b><em class=em>Наименование проекта *</em></b></td>
    </tr><tr>
        <td><input type=text name='info' size=100></td>
    </tr>

<?    
   
   $res3=mysql_query("SELECT * FROM poptions WHERE proarrid='".$proarrid."' ORDER BY id");
   $cnt=0;
    while($param = mysql_fetch_array($res3))
   { 
    echo"<tr>";
    if ($param['name'] != "[empty]")
     echo"<td><p class=ptd><b>".$param['name']."</b></p></td>";
    else
     echo"<td><p class=ptd><b>Пояснение или комментарий:</b></p></td>";
    echo"</tr>";
    if ($param['content']=="yes") {
     if ($param['typetext']=="textarea") {
      echo"<tr><td><textarea name='content".$param['id']."' style='width:100%' rows='20'></textarea></td></tr>";
     } 
     elseif ($param['typetext']=="text") {
      echo"<tr><td><input type=text name='content".$param['id']."' size=100></td></tr>";
     } 
    }
    if ($param['files']=="yes") {
     $cnt=$cnt+1;
     echo "<tr><td><div id='form_upload".$cnt."' style='display: block;'>";
     echo"<p class=ptd><b>Прикрепить новый файл к разделу '".$param['name']."'.</b> Размер нового файла не должен превышать 1Мб.</p>";
     echo"<input type='file' name='file".$param['id']."' id='file".$param['id']."'/>";
     echo"</div></td></tr>";
    }
    
   } 
?> 
    <tr align="center">
        <td colspan="3">
          <div id="progress_bar"> 
            <div id="bg"></div> 
          </div></p> 
        </td>
    </tr>           
    <tr align="center">
        <td colspan="3">
            <input type="submit" value="Сохранить проект">&nbsp;&nbsp;&nbsp;
            <input type="button" name="close" value="Назад" onclick="history.back()"> 
        </td>
    </tr>           


</table></div></p>
</form>
<?}?>
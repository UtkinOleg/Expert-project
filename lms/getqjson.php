<?
  include "config.php";

  $log = 0;
  $questid = intval($_POST['questid']);  
  $num = intval($_POST['numid']);  
  $allq = intval($_POST['allq']);  
  $token = $_POST['token'];  
  $direction = intval($_POST['direction']);  
  $writeonly = intval($_POST['writeonly']);  
  $strmulti = $_POST['strmulti'];  
  $strkbd = $_POST['kbd'];  
  $ansqid = intval($_POST['ansqid']);  

  $s="";
  if ($log>0) $s.="qid=".$questid." token=".$token." multi=".$strmulti." ansqid=".$ansqid." strkbd=".$strkbd."<br>";
  
  // разобъем строку на массив
  $multi = explode("-", $strmulti);
  // запишем ответ в БД
  $qq = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questions WHERE id='".$ansqid."' ORDER BY id LIMIT 1;");
  $quest = mysqli_fetch_array($qq);
  if (!empty($quest))
        {
          if ($quest['qtype']=='multichoice')
          {
           $ans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT id FROM answers WHERE questionid='".$quest['id']."' ORDER BY id");
           $k=0;
           mysqli_query($mysqli,"START TRANSACTION;");
           while($answer = mysqli_fetch_array($ans))
           {
             $tmpanswer = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM tmpmultianswer WHERE questionid='".$quest['id']."' AND answerid='".$answer['id']."' AND signature='".$token."' ORDER BY id;");
             $total = mysqli_fetch_array($tmpanswer);
             $allcount = $total['count(*)'];
             $qid = $quest['id'];
             $aid = $answer['id'];
             $value = $multi[$k];
             $k++;
             if ($allcount==0)
              mysqli_query($mysqli,"INSERT INTO tmpmultianswer VALUES (0,
                                        '$token',
                                        $qid,
                                        $aid,
                                        $value);");
             else
              if (!mysqli_query($mysqli,"UPDATE tmpmultianswer SET value=".$value." WHERE questionid='".$qid."' AND answerid='".$aid."' AND signature='".$token."'"))
               if ($log>0) $s.=" err";
             mysqli_free_result($tmpanswer);
           }
           mysqli_query($mysqli,"COMMIT");
           mysqli_free_result($ans);
          }
          else
          if ($quest['qtype']=='shortanswer')
          {
             mysqli_query($mysqli,"START TRANSACTION;");
             $tmpanswer = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM tmpshortanswer WHERE questionid='".$quest['id']."' AND signature='".$token."' ORDER BY id;");
             $total = mysqli_fetch_array($tmpanswer);
             $allcount = $total['count(*)'];
             $qid = $quest['id'];
             if ($log>0) $s.=" ans2=".$qid;
             if ($allcount==0)
              mysqli_query($mysqli,"INSERT INTO tmpshortanswer VALUES (0,
                                        '$token',
                                        $qid,
                                        '$strkbd');");
             else
              if (!mysqli_query($mysqli,"UPDATE tmpshortanswer SET value='".$strkbd."' WHERE questionid='".$qid."' AND signature='".$token."'"))
               if ($log>0) $s.=" err";
             mysqli_free_result($tmpanswer);
             mysqli_query($mysqli,"COMMIT");
          }
        }   
  mysql_free_result($qq);

  if ($writeonly==0) {
  // Покажем текущий вопрос
  $qq = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM questions WHERE id='".$questid."' ORDER BY id LIMIT 1;");
  $quest = mysqli_fetch_array($qq);
  if (!empty($quest))
        {
        $qgid = $quest['qgroupid'];
        
        $qg = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT name FROM questgroups WHERE id='".$qgid."' LIMIT 1");
        $questgroup = mysqli_fetch_array($qg);
        $s.="<p align='center' style='font-size: 1em;'>Тема: ".$questgroup['name']."</p><p style='font-size: 1.2em;'><b>".$quest['content']."</b></p>";
        mysql_free_result($qg);
       
          if ($quest['qtype']=='multichoice')
          {
           
           $ans = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT * FROM answers WHERE questionid='".$quest['id']."' ORDER BY id");
           $anscnt = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM answers WHERE questionid='".$quest['id']."' ORDER BY id");
           $answercnt = mysqli_fetch_array($anscnt);
           $answer_count = $answercnt['count(*)'];
           mysqli_free_result($anscnt);
           if ($answer_count==2)
           {
             $a=0;
             while($answer = mysqli_fetch_array($ans))
             {
             $a++;
             $s.="<script>\$(function() {\$('#check".$a."').button().click(function() {
             if (\$( this ).val()==0) {
             \$( this ).val(1); \$( this ).button( 'option', 'label', '<i class=\'fa fa-check fa-2x\'></i>' );} 
             else {
             \$( this ).val(0); \$( this ).button( 'option', 'label', '<img src=\'img/empty.png\' width=22>' );}
             } );});</script>";
             $tmpanswer = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT value FROM tmpmultianswer WHERE questionid='".$quest['id']."' AND answerid='".$answer['id']."' AND signature='".$token."' ORDER BY id LIMIT 1;");
             $curanswer = mysqli_fetch_array($tmpanswer);
             if (!empty($curanswer))
             {
              if ($curanswer['value']==1)
               $s.="<p style='font-size: 1em;'><input value='1' type='checkbox' id='check".$a."' checked><label style='font-size: 0.7em; background:#fff;' for='check".$a."'><i class='fa fa-check fa-2x'></i></label> ".$answer['name']."</p>";
              else
               $s.="<p style='font-size: 1em;'><input value='0' type='checkbox' id='check".$a."'><label style='font-size: 0.7em; background:#fff;' for='check".$a."'><img src='img/empty.png' width='22'></label> ".$answer['name']."</p>";
             }
             else
              $s.="<p style='font-size: 1em;'><input value='0' type='checkbox' id='check".$a."'><label style='font-size: 0.7em; background:#fff;' for='check".$a."'><img src='img/empty.png' width='22'></label> ".$answer['name']."</p>";
             mysqli_free_result($tmpanswer);
             }
             mysqli_free_result($ans);
             $s.="<input type='hidden' id='allcheck' value='2'>";
           }
           else
           {
           $a=0;
           while($answer = mysqli_fetch_array($ans))
           {
             $a++;
             $s.="<script>\$(function() {\$('#check".$a."').button().click(function() {
             if (\$( this ).val()==0) {
             \$( this ).val(1); \$( this ).button( 'option', 'label', '<i class=\'fa fa-check fa-2x\'></i>' );} 
             else {
             \$( this ).val(0); \$( this ).button( 'option', 'label', '<img src=\'img/empty.png\' width=22>' );}
             } );});</script>";
             $tmpanswer = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT value FROM tmpmultianswer WHERE questionid='".$quest['id']."' AND answerid='".$answer['id']."' AND signature='".$token."' ORDER BY id LIMIT 1;");
             $curanswer = mysqli_fetch_array($tmpanswer);
             if (!empty($curanswer))
             {
              if ($curanswer['value']==1)
               $s.="<p style='font-size: 1em;'><input value='1' type='checkbox' id='check".$a."' checked><label style='font-size: 0.7em; background:#fff;' for='check".$a."'><i class='fa fa-check fa-2x'></i></label> ".$answer['name']."</p>";
              else
               $s.="<p style='font-size: 1em;'><input value='0' type='checkbox' id='check".$a."'><label style='font-size: 0.7em; background:#fff;' for='check".$a."'><img src='img/empty.png' width='22'></label> ".$answer['name']."</p>";
             }
             else
              $s.="<p style='font-size: 1em;'><input value='0' type='checkbox' id='check".$a."'><label style='font-size: 0.7em; background:#fff;' for='check".$a."'><img src='img/empty.png' width='22'></label> ".$answer['name']."</p>";
             mysqli_free_result($tmpanswer);
           }
           mysqli_free_result($ans);
           $s.="<input type='hidden' id='allcheck' value='".$a."'>";
          }
          }
          else
          if ($quest['qtype']=='shortanswer')
          {
             $s.="<script>\$(function() { \$('#kbd').focus(); });</script>";
             $tmpanswer = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT value FROM tmpshortanswer WHERE questionid='".$quest['id']."' AND signature='".$token."' ORDER BY id LIMIT 1;");
             $curanswer = mysqli_fetch_array($tmpanswer);
             $s.="<p style='font-size: 0.7em;'>Введите ответ с клавиатуры:<p>";
             $s.="<p><input value='".$curanswer['value']."' type='text' id='kbd' style='box-shadow: inset 0 1px 1px rgba(0,0,0,.075); transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s; border-radius: 4px; border: 1px solid #000; font-size: 1.5em; width:99%;'></p>";
             mysqli_free_result($tmpanswer);
          }
          
          $s.="<input type='hidden' id='ansqid' value='".$quest['id']."'>";
         }
  mysqli_free_result($quest);


        $z=0;
        $qq = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT questionid FROM tmptest WHERE signature='".$token."' ORDER BY id ASC;");
        while ($quest = mysqli_fetch_array($qq))
         {
          if ($z>0) 
          {
           $json['nextq'] = $quest['questionid'];  
           break;
          }
          if ($quest['questionid']==$questid) $z++;
         }
        mysqli_free_result($quest);
        $z=0;
        $qq = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT questionid FROM tmptest WHERE signature='".$token."' ORDER BY id DESC;");
        while ($quest = mysqli_fetch_array($qq))
         {
          if ($z>0) 
          {
           $json['prevq'] = $quest['questionid'];  
           break;
          }
          if ($quest['questionid']==$questid) $z++;
         }
        mysqli_free_result($quest);
  }

  if ($log>0) $s.="<br>prev=".$json['prevq']." next=".$json['nextq'];
  $s.="<input type='hidden' id='num' value='".$num."'>";
  
  $json['num'] = $num;  
  $json['allq'] = $allq;  
  $json['direction'] = $direction;  
  
  $json['content'] = htmlspecialchars_decode($s);  
  
  if(!empty($json['content']))  { 
             $json['ok'] = '1';  
  } else {  
             $json['ok'] = '0'; 
  }      
  echo json_encode($json); 
?>  
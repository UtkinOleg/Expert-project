<script>
$(document).ready(function(){
    $( document ).tooltip({
      items: "[data-menu]",
      position: {
          my: "left top",
          at: "right+10 top+5"
      },
      content: function() {
        var element = $( this );
        if ( element.is( "[data-menu]" ) ) {
          return element.attr( "title" );
        }
      }   
    });  
    
    $(window).scroll(function () {
      if ($(this).scrollTop() > 0) {$('#scroller').fadeIn();} else {$('#scroller').fadeOut();}
    });
    
    $('#scroller').click(function () {
      $('body,html').animate({scrollTop: 0}, 500); return false;
    });
 });
</script>
<style>
    label { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 0.7em; margin: .6em 0; }
    .ui-dialog .ui-state-error { padding: .3em;}
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
    .validateTips2 { border: 1px solid transparent; padding: 0.3em; }
    .validateRegTips { border: 1px solid transparent; padding: 0.3em; }
    .validateForgotTips { border: 1px solid transparent; padding: 0.3em; }
    .ui-widget { font-size: 75%; }  
    .ui-widget-header { background: #497787 50% 50% repeat-x; }
    .ui-dialog .ui-dialog-title { color: #FFFFFF; }
    #menu_glide h3 { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF; margin: 0; padding: 0.4em; text-align: center; }
</style>

<? if (USER_REGISTERED) { ?>

<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css">
 <style>
.badge {
  font-family: Verdana,Arial,sans-serif;
  display: block;
  height: 1em;
  line-height: 1em;
  padding: 3px 7px;
  font-size: 14px;
  font-weight: 700;
  line-height: 1;
  color: #fff;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  background: #497787 url("scripts/jquery-ui/images/ui-bg_inset-soft_75_497787_1x100.png") 50% 50% repeat-x;
}

.smenu, .smenu-bar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    list-style-type: none;
    margin: 0;
    padding: 0;
    background: #f7f7f7;
    z-index:10;  
    overflow:hidden;
    box-shadow: 2px 0 18px rgba(0, 0, 0, 0.26);
}
.smenu li a{
  display: block;
  text-indent: -500em;
  height: 3em;
  width: 3em;
  line-height: 3em;
  text-align:center;
  color: #497787;
  position: relative;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  transition: background 0.1s ease-in-out;
}
.smenu li a:before {
  font-family: FontAwesome;
  speak: none;
  text-indent: 0em;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  font-size: 1.4em;
}
.smenu li a.projects:before {
  content: "\f02d";
}
.smenu li a.profile:before {
  content: "\f007";
}
.smenu li a.new:before {
  content: "\f040";
}
.smenu li a.messages:before {
  content: "\f003";
}
.smenu li a.exit:before {
  content: "\f090";
}
.smenu li a.home:before {
  content: "\f0c9";
}
.smenu li a.expert:before {
  content: "\f087";
}
.smenu li a.supervisor:before {
  content: "\f185";
}
.smenu li a.supervisor2:before {
  content: "\f059";
}
.smenu li a.admin:before {
  content: "\f013";
}
.smenu-bar li a:hover,
.smenu li a:hover {
  background: #497787;
  color: #fff;
}
.smenu-bar{
    overflow:hidden;
    left:3em;
    z-index:5;
    width:0;
    height:0;
    transition: all 0.1s ease-in-out;
}
.smenu-bar li a{
  display: block;
  height: 3em;
  line-height: 3em;
  text-align:center;
  color: #497787;
  text-decoration:none;  
  position: relative;
  font-family:verdana;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
  transition: background 0.1s ease-in-out;
}

.smenu-bar li:first-child a{
    height: 3em;
    background: #497787 url("scripts/jquery-ui/images/ui-bg_inset-soft_75_497787_1x100.png") 50% 50% repeat-x;
    color: #f7f7f7;
    pointer-events: none;
    cursor: default;
} 

.para{
    color:#033f72;
    padding-left:100px;
    font-size:3em;
    margin-bottom:20px;
}

.open{
    width:10em;
    height:100%;
}

@media all and (max-width: 500px) {
    .container{
        margin-top:100px;
    }
    .smenu{
        height:5em;
        width:100%;
    }
    .smenu li{
        display:inline-block;
        float:left;
    }
    .smenu-bar li a{
        width:100%;
    }
    .smenu-bar{
        width:100%;
        left:0;
        height:0;
    }
    .open{
        width:100%;
        height:auto;
    }
    .para{
    padding-left:5px;
}  
}
@media screen and (max-height: 34em){
  .smenu li,
  .smenu-bar,
  .badge {
    font-size:70%;
  }
}
@media screen and (max-height: 34em) and (max-width: 500px){
  .smenu,
  .badge {
        height:2em;
    }
}
</style>
<script type="text/javascript" src="scripts/ssmenu.js"></script> 
</head>
<body style='background-color:#e6e6e6;'>
<?
       $ctot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM projects WHERE userid='".USER_ID."'");
       $ctotal = mysqli_fetch_array($ctot);
       $count = $ctotal['count(*)'];
       mysqli_free_result($ctot);
?>
    <ul class="smenu">
      <li><a title="Новый проект" href="newprojects" class="new" data-menu="">Новый проект</a></li>
      <? if ($count>0){ ?>
       <li><a title="Мои проекты" href="projects" class="projects" data-menu="">Мои проекты</a></li>
       <span title="Создано проектов - <? echo $count;?>" class="badge" data-menu=""><? echo $count;?></span>
      <?} else {?>
       <li><a title="Мои проекты" data-menu="" href="projects" class="projects">Мои проекты</a></li>
      <?}?>
      <? if (defined("IN_EXPERT")) { 
      ?>
       <li><a href="#" id="button-expert" class="menu-button expert">Эксперт</a></li>
      <?
       $ctot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM shablondb WHERE userid='".USER_ID."'");
       $ctotal = mysqli_fetch_array($ctot);
       $count = $ctotal['count(*)'];
       mysqli_free_result($ctot);
       if ($count>0){
      ?>
       <span data-menu="" title="Проведено экспертиз - <? echo $count;?>" class="badge"><? echo $count;?></span>
      <?}}?>
      <? if (defined("IN_SUPERVISOR")) { ?>
       <li><a href="#" id="button-supervisor" class="menu-button supervisor">Супервизор</a></li>
      <?
       $ctot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM projectarray WHERE ownerid='".USER_ID."'");
       $ctotal = mysqli_fetch_array($ctot);
       $count = $ctotal['count(*)'];
       mysqli_free_result($ctot);
       if ($count>0){
      ?>
       <span data-menu="" title="Создано моделей - <? echo $count;?>" class="badge"><? echo $count;?></span>
      <?}?>
       <li><a href="#" id="button-supervisor2" class="menu-button supervisor2">Тестирование</a></li>
      <?
       $ctot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM testgroups WHERE ownerid='".USER_ID."'");
       $ctotal = mysqli_fetch_array($ctot);
       $count = $ctotal['count(*)'];
       mysqli_free_result($ctot);
       if ($count>0){
      ?>
       <span data-menu="" title="Создано тестов - <? echo $count;?>" class="badge"><? echo $count;?></span>
      <?}?>
      <?}?>
      <? if (defined("IN_ADMIN")) { ?>
       <li><a href="#" id="button-admin" class="menu-button admin">Администратор</a></li>
      <?}?>
      <li data-menu="" title="Профиль"><a href="welcome" class="profile">Мой профиль</a></li>
      <li data-menu="" title="Сообщения"><a href="usermsgs" class="messages">Сообщения</a></li>
      <li data-menu="" title="Выход"><a href="logout" class="exit">Выход</a></li>
    </ul>

<? if (defined("IN_EXPERT")) { ?>

<script type="text/javascript">

		$(document).ready(function() {
			$('.fancybox').fancybox();
		});
  
   function closeFancyboxAndRedirectToProjects(paid){
    $.fancybox.close();
    location.replace("http://expert03.ru/projects&paid="+paid);
   }   

   function closeFancyboxAndRedirectToLists(paid){
    $.fancybox.close();
    location.replace("http://expert03.ru/lists&paid="+paid);
   }   

   function closeFancyboxAndRedirectToUrl(url){
    $.fancybox.close();
    location.replace(url);
   }    

   function closeFancybox(){
    $.fancybox.close();
   }    
       
	$(document).ready(function() {

    	$("#selprojects").click(function() {
				$.fancybox.open({
					href : 'selector&m=p',
					type : 'iframe',
          width : 750,
          height : 550,
          fitToView : true,
          autoSize : true,          
					padding : 5
				});
			});

      $("#selexpertize").click(function() {
				$.fancybox.open({
					href : 'selector&m=e',
					type : 'iframe',
          width : 750,
          height : 550,
          fitToView : true,
          autoSize : true,          
					padding : 5
				});
			});
    });  

</script>    
    
    <ul id="expert-menu" class="smenu-bar">
        <li><a href="#" id="button-expert-h" class="menu-button"><i class='fa fa-thumbs-o-up fa-lg'></i> Эксперт</a></li>
        <li data-menu="" title="Просмотр проектов участников"><a href="javascript:;" id="selprojects">Проекты</a></li>
        <li data-menu="" title="Проведение экспертизы проектов"><a href="javascript:;" id="selexpertize">Экспертиза</a></li>
    </ul> 
<? }     
 if (defined("IN_SUPERVISOR")) { ?>

<script type="text/javascript">

	$(document).ready(function() {

      $("#limit").click(function() {
				$.fancybox.open({
					href : 'robokassa3&sum=900',
					type : 'iframe',
          width : document.documentElement.clientWidth,
          height : document.documentElement.clientHeight,
          fitToView : true,                                    
          autoSize : false,
					padding : 5
				});
  		});
    	$("#base").click(function() {
				$.fancybox.open({
					href : 'robokassa4&sum=3900',
					type : 'iframe',
          width : document.documentElement.clientWidth,
          height : document.documentElement.clientHeight,
          fitToView : true,
          autoSize : false,
					padding : 5
				});
  	 	});
      $("#createtest").click(function() {
				$.fancybox.open({
					href : 'createtest&paid=0',
					type : 'iframe',
          width : document.documentElement.clientWidth,
          height : 700,
          fitToView : true,
          autoSize : false,
          modal : true,
          showCloseButton : false,
					padding : 5
				});
			});
    	$("#addproarr").click(function() {
				$.fancybox.open({
					href : 'addproarr',
					type : 'iframe',
          width : document.documentElement.clientWidth,
          height : 700,
          fitToView : true,
          autoSize : false,
          modal : true,
          showCloseButton : false,
					padding : 5
				});
			});
    });  

  function HelpmodelShow(active) {
    $( "#helpmodel" ).fadeOut("fast");
    $( "#helpcont" ).empty();
    active++;
    if (active == 2)
     $( "#helpcont" ).append('Установите период действия модели.');
    else
    if (active == 3)
     $( "#helpcont" ).append('Параметры для экспертизы проектов: итоговая оценка, комментарии, обезличенная экспертиза.');
    else
    if (active == 4)
     $( "#helpcont" ).append('Параметры проектов: установка стоимости оплаты за размещение, открытые проекты, проверка готовых проектов.');
    else
    if (active == 5)
     $( "#helpcont" ).append('Установите максимальный размер загружаемых файлов в проектах.');
    else
    if (active == 6)
     $( "#helpcont" ).append('Подключите дополнительные сервисы - онлайн тестирование и голосование.');
    else
     $( "#helpcont" ).append('Установите общие параметры: наименование модели, картинку модели. Далее - настройте период действия');
    $( "#helpmodel" ).fadeIn("fast");
  }    
</script>    
<style>
#helpmodel { 
 display:block;
 font-family:Arial;
 text-align: center; 
 width: 99%; 
 height: 50px; 
 font-size: 1.2em;
 left: 5px;
 position: absolute;
 z-index: 1000;
 top: 0px;
}
<style>
.none { 
 display:none;
}
</style>

<div id="helpmodel" style="display:none" class="ui-widget tops">
	<div class="ui-state-highlight ui-corner-all" style="margin-top: 10px; padding: 0 .7em;">
		<p><div id='helpcont'></div></p>
	</div>
</div>

    <ul id="supervisor-menu" class="smenu-bar">
        <li><a href="#" id="button-supervisor-h" class="menu-button"><i class='fa fa-sun-o fa-lg'></i> Супервизор</a></li>
<?
   $tot2 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM projectarray WHERE ownerid='".USER_ID."'");
   if (!$tot2) puterror("Ошибка при обращении к базе данных");
   $total2 = mysqli_fetch_array($tot2);
   $tot3 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT pacount FROM users WHERE id='".USER_ID."' LIMIT 1;");
   if (!$tot3) puterror("Ошибка при обращении к базе данных");
   $total3 = mysqli_fetch_array($tot3);
   $pacount = $total3['pacount'];
   $count = $total2['count(*)'];
   mysqli_free_result($tot2);
   mysqli_free_result($tot3);

   if ((defined("IN_SUPERVISOR") and $count < $pacount) or defined("IN_ADMIN")) 
   {
?>
    <li data-menu="" title="Создать новую модель конкурса"><a href="javascript:;" id="addproarr">Создать модель</a></li>
<? } if(defined("IN_ADMIN")) 
       $ctot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM projectarray WHERE closed='0'");
     else
       $ctot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM projectarray WHERE ownerid='".USER_ID."' AND closed='0'");
     $ctotal = mysqli_fetch_array($ctot);
     $count = $ctotal['count(*)'];
     mysqli_free_result($ctot);
     if ($count>0){
?>
        <li data-menu="" title="Просмотр и изменение моделей"><a href="parray">Модели</a></li>

<?}  
     if(defined("IN_ADMIN")) 
       $ctot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM projectarray WHERE closed='1'");
     else
       $ctot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM projectarray WHERE ownerid='".USER_ID."' AND closed='1'");
     $ctotal = mysqli_fetch_array($ctot);
     $count = $ctotal['count(*)'];
     mysqli_free_result($ctot);
     if ($count>0){
?>
        <li data-menu="" title="Просмотр архивных (закрытых) моделей"><a href="parray&arc=1">Архив моделей</a></li>
<?}?>
        <li data-menu="" title="Создание и изменение новостей и страниц"><a href="newses">Страницы</a></li>
    </ul> 
    <ul id="supervisor-menu2" class="smenu-bar">
        <li><a href="#" id="button-supervisor-h2" class="menu-button"><i class='fa fa-question fa-lg'></i> Тестирование</a></li>
        <li data-menu="" title="Создание нового пробного теста"><a href="javascript:;" id="createtest">Создать тест</a></li>
        <li data-menu="" title="Определение областей знаний для групп вопросов"><a href="knows">Знания</a></li>
        <li data-menu="" title="Создание и изменение групп вопросов"><a href="questgroups">Вопросы</a></li>
        <li data-menu="" title="Просмотр и изменение пробных тестов"><a href="testoptions">Мои тесты</a></li>
    <?
     if(defined("IN_ADMIN")) 
       $ctot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM singleresult as s;");
     else
       $ctot = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM testgroups as t, singleresult as s WHERE s.testid=t.id AND t.ownerid=".USER_ID.";");
     $stotal = mysqli_fetch_array($ctot);
     $scount = $stotal['count(*)'];
     mysqli_free_result($ctot);
     if ($scount>0){
    ?>    
        <li data-menu="" title="Просмотр результатов тестирования"><a href="viewtestresults">Результаты</a></li>
    <? } ?>    
    </ul> 
<? } 

if (defined("IN_ADMIN"))
 { ?>
    <ul id="admin-menu" class="smenu-bar">
        <li><a href="#" id="button-admin-h" class="menu-button"><i class='fa fa-gear fa-spin fa-lg'></i> Администратор</a></li>
        <li><a href="members">Пользователи</a></li>
        <li><a href="experts">Эксперты</a></li>
        <li><a href="experts&s=1">Супервизоры</a></li>
        <li><a href="logs">Журнал</a></li>
        <li><a href="admshab&mode=project">П.шаблоны</a></li>
        <li><a href="admshab&mode=list">К.шаблоны</a></li>
        <li><a href="admlimit">Ограниченные</a></li>
    </ul> 
<? } ?>
  
<?} 

if (USER_REGISTERED) 
 {
  echo '<div id="pagewidth" style="background-color:#e6e6e6; margin-top: 0px;"><div id="wrapper" class="clearfix">';
  echo '<div id="maincol" style="left:3em; background-color:#e6e6e6; width:96%;">';

         $tot2 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT email, job FROM users WHERE id='".USER_ID."' LIMIT 1");
         if (!$tot2) puterror("Ошибка при обращении к базе данных");
         $total2 = mysqli_fetch_array($tot2);
         $email = $total2['email'];       
         $job = $total2['job'];       
         mysqli_free_result($tot2); 
         if ($_SERVER['REQUEST_URI'] != "/edituser")
         {

          if (empty($email) or empty($job))
          {
          ?>
          <div class="ui-widget" style="margin-top: 5px;">
            	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
            		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
              		<strong>Внимание:</strong> В вашем профиле не заполнены поля - 
                  <? if (empty($email)) echo '"Электронная почта" '; ?> <? if (empty($job)) echo '"Место работы (учебы)"'; ?>. <strong><a href="edituser">Перейти в профиль</a></strong></p>
            	</div>
           </div>    
          <?
          }
         }

  $tot2 = mysqli_query($mysqli,"/*" . MYSQLND_QC_ENABLE_SWITCH . "*/" . "SELECT count(*) FROM msgs WHERE touser='".USER_ID."' AND readed='0'");
  if (!$tot2) puterror("Ошибка при обращении к базе данных");
  $total2 = mysqli_fetch_array($tot2);
  $count2 = $total2['count(*)'];
  mysqli_free_result($tot2);
  
         if ($count2>0 and $_SERVER['REQUEST_URI'] != "/usermsgs")
         {

          ?>
          <div class="ui-widget" style="margin-top: 5px;">
            	<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">
            		<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
              		Пришли новые сообщения - <? echo $count2; ?> <strong><a href="usermsgs">Прочитать</a></strong></p>
            	</div>
           </div>    
          <?
         }

 }
else
 {
  echo '<div id="pagewidth"><div id="wrapper" class="clearfix">';
  echo '<div id="maincol">';
 }
// echo "<p></p>";
 echo "<h1 class=z1>".$titlepage."</h1>";
?>




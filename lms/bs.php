<?
  include "config.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<title>Экспертная система оценки проектов</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Экспертная система оценки проектов">
<meta name="author" content="">
<link href="css/bootstrap.css" rel="stylesheet">
<style>
body {padding-bottom:10px;color:#5a5a5a;}
.navbar-wrapper{position:fixed;top:0;left:0;right:0;z-index:10;margin-top:0px;margin-bottom:-90px; }
.navbar-wrapper .navbar{}.navbar .navbar-inner{border:0;-webkit-box-shadow:0 2px 10px rgba(0,0,0,.25);-moz-box-shadow:0 2px 10px rgba(0,0,0,.25);box-shadow:0 2px 10px rgba(0,0,0,.25);}.navbar .brand{padding:14px 20px 16px;font-size:16px;font-weight:bold;color:white;/* text-shadow:0 -1px 0 rgba(0,0,0,.5); */}.navbar .navbar .btn-navbar{margin-top:10px;}.carousel{margin-bottom:60px;}.carousel .container{position:relative;z-index:9;}.carousel-control{height:80px;margin-top:0;font-size:120px;text-shadow:0 1px 1px rgba(0,0,0,.4);background-color:transparent;border:0;}.carousel .item{height:500px;}.carousel img{position:absolute;top:0;left:0;min-width:100%;height:500px;}.carousel-caption{background-color:transparent;position:static;max-width:850px;padding:0 20px;margin-top:150px;}.carousel-caption h1,.carousel-caption .lead{margin:0;line-height:1.25;color:#fff;text-shadow:0 1px 1px rgba(0,0,0,.4);}.carousel-caption .btn{margin-top:10px;}.marketing .span4{text-align:center;}.marketing h2{font-weight:normal;}.marketing .span4 p{margin-left:10px;margin-right:10px;}.featurette-divider{margin:10px 0; }.featurette{padding-top:0px;overflow:hidden; }.featurette-image{margin-top:-20px;}.featurette-image.pull-left{margin-right:40px;}.featurette-image.pull-right{margin-left:40px;}.featurette-heading{font-size:30px;font-weight:300;line-height:1;letter-spacing:-1px;}@media (max-width: 979px) {.container.navbar-wrapper{margin-bottom:0;width:auto;}.navbar-inner{border-radius:0;margin:-20px 0;}.carousel .item{height:500px;}.carousel img{width:auto;height:500px;}.featurette{height:auto;padding:0;}.featurette-image.pull-left,.featurette-image.pull-right{display:block;float:none;max-width:40%;margin:0 auto 20px;}}@media (max-width: 767px) {.navbar-inner{margin:-20px;}.carousel{margin-left:-20px;margin-right:-20px;}.carousel .container{}.carousel .item{height:300px;}.carousel img{height:300px;}.carousel-caption{width:65%;padding:0 70px;margin-top:100px;}.carousel-caption h1{font-size:30px;}.carousel-caption .lead,.carousel-caption .btn{font-size:18px;}.marketing .span4+.span4{margin-top:40px;}.featurette-heading{font-size:30px;}.featurette .lead{font-size:18px;line-height:1.5;}}
.menu_glide_img img{
    -moz-border-radius-topright: 15px;
    -moz-border-radius-topleft: 15px;
    -webkit-border-top-left-radius: 15px;
    -webkit-border-top-right-radius: 15px;
    -khtml-border-radius-topright: 15px;
    -khtml-border-radius-topleft: 15px;
    border-radius-topright: 15px;
    border-radius-topleft: 15px;
    -moz-border-radius-bottomright: 15px;
    -moz-border-radius-bottomleft: 15px;
    -webkit-border-bottom-left-radius: 15px;
    -webkit-border-bottom-right-radius: 15px;
    -khtml-border-radius-bottomright: 15px;
    -khtml-border-radius-bottomleft: 15px;
    border-radius-bottomright: 15x;
    border-radius-bottomleft: 15px;
    border-radius:15px;
}
   label { display:block; }
   input.text { margin-bottom:12px; width:95%; padding: .4em; }
   fieldset { padding:0; border:0; margin-top:25px; }
   .ui-dialog .ui-state-error { padding: .3em;}
   .validateTips { border: 1px solid transparent; padding: 0.3em; }
   .validateTips2 { border: 1px solid transparent; padding: 0.3em; }
   .ui-widget { font-size: 75%; }
   .ui-widget-header { background: #497787 50% 50% repeat-x; }
   .ui-dialog .ui-dialog-title { color: #FFFFFF; }
</style>

<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<link rel="shortcut icon" href="http://siberia-soft.ru/sites/default/files/danland_favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
</head>
<body>  

<div style="display:none">
  <div id="msg-form" title="Отправить сообщение">  
    <p class="validateTips2">Все поля необходимо заполнить
    </p>  
    <form>  
      <fieldset>    
        <label for="name2">Имя
        </label>    
        <input type="text" name="name" id="name2" value="<? if(defined("IN_USER")) echo USER_FIO; ?>" class="text ui-widget-content ui-corner-all">    
        <label for="email">Email
        </label>    
        <input type="text" name="email" id="email" value="<? if(defined("IN_USER")) echo USER_EMAIL; ?>" class="text ui-widget-content ui-corner-all">         
        <label for="body">Сообщение
        </label>    
<textarea name="body" id="body" style='width:100%' rows="5"></textarea>  
      </fieldset>  
    </form>
  </div>
</div> 

<div style="display:none">
  <div id="login-form" title="Вход">  
    <p class="validateTips">Все поля необходимо заполнить
    </p>  
    <form>  
      <fieldset>    
        <label for="name">Логин
        </label>    
        <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all">    
        <label for="pass">Пароль
        </label>    
        <input type="password" name="pass" id="pass" value="" class="text ui-widget-content ui-corner-all">    
        <input type='hidden' id='saveme' name='saveme' value='1'>  
      </fieldset>
      <p align='center'>  
        <a href='forgot'>
          <font face='Tahoma, Arial'>Забыли пароль?
          </font></a> | 
        <a href='reg'>  
          <font face='Tahoma, Arial'>Регистрация
          </font></a>
        <font size='-1'>или вход через
      </p>
      <p align='center'>
        <a href="https://loginza.ru/api/widget?token_url=<? echo urlencode ('http://expert03.ru/login'); ?>" class="loginza">    
          <img src="http://loginza.ru/img/providers/facebook.png" alt="Facebook" title="Facebook">    
          <img src="http://loginza.ru/img/providers/yandex.png" alt="Yandex" title="Yandex">    
          <img src="http://loginza.ru/img/providers/google.png" alt="Google" title="Google Accounts">    
          <img src="http://loginza.ru/img/providers/vkontakte.png" alt="Вконтакте" title="Вконтакте">    
          <img src="http://loginza.ru/img/providers/mailru.png" alt="Mail.ru" title="Mail.ru">    
          <img src="http://loginza.ru/img/providers/twitter.png" alt="Twitter" title="Twitter"></a>
      </p>  
    </form>
  </div>
</div> 


  <div class="navbar-wrapper">  
    <div class="container">
      <div class="navbar">
        <div class="navbar-inner">  
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar">
            </span>
            <span class="icon-bar">
            </span>
            <span class="icon-bar">
            </span></a>
          <a class="brand" href="http://expert03.ru">
            <img src="img/logoexpert.gif" alt="Экспертная система оценки проектов">
          </a>  
          <div class="nav-collapse collapse">
            <ul class="nav">
            <li class="dropdown">
                <a id="login1" href="javascript:;">Вход</a>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Проекты 
                <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li>
                <a href="#">Опубликованные проекты</a>
            </li>
            <li class="divider">
            </li>
            <li>
            <a href="#">Голосования по проектам</a>
            </li>
            </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Рейтинги 
                <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li>
                <a href="#">Рейтинги экспертов</a>
            </li>
            <li class="divider">
            </li>
            <li>
            <a href="#">Рейтинги проектов</a>
            </li>
            </ul>
            </li>
            <li class="dropdown">
              <a id="msg1" href="javascript:;">Контакты</a>
            </li>
            </ul>
          </div> 
          <form class="navbar-search pull-left">
           <input type="text" class="search-query" placeholder="Поиск...">
          </form> 
        </div>  
      </div>  
    </div>   
  </div>    
  
  
  <div id="myCarousel" class="carousel slide">  
    <div class="carousel-inner">    
      <div class="item active">      
        <img src="img/slide-01-1.jpg" alt="">      
        <div class="container">        
          <div class="carousel-caption"><h1>Экспертная система оценки проектов</h1>          
            <p class="lead">Вас не устраивает объективность и независимость проводимых конкурсов? Нужны независимые эксперты для оценки Вашего проекта? Необходима разработка показателей оценки проекта? Поможет экспертная система оценки проектов.           
            </p>          
            <a class="btn btn-large btn-primary" href="reg">Узнать больше</a>        
          </div>      
        </div>    
      </div>    
      <div class="item">      
        <img src="img/slide-02-1.jpg" alt="">      
        <div class="container">        
          <div class="carousel-caption"><h1>Экспертная система оценки проектов</h1>          
            <p class="lead">Это независимая оценка экспертов, совместная разработка критериев, независимая экспертиза проектов, открытые рейтинги и голосования, публикация проектов, разработка проказателей реализации проектов, оценка реализованных проектов.           
            </p>          
            <a class="btn btn-large btn-primary" href="#">Регистрация бесплатно</a>        
          </div>      
        </div>    
      </div>    
      <div class="item">      
        <img src="img/slide-05-1.jpg" alt="">      
        <div class="container">             
          <div class="carousel-caption">  <h1>Открытые рейтинги проектов</h1>                  
            <p class="lead">Итоговый рейтинг проекта формируется в режиме реального времени в процессе оценки проектов экспертами. По мере того, как эксперты заполняют экспертные листы, рейтинг автоматически пересчитывается. После экспертизы всех проектов в системе сформируется окончательный рейтинг.                
            </p>                  
            <a class="btn btn-large btn-primary" href="tops">Посмотреть примеры</a>             
          </div>      
        </div>    
      </div>    
<!--      <div class="item">      
        <img src="img/slide-05-1.jpg" alt="">      
        <div class="container">             
          <div class="carousel-caption">  <h1>Рейтинг лучших экспертов</h1>                  
            <p class="lead">Если Вы квалифицированный специалист - присоединяйтесь к сообществу экспертов. Проводите  независимую оценку проектов, накапливайте опыт и зарабатывайте на экспертизе.                
            </p>                  
            <a class="btn btn-large btn-primary" href="tops">Посмотреть рейтинг</a>             
          </div>      
        </div>    
      </div>  -->
    </div>  
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>  
    <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
  </div>      
  
  
  <div class="container marketing">
    
    <div class="row">

      <?
      
$sql = mysql_query("SELECT * FROM news WHERE pagetype='news' AND published=1 ORDER BY ndate DESC LIMIT 1,6;");


if(mysql_num_rows($sql)>0) { 
        while($post = mysql_fetch_assoc($sql)){  
            foreach($post AS $n=>$m)
            { 
             $post[$n] = $m; 
            }
            if (!empty($post['content']))
             $content = htmlspecialchars_decode($post['content']);
            else
             $content = '';      
            if (!empty($post['docurl']))
             $docurl = urlencode($post['docurl']); 
            else
             $docurl = '';      
            $picurl = $post['picurl'];  
            $newsdate = date("d-m-Y", strtotime($post['ndate'])); 
      ?>
      <div class="span6">
        <img class="img-circle" width="140" src="<? echo $picurl; ?>">
        <h2><? echo $post['name']; ?></h2>
        <p><? echo $content; ?></p>
        <p><a class="btn btn-primary" href="page&id=<? echo $post['id']; ?>">Подробнее &raquo;</a></p>
      </div>  
      <?
         }   
        }       
      ?>

      <div class="span12">
        <p>
          <a class="btn btn-primary" href="#">Все новости &raquo;</a>
        </p>
      </div>  
    </div>    
    
    <div class="row">
      <div class="span12">
        <ul class="ch-grid">					
          <li>						
          <div class="ch-item ch-img-1">							
            <div class="ch-info">
             <p>Учитель года Бурятии 2014</p>							
            </div>						
          </div>					
          </li>
        </ul>           <h2>Открытый рейтинг 
          <span class="muted">Учитель года Бурятии - 2014 (Заочный тур)
          </span></h2>
        <p>Цели и задачи конкурса: Поддержка учительского потенциала в рамках НОИ "Наша новая школа", внедрение новых педагогических технологий в систему образования, рост профессионального мастерства педагогов, распространение педагогического опыта лучших учителей республики, развитие и расширение профессиональных контактов, поддержка талантливых, творчески работающих учителей.
        </p>
      </div>  
    </div>  
    
    <hr class="featurette-divider">
    
    <div class="featurette">
      <div class='menu_glide_img'>
        <img class="featurette-image pull-right" src="http://expert03.ru/uploads/pavatars/45fcpro.jpg">
      </div>
      <h2 class="featurette-heading">Опубликованные проекты 
        <span class="muted">Фестиваль инновационных идей стажировочных площадок 2013
        </span></h2>
      <p class="lead">Фестиваль инновационных педагогических идей стажировочных площадок Республики Бурятия проводится в рамках реализации Федеральной целевой программы развития образования на 2011-2015 годы и ее направления «Распространение на всей территории Российской Федерации моделей образовательных систем, обеспечивающих современное качество общего образования».
      </p>
      <p>
        <a class="btn" href="#">Просмотр проектов &raquo;</a>
      </p>
    </div>
    
    <hr class="featurette-divider">
    
    <div class="featurette">
      <div class='menu_glide_img'>
        <img class="featurette-image pull-left" src="http://expert03.ru/uploads/pavatars/43b9820db4.jpg">
      </div>
      <h2 class="featurette-heading">Опубликованные проекты 
        <span class="muted">Новый детский сад Бурятии 2013
        </span></h2>
      <p class="lead">Цель конкурса: - выявление и поддержка наиболее успешных образовательных организаций, внедряющих инновационные методы, средства и технологии дошкольного образования, - развитие образовательной среды для детей дошкольного возраста, - распространение лучших образцов профессионального опыта педагогических работников образовательных организаций.
      </p>
      <p>
        <a class="btn" href="#">Просмотр проектов &raquo;</a>
      </p>
    </div>
    
    <hr class="featurette-divider">
    
    <div class="featurette">
      <div class='menu_glide_img'>
        <img class="featurette-image pull-right" src="http://expert03.ru/uploads/pavatars/27intel-mobklas.jpg">
      </div>
      <h2 class="featurette-heading">Опубликованные проекты 
        <span class="muted">Конкурс Умная школа Бурятии - 2013
        </span></h2>
      <p class="lead">Конкурс направлен на выявление 30 (тридцати) общеобразовательных учреждений, в которых в течение 2013 года Министерством образования и науки РБ будут проведены мероприятия, направленные на улучшение материально-технической оснащенности школ и создания необходимых условий для успешного внедрения «Электронной образовательной среды» в течение 2013 года.
      </p>
      <p>
        <a class="btn" href="#">Просмотр проектов &raquo;</a>
      </p>
    </div>
    
    <hr class="featurette-divider">    
    
    <footer>
      <p class="pull-right">
        <a href="#">Наверх</a>
      </p>
      <p>&copy; 2014 Константин Цепков &middot; Олег Уткин &middot; 
        <a href="#">Отправить сообщение</a>
      </p>
    </footer>
  
  </div>  
<script src="http://code.jquery.com/jquery-1.9.1.js"></script> 
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> 
<script src="http://loginza.ru/js/widget.js"></script>
<script src="scripts/func.js"></script> 
<script src="js/bootstrap-transition.js"></script> 
<script src="js/bootstrap-alert.js"></script> 
<script src="js/bootstrap-modal.js"></script> 
<script src="js/bootstrap-dropdown.js"></script> 
<script src="js/bootstrap-scrollspy.js"></script> 
<script src="js/bootstrap-tab.js"></script> 
<script src="js/bootstrap-tooltip.js"></script> 
<script src="js/bootstrap-popover.js"></script> 
<script src="js/bootstrap-button.js"></script> 
<script src="js/bootstrap-collapse.js"></script> 
<script src="js/bootstrap-carousel.js"></script> 
<script src="js/bootstrap-typeahead.js"></script> 
<script>
      !function ($) {
        $(function(){
          // carousel demo
          $('#myCarousel').carousel()
        })
      }(window.jQuery) 
</script>
</body>
</html>

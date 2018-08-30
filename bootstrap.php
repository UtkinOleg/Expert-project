<!DOCTYPE html>
<html lang="ru"> 
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Создание тестов онлайн">
    <meta name="keywords" content="онлайн тестирование, конструктор тестов, создать тест, компьютерное тестирование, редактор тестов, тесты онлайн, бесплатные тесты, тесты бесплатно, тесты без смс, создать тест онлайн" />    <meta name="author" content="Siberia-Soft">
    <meta name='yandex-verification' content='6dcc51c08b1fd8e9' />
    <link rel="icon" href="ico/favicon.ico">
    <title>Test Life</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
  </head>
  <body>

<div class="modal fade" id="myModalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabelLogin" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabelLogin">Вход в систему</h4>
      </div>
      <div class="modal-body">
<div class="row">
  <div class="col-xs-12 col-md-4">
<form role="form">
  <div id="Loginformgroup" class="form-group">
    <input type="text" class="form-control" id="InputLogin" placeholder="Логин">
  </div>
  <div id="Passformgroup" class="form-group">
    <input type="password" class="form-control" id="InputPass" placeholder="Пароль">
  </div>
  <div class="text-center form-group">
        <button type="button" class="btn btn-primary" onclick="formLoginIn();">Вход</button>
        <button type="button" class="btn btn-primary" onclick="$('#myModalLogin').modal('hide');">Отмена</button>
  </div>
</form>
  </div>
</div>      
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myInfoMsg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel2">Сообщение</h4>
      </div>
      <div id="myInfoMsgContent" class="modal-body">
      </div>
    </div>
  </div>
</div>

    <div class="container">

      <div class="masthead">
        <h3 class="text-muted">TestLife.org - создание тестов и проведение тестирования онлайн</h3>
        <ul class="nav nav-justified">
          <li class="active"><a href="javascript:;" onclick="formLoginShow();">Вход</a></li>
          <li><a href="#">Тесты</a></li>
          <li><a href="#">Результаты</a></li>
          <li><a href="#">О сервисе</a></li>
          <li><a href="#">Контакты</a></li>
        </ul>
      </div>

      <div class="jumbotron">
        <p class="lead">Создать тест и пройти тестирование онлайн просто - зарегестрируйтесь на testlife.org, бесплатно создайте тест и отправьте готовый тест участникам, получайте и анализируйте результаты при помощи инструментов testlife.org.</p>
        <p><a class="btn btn-lg btn-success" href="#" role="button">Начать тестирование</a></p>
      </div>

      <div class="row">
        <div class="col-lg-4">
          <h2>Что это такое?</h2>
          <p>Сервис testlife.org позволяет в режиме online создать тест из готовых вопросов, провести тестирование и получить результаты. В отличие от LMS Moodle и других подобных систем, testlife.org проще в использовании (позволяет импортировать готовые вопросы) и предоставляет расширенные возможности анализа результатов тестирования.</p>
          <p><a class="btn btn-primary" href="#" role="button">Подробнее &raquo;</a></p>
        </div>
        <div class="col-lg-4">
          <h2>Зачем создавать тест?</h2>
          <p>Для проведения тестирования онлайн необходимо пройти регистрацию, при помощи инструментов сервиса создать тест и отправить ссылку на тест участникам.</p>
          <p><a class="btn btn-primary" href="#" role="button">Подробнее &raquo;</a></p>
       </div>
        <div class="col-lg-4">
          <h2>Как пройти тест?</h2>
          <p>Пройдите бесплатную регистрацию и откройте тест по ссылке.</p>
          <p><a class="btn btn-primary" href="#" role="button">Подробнее &raquo;</a></p>
        </div>
      </div>

      <div class="footer">
        <p>&copy; <a href="http://siberia-soft.ru">Siberia-Soft</a> 2014</p>

<p><a href="https://metrika.yandex.ru/stat/?id=27317486&amp;from=informer"
target="_blank" rel="nofollow"><img src="//bs.yandex.ru/informer/27317486/3_0_FFFFFFFF_FFFFFFFF_0_pageviews"
style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:27317486,lang:'ru'});return false}catch(e){}"/></a>
</p>

      </div>

    </div> 
    
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter27317486 = new Ya.Metrika({id:27317486,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/27317486" style="position:absolute; left:-9999px;" alt="" /></div></noscript>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/docs.min.js"></script>
    <script src="http://loginza.ru/js/widget.js" type="text/javascript"></script>
    <script>
     var maxtops = <?echo $cnt;?>;
     var maxpublics = <?echo $cnt;?>;
    </script>
    <script src="js/newscript.pack.js"></script>
  </body>
</html>
                                
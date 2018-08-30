
  var n; 
  var topoffset; 
  var publicoffset; 
  var left = false;
  var right = false;
  var leftp = false;
  var rightp = false;

  function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
  }
  
  $(function() {
     var iu = getUrlVars();

     news();
     
     tops(1,'#topscenter');
     tops(1,'#topsright');
     
     publics(1,'#topscenterp');
     publics(1,'#topsrightp');

     if (iu[1]=='news') 
      $('body,html').animate({scrollTop: $("#intro").offset().top - 40}, 500);
     else
     if (iu[1]=='tops') 
      $('body,html').animate({scrollTop: $("#tops").offset().top - 20}, 500);
     else
     if (iu[1]=='projects') 
      $('body,html').animate({scrollTop: $("#projects").offset().top - 20}, 500); 
     else
     if (iu[1]=='whatis') 
      $('body,html').animate({scrollTop: $("#whatis").offset().top - 20}, 500);
     else
     if (iu[1]=='test') 
      $('body,html').animate({scrollTop: $("#test").offset().top - 60}, 500);
     else
     if (iu[1]=='pay') 
      $('body,html').animate({scrollTop: $("#pay").offset().top - 60}, 500);
     else
     if (iu[1]=='howto') 
      $('body,html').animate({scrollTop: $("#howto").offset().top - 20}, 500);
     else
     if (iu[1]=='stat') 
      $('body,html').animate({scrollTop: $("#stat").offset().top - 20}, 500);
     else
     if (iu[1]=='tarif') 
      $('body,html').animate({scrollTop: $("#intro2").offset().top - 20}, 500);
  });
  
  $(document).ready(function(){
       $window = $(window);
       var c1 = 0;
       var c2 = 2;
       var cp1 = 0;
       var cp2 = 2;
 
       $('section[data-type="background"]').each(function(){
        var $scroll = $(this);
                     
        $(window).scroll(function() {
        var yPos = -($window.scrollTop() / $scroll.data('speed')); 
        var coords = '50% '+ yPos + 'px';
 
        $scroll.css({ backgroundPosition: coords });    
        }); 
       }); 

       $(window).scroll(function() {
        if ($window.scrollTop()>0)
          $("#mainbar").addClass('top-20');
        else  
          $("#mainbar").removeClass('top-20');
       }); 
   
       $('#scroll_news').click(function () {
         $('body,html').animate({scrollTop: $("#intro").offset().top - 40}, 500); return false;
       });
       $('#scroll_tops').click(function () {
         $('body,html').animate({scrollTop: $("#tops").offset().top - 20}, 500); return false;
       });
       $('#scroll_projects').click(function () {
         $('body,html').animate({scrollTop: $("#projects").offset().top - 20}, 500); return false;
       });
       $('#scroll_tops2').click(function () {
         $('body,html').animate({scrollTop: $("#tops").offset().top - 20}, 500); return false;
       });
       $('#scroll_projects2').click(function () {
         $('body,html').animate({scrollTop: $("#projects").offset().top - 20}, 500); return false;
       });
       $('#scroll_whatis').click(function () {
         $('#dropdown1').removeClass('open');
         $('body,html').animate({scrollTop: $("#whatis").offset().top - 20}, 500); return false;
       });
       $('#scroll_whatis1').click(function () {
         $('body,html').animate({scrollTop: $("#whatis").offset().top - 20}, 500); return false;
       });
       $('#scroll_whatis2').click(function () {
         $('body,html').animate({scrollTop: $("#whatis").offset().top - 20}, 500); return false;
       });
       $('#scroll_whatis3').click(function () {
         $('body,html').animate({scrollTop: $("#whatis").offset().top - 20}, 500); return false;
       });
       $('#scroll_whatis4').click(function () {
         $('body,html').animate({scrollTop: $("#whatis").offset().top - 20}, 500); return false;
       });
       $('#scroll_whatis5').click(function () {
         $('body,html').animate({scrollTop: $("#whatis").offset().top - 20}, 500); return false;
       });
       $('#scroll_test1').click(function () {
         $('body,html').animate({scrollTop: $("#test").offset().top - 60}, 500); return false;
       });
       $('#scroll_test2').click(function () {
         $('body,html').animate({scrollTop: $("#test").offset().top - 60}, 500); return false;
       });
       $('#scroll_pay').click(function () {
         $('body,html').animate({scrollTop: $("#pay").offset().top - 60}, 500); return false;
       });
       $('#scroll_howto').click(function () {
         $('#dropdown1').removeClass('open');
         $('body,html').animate({scrollTop: $("#howto").offset().top - 20}, 500); return false;
       });
       $('#scroll_stat').click(function () {
         $('#dropdown1').removeClass('open');
         $('body,html').animate({scrollTop: $("#stat").offset().top - 20}, 500); return false;
       });
       $('#scroll_tariffs').click(function () {
         $('body,html').animate({scrollTop: $("#intro2").offset().top - 20}, 500); return false;
       });
       
       $('#topCaruselLeft').click(function() {
         if (topoffset<=0) $('#topCaruselLeft').addClass('disabled');
         $('#topCaruselRight').removeClass('disabled');
         right= true;
         if (left)
         {
         left=false;
         if (c2==0)
          tops(false,'#topsleft');
         else
         if (c2==1)
          tops(false,'#topscenter');
         else
         if (c2==2)
          tops(false,'#topsright');

         if (c2==0)
          tops(false,'#topsleft');
         else
         if (c2==1)
          tops(false,'#topscenter');
         else
         if (c2==2)
          tops(false,'#topsright');
         }
         if (c2==0)
          tops(false,'#topsleft');
         else
         if (c2==1)
          tops(false,'#topscenter');
         else
         if (c2==2)
          tops(false,'#topsright');
         c2--;
         if (c2<0) c2=2; 
         c1--;
         if (c1<0) c1=2; 
         $('#topCarousel').carousel('prev');
       });

       $('#topCaruselRight').click(function() {
         $('#topCaruselLeft').removeClass('disabled');
         left = true;
         if (right)
         {
          right=false;
         if (c1==0)
          tops(true,'#topsleft');
         else
         if (c1==1)
          tops(true,'#topscenter');
         else
         if (c1==2)
          tops(true,'#topsright');   
         
         if (c1==0)
          tops(true,'#topsleft');
         else
         if (c1==1)
          tops(true,'#topscenter');
         else
         if (c1==2)
          tops(true,'#topsright');   
         }
         if (c1==0)
          tops(true,'#topsleft');
         else
         if (c1==1)
          tops(true,'#topscenter');
         else
         if (c1==2)
          tops(true,'#topsright');
         c1++;
         if (c1==3) c1=0; 
         c2++;
         if (c2==3) c2=0; 
         $('#topCarousel').carousel('next');
         if (topoffset>=maxtops)
          $('#topCaruselRight').addClass('disabled');
       });       

       $('#publicCaruselLeft').click(function() {
         if (publicoffset<=0) $('#publicCaruselLeft').addClass('disabled');
         $('#publicCaruselRight').removeClass('disabled');
         rightp = true;
         if (leftp)
         {
         leftp = false;
         if (cp2==0)
          publics(false,'#topsleftp');
         else
         if (cp2==1)
          publics(false,'#topscenterp');
         else
         if (cp2==2)
          publics(false,'#topsrightp');

         if (cp2==0)
          publics(false,'#topsleftp');
         else
         if (cp2==1)
          publics(false,'#topscenterp');
         else
         if (cp2==2)
          publics(false,'#topsrightp');
         }
         if (cp2==0)
          publics(false,'#topsleftp');
         else
         if (cp2==1)
          publics(false,'#topscenterp');
         else
         if (cp2==2)
          publics(false,'#topsrightp');
         cp2--;
         if (cp2<0) cp2=2; 
         cp1--;
         if (cp1<0) cp1=2; 
         $('#publicCarousel').carousel('prev');
       });

       $('#publicCaruselRight').click(function() {
         $('#publicCaruselLeft').removeClass('disabled');
         leftp = true;
         if (rightp)
         {
          rightp=false;
         if (cp1==0)
          publics(true,'#topsleftp');
         else
         if (cp1==1)
          publics(true,'#topscenterp');
         else
         if (cp1==2)
          publics(true,'#topsrightp');   
         
         if (cp1==0)
          publics(true,'#topsleftp');
         else
         if (cp1==1)
          publics(true,'#topscenterp');
         else
         if (cp1==2)
          publics(true,'#topsrightp');   
         }
         if (cp1==0)
          publics(true,'#topsleftp');
         else
         if (cp1==1)
          publics(true,'#topscenterp');
         else
         if (cp1==2)
          publics(true,'#topsrightp');
         cp1++;
         if (cp1==3) cp1=0; 
         cp2++;
         if (cp2==3) cp2=0; 
         $('#publicCarousel').carousel('next');
         if (publicoffset>=maxpublics)
          $('#publicCaruselRight').addClass('disabled');
       });       

  }); 


  function myInfoMsgShow(info) {
     $('#myInfoMsgContent').empty();
     $('#myInfoMsgContent').append(info);
     $('#myInfoMsg').modal('show');  
  }

  function formRegShow(check) {
     $('#myModalLogin').modal('hide');  
     $('#RegEmailformgroup').removeClass('has-error');
     $('#RegInputEmail').val('');
     $('#RegNameformgroup').removeClass('has-error');
     $('#RegInputName').val('');
     $('#RegJobformgroup').removeClass('has-error');
     $('#RegInputJob').val('');
     $('#RegSupervisor').prop('checked', check);
     if (check)
      $('#myModalLabelReg').html('Регистрация супервизора');
     else
      $('#myModalLabelReg').html('Регистрация участника или эксперта');
     $('#myModalReg').modal('show');  
  }

  function formRegIn() {
     var postParams;
     var tt = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
     
     $('#RegNameformgroup').removeClass('has-error');
     $('#RegEmailformgroup').removeClass('has-error');
     $('#RegJobformgroup').removeClass('has-error');
     if ($('#RegInputName').val().length==0) 
     {
         $('#RegNameformgroup').addClass('has-error');
         $('#RegInputName').focus();
     }
     else
     if ($('#RegInputEmail').val().length==0) 
     {
         $('#RegEmailformgroup').addClass('has-error');
         $('#RegInputEmail').focus();
     }
     else
     if (!tt.test($('#RegInputEmail').val())) 
     {
         $('#RegEmailformgroup').addClass('has-error');
         $('#RegInputEmail').focus();
     }
     else
     if ($('#RegInputJob').val().length==0)
     { 
         $('#RegJobformgroup').addClass('has-error');
         $('#RegInputJob').focus();
     }
     else
     {
         $('#RegNameformgroup').removeClass('has-error');
         $('#RegEmailformgroup').removeClass('has-error');
         $('#RegJobformgroup').removeClass('has-error');
         postParams = {
                    fio: $('#RegInputName').val(),
                    email: $('#RegInputEmail').val(),
                    job: $('#RegInputJob').val(),
                    supervisor: $('#RegSupervisor').prop('checked')
                }; 
         $('#myModalReg').modal('hide');  
         $.post("regajax.php", postParams, function (data) {
                    eval("var obj=" + data),
                    obj.ok == "1" ? myInfoMsgShow("Регистрация прошла успешно! Проверьте Ваш ящик электронной почты.") : myInfoMsgShow("Ошибка при регистрации! " + obj.error)
                });
     }           
  }

  function formForgotShow() {
     $('#myModalLogin').modal('hide');  
     $('#ForgotEmailformgroup').removeClass('has-error');
     $('#ForgotInputEmail').val('');
     $('#myModalForgot').modal('show');  
  }

  function formForgotIn() {
     var postParams;
     var tt = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
     
     $('#ForgotEmailformgroup').removeClass('has-error');
     if ($('#ForgotInputEmail').val().length==0) 
     {
         $('#ForgotEmailformgroup').addClass('has-error');
         $('#ForgotInputEmail').focus();
     }
     else
     if (!tt.test($('#ForgotInputEmail').val())) 
     {
         $('#ForgotEmailformgroup').addClass('has-error');
         $('#ForgotInputEmail').focus();
     }
     else
     {
         $('#ForgotEmailformgroup').removeClass('has-error');
         postParams = {
                    email: $('#ForgotInputEmail').val()
                }; 
         $('#myModalForgot').modal('hide');  
         $.post("forgotajax.php", postParams, function (data) {
                    eval("var obj=" + data),
                    obj.ok == "1" ?  myInfoMsgShow("На Ваш ящик электронной почты " + $('#ForgotInputEmail').val() + " отправлена ссылка на изменение пароля.") : myInfoMsgShow("Ошибка при восстановлении пароля! " + obj.error)
                });
     }           
  }

  function formLoginShow() {
     $('#Loginformgroup').removeClass('has-error');
     $('#Passformgroup').removeClass('has-error');
     $('#InputLogin').val('');
     $('#InputPass').val('');
     $('#myModalLogin').modal('show');  
  }

  function formLoginIn() {
     var postParams;
     
     $('#Loginformgroup').removeClass('has-error');
     $('#Passformgroup').removeClass('has-error');
     if ($('#InputLogin').val().length==0) 
     {
         $('#Loginformgroup').addClass('has-error');
         $('#InputLogin').focus();
     }
     else
     if ($('#InputPass').val().length==0) 
     {
         $('#Passformgroup').addClass('has-error');
         $('#InputPass').focus();
     }
     else
     {
         $('#Loginformgroup').removeClass('has-error');
         $('#Passformgroup').removeClass('has-error');
         postParams = {
                    login: $('#InputLogin').val(),
                    password: $('#InputPass').val()
                }; 
         $('#myModalLogin').modal('hide');  
         $.post("loginajax.php", postParams, function (data) {
                    eval("var obj=" + data),
                    obj.ok == "1" ? location.replace('projects') : myInfoMsgShow("Нет такого пользователя или логин и пароль введены неправильно!")
                });
     }           
  }

  function formShow(title,info) {
     $('#Nameformgroup').removeClass('has-error');
     $('#Emailformgroup').removeClass('has-error');
     $('#Infoformgroup').removeClass('has-error');
     $('#myModalLabel1').html(title);
     $('#LabelInfo').html(info);
     $('#hiddenInfo').val(info);
     $('#InputInfo').val('');
     $('#myModalMsg').modal('show');  
  }

  function formSend() {
     var postParams;
     var tt = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
     
     $('#Nameformgroup').removeClass('has-error');
     $('#Emailformgroup').removeClass('has-error');
     $('#Infoformgroup').removeClass('has-error');
     if ($('#InputName').val().length==0) 
     {
         $('#Nameformgroup').addClass('has-error');
         $('#InputName').focus();
     }
     else
     if ($('#InputEmail').val().length==0) 
     {
         $('#Emailformgroup').addClass('has-error');
         $('#InputEmail').focus();
     }
     else
     if (!tt.test($('#InputEmail').val())) 
     {
         $('#Emailformgroup').addClass('has-error');
         $('#InputEmail').focus();
     }
     else
     if ($('#InputInfo').val().length==0)
     { 
         $('#Infoformgroup').addClass('has-error');
         $('#InputInfo').focus();
     }
     else
     {
         $('#Nameformgroup').removeClass('has-error');
         $('#Emailformgroup').removeClass('has-error');
         $('#Infoformgroup').removeClass('has-error');
         postParams = {
                    name: $('#InputName').val(),
                    email: $('#InputEmail').val(),
                    title: $('#hiddenInfo').val(),
                    body: $('#InputInfo').val()
                }; 
         $('#myModalMsg').modal('hide');  
         $.post("msgajax.php", postParams, function (data) {
                    eval("var obj=" + data),
                    obj.ok == "1" ? myInfoMsgShow("Ваше сообщение отправлено!") : myInfoMsgShow("Ошибка при отправке сообщения!")
                });
     }           
  }

  function news() {    
   if(n==undefined) { 
     n=0; 
   } else { 
     n=n+3; 
   } 
   $.post('newsjson2.php',{offset:n}, 
    function(data){  
      eval('var obj='+data);         
      if(obj.ok=='1'){
        var s = '<div class="row">';
        for(var i = 0; i < obj.name.length; i++) {                             
           s1 = obj.content[i];
           s+='<div class="col-sm-6 col-md-4"><div class="thumbnail">'+
           '<img width="120" class="img-thumbnail" src="'+obj.picurl[i]+'" alt="' + obj.name[i] + '">'+
           '<div class="caption"><h4><small>' + obj.newsdate[i] + ':</small> ' + obj.name[i] + '</h4>'+
           '<div id="singlenews'+obj.id[i]+'"><p>'+s1.substring(500,0)+'...</p></div><div id="singlenewsb'+obj.id[i]+'"><p><a class="btn btn-primary" onclick="allnews('+obj.id[i]+');" href="javascript:;" role="button">Далее</a></p></div></div></div></div>';
        }
        s+='</div>';
        $('#newsresult').append(s);        
      } 
      else 
      if(obj.ok=='3') { 
       $('#newsbutton').addClass('button_disabled').attr('disabled', true); 
      }                
    }); 
   }  

  function allnews(nid) {    
   $.post('newsjson1.php',{id:nid}, 
    function(data){  
      eval('var obj='+data);         
      if(obj.ok=='1'){
        var s1 = obj.content[0];
        $('#singlenews'+nid).empty();
        $('#singlenewsb'+nid).empty();
        $('#singlenews'+nid).append(s1);        
      } 
    }); 
   }  

  function tops(dest,destname){    
   if(topoffset==undefined) { 
     topoffset=0; 
   } else { 
     if (dest)
      topoffset = topoffset + 3; 
     else 
     if (!dest)
      topoffset = topoffset - 3; 
   } 
   $.post('topsjson3.php',{offset:topoffset}, 
    function(data){  
      eval('var obj='+data);         
      if(obj.ok=='1'){
        var s = '';
        for(var i = 0; i < obj.name.length; i++) {                             
           s1 = obj.comment[i];
           s+='<div class="col-xs-12 col-sm-6 col-lg-4">'+
           '<img width="120" class="img-circle" src="'+obj.picurl[i]+'" alt="' + obj.name[i] + '">'+
           '<h3>' + obj.name[i] + ' <small>' + obj.topdate[i] + '</small></h3>';
           if (s1.substring(300,0).length>0)
            s+='<p>'+s1.substring(300,0)+'...</p>';
           s+='<p><a class="btn btn-primary" href="rating?a='+obj.id[i]+'" role="button">Посмотреть рейтинг</a></p></div>';
           if (i==2) 
            s+='<div class="clearfix visible-xs visible-sm"></div>';
        }
        $(destname).empty();        
        $(destname).append(s);        
      } 
      else 
      if(obj.ok=='3') { 
      }                
    }); 
   }  

  function publics(dest,destname){    
   if(publicoffset==undefined) { 
     publicoffset=0; 
   } else { 
     if (dest)
      publicoffset = publicoffset + 3; 
     else 
     if (!dest)
      publicoffset = publicoffset - 3; 
   } 
   $.post('publicjson3.php',{offset:publicoffset}, 
    function(data){  
      eval('var obj='+data);         
      if(obj.ok=='1'){
        var s = '';
        for(var i = 0; i < obj.name.length; i++) {                             
           s1 = obj.comment[i];
           s+='<div class="col-xs-12 col-sm-6 col-lg-4">'+
           '<img width="120" class="img-circle" src="'+obj.picurl[i]+'" alt="' + obj.name[i] + '">'+
           '<h3>' + obj.name[i] + ' <small>' + obj.topdate[i] + '</small></h3>';
           if (s1.substring(300,0).length>0)
            s+='<p>'+s1.substring(300,0)+'...</p>';
           if (obj.count[i]>0)
            s+='<p><a class="btn btn-primary" href="public?a='+obj.id[i]+'" role="button">Посмотреть проекты <span class="badge">'+obj.count[i]+'</span></a></p></div>';
           else
            s+='<h4>Нет опубликованных проектов</h4></div>';
           if (i==2) 
            s+='<div class="clearfix visible-xs visible-sm"></div>';
        }
        $(destname).empty();        
        $(destname).append(s);        
      } 
      else 
      if(obj.ok=='3') { 
      }                
    }); 
   }  


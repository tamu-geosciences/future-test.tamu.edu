jQuery(document).ready(function(){jQuery(".sidebar ul ul").addClass("nav-list");jQuery(".zenslider").addClass("start");jQuery(".zenslider h3").click(function(){jQuery(this).parent().removeClass("start")});jQuery(".accordion-toggle").prepend("<span class='accordion-icon'></span>");jQuery(".accordion-toggle").not(":first").addClass("collapsed");jQuery(function(){function e(){jQuery("#sidebar-main,#main-area").equalHeight()}if(jQuery(window).width()>759){e();var t=0,n=null;n=setInterval(function(){t++;t<=20?e():clearInterval(n)},200)}jQuery(window).resize(function(){jQuery(window).width()>759&&e();jQuery(window).width()<759&&jQuery("#main-side,#main-area,#sidebar-main").css({height:"auto"})});jQuery("a,li,span").click(function(){e();var t=0,n=null;n=setInterval(function(){t++;t<=20?e():clearInterval(n)},200)})})});(function(e){e.fn.equalHeight=function(){var t=0,n=e.browser.msie?"1%":"auto";return this.css("height",n).each(function(){t=Math.max(t,this.offsetHeight)}).css("height",t).each(function(){var n=this.offsetHeight;n>t&&e(this).css("height",t-(n-t))})};e.fn.lazyload=function(t){var n={threshold:0,failurelimit:0,event:"scroll",effect:"show",container:window};t&&e.extend(n,t);var r=this;"scroll"==n.event&&e(n.container).bind("scroll",function(t){var i=0;r.each(function(){if(!e.belowthefold(this,n)&&!e.rightoffold(this,n))e(this).trigger("appear");else if(i++>n.failurelimit)return!1});var s=e.grep(r,function(e){return!e.loaded});r=e(s)});return this.each(function(){var t=this;e(t).attr("original",e(t).attr("src"));if("scroll"!=n.event||e.belowthefold(t,n)||e.rightoffold(t,n)){n.placeholder?e(t).attr("src",n.placeholder):e(t).removeAttr("src");t.loaded=!1}else t.loaded=!0;e(t).one("appear",function(){this.loaded||e("<img />").bind("load",function(){e(t).hide().attr("src",e(t).attr("original"))[n.effect](n.effectspeed);t.loaded=!0}).attr("src",e(t).attr("original"))});"scroll"!=n.event&&e(t).bind(n.event,function(n){t.loaded||e(t).trigger("appear")})})};e.belowthefold=function(t,n){if(n.container===undefined||n.container===window)var r=e(window).height()+e(window).scrollTop();else var r=e(n.container).offset().top+e(n.container).height();return r<=e(t).offset().top-n.threshold};e.rightoffold=function(t,n){if(n.container===undefined||n.container===window)var r=e(window).width()+e(window).scrollLeft();else var r=e(n.container).offset().left+e(n.container).width();return r<=e(t).offset().left-n.threshold};e.extend(e.expr[":"],{"below-the-fold":"$.belowthefold(a, {threshold : 0, container: window})","above-the-fold":"!$.belowthefold(a, {threshold : 0, container: window})","right-of-fold":"$.rightoffold(a, {threshold : 0, container: window})","left-of-fold":"!$.rightoffold(a, {threshold : 0, container: window})"})})(jQuery);
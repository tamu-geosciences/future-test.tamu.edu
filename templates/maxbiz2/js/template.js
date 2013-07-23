jQuery(document).ready(function() {

	jQuery(".sidebar ul ul").addClass("nav-list");
	
	// Fixes default classes not applied to Joomal contact forms for open items
	jQuery(".zenslider").addClass("start");
	jQuery(".zenslider h3").click(function () {
		jQuery(this).parent().removeClass("start");
	});
	
	// Fixes default classes not applied to Joomal contact forms for open items
		jQuery(".accordion-toggle").prepend("<span class='accordion-icon'></span>");
		jQuery(".accordion-toggle").not(":first").addClass("collapsed");

	
	

	jQuery(function() {
		function triggerEqualHeight() {
			jQuery('#sidebar-main,#main-area').equalHeight();
		}
		
		if(jQuery(window).width() > 759) {
		// On Load
		triggerEqualHeight();
		var f = 0;
		var interval = null;
		interval = setInterval(function() {
				f++;
				if (f <= 20) {
					triggerEqualHeight();
				} else {
					clearInterval(interval);
				}
			}, 200);
		}
		
		// Window resize
		jQuery(window).resize(function() {
		
			if(jQuery(window).width() > 759) {
			    	triggerEqualHeight();
			    }
			 
			 if(jQuery(window).width() < 759) {
			     	jQuery("#main-side,#main-area,#sidebar-main").css({"height":"auto"});
			  }  
			   	
		});
		
		jQuery("a,li,span").click(function() {
			triggerEqualHeight();
			
			var f = 0;
			var interval = null;
			interval = setInterval(function() {
					f++;
					if (f <= 20) {
						triggerEqualHeight();
					} else {
						clearInterval(interval);
					}
				}, 200);
			
		});
		
		
	});
	

});


(function($) {

    $.fn.equalHeight = function(){
    	var height = 0,
    		reset = $.browser.msie ? "1%" : "auto";

    	return this
    		.css("height", reset)
    		.each(function() {
    			height = Math.max(height, this.offsetHeight);
    		})
    		.css("height", height)
    		.each(function() {
    			var h = this.offsetHeight;
    			if (h > height) {
    				$(this).css("height", height - (h - height));
    			};
    		});

    };
    
    
    $.fn.lazyload=function(options){var settings={threshold:0,failurelimit:0,event:"scroll",effect:"show",container:window};if(options){$.extend(settings,options);}
    var elements=this;if("scroll"==settings.event){$(settings.container).bind("scroll",function(event){var counter=0;elements.each(function(){if(!$.belowthefold(this,settings)&&!$.rightoffold(this,settings)){$(this).trigger("appear");}else{if(counter++>settings.failurelimit){return false;}}});var temp=$.grep(elements,function(element){return!element.loaded;});elements=$(temp);});}
    return this.each(function(){var self=this;$(self).attr("original",$(self).attr("src"));if("scroll"!=settings.event||$.belowthefold(self,settings)||$.rightoffold(self,settings)){if(settings.placeholder){$(self).attr("src",settings.placeholder);}else{$(self).removeAttr("src");}
    self.loaded=false;}else{self.loaded=true;}
    $(self).one("appear",function(){if(!this.loaded){$("<img />").bind("load",function(){$(self).hide().attr("src",$(self).attr("original"))
    [settings.effect](settings.effectspeed);self.loaded=true;}).attr("src",$(self).attr("original"));};});if("scroll"!=settings.event){$(self).bind(settings.event,function(event){if(!self.loaded){$(self).trigger("appear");}});}});};$.belowthefold=function(element,settings){if(settings.container===undefined||settings.container===window){var fold=$(window).height()+$(window).scrollTop();}
    else{var fold=$(settings.container).offset().top+$(settings.container).height();}
    return fold<=$(element).offset().top-settings.threshold;};$.rightoffold=function(element,settings){if(settings.container===undefined||settings.container===window){var fold=$(window).width()+$(window).scrollLeft();}
    else{var fold=$(settings.container).offset().left+$(settings.container).width();}
    return fold<=$(element).offset().left-settings.threshold;};$.extend($.expr[':'],{"below-the-fold":"$.belowthefold(a, {threshold : 0, container: window})","above-the-fold":"!$.belowthefold(a, {threshold : 0, container: window})","right-of-fold":"$.rightoffold(a, {threshold : 0, container: window})","left-of-fold":"!$.rightoffold(a, {threshold : 0, container: window})"});
    

    
    		
})(jQuery);
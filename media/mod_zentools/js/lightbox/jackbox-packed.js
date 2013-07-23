/* --------------------------------------------- */
/* Author: http://codecanyon.net/user/CodingJack */
/* --------------------------------------------- */

;(function($) {

	var touchStop, touchMove, touchStart,

	methods = {

		touchSwipe: function($this, cb) {

			methods.touchSwipeLeft($this, cb);
			methods.touchSwipeRight($this, cb);

		},

		touchSwipeLeft: function($this, cb, prevent) {

			if(prevent) $this.stopProp = true;
			if(!$this.swipeLeft) $this.swipeLeft = cb;
			if(!$this.swipeRight) $this.addEventListener(touchStart, startIt);

		},

		touchSwipeRight: function($this, cb, prevent) {

			if(prevent) $this.stopProp = true;
			if(!$this.swipeRight) $this.swipeRight = cb;
			if(!$this.swipeLeft) $this.addEventListener(touchStart, startIt);

		},

		unbindSwipe: function($this) {

			$this.removeEventListener(touchStart, startIt);
			$this.removeEventListener(touchMove, moveIt);
			$this.removeEventListener(touchStop, endIt);

			clearData($this);

		}

	};

	if("ontouchend" in document) {

		touchStop = "touchend";
		touchMove = "touchmove";
		touchStart = "touchstart";

	}
	else {

		touchStop = "mouseup";
		touchMove = "mousemove";
		touchStart = "mousedown";

	}

	$.fn.cjSwipe = function(type, a, b) {

		methods[type](this[0], a, b);

	};

	function clearData($this, typed) {

		if(!typed) {

			delete $this.swipeLeft;
			delete $this.swipeRight;
			delete $this.stopProp;

		}

		delete $this.newPageX;
		delete $this.pageX;

	}

	function startIt(event) {

		var pages = event.touches ? event.touches[0] : event;

		if(this.stopProp) event.stopImmediatePropagation();

		this.pageX = pages.pageX;
		this.addEventListener(touchStop, endIt);
		this.addEventListener(touchMove, moveIt);

	}

	function moveIt(event) {

		var pages = event.touches ? event.touches[0] : event,
		newPageX = this.newPageX = pages.pageX;

		if(Math.abs(this.pageX - newPageX) > 10) event.preventDefault();

	}

	function endIt() {

		var newPageX = this.newPageX, pageX = this.pageX, evt, typed = this.cjThumbs;

		this.removeEventListener(touchMove, moveIt);
		this.removeEventListener(touchStop, endIt);

		if(Math.abs(pageX - newPageX) < 30) return;

		if(!typed) this.removeEventListener(touchStart, startIt);

		if(pageX > newPageX) {

			if(this.swipeLeft) {

				evt = this.swipeLeft;
				clearData(this, typed);
				evt();

			}

		}
		else {

			if(this.swipeRight) {

				evt = this.swipeRight;
				clearData(this, typed);
				evt(1);

			}

		}

	}


})(jQuery);





/* --------------------------------------------- */
/* Author: http://codecanyon.net/user/CodingJack */
/* --------------------------------------------- */

;(function($) {

	// These can be overriden with the main jackBox() call, see "Global Settings" in the help documentation
	var defaults = {

		useThumbs: true,
		deepLinking: true,
		autoPlayVideo: false,
		flashVideoFirst: false,

		defaultVideoWidth: 960,
		defaultVideoHeight: 540,

		thumbnailWidth: 75,
		thumbnailHeight: 50,
		useThumbTooltips: true,

		preloadGraphics: true,
		showInfoByDefault: false,
		thumbsStartHidden: false,
		showPageScrollbar: false,
		useKeyboardControls: true,
		fullscreenScalesContent: true,
		defaultShareImage: "jackbox/img/default_share.jpg"

	},

	// The padding/buffer for the lightbox main content container
	boxBuffer = 10,

	// Accounts for the thumb panel's border
	thumbnailMargin = 2,

	// Accounts for the large prev/next buttons you'll find on the left and right sides of the screen
	// This number makes sure the content always fits between these buttons
	thumbPanelBuffer = 160,

	// The name of the main folder where all of the JackBox modules can be found
	baseName = "jackbox",

	// The path to the JackBox graphics folder for preloading
	graphicsPath = "../../images/lightbox/graphics/",

	// The url to the preloader script
	preloaderUrl = baseName + "/php/graphics.php",

	// The url to the swf module
	swfPlayer = "jackbox/modules/jackbox_swf.html",

	// The url to the fallback thumbnail
	defaultThumb = "jackbox/img/thumbs/default.jpg",

	// The url to the video player module
	videoPlayer = "jackbox/modules/jackbox_video.html",

	// The url to the audio player module
	audioPlayer = "jackbox/modules/jackbox_audio.html",

	// The Vimeo video iframe to be used (edit with caution)
	vimeoMarkup = "http://player.vimeo.com/video{url}?title=0&byline=0&portrait=0&autoplay={autoplay}&color=FFFFFF&wmode=transparent",

	// The Youtube video iframe to be used (edit with caution)
	youTubeMarkup = "http://www.youtube.com/embed/{url}?autoplay={autoplay}&autohide=1&hd=1&iv_load_policy=3&showinfo=0&showsearch=0&wmode=transparent",

	// The markup or "header" that will be placed above the item's content
	topMarkup =

		'<div class="jackbox-top clearfix">' +

			// fullscreen and close buttons
			'<div class="jackbox-top-icons">' +

				'<span class="jackbox-fullscreen"><span class="jackbox-button jackbox-fs"></span><span class="jackbox-button jackbox-ns"></span></span>' +
				'<span class="jackbox-button jackbox-button-margin jackbox-close"></span>' +

			'</div>' +

		'<div />',

	// The markup or "footer" that will be placed below the item's content
	bottomMarkup =

		'<div class="jackbox-bottom clearfix">' +

			// prev/next buttons
			'<div class="jackbox-controls">' +

				'<span class="jackbox-button jackbox-arrow-left"></span>' +
				'<span class="jackbox-button jackbox-arrow-right"></span>' +

			'</div>' +

			// title text plus current number
			'<div class="jackbox-title-text">' +

				'<span class="jb-current"></span><span class="jb-divider">/</span><span class="jb-total"></span>' +
				'<span class="jackbox-title-txt"></span>' +

			'</div>' +

			// info and thumb toggle buttons
			'<div class="jackbox-bottom-icons">' +

				'<span class="jackbox-button jackbox-info"></span>' +
				'<span class="jackbox-button-margin jackbox-button-thumbs"><span class="jackbox-button jackbox-hide-thumbs"></span><span class="jackbox-button jackbox-show-thumbs"></span></span>' +

			'</div>' +

		'</div>',


	////////////////////////////////////////////////////////
	// END SETTINGS ////////////////////////////////////////
	////////////////////////////////////////////////////////

	resizeThrottle,
	bottomContent,
	hasFullscreen,
	holderPadLeft,
	holderPadTop,
	thumbTipText,
	thumbBufTall,
	currentWidth,
	holderHeight,
	isFullscreen,
	itemCallback,
	thumbTipBuf,
	paddingTall,
	paddingWide,
	totalThumbs,
	description,
	panelBuffer,
	thumbMinus,
	thumbStrip,
	thumbPanel,
	stripWidth,
	thumbRight,
	topContent,
	descHolder,
	descHeight,
	showThumbs,
	hideThumbs,
	panelRight,
	fullNormal,
	scrollPos,
	panelLeft,
	titleText,
	thumbLeft,
	container,
	preloader,
	numThumbs,
	descripts,
	oldHeight,
	unescaped,
	swipeable,
	oldWidth,
	thumbTip,
	thumbVis,
	showHide,
	closeBtn,
	descText,
	controls,
	elements,
	useTips,
	scaleUp,
	divider,
	descVis,
	thumbOn,
	wrapper,
	current,
	goRight,
	legged,
	preBuf,
	goLeft,
	upsize,
	fader,
	touch,
	boxed,
	title,
	total,
	cover,
	words,
	$this,
	items,
	info,
	docs,
	doc,
	url,
	wid,
	win,
	leg,
	isIE,
	cats,
	high,
	hash,
	list,
	isIE8,
	pushW,
	pushH,
	timer,
	toCall,
	thumbs,
	bodies,
	holder,
	lights,
	oWidth,
	isLocal,
	runTime,
	scaling,
	content,
	oHeight,
	isImage,
	winWide,
	winTall,
	bufferW,
	bufferH,
	theType,
	parents,
	browser,
	thmWidth,
	dataDesc,
	isActive,
	preAdded,
	scroller,
	keyboard,
	useThumbs,
	isLoading,
	fromThumb,
	hasThumbs,
	holOrigTop,
	firstCheck,
	currentCat,
	showScroll,
	deepLinking,
	lightSocial,
	socialFrame,
	thumbHolder,
	eventsAdded,
	preThumbBuf,
	holOrigLeft,
	instantiated,
	arrowClicked,
	arrowsActive,
	thumbsChecked,
	autoPlayVideo,
	thumbnailWidth,
	jackBoxIsReady,
	thumbnailHeight,
	flashVideoFirst,
	defaultShareImage,
	showInfoByDefault,
	thumbsStartHidden,
	defaultVideoWidth,
	defaultVideoHeight,

	isOn = 1,
	catOn = -1,
	numCats = 0,
	firstRun = true,
	initialLoad = true,

	methods = {

		init: function($this, settings) {

			if(!instantiated) {

				if(typeof Jacked === "undefined") return;
				if(settings) $.extend(defaults, settings);

				useThumbs = defaults.useThumbs;
				deepLinking = defaults.deepLinking;
				useTips = defaults.useThumbTooltips;
				autoPlayVideo = defaults.autoPlayVideo;
				keyboard = defaults.useKeyboardControls;
				showScroll = defaults.showPageScrollbar;
				thumbnailWidth = defaults.thumbnailWidth;
				scaling = defaults.fullscreenScalesContent;
				thumbnailHeight = defaults.thumbnailHeight;
				flashVideoFirst = defaults.flashVideoFirst;
				showInfoByDefault = defaults.showInfoByDefault;
				thumbsStartHidden = defaults.thumbsStartHidden;
				defaultShareImage = defaults.defaultShareImage;
				defaultVideoWidth = defaults.defaultVideoWidth;
				defaultVideoHeight = defaults.defaultVideoHeight;

				doc = document;
				docs = $(document);
				instantiated = true;
				thmWidth = thumbnailWidth + thumbnailMargin;

				isLocal = doc.URL.search("file:///") !== -1;
				Jacked.setEngines({ios: true, safari: true, opera: true, firefox: true});

				// if(defaults.preloadGraphics && !isLocal) {

				// 	$.getJSON(preloaderUrl + "?jackbox_path=" + graphicsPath, jsonLoaded);

				// }

				lights = $this;
				defaults = null;

				isIE8 = Jacked.getIE();
				touch = Jacked.getMobile();
				browser = Jacked.getBrowser();
				isIE = browser === "ie";

				if(touch) showScroll = false;

				if(typeof $.address !== "undefined" && deepLinking) {

					if(!isIE) {

						$.address.init(init);

					}
					else {

						init();
						$.address.update();

					}

				}
				else {

					deepLinking = false;
					init();

				}

				if(typeof StackBlurImage !== "undefined" && !isLocal && !isIE8) {

					$(".jackbox-hover-blur").each(drawBlur);

				}

				$(".jackbox-tooltip").each(addTip);

			}

		},

		frameReady: function() {

			if(isActive) loaded();

		},

		// API Method for adding a lightbox item to the stack on the fly
		newItem: function($this, settings) {

			var i = cats.length,
			found = -1,
			listed,
			group,
			str;

			if(settings && typeof settings === "object") {

				var prop, val, itm;

				for(prop in settings) {

					if(prop !== "trigger") {

						val = "data-" + prop;
						itm = settings[prop];
						$this.attr(val, itm);

						if(prop === "group") group = itm;

					}

				}

			}
			else {

				settings = null;

			}

			if(!group) group = $this.attr("data-group");
			if(!group) return;

			str = group.split(" ").join("").toLowerCase();

			while(i--) {

				if(cats[i] === str) {

					found = i;
					break;

				}

			}

			if(found > -1) {

				listed = list[found];

				for(var itms in listed) {

					if(listed[itms[0]][0] === $this[0]) {

						return;

					}

				}

				i = listed.length;
				listed[i] = $this;

			}
			else {

				found = cats.length;
				i = list.length;

				cats[found] = str;
				numCats++;

				list[i] = [$this];
				i = 0;

			}

			itemEach($this);
			$this.data({id: i, cat: found});
			if(settings && settings.trigger) $this.trigger("click");

		}

	},

	loadFrame = (function() {

		var obj = {

			type: "text/html",
			frameborder: 0,
			mozallowfullscreen: "mozallowfullscreen",
			webkitallowfullscreen: "webkitallowfullscreen",
			allowfullscreen: "allowfullscreen"

		};

		return function(st, special, scrolls) {

			obj.width = wid;
			obj.height = high;
			obj.scrolling = !scrolls ? "no" : "auto";

			content = $("<iframe />").attr(obj).addClass("jackbox-content").prependTo(container);

			if(!special) content.one("load.jackbox", loaded);
			content.attr("src", st);

		};

	})(),

	loaded = (function() {

		var obj = {};

		return function(event) {

			if(event) event.stopPropagation();
			if(isImage) {

				oWidth = this.width || content.width();
				oHeight = this.height || content.height();
				setSize();

			}

			obj.width = wid;
			obj.height = high;

			content.css(obj);
			tweenContent(true);

			if(itemCallback) itemCallback();
			win.on("resize.jackbox", resized);

		};

	})(),

	tweenContent = (function() {

		var tw = {}, tw2 = {}, props = {};

		return function(callback) {

			var newW;

			if(callback) {

				if(wid < 260) pushW += 260 - wid;
				newW = Math.max(wid, 260);

				if(newW === oldWidth && high === oldHeight) {

					showContent();
					return;

				}

				tw.callback = showContent;
				tw.duration = oldWidth ? Math.abs(newW - oldWidth) > 50 || Math.abs(high - oldHeight) > 50 ? 600 : 300 : 600;

			}
			else {

				newW = wid;
				props.width = newW;

				tw.duration = 300;
				delete tw.callback;

				if(topContent) Jacked.tween(topContent[0], props, tw);
				if(bottomContent) Jacked.tween(bottomContent[0], props, tw);

				props.height = high;
				Jacked.stopTween(content[0], true);
				Jacked.tween(content[0], props, tw);

			}

			tw2.marginLeft = -(((pushW >> 1) + 0.5) | 0);
			tw2.marginTop = -(((pushH >> 1) + 0.5) | 0);
			tw2.height = high;
			tw2.width = newW;

			if(!oldWidth) tw2.opacity = 1;

			Jacked.tween(holder[0], tw2, tw);

			oldWidth = newW;
			oldHeight = high;

		};

	})(),

	showContent = (function() {

		var dur = {}, style = {opacity: 1, visibility: "visible"};

		return function() {

			showElements();
			preloader.removeClass("jackbox-spin-preloader");

			var maxed = Math.max(wid, 260);
			dur.duration = 600;

			if(isImage && !isIE8) {

				Jacked.fadeIn(content[0], dur);

			}
			else {

				content.show();

				if(theType === "audio" || theType === "local") {

					content[0].contentWindow.cjInit();

				}

			}

			dur.duration = 300;

			if(!isIE8) {

				if(topContent) {

					topContent.css("width", maxed);
					Jacked.fadeIn(topContent[0], dur);

				}

				if(bottomContent) {

					bottomContent.css("width", maxed);
					Jacked.fadeIn(bottomContent[0], dur);

				}

			}
			else {

				if(topContent) topContent.css("width", maxed).show();
				if(bottomContent) bottomContent.css("width", maxed).show();

			}

			if(info && descText) {

				info.show();
				descHolder.html(descText).show();

				descHeight = -descHolder.outerHeight();
				description.css("height", -descHeight < high ? -descHeight : high);

				if(!showInfoByDefault) {

					descVis = false;
					descHolder.css("margin-top", descHeight);

				}
				else {

					descVis = true;
					info.addClass("jb-info-inactive");
					descHolder.css({visibility: 'visible', marginTop: 0});

				}

			}
			else if(info) {

				info.hide();
				descHolder.hide();

			}

			isLoading = false;

			if(!eventsAdded && legged) {

				panelLeft.css(style);
				panelRight.css(style);

			}

			if(!thumbsChecked && useThumbs && legged) loadThumbs();
			if(!eventsAdded) addEvents();

			if(touch) {

				content[0].removeEventListener("touchstart", returnFalse, false);
				content[0].removeEventListener("touchmove", returnFalse, false);
				content[0].removeEventListener("touchend", returnFalse, false);
				removeTouches();

			}

			if(legged) turnOn();

		};

	})(),

	showElements = (function() {

		var obj = {

			type: "text/html",
			frameborder: 0,
			mozallowfullscreen: "mozallowfullscreen",
			webkitallowfullscreen: "webkitallowfullscreen",
			allowfullscreen: "allowfullscreen",
			scrolling: "no"

		};

		return function() {

			if(!elements) createElements();

			if(!legged) {

				if(controls) controls.hide();
				if(showHide) showHide.hide();

			}

			if(title) {

				if(titleText === "false") titleText = false;
				var hasContent = words && titleText;

				if(current && legged) {

					current.text((isOn + 1)).show();
					total.html(leg).show();
					if(divider) divider.show();

				}
				else {

					if(total) total.hide();
					if(current) current.hide();
					if(divider) divider.hide();

				}

				if(hasContent) {
					words.html("&nbsp;-&nbsp;" + unescaped);

					var a = words.find("a");

					if(a.length) {

						a.on("click.jackbox", stopProp);
						words.data("links", a);

					}

				}

			}

			if(!lightSocial || isLocal) return;

			var poster, ur, domain = doc.URL.split("#")[0], len = domain.length - 1;

			if(domain.search("/") !== -1) {

				if(domain.charAt(len) !== "/") {

					(deepLinking) ? ur = domain + "#/" + hash + "/" + (isOn + 1) : ur = domain;
					domain = domain.substring(0, domain.lastIndexOf("/"));

				}
				else {

					domain = domain.substring(0, len);
					(deepLinking) ? ur = domain + "/#/" + hash + "/" + (isOn + 1) : ur = domain;

				}

			}

			if(isImage) {

				poster = $this.attr("href") || $this.attr("data-href");

			}
			else {

				var alt = $this.children("img");
				poster = alt.length ? alt.attr("src") : defaultShareImage;

			}

			if(poster.search("http") === -1) poster = poster.charAt(0) !== "/" ? domain + "/" + poster : domain + poster;
		};

	})(),

	toggleInfo = (function() {

		var obj1 = {}, obj2 = {duration: 300};

		return function(event) {

			if(event) event.stopPropagation();

			if(!descVis) {

				info.addClass("jb-info-inactive");
				description.css("visibility", "visible");

				obj1.marginTop = 0;
				delete obj2.callback;

			}
			else {

				obj1.marginTop = descHeight;
				obj2.callback = infoIndex;
				info.removeClass("jb-info-inactive");

			}

			Jacked.tween(descHolder[0], obj1, obj2);
			descVis = !descVis;

		};

	})(),

	overThumb = (function() {

		var obj = {opacity: 1, visibility: "visible"};

		return function() {

			if(touch) {

				clearTimeout(fader);
				fader = setTimeout(outThumb, 2000);

			}

			var $this = $(this), ww, xx, buffer, lft;
			thumbTipText.text($this.data("theTitle"));

			ww = parseInt(thumbTipText.css("width"), 10);
			lft = thumbPanel.data("offLeft");
			xx = $this.offset().left;

			buffer = lft + thumbPanel.width() - ww - thumbTipBuf;

			obj.width = ww;
			obj.left = xx < lft ? lft : xx > buffer ? buffer : xx;

			thumbTip.css(obj);

		};

	})(),

	toggleThumbs = (function() {

		var obj1 = {}, obj2 = {duration: 300};

		return function(event) {

			if(event) event.stopPropagation();

			if(thumbVis === 0) {

				thumbVis = holderHeight;

				if(showHide) {

					hideThumbs.hide();
					showThumbs.show();

				}

			}
			else {

				thumbVis = 0;

				if(showHide) {

					showThumbs.hide();
					hideThumbs.show();

				}

			}

			obj1.bottom = thumbVis;
			Jacked.tween(thumbHolder[0], obj1, obj2);

			if(winWide < 569) return;

			sizer("true");
			tweenContent();

		};

	})(),

	posThumbs = (function() {

		var obj = {};

		return function(resize) {

			var maxWidth = winWide - thumbPanelBuffer;

			if(stripWidth < maxWidth) {

				numThumbs = totalThumbs;
				arrowsActive = false;

			}
			else {

				numThumbs = (maxWidth / thmWidth) | 0;
				arrowsActive = true;

			}

			currentWidth = (thmWidth * numThumbs) - thumbnailMargin;
			thumbMinus = numThumbs - 1;

			obj.marginLeft = -(currentWidth >> 1) - thumbnailMargin;
			obj.width = currentWidth;

			thumbPanel.css(obj);
			thumbStrip.css("width", stripWidth);

			checkThumbs(resize);

		};

	})(),

	checkThumbs = (function() {

		var obj1 = {}, obj2 = {duration: 300};

		return function(resize, tween) {

			if(resize) {

				thumbOn = isOn;

				if(isOn !== 0 && isOn + numThumbs > leg) thumbOn = leg - numThumbs;

				Jacked.stopTween(thumbStrip[0]);
				thumbStrip.css("left", thumbOn * -thmWidth);

			}

			else {

				if(isOn === 0) {

					thumbOn = 0;

				}
				else if(isOn > thumbOn + thumbMinus) {

					while(isOn > thumbOn + thumbMinus) thumbOn++;

				}

				if(tween) {

					obj1.left = thumbOn * -thmWidth;
					Jacked.tween(thumbStrip[0], obj1, obj2);

				}
				else {

					Jacked.stopTween(thumbStrip[0]);
					thumbStrip.css("left", thumbOn * -thmWidth);

				}

			}

			checkArrows(resize, false);

		};

	})(),

	checkArrows = (function() {

		var obj1 = {}, obj2 = {duration: 300};

		return function(resize, fromArrow) {

			thumbLeft.off(".jackbox");
			thumbRight.off(".jackbox");

			if(!arrowsActive) {

				thumbLeft.hide();
				thumbRight.hide();

			}
			else {

				if(touch) thumbPanel.cjSwipe("unbindSwipe");

				if(thumbOn < leg - numThumbs) {

					thumbRight.on("click.jackbox", thumbArrowNext).show();

					if(touch) thumbPanel.cjSwipe("touchSwipeLeft", thumbArrowNext, true);

				}
				else {

					thumbRight.hide();

				}

				if(thumbOn > 0) {

					thumbLeft.on("click.jackbox", thumbArrowPrev).show();

					if(touch) thumbPanel.cjSwipe("touchSwipeRight", thumbArrowPrev, true);

				}
				else {

					thumbLeft.hide();

				}

				if(fromArrow) {

					obj1.left = thumbOn * -thmWidth;
					Jacked.tween(thumbStrip[0], obj1, obj2);

				}
				else if(resize || !firstCheck) {

					var off = thumbPanel.offset().left;

					thumbLeft.css("left", off);
					thumbRight.css("left", off + currentWidth);
					firstCheck = true;

				}

			}

		};

	})(),

	sizer = (function() {

		var obj1 = {opacity: 1}, obj2 = {};

		return function(noResize) {

			winWide = win.width();
			winTall = Math.max(win.height(), 226);

			var tBuf = winWide > 568 && thumbVis === 0 ? thumbBufTall : 0;

			if(theType !== "audio" && theType !== "inline") {

				scaleUp = !isFullscreen ? upsize : upsize || scaling;

			}
			else {

				scaleUp = false;

			}

			if(bufferW < winWide && bufferH + tBuf < winTall && !scaleUp) {

				wid = oWidth;
				high = oHeight;

			}
			else {

				wid = winWide / bufferW;
				high = winTall / bufferH;

				var perc = (wid > high) ? high : wid;

				wid = oWidth * perc;
				high = oHeight * perc;

				if(winWide > winTall) {

					if(high + boxed + tBuf > winTall) {

						high = winTall - paddingTall - boxBuffer - tBuf;
						wid = oWidth * (high / oHeight);

					}

				}
				else {

					if(wid > high) {

						if(wid + bufferW > winWide) {

							wid = winWide - boxBuffer;
							high = oHeight * (wid / oWidth);

						}

					}
					else {

						if(high + boxed + tBuf > winTall) {

							high = winTall - paddingTall - boxBuffer - tBuf;
							wid = oWidth * (high / oHeight);

						}

					}

				}

				if(wid !== (wid | 0)) wid = (wid + 1) | 0;
				if(high !== (high | 0)) high = (high + 1) | 0;

			}

			if(theType === "inline") {

				var w = winWide - paddingWide - panelBuffer - boxBuffer;
				var h = winTall - paddingTall - boxBuffer - tBuf;

				wid = oWidth > w ? w : oWidth;
				high = oHeight < h ? oHeight : high;

			}

			pushW = wid + paddingWide;
			pushH = high + paddingTall + tBuf;

			if(noResize === "true") return;

			Jacked.stopTween(holder[0], false, true);
			if(content) Jacked.stopTween(content[0], true, true);

			if(wid < 260) pushW += 260 - wid;
			var maxed = Math.max(260, wid);

			obj1.width = maxed;
			obj1.height = high;

			obj2.marginLeft = -(((pushW * 0.5) + 0.5) | 0);
			obj2.marginTop = -(((pushH * 0.5) + 0.5) | 0);
			obj2.width = maxed;
			obj2.height = high;

			holder.css(obj2);
			content.css(obj1);

			if(bottomContent) {

				Jacked.stopTween(bottomContent[0]);
				bottomContent.css("width", maxed);

			}

			if(topContent) {

				Jacked.stopTween(topContent[0]);
				topContent.css("width", maxed);

			}

			if(info && descText) {

				descHeight = -descHolder.outerHeight();
				description.css("height", -descHeight < high ? -descHeight : high);

				if(!descVis) {

					Jacked.stopTween(descHolder[0], false, true);
					descHolder.css("margin-top", descHeight);

				}

			}

			if(hasThumbs) posThumbs(true);

		};

	})(),

	moveTip = (function() {

		var obj = {};

		return function(event) {

			var data = $(this).data();

			obj.left = event.pageX - data.tipX - data.tipWidth;
			obj.top = event.pageY - data.tipY - data.tipHeight;

			data.tip.css(obj);

		};

	})();

	$.fn.jackBox = function(func, params) {

		if(methods.hasOwnProperty(func)) {

			methods[func](this, params);

		}

	};

	$.jackBox = {

		available: function(callback) {

			if(!callback) return;

			if(jackBoxIsReady) {

				(deepLinking) ? setTimeout(callback, 250) : callback();

			}
			else {

				toCall = callback;

			}

		},

		itemLoaded: function(callback) {

			itemCallback = callback;

		}

	};

	function init() {

		win = $(window);
		bodies = $("body");
		scroller = $("body, html");
		cover = $("<div />").addClass("jackbox-modal");
		holder = $("<div />").addClass("jackbox-holder");
		wrapper = $("<div />").addClass("jackbox-wrapper");
		preloader = $("<div />").addClass("jackbox-preloader");

		panelLeft = $("<span />").addClass("jackbox-panel jackbox-panel-left");
		panelRight = $("<span />").addClass("jackbox-panel jackbox-panel-right");

		var frag = doc.createDocumentFragment();
		frag.appendChild(wrapper[0]);
		frag.appendChild(preloader[0]);
		cover[0].appendChild(frag);

		frag = doc.createDocumentFragment();
		frag.appendChild(panelLeft[0]);
		frag.appendChild(panelRight[0]);
		frag.appendChild(holder[0]);
		wrapper[0].appendChild(frag);

		container = $("<div />").addClass("jackbox-container");

		if(!isIE8) {

			var preOutside = $("<span />").addClass("jackbox-pre-outside").appendTo(preloader);
			$("<span />").addClass("jackbox-pre-inside").appendTo(preOutside);

		}

		boxBuffer *= 2;
		scrollPos = 0;
		frag = doc.createDocumentFragment();

		if(!!topMarkup) {

			topContent = $(topMarkup).hide();
			frag.appendChild(topContent[0]);

		}

		frag.appendChild(container[0]);

		if(!!bottomMarkup) {

			bottomContent = $(bottomMarkup).hide();
			frag.appendChild(bottomContent[0]);

		}

		holder[0].appendChild(frag);
		holderHeight = -(thumbnailHeight + thumbnailMargin);

		descripts = [];
		items = [];
		list = [];
		cats = [];

		lights.each(catEach);

		if(deepLinking) {

			$.address.internalChange(insideChange);
			$.address.externalChange(browserChange);

		}

		jackBoxIsReady = true;

		if(toCall) {

			(deepLinking) ? setTimeout(toCall, 250) : toCall();
			toCall = null;

		}

		lights = items = topMarkup = bottomMarkup = descripts = null;

	}

	function catEach() {

		var str = $(this).attr("data-group").split(" ").join("").toLowerCase();

		if(!isIE8) {

			if(cats.indexOf(str) === -1) addCat(str);

		}
		else {

			var i = cats.length, found = false;

			while(i--) {

				if(cats[i] === str) {

					found = true;
					break;

				}

			}

			if(!found) addCat(str);

		}

	}

	function addCat(str) {

		cats[cats.length] = str;
		items = [];

		$(".jackbox[data-group=" + str + "]").each(itemEach);

		list[list.length] = items;
		numCats++;

	}

	function itemEach(i) {

		if(!isNaN(i)) {

			$this = $(this).data({id: i, cat: numCats});
			items[i] = $this;

		}
		else {

			$this = i;

		}

		url = $this.attr("href") || $this.attr("data-href");
		if(!url) return;

		var popped, video;

		if(url.charAt(0) !== "#") {

			popped = url.toLowerCase().split(".").pop();

		}
		else {

			popped = "inline";

		}

		video = checkVideo(url, popped);

		if(video) {

			$this.data("type", video);

			if(!$this.attr("data-thumbnail")) {

				if(video === "vimeo") {

					getVimeoThumb($this, url);

				}
				else if(video === "youtube") {

					$this.attr("data-thumbnail", "http://img.youtube.com/vi/" + url.split("http://www.youtube.com/watch?v=")[1] + "/1.jpg");

				}

			}

		}
		else if(checkImage(popped)) {

			$this.data("type", "image");

		}
		else {

			switch(popped) {

				case "mp3":

					$this.data("type", "audio");

				break;

				case "swf":

					$this.data("type", "swf");

				break;

				case "inline":

					$this.data("type", "inline");

				break;

				default:

					$this.data("type", "iframe");

				// end default

			}

		}

		$this.on("click.jackbox", clicked);
		dataDesc = $this.attr("data-description");

		if(!dataDesc) return;

		// if the same description is used for more than one item we only hit the DOM once for it
		if(descripts) {

			var dIndex = descripts.indexOf(dataDesc);

			if(dIndex === -1) {

				dataDesc = $(dataDesc);

				if(!dataDesc.length) return;
				descripts[descripts.length] = dataDesc;

			}
			else {

				dataDesc = descripts[dIndex];

			}

		}
		else {

			dataDesc = $(dataDesc);
			if(!dataDesc.length) return;

		}

		$this.data("description", dataDesc);

	}

	function browserChange(event) {

		if(initialLoad) {

			initialLoad = false;

			var url = doc.URL, splits = url.split("?url=");

			if(splits.length === 2) {

				window.location = splits[0] + "#/" + splits[1];
				return;

			}

		}

		clearTimeout(runTime);
		getHash(event.value);

		if(catOn !== -1) {

			if(firstRun) {

				firstRun = false;
				insideChange();

			}
			else {

				runTime = setTimeout(insideChange, 750);

			}

		}
		else if(isActive) {

			closing();

		}

	}

	function insideChange(event) {

		if(typeof event === "object") {

			clearTimeout(runTime);
			getHash(event.value);

		}

		if(catOn !== -1) {

			loadItem();

		}
		else if(isActive) {

			closing();

		}

	}

	function getHash(val) {

		if(hasThumbs && !fromThumb && !arrowClicked) thumbs[isOn].removeClass("jb-thumb-active");

		if(val !== "/") {

			var ars = val.split("/");

			if(ars.length === 3) {

				isOn = parseInt(ars[2], 10) - 1;

				if(isNaN(isOn)) isOn = 0;
				hash = ars[1];

			}
			else {

				if(isNaN(ars[1])) {

					isOn = 0;
					hash = ars[1];

				}
				else {

					isOn = parseInt(ars[1], 10) - 1;
					hash = "/";

				}

			}

		}
		else {

			hash = "/";
			isOn = 0;

		}

		if(hash !== "/") {

			var i = numCats;

			while(i--) {

				if(cats[i] === hash) {

					catOn = i;
					leg = list[catOn].length;
					legged = leg !== 1;
					break;

				}

			}

		}
		else {

			catOn = -1;

		}

		arrowClicked = false;

	}

	function resized() {

		clearTimeout(resizeThrottle);
		resizeThrottle = setTimeout(sizer, 100);

	}

	function clicked(event) {

		event.stopPropagation();
		event.preventDefault();

		var data = $(this).data();
		runItem(data.cat, data.id, true);

	}

	function runItem(cat, id, first) {

		clearTimeout(runTime);
		if(!first) turnOff();

		if(deepLinking) {

			$.address.value(cats[cat] + "/" + (id + 1));

		}
		else {

			if(hasThumbs && !fromThumb && !arrowClicked) thumbs[isOn].removeClass("jb-thumb-active");

			isOn = id;
			catOn = cat;
			leg = list[catOn].length;
			legged = leg !== 1;
			loadItem();

		}

	}

	function nextItem(event) {

		if(event) {

			event.stopPropagation();
			if(isLoading) return false;

		}

		if(!isActive) return;
		if(hasThumbs && !fromThumb) thumbs[isOn].removeClass("jb-thumb-active");

		((isOn) < list[catOn].length - 1) ? isOn++ : isOn = 0;

		arrowClicked = true;
		runItem(catOn, isOn);

	}

	function previousItem(event) {

		if(event) {

			event.stopPropagation();
			if(isLoading) return false;

		}

		if(!isActive) return;
		if(hasThumbs && !fromThumb) thumbs[isOn].removeClass("jb-thumb-active");

		((isOn) > 0) ? isOn-- : isOn = list[catOn].length - 1;

		arrowClicked = true;
		runItem(catOn, isOn);

	}

	function addTouches() {

		var ar = [cover[0], wrapper[0], holder[0], container[0]], i = 4;

		while(i--) {

			ar[i].addEventListener("touchstart", returnFalse, false);
			ar[i].addEventListener("touchmove", returnFalse, false);
			ar[i].addEventListener("touchend", returnFalse, false);

		}

	}

	function removeTouches() {

		var ar = [cover[0], wrapper[0], holder[0], container[0]], i = 4;

		while(i--) {

			ar[i].removeEventListener("touchstart", returnFalse, false);
			ar[i].removeEventListener("touchmove", returnFalse, false);
			ar[i].removeEventListener("touchend", returnFalse, false);

		}

	}

	function loadItem() {

		currentCat = list[catOn];
		$this = currentCat[isOn];

		if(!$this) return;

		if(touch) addTouches();
		url = $this.attr("href") || $this.attr("data-href");

		if(isActive) {

			isLoading = true;
			killActive();
			loadContent();

		}
		else {

			isActive = true;
			if(!touch) scroller.stop();
			scrollPos = win.scrollTop();

			cover.appendTo(bodies).one("click.jackbox", closer);
			if(!showScroll) parents = cover.parents().each(addOverflow);
			if(keyboard) docs.on("keydown.jackbox_keyboard", checkKeyClose);

			if(!preAdded) {

				preAdded = true;
				paddingWide = parseInt(holder.css("padding-left"), 10) + parseInt(holder.css("padding-right"), 10);
				paddingTall = parseInt(holder.css("padding-top"), 10) + parseInt(holder.css("padding-bottom"), 10);
				panelBuffer = parseInt(panelLeft.css("width"), 10) + 14;

				boxed = paddingTall + boxBuffer;
				thumbBufTall = thumbnailHeight + (thumbnailMargin << 1);
				preBuf = parseInt(preloader.css("margin-top"), 10);
				preThumbBuf = preBuf - (thumbBufTall >> 1);

				holderPadLeft = parseInt(holder.css("padding-left"), 10);
				holderPadTop = parseInt(holder.css("padding-top"), 10);

				fullNormal = $(".jackbox-fullscreen");
				if(!fullNormal.length) fullNormal = null;

				hasFullscreen = !touch && browser !== "safari" && ("webkitRequestFullScreen" in cover[0] || "mozFullScreenEnabled" in doc);

			}

			thumbVis = !thumbsStartHidden && useThumbs && legged ? 0 : holderHeight;
			winWide = win.width();
			winTall = win.height();

			holder.css({

				width: winWide,
				height: winTall,
				marginLeft: -(winWide >> 1) - holderPadLeft,
				marginTop: -(winTall >> 1) - holderPadTop

			});

			Jacked.fadeIn(cover[0], {callback: addScroll});
			timer = setTimeout(loadContent, 250);
			wrapper.on("click.jackbox", preventDefault);

		}

	}

	function addOverflow() {

		$(this).addClass("jackbox-overflow");

	}

	function removeOverflow() {

		$(this).removeClass("jackbox-overflow");

	}

	function addScroll() {

		if(!showScroll) win[0].scrollTo(0, 0);

	}

	function convert(st) {

		return st === "true" || st === true;

	}

	function loadContent() {

		if(hasThumbs) {

			thumbs[isOn].addClass("jb-thumb-active");

			(fromThumb) ? fromThumb = false : checkThumbs(false, true);

		}

		var autoplay = convert($this.attr("data-autoplay") ? $this.attr("data-autoplay") : autoPlayVideo),
		thisDesc = $this.data("description") || null,
		thisTitle = $this.attr("data-title") || "",
		passedLocal;

		upsize = $this.attr("data-scaleUp") === "true";
		descText = thisDesc && typeof thisDesc !== "string" ? thisDesc.html() : false;
		theType = $this.data("type");
		autoplay = convert(autoplay);

		if(thisTitle) {

			unescaped = thisTitle;
			titleText = escape(unescaped);

		}
		else {

			titleText = false;

			if(typeof words !== "undefined") {

				if(words.data("links")) words.data("links").off(".jackbox");
				words.empty();

			}

		}

		if(touch) {

			swipeable = theType === "image";
			if(theType !== "inline" && theType !== "iframe") doc.addEventListener("touchmove", returnFalse, false);

		}

		if(theType !== "image") writeSize();

		if(winWide > 568) {

			preloader.css("margin-top", thumbVis === 0 ? preThumbBuf : preBuf);

		}
		else {

			preloader.css("margin-top", preBuf);

		}

		wrapper.show();
		preloader.addClass("jackbox-spin-preloader");

		switch(theType) {

			case "image":

				isImage = true;
				content = $("<img />").addClass("jackbox-content").one("load.jackbox", loaded).prependTo(container);

				if(touch) {

					content[0].addEventListener("touchstart", returnFalse, false);
					content[0].addEventListener("touchmove", returnFalse, false);
					content[0].addEventListener("touchend", returnFalse, false);

				}

				content.attr("src", url);

			break;

			case "youtube":

				loadFrame(youTubeMarkup.split("{url}").join(url.split("watch?v=")[1]).split("{autoplay}").join(autoplay ? 1 : 0));

			break;

			case "vimeo":

				loadFrame(vimeoMarkup.split("{url}").join(url.substring(url.lastIndexOf("/"))).split("{autoplay}").join(autoplay));

			break;

			case "local":

				var vPoster = fullPath(),
				ffUsesFlash = $this.attr("data-firefoxUsesFlash") === "true" ? "true" : "false",
				flashing = ($this.attr("data-flashHasPriority") ? $this.attr("data-flashHasPriority") : flashVideoFirst.toString());
				passedLocal = flashing === "false" && hasFullscreen && browser !== "firefox";

				if($this.attr("data-poster")) {

					vPoster += $this.attr("data-poster");

				}
				else {

					vPoster = "false";

				}

				loadFrame(

					videoPlayer +
					"?video=" + url +
					"&autoplay=" + autoplay +
					"&flashing=" + flashing +
					"&width=" + oWidth +
					"&height=" + oHeight +
					"&poster=" + vPoster +
					"&firefox=" + ffUsesFlash,

				true);

			break;

			case "audio":

				fullPath();
				loadFrame(audioPlayer + "?audio=" + url + "&title=" + ($this.attr("data-audiotitle") ? $this.attr("data-audiotitle") : titleText) + "&autoplay=" + autoplay);

			break;

			case "swf":

				fullPath();
				loadFrame(swfPlayer + "?swf=" + url + "&width=" + (wid.toString() + "&height=" + high.toString()));

			break;

			case "inline":

				var htm = $(url), con = htm.length ? htm.html() : "";
				content = $("<div />").addClass("jackbox-content jackbox-html").html(con).prependTo(container);
				content.css("width", wid).find("a").on("click", stopProp);

				$this.attr("data-height", content.outerHeight(true));
				writeSize();
				loaded();

			break;

			default:

				loadFrame(url, false, true);

			// end default

		}

		if(!hasFullscreen) return;
		(!passedLocal) ? fullNormal.show() : fullNormal.hide();

	}

	function fullPath() {

		if(url.search("http") !== -1) return;

		var root = doc.URL.split("#")[0];

		if(root[root.length - 1] !== "/") {

			root = root.substring(0, root.lastIndexOf("/") + 1);

		}

		url = root + url;
		return root;

	}

	function turnOn() {

		if(hasThumbs) {

			var i = thumbs.length;
			while(i--) thumbs[i].on("click.jackbox", thumbClick);

		}

		panelLeft.on("click.jackbox", previousItem);
		panelRight.on("click.jackbox", nextItem);

		if(keyboard) docs.on("keydown.jackbox", checkKey);
		if(touch && swipeable) content.cjSwipe("touchSwipe", catchSwipe);

	}

	function turnOff() {

		if(hasThumbs) {

			var i = thumbs.length;
			while(i--) thumbs[i].off("click.jackbox");

		}

		panelLeft.off(".jackbox");
		panelRight.off(".jackbox");

		if(keyboard) docs.off("keydown.jackbox");
		if(touch && swipeable) content.cjSwipe("unbindSwipe");

	}

	function checkKey(event) {

		switch(event.keyCode) {

			case 39:

				nextItem();

			break;

			case 37:

				previousItem();

			break;

			case 40:

				toggleThumbs();

			break;

			case 38:

				toggleThumbs();

			break;

		}

	}

	function checkKeyClose(event) {

		if(event.keyCode === 27) closer(event);

	}

	function infoIndex() {

		description.css("visibility", "hidden");

	}

	function ripThumbs() {

		var frag = doc.createDocumentFragment(), halfHeight = thumbnailHeight >> 1;

		thumbHolder = $("<div />").addClass("jackbox-thumb-holder").css("height", thumbnailHeight).appendTo(cover);
		thumbPanel = $("<div />").addClass("jackbox-thumb-panel").css("height", thumbnailHeight);
		thumbRight = $("<div />").addClass("jackbox-thumb-right");
		thumbLeft = $("<div />").addClass("jackbox-thumb-left");

		frag.appendChild(thumbPanel[0]);
		frag.appendChild(thumbRight[0]);
		frag.appendChild(thumbLeft[0]);

		thumbPanel[0].cjThumbs = true;
		thumbHolder[0].appendChild(frag);
		thumbStrip = $("<div />").addClass("jackbox-thumb-strip").appendTo(thumbPanel);

		thumbLeft.css("top", halfHeight);
		thumbRight.css("top", halfHeight);

	}

	function loadThumbs() {

		var cur = list[catOn],
		ar = [],
		i = leg,
		titles,
		holds,
		$this,
		frag,
		imgs,
		img,
		ww,
		hh,
		pc;

		thumbsChecked = true;

		while(i--) {

			$this = cur[i];
			if($this.attr("data-thumbnail")) {

				ar[i] = false;
				continue;

			}

			imgs = $this.children("img");
			if(imgs.length) {

				$this.attr("data-thumbnail", imgs.attr("src"));
				ar[i] = imgs;

			}
			else if($this.data("type") === "image") {

				$this.attr("data-thumbnail", $this.attr("href") || $this.attr("data-href"));
				ar[i] = false;

			}
			else {

				$this.attr("data-thumbnail", defaultThumb);
				ar[i] = false;

			}

		}

		thumbs = [];
		if(!thumbHolder) ripThumbs();
		frag = doc.createDocumentFragment();

		for(i = 0; i < leg; i++) {

			holds = thumbs[i] = $("<div />").data("id", i).addClass("jackbox-thumb").css({

				width: thumbnailWidth,
				height: thumbnailHeight,
				left: thmWidth * i

			}).on("click.jackbox", thumbClick);

			if(useTips) {

				titles = currentCat[i].attr("data-thumbTooltip") || currentCat[i].attr("data-title");
				if(titles) holds.data("theTitle", titles).on("mouseenter.jackbox", overThumb).on("mouseleave.jackbox", outThumb);

			}

			frag.appendChild(holds[0]);
			img = $("<img />").addClass("jb-thumb").one("load.jackbox", thumbLoaded).appendTo(holds);
			holds.data("theThumb", img);

			if(ar[i]) {

				ww = ar[i].attr("width") || ar[i].width();
				hh = ar[i].attr("height") || ar[i].height();

			}
			else {

				ww = thumbnailWidth;
				hh = thumbnailHeight;

			}

			if(ww > thumbnailWidth && hh > thumbnailHeight) {

				pc = ww > hh ? thumbnailWidth / ww : thumbnailHeight / hh;

				ww *= pc;
				hh *= pc;

				if(hh < thumbnailHeight) {

					var dif = (thumbnailHeight - hh) / thumbnailHeight;

					ww += ww * dif;
					hh += hh * dif;

				}

				if(ww < thumbnailWidth) {

					var difs = (thumbnailWidth - ww) / thumbnailWidth;

					ww += ww * difs;
					hh += hh * difs;

				}

				if(ww !== (ww | 0)) ww = (ww + 1) | 0;
				if(hh !== (hh | 0)) hh = (hh + 1) | 0;

			}

			img.attr({width: ww, height: hh, src: cur[i].attr("data-thumbnail")});

		}

		thumbStrip[0].appendChild(frag);
		totalThumbs = thumbs.length;
		stripWidth = thmWidth * i;
		hasThumbs = true;

		thumbOn = 0;
		thumbHolder.on("click.jackbox", preventDefault).show();
		posThumbs();

		if(!showHide) return;
		if(!thumbsStartHidden) {

			showThumbs.hide();
			hideThumbs.show();
			thumbHolder.css("bottom", 0);

		}
		else {

			hideThumbs.hide();
			showThumbs.show();
			thumbHolder.css("bottom", thumbVis);

		}

		showHide.on("click.jackbox", toggleThumbs);

	}

	function thumbEnter() {

		thumbPanel.data("offLeft", thumbPanel.offset().left);

	}

	function outThumb() {

		thumbTip.css({opacity: 0, visibility: "hidden"});

	}

	function thumbArrowNext(event) {

		if(typeof event === "object") event.stopPropagation();

		if(thumbOn < leg - numThumbs) {

			thumbOn++;
			checkArrows(false, true);

		}

	}

	function thumbArrowPrev(event) {

		if(typeof event === "object") event.stopPropagation();

		if(thumbOn > 0) {

			thumbOn--;
			checkArrows(false, true);

		}

	}

	function thumbLoaded(event) {

		event.stopPropagation();

		var $this = $(this).parent();
		$this.addClass("jb-thumb-fadein");

		if(!touch) $this.addClass("jb-thumb-hover");
		if($this.data("id") === isOn) $this.addClass("jb-thumb-active");

	}

	function thumbClick(event) {

		event.stopPropagation();
		if(isLoading) return false;

		var $this = $(this), id = $this.data("id");

		if(id === isOn) return;
		if(hasThumbs) thumbs[isOn].removeClass("jb-thumb-active");

		isOn = id;
		fromThumb = true;
		runItem(catOn, isOn);

	}

	function addEvents() {

		eventsAdded = true;

		if(hasFullscreen) fullNormal.on("click.jackbox", toggleFull);
		if(closeBtn) closeBtn.one("click.jackbox", closer);
		if(info) info.on("click.jackbox", toggleInfo);

		if(!legged) return;
		if(goRight) goRight.on("click.jackbox", nextItem);
		if(goLeft) goLeft.on("click.jackbox", previousItem);
		if(useTips && thumbPanel) thumbPanel.on("mouseenter.jackbox", thumbEnter);

		if(touch && description) {

			description[0].addEventListener("touchstart", stopProp, false);
			description[0].addEventListener("touchmove", stopProp, false);
			description[0].addEventListener("touchend", stopProp, false);

		}

	}

	function toggleFull() {

		if(!isFullscreen) {

			win.off(".jackbox");
			isFullscreen = true;

			if(doc.mozFullScreenEnabled) {

				doc.addEventListener("mozfullscreenchange", fsChange, false);
				cover[0].mozRequestFullScreen();

			}
			else if(cover[0].webkitRequestFullScreen) {

				doc.addEventListener("webkitfullscreenchange", fsChange, false);
				cover[0].webkitRequestFullScreen();

			}

		}
		else {

			exitFull();

		}

	}

	function fsChange() {

		if(doc.webkitIsFullScreen || doc.mozFullScreen) {

			sizer();

		}
		else {

			exitFull(true);

		}

	}

	function nativeExit(event) {

		doc.removeEventListener(event.type, nativeExit, false);

		sizer();

		win.on("resize.jackbox", resized);

	}

	function exitFull(fromNative) {

		isFullscreen = false;

		if(doc.mozFullScreenEnabled) {

			doc.removeEventListener("mozfullscreenchange", fsChange, false);

			if(fromNative) {

				sizer();
				win.on("resize.jackbox", resized);

			}
			else {

				doc.addEventListener("mozfullscreenchange", nativeExit, false);
				doc.mozCancelFullScreen();

			}

		}
		else if(cover[0].webkitRequestFullScreen) {

			doc.removeEventListener("webkitfullscreenchange", fsChange, false);

			if(fromNative) {

				sizer();
				win.on("resize.jackbox", resized);

			}
			else {

				doc.addEventListener("webkitfullscreenchange", nativeExit, false);
				doc.webkitCancelFullScreen();

			}

		}

	}

	function writeSize() {

		isImage = false;

		oWidth = $this.attr("data-width") ? parseInt($this.attr("data-width"), 10) : defaultVideoWidth;
		oHeight = $this.attr("data-height") ? parseInt($this.attr("data-height"), 10) : defaultVideoHeight;
		upsize = $this.attr("data-scaleUp") === "true";

		setSize();

	}

	function setSize() {

		bufferW = oWidth + paddingWide + panelBuffer + boxBuffer;
		bufferH = oHeight + boxed;

		sizer("true");

	}

	function killActive() {

		clearTimeout(timer);
		Jacked.stopTween(holder[0]);

		win.off(".jackbox");
		if(touch) doc.removeEventListener("touchmove", returnFalse, false);

		if(content) {

			Jacked.stopTween(content[0]);
			content.remove();
			content = null;

		}

		if(topContent) {

			Jacked.stopTween(topContent[0], true);
			topContent.hide();

		}

		if(bottomContent) {

			Jacked.stopTween(bottomContent[0], true);
			bottomContent.hide();

		}

		if(!info) return;

		info.removeClass("jb-info-inactive");
		Jacked.stopTween(descHolder[0]);
		descHolder.empty().hide();

	}

	function closer(event) {

		event.stopPropagation();

		(deepLinking) ? $.address.value("") : closing();

	}

	function killThumbs() {

		Jacked.stopTween(thumbHolder[0]);
		thumbHolder.off(".jackbox").hide();

		var thumber;

		while(thumbs.length) {

			thumber = thumbs[0];
			Jacked.stopTween(thumber[0]);

			thumber.remove();
			thumbs.shift();

		}

		thumbLeft.off(".jackbox").hide();
		thumbRight.off(".jackbox").hide();

		if(touch) thumbPanel.cjSwipe("unbindSwipe");

		Jacked.stopTween(thumbStrip[0]);
		thumbStrip.empty().css("margin-left", 0);

		if(showHide) {

			showHide.off(".jackbox");

			if(showHide) {

				showThumbs.hide();
				hideThumbs.show();

			}

		}

		hasThumbs = thumbs = null;

	}

	function closing() {

		clearTimeout(runTime);

		killActive();
		cover.unbind(".jackbox");
		if(keyboard) docs.off("keydown.jackbox_keyboard");

		if(legged) {

			if(keyboard) docs.off("keydown.jackbox");
			if(goLeft) goLeft.off(".jackbox");
			if(goRight) goRight.off(".jackbox");
			if(useTips && thumbPanel) thumbPanel.off(".jackbox");

			Jacked.stopTween(panelRight[0], true);
			Jacked.stopTween(panelLeft[0], true);

			var style = {opacity: 0, visibility: "hidden"};
			panelRight.off(".jackbox").css(style);
			panelLeft.off(".jackbox").css(style);

		}
		else {

			if(controls) controls.show();
			if(showHide) showHide.show();

		}

		wrapper.hide().off(".jackbox");
		preloader.removeClass("jackbox-spin-preloader");

		if(typeof words !== "undefined") {

			if(words.data("links")) words.data("links").off(".jackbox");
			words.empty();

		}

		if(hasFullscreen) fullNormal.off(".jackbox");
		if(closeBtn) closeBtn.unbind(".jackbox");
		if(info) info.off(".jackbox");
		if(hasThumbs) killThumbs();

		if(!showScroll) parents.each(removeOverflow);
		Jacked.fadeOut(cover[0], {duration: 1000, callback: onFaded});
		holder.css({marginLeft: holOrigLeft, marginTop: holOrigTop});

		if(touch) {

			removeTouches();
			doc.removeEventListener("touchmove", returnFalse, false);

		}

		setTimeout(scrollback, 10);

		if(description) {

			description.css("visibility", "hidden");

			if(touch) {

				description[0].removeEventListener("touchstart", stopProp, false);
				description[0].removeEventListener("touchmove", stopProp, false);
				description[0].removeEventListener("touchend", stopProp, false);

			}

		}

		$this = isActive = isFullscreen = fromThumb = firstCheck = eventsAdded = arrowClicked = thumbsChecked = oldWidth = null;

	}

	function scrollback() {

		if(scrollPos !== 0) {

			if(!showScroll && !touch) {

				scroller.animate({scrollTop: scrollPos}, {duration: 300, queue: false});

			}
			else {

				scroller.scrollTop(scrollPos);

			}

		}

	}

	function onFaded() {

		cover.detach();

	}

	function catchSwipe(left) {

		(!left) ? nextItem() : previousItem();

	}

	function returnFalse(event) {

		event.preventDefault();

	}

	function createElements() {

		elements = true;
		total = $(".jb-total");
		info = $(".jackbox-info");
		divider = $(".jb-divider");
		current = $(".jb-current");
		closeBtn = $(".jackbox-close");
		title = $(".jackbox-title-text");
		words = $(".jackbox-title-txt");
		controls = $(".jackbox-controls");
		goLeft = $(".jackbox-arrow-left");
		goRight = $(".jackbox-arrow-right");
		showHide = $(".jackbox-button-thumbs");
		showThumbs = $(".jackbox-show-thumbs");
		hideThumbs = $(".jackbox-hide-thumbs");

		if(!words.length) words = null;
		if(!title.length) title = null;
		if(!goLeft.length) goLeft = null;
		if(!divider.length) divider = null;
		if(!goRight.length) goRight = null;
		if(!controls.length) controls = null;
		if(!closeBtn.length) closeBtn = null;
		if(!current.length || !total.length) current = null;

		if(hasFullscreen) {

			$(".jackbox-ns").hide();
			if(!fullNormal.length) fullNormal = hasFullscreen = null;

		}
		else if(fullNormal) {

			fullNormal.hide();

		}

		if(useThumbs) {

			if(showHide.length && showThumbs.length && hideThumbs.length) {

				showThumbs.hide();

			}
			else {

				showHide = showThumbs = hideThumbs = null;

			}

		}
		else {

			showHide.hide();
			showHide = showThumbs = hideThumbs = null;

		}

		if(info.length) {

			description = $("<div />").addClass("jackbox-info-text").appendTo(container).css("visibility", "hidden");
			descHolder = $("<div />").addClass("jackbox-description-text").appendTo(description);

		}
		else {

			info = null;

		}

		if(!useTips) return;

		thumbTip = $("<span />").addClass("jackbox-thumb-tip").css("bottom", thumbnailHeight);
		thumbTipText = $("<span />").addClass("jackbox-thumb-tip-text").text("render me").appendTo(thumbTip);

		thumbTip.appendTo(cover);
		thumbTipBuf = (parseInt(thumbTip.css("padding-left"), 10) << 1) - (thumbnailMargin << 1);

	}

	function jsonLoaded(data, response) {

    	if(isActive || response.toLowerCase() !== "success" || !data) return;

		var i = data.length, base = document.URL;
		base = base.substring(0, base.lastIndexOf("/"));

		while(i--) {

			$("<img />").attr("src", baseName + "/" + data[i].split("../").join(""));

		}

	}

	function getVimeoThumb($video, url) {

		$.getJSON("http://vimeo.com/api/v2/video/" + url.split("http://vimeo.com/")[1] + ".json?callback=?", {format: "json"}, function(data) {

			$video.attr("data-thumbnail", data[0].thumbnail_small);

		});

	}

	function drawBlur() {

		var $this = $(this), img = $this.next("img"), newImg, href = img.attr("src");

		if(!img.length) return;

		newImg = $("<img />").attr({

			width: img.attr("width"),
			height: img.attr("height")

		}).data("parent", $this).one("load.jackbox", blurThumbLoaded).insertAfter(img);

		img.remove();
		newImg.attr("src", href);

	}

	function blurThumbLoaded() {

		var img = $(this),
		$this = img.data("parent"),

		width = parseInt($this.css("width"), 10) || $this.width(),
		height = parseInt($this.css("height"), 10) || $this.height(),

		canvas = $("<canvas />").addClass("jackbox-canvas-blur").attr({

			width: width,
			height: height

		}).insertBefore($this),

		now = Date.now(),
		imgId = now + 1,
		canvasId = now + 2;

		img.attr("id", imgId);
		canvas.attr("id", canvasId);
		StackBlurImage(imgId, canvasId, 29);

	}

	function addTip() {

		var $this = $(this);

		$this.parent().data({

			tip: $this,
			tipWidth: $this.width() - 27,
			tipHeight: $this.height() + 17

		}).on("mouseenter.jackbox", overTip).on("mouseleave.jackbox", outTip);

	}

	function overTip() {

		var $this = $(this), off = $this.offset(), data = $this.data();

		data.tipX = off.left,
		data.tipY = off.top,
		data.tip.css({opacity: 1, visibility: "visible"});

		$this.on("mousemove.jackbox", moveTip);

	}

	function outTip() {

		var $this = $(this).off("mousemove.jackbox");

		if(!isIE8) {

			$this.data("tip").css({opacity: 0, visibility: "hidden"});

		}
		else {

			$this.data("tip").css("opacity", 0);

		}

	}

	function checkImage(st) {

		return st === "jpg" || st === "png" || st === "jpeg" || st === "gif";

	}

	function checkVideo(st, popped) {

		if(st.search("youtube.com") !== -1) {

			return "youtube";

		}
		else if(st.search("vimeo.com") !== -1) {

			return "vimeo";

		}
		else if(popped === "mp4") {

			return "local";

		}
		else {

			return false;

		}

	}

	function stopProp(event) {

		event.stopImmediatePropagation();

	}

	function preventDefault(event) {

		if(!$(event.target).is("a")) {

			event.stopPropagation();
			event.preventDefault();

		}

	}


})(jQuery);

function jackboxFrameReady() {

	jQuery.fn.jackBox("frameReady");

}

























// © CodingJack www.codingjack.com
// License: http://creativecommons.org/licenses/by-sa/3.0/
// www.codingjack.com/playground/jacked/
// 16kb minified: www.codingjack.com/playground/jacked/js/codingjack/Jacked.min.js

;(function() {

	var compute = window.getComputedStyle ? document.defaultView.getComputedStyle : null,
	request = timeline("Request", "AnimationFrame"),
	cancel = timeline("Cancel", "AnimationFrame"),
	temp = document.createElement("span").style,
	agent = navigator.userAgent.toLowerCase(),
	defaultEase = "Quint.easeOut",
	defaultDuration = 500,
	speeds = getSpeed(),
	dictionary = [],
	css = getCSS(),
	engineRunning,
	transformProp,
	length = 0,
	skeleton,
	element,
	browser,
	useCSS,
	moved,
	timer,
	trans,
	run,
	leg,
	rip,
	itm,
	clrs,
	mobile,
	gotcha,
	colors,
	borColor,
	comma = /,/g,
	reg = /[A-Z]/g,
	regT = / cj-tween/g,
	trim = /^\s+|\s+$/g,
	regP = new RegExp("{props}"),
	regE = new RegExp("{easing}"),
	regD = new RegExp("{duration}"),

	positions = /(right|bottom|center)/,

	// credit: http://www.bitstorm.org/jquery/color-animation/
	color2 = /#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])/,
	color1 = /#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/,

	// true = use CSS3 above all else when available, false = use requestAnimationFrame with Timer fallback
	// combining browsers + mobile devices is not currently supported (i.e. all Android browsers will be passed the "android" parameter)
	// Microsoft added for the future, will fallback to request/timer for now
	defaults = {ios: false, android: false, winMobile: false, firefox: false, chrome: false, safari: false, opera: false, ie: false},

	// set timer speed and check for IE
	intervalSpeed = speeds[0],
	version = speeds[1],
	isIE = version !== 0 && version < 9;

	if(!request || !cancel) request = cancel = null;

	// if css3 transitions are supported
	if(css) {

		var pre = css[1], sheet = document.createElement("style");
		transformProp = getTransform();
		mobile = getMobile();

		sheet.type = "text/css";
		sheet.innerHTML = ".cj-tween{" + pre + "-property:none !important;}";
		document.getElementsByTagName("head")[0].appendChild(sheet);

		skeleton = pre + "-property:{props};" + pre + "-duration:{duration}s;" + pre + "-timing-function:cubic-bezier({easing});";
		browser = !mobile ? css[2] : mobile;
		borColor = /(chrome|opera)/.test(browser);
		css = css[0];

		setDefaults();

	}

	if(!isIE) {

		element = HTMLElement;

		clrs = /(#|rgb)/;
		gotcha = /(auto|inherit|rgb|%|#)/;

	}
	// IE8
	else if(version === 8) {

		element = Element;

		// support for commonly named colors in IE8
		clrs = /(#|rgb|red|blue|green|black|white|yellow|pink|gray|grey|orange|purple)/;
		gotcha = /(auto|inherit|rgb|%|#|red|blue|green|black|white|yellow|pink|gray|grey|orange|purple)/;
		colors = {

			red: "#F00",
			blue: "#00F",
			green: "#0F0",
			black: "#000",
			white: "#FFF",
			yellow: "#FF0",
			pink: "#FFC0CB",
			gray: "#808080",
			grey: "#808080",
			orange: "#FFA500",
			purple: "#800080"

		};

	}
	// Bounce for < IE8
	else {

		return;

	}

	// extend Array if necessary
	if(!Array.prototype.indexOf) {

		Array.prototype.indexOf = function($this) {

			var i = this.length;

			while(i--) {

				if(this[i] === $this) return i;

			}

			return -1;

		};

	}

	// credit https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Date/now
	if(!Date.now) {

		Date.now = function now() {

			return +(new Date);

		};

	}

	// static methods
	this.Jacked = {

		ready: function(callback) {

			window.onload = callback;

		},

		setEngines: function(settings) {

			for(var prop in settings) {

				if(defaults.hasOwnProperty(prop)) defaults[prop] = settings[prop];

			}

			setDefaults();

		},

		tween: function(obj, to, settings) {

			if(obj.cj) obj.cj.stop();
			if(!settings) settings = {};

			if(!settings.mode) {

				if(!css || !useCSS) {

					new CJ(obj, to, settings);

				}
				else {

					new CJcss(obj, to, settings);

				}

			}
			else if(settings.mode === "timeline" || !css) {

				new CJ(obj, to, settings);

			}
			else {

				new CJcss(obj, to, settings);

			}

		},

		fadeIn: function(obj, settings) {

			if(!settings) settings = {};
			settings.fadeIn = true;

			Jacked.tween(obj, {opacity: 1}, settings);

		},

		fadeOut: function(obj, settings) {

			if(!settings) settings = {};
			settings.fadeOut = true;

			Jacked.tween(obj, {opacity: 0}, settings);

		},

		percentage: function(obj, to, settings) {

			if(obj.cj) obj.cj.stop();
			if(!("from" in to) || !("to" in to)) return;
			if(!settings) settings = {};

			var mode = settings.mode;

			if(!mode) {

				if(css && useCSS) {

					percCSS(obj, to, settings);

				}
				else {

					new CJpercentage(obj, to, settings);

				}

				return;

			}

			if(mode === "css3" && css) {

				percCSS(obj, to, settings);
				return;

			}

			new CJpercentage(obj, to, settings);

		},

		special: function(obj, settings) {

			if(obj.cj) obj.cj.stop();

			new CJspecial(obj, settings);

		},

		transform: function(obj, to, settings, fallback) {

			if(obj.cj) obj.cj.stop();

			if(css && transformProp) {

				if(!settings) settings = {};
				settings.mode = "css3";

				if("transform" in to) {

					to[transformProp] = to.transform;
					delete to.transform;

				}

				new Jacked.tween(obj, to, settings);

			}
			else if(fallback) {

				new Jacked.tween(obj, fallback, settings);

			}

		},

		stopTween: function(obj, complete, callback) {

			var itm = obj.cj;
			if(!itm) return;

			if(!itm.isCSS) {

				itm.stop(complete, callback);

			}
			else {

				itm.stop(callback);

			}

		},

		stopAll: function(complete) {

			(cancel) ? cancel(engine) : clearInterval(timer);

			var i = dictionary.length, itm;
			length = 0;

			while(i--) {

				itm = dictionary[i];

				if(!itm.isCSS) {

					itm.stop(complete, false, true, true);

				}
				else {

					itm.stop(false, true);

				}

			}

			dictionary = [];
			engineRunning = false;
			itm = trans = null;

		},

		setEase: function(easing) {

			var ar = easing.toLowerCase().split(".");

			if(ar.length < 2) return;
			if(!PennerEasing[ar[0]]) return;
			if(!PennerEasing[ar[0]][ar[1]]) return;

			defaultEase = easing;

		},

		setDuration: function(num) {

			if(isNaN(num)) return;

			defaultDuration = num;

		},

		getMobile: function() {

			return mobile;

		},

		getIE: function() {

			return isIE;

		},

		getBrowser: function() {

			return browser;

		},

		getTransition: function() {

			return css;

		},

		getEngine: function() {

			return engineRunning;

		},

		getTransform: function() {

			return transformProp;

		}

	};

	// ticker used for JS animations
	function engine() {

		run = false;
		leg = length;

		while(leg--) {

			itm = dictionary[leg];

			if(!itm) break;
			if(itm.isCSS) continue;

			if(itm.cycle()) {

				run = true;

			}
			else {

				itm.stop(false, itm.complete, false, true);

			}

		}

		if(request) {

			if(run) {

				request(engine);

			}
			else {

				cancel(engine);
				itm = trans = null;

			}

		}
		else {

			if(run) {

				if(!engineRunning) timer = setInterval(engine, intervalSpeed);

			}
			else {

				clearInterval(timer);
				itm = trans = null;

			}

		}

		engineRunning = run;

	}

	// default JS transition
	this.CJ = function(obj, to, sets) {

		length = dictionary.length;

		var $this = obj.cj = dictionary[length++] = this;

		this.runner = function(force) {

			$this.obj = obj;
			$this.complete = sets.callback;
			$this.completeParams = sets.callbackParams;

			if(force === true) {

				$this.transitions = [];
				return;

			}

			var key,
			i = 0,
			tweens = [],
			style = obj.style,
			duration = sets.duration || defaultDuration,
			easing = (sets.ease || defaultEase).toLowerCase().split(".");
			easing = PennerEasing[easing[0]][easing[1]];

			style.visibility = "visible";

			if(sets.fadeIn) {

				style.display = sets.display || "block";
				style.opacity = 0;

			}

			if(isIE && "opacity" in to) {

				style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=" + (sets.fadeIn ? 0 : 100) + ")";

			}

			if(to.borderColor && !borColor) {

				var clr = to.borderColor;

				to.borderTopColor = clr;
				to.borderRightColor = clr;
				to.borderBottomColor = clr;
				to.borderLeftColor = clr;

				delete to.borderColor;

			}

			for(key in to) {

				if(!to.hasOwnProperty(key)) continue;

				if(key !== "backgroundPosition") {

					tweens[i++] = $this.animate(obj, key, to[key], duration, easing);

				}
				else {

					tweens[i++] = $this.bgPosition(obj, key, to[key], duration, easing);

				}

			}

			$this.transitions = tweens;
			(engineRunning) ? setTimeout(checkEngine, 10) : engine();

		};

		if(sets.fadeOut) {

			obj.cjFadeOut = true;

		}
		else if(sets.fadeIn) {

			obj.cjFadeIn = true;

		}

		if(sets.duration === 0) {

			this.runner(true);
			this.stop();
			return;

		}

		if(!sets.delay) {

			this.runner();

		}
		else {

			this.delayed = setTimeout(this.runner, sets.delay);

		}

	};

	// cycles through all JS animations every frame/interval
	CJ.prototype.cycle = function() {

		trans = this.transitions;
		if(!trans) return true;

		rip = trans.length;
		moved = false;

		while(rip--) {

			if(trans[rip]()) moved = true;

		}

		return moved;

	};

	// each JS animation runs through this function
	CJ.prototype.animate = function(obj, prop, value, duration, ease) {

		var tick, style, val, opacity = prop === "opacity", passed = true;

		if(!opacity || !isIE) {

			style = obj.style;
			val = style[prop];

			tick = (val !== "") ? val : compute ? compute(obj, null)[prop] : obj.currentStyle[prop];

		}
		else {

			style = obj.filters.item("DXImageTransform.Microsoft.Alpha");
			prop = "Opacity";
			tick = style[prop];
			value *= 100;

		}

		if(!gotcha.test(tick)) {

			tick = parseFloat(tick);

		}
		else {

			if(!clrs.test(tick)) {

				tick = 0;

			}
			else {

				if(value.search("rgb") === -1) {

					if(isIE && tick in colors) tick = colors[tick];
					return this.color(obj, prop, tick, value, duration, ease);

				}
				else {

					passed = false;

				}

			}

		}

		var px = !opacity ? "px" : 0,
		constant = value - tick,
		range = tick < value,
		then = Date.now(),
		begin = tick,
		timed = 0,
		finish,
		pTick,
		now;

		finish = value + px;

		if(!opacity || isIE) {

			(range) ? value -= 0.25 : value += 0.25;

		}
		else {

			(range) ? value -= 0.025 : value += 0.025;

		}

		function trans() {

			now = Date.now();
			timed += now - then;
			tick = ease(timed, begin, constant, duration);
			then = now;

			if(!opacity || isIE) {

				tick = range ? (tick + 0.5) | 0 : (tick - 0.5) | 0;

			}
			else {

				tick = tick.toFixed(2);

			}

			if(tick === pTick) return true;

			if(range) {

				if(tick >= value) {

					style[prop] = finish;
					return false;

				}

			}
			else {

				if(tick <= value) {

					style[prop] = finish;
					return false;

				}

			}

			pTick = tick;
			style[prop] = tick + px;

			return true;

		}

		function cancelled() {

			return false;

		}

		if(passed) {

			trans.stored = [prop, finish];
			return trans;

		}
		else {

			cancelled.stored = [prop, finish];
			return cancelled;

		}


	};


	// color transitions
	CJ.prototype.color = function(obj, prop, tick, value, duration, ease) {

		var pound = value.search("#") !== -1 ? "" : "#",
		finish = pound + value,
		then = Date.now(),
		style = obj.style,
		passed = false,
		starts = [],
		ends = [],
		timed = 0,
		i = -1,
		now,
		clr,
		st;

		if(tick.search("rgb") !== -1) {

			i = -1;
			starts = tick.split("(")[1].split(")")[0].split(",");
			while(++i < 3) starts[i] = parseInt(starts[i], 10);

		}
		else {

			starts = getColor(tick);

		}

		ends = getColor(value);
		i = -1;

		while(++i < 3) {

			if(starts[i] !== ends[i]) passed = true;

		}

		function trans() {

			now = Date.now();
			timed += now - then;
			then = now;

			tick = ease(timed, 0, 1, duration);

			if(tick < 0.99) {

				i = -1;
				st = "rgb(";

				while(++i < 3) {

					clr = starts[i];
					st += (clr + tick * (ends[i] - clr)) | 0;
					if(i < 2) st += ",";

				}

				style[prop] = st + ")";
				return true;

			}
			else {

				style[prop] = finish;
				return false;

			}

		}

		function cancelled() {

			return false;

		}

		if(passed) {

			trans.stored = [prop, finish];
			return trans;

		}
		else {

			cancelled.stored = [prop, finish];
			return cancelled;

		}

	};


	// animates bgPosition
	CJ.prototype.bgPosition = function(obj, prop, value, duration, ease) {

		var style = obj.style,
		val = style[prop],
		then = Date.now(),
		passed = true,
		ie = isIE,
		timed = 0,
		finalX,
		finalY,
		finish,
		prevX,
		prevY,
		hasX,
		hasY,
		difX,
		difY,
		tick,
		now,
		xx,
		yy,
		x,
		y;

		if(!ie) {

			tick = (val !== "") ? val.split(" ") : compute(obj, null).backgroundPosition.split(" ");

			x = tick[0];
			y = tick[1];

		}
		else {

			x = obj.currentStyle.backgroundPositionX;
			y = obj.currentStyle.backgroundPositionY;

			if(positions.test(x) || positions.test(y)) passed = false;

			if(x === "left") x = 0;
			if(y === "top") y = 0;

		}

		if(x.search("%") !== -1) {

			if(x !== "0%") passed = false;

		}

		if(y.search("%") !== -1) {

			if(y !== "0%") passed = false;

		}

		x = parseInt(x, 10);
		y = parseInt(y, 10);

		if(value.hasOwnProperty("x")) {

			xx = value.x;
			hasX = true;

		}
		else {

			xx = x;
			hasX = false;

		}

		if(value.hasOwnProperty("y")) {

			yy = value.y;
			hasY = true;

		}
		else {

			yy = y;
			hasY = false;

		}

		hasX = hasX && x !== xx;
		hasY = hasY && y !== yy;
		if(!hasX && !hasY) passed = false;

		difX = xx - x;
		difY = yy - y;
		finalX = xx + "px";
		finalY = yy + "px";
		finish = !ie ? finalX + " " + finalY : [finalX, finalY];

		function trans() {

			now = Date.now();
			timed += now - then;
			then = now;

			tick = ease(timed, 0, 1, duration);

			if(tick < 0.99) {

				if(hasX) {

					xx = ((x + (difX * tick)) + 0.5) | 0;

				}

				if(hasY) {

					yy = ((y + (difY * tick)) + 0.5) | 0;

				}

				if(xx === prevX && yy === prevY) return true;

				prevX = xx;
				prevY = yy;

				if(!ie) {

					style.backgroundPosition = xx + "px" + " " + yy + "px";

				}
				else {

					style.backgroundPositionX = xx + "px";
					style.backgroundPositionY = yy + "px";

				}

				return true;

			}
			else {

				if(!ie) {

					style[prop] = finish;

				}
				else {

					style.backgroundPositionX = finalX;
					style.backgroundPositionY = finalY;

				}

				return false;

			}

		}

		function cancelled() {

			return false;

		}

		if(passed) {

			trans.stored = [prop, finish];
			return trans;

		}
		else {

			cancelled.stored = [prop, finish];
			return cancelled;

		}

	};

	// stops JS animations
	CJ.prototype.stop = function(complete, callback, popped) {

		var element = this.obj;

		if(!element) {

			clearTimeout(this.delayed);

			this.runner(true);
			this.stop(complete, callback);

			return;

		}

		delete element.cj;

		if(complete) {

			var group = this.transitions, i = group.length, ar, prop;

			while(i--) {

				ar = group[i].stored;
				prop = ar[0];

				if(!isIE) {

					element.style[prop] = ar[1];

				}
				else {

					switch(prop) {

						case "Opacity":

							element.filters.item("DXImageTransform.Microsoft.Alpha").Opacity = ar[1] * 100;

						break;

						case "backgroundPosition":

							var style = element.style;
							style.backgroundPositionX = ar[1][0];
							style.backgroundPositionY = ar[1][1];

						break;

						default:

							element.style[prop] = ar[1];

						// end default

					}

				}

			}

		}

		checkElement(element);
		if(callback) callback = this.complete;
		if(!popped) popTween(this, element, callback, this.completeParams);

	};


	// CSS3 Transitions
	this.CJcss = function(obj, to, sets) {

		length = dictionary.length;

		var $this = obj.cj = dictionary[length++] = this,
		style = obj.style, transform = transformProp in to;

		this.isCSS = true;
		this.storage = obj;
		this.complete = sets.callback;
		this.completeParams = sets.callbackParams;

		this.runner = function() {

			if(!sets.cssStep) {

				$this.step();

			}
			else {

				style.visibility = "visible";
				$this.stepped = setTimeout($this.step, 30);

			}

		};

		this.step = function(added) {

			$this.obj = obj;

			if(added === true) {

				$this.moves = "";
				return;

			}

			var j,
			key,
			str,
			cur,
			orig,
			bgPos,
			i = 0,
			total,
			finder,
			moving,
			replaced,
			values = [],
			tweens = [],
			current = obj.getAttribute("style") || "",
			duration = sets.duration || defaultDuration,
			easing = (sets.ease || defaultEase).toLowerCase().split(".");

			for(key in to) {

				if(!to.hasOwnProperty(key)) continue;

				str = key;
				finder = str.match(reg);

				if(finder) {

					j = finder.length;

					while(j--) {

						cur = finder[j];
						str = str.replace(new RegExp(cur, "g"), "-" + cur.toLowerCase());

					}

				}

				cur = orig = to[key];
				bgPos = key === "backgroundPosition";

				if(!gotcha.test(cur) && key !== "opacity" && !bgPos && !transform) {

					cur += "px;";

				}
				else if(!bgPos) {

					cur += ";";

				}
				else {

					var x = orig.x, y = orig.y, isX = isNaN(x), isY = isNaN(y);

					if(!isX && !isY) {

						x += "px";
						y += "px";

					}
					else {

						var val = style.backgroundPosition,
						tick = (val !== "") ? val.split(" ") : compute(obj, null).backgroundPosition.split(" ");

						(!isX) ? x += "px" : x = tick[0];
						(!isY) ? y += "px" : y = tick[1];

					}

					cur = x + " " + y + ";";

				}

				values[i] = str + ":" + cur;
				tweens[i++] = str;

				if(!current) continue;
				finder = current.search(str);

				if(finder !== -1) {

					total = current.length - 1;
					j = finder - 1;

					while(++j < total) {

						if(current[j] === ";") break;

					}

					current = current.split(current.substring(finder, j + 1)).join("");

				}

			}

			$this.moves = moving = skeleton.replace(regP, tweens.toString()).replace(regD, (duration * 0.001).toFixed(2)).replace(regE, CeaserEasing[easing[0]][easing[1]]);

			replaced = values.toString();
			if(!transform) replaced = replaced.replace(comma, "");

			obj.className = obj.className.replace(regT, "");
			obj.addEventListener(css, cssEnded, false);
			obj.setAttribute("style", current.replace(trim, "") + moving + replaced);

		};

		if(!sets.fadeIn) {

			if(sets.fadeOut) obj.cjFadeOut = true;

		}
		else {

			obj.cjFadeIn = true;
			style.display = sets.display || "block";
			style.opacity = 0;

		}

		if(sets.duration === 0) {

			this.runner(true);
			this.stop();
			return;

		}

		if(!sets.cssStep) style.visibility = "visible";

		if(!sets.delay) {

			this.delayed = setTimeout(this.runner, 30);

		}
		else {

			this.delayed = setTimeout(this.runner, sets.delay > 30 ? sets.delay : 30);

		}

	};

	// stops a CSS3 Transition
	CJcss.prototype.stop = function(callback, popped) {

		var element = this.obj;

		if(callback) callback = this.complete;

		if(!element) {

			clearTimeout(this.delayed);
			clearTimeout(this.stepped);

			checkElement(this.storage);
			if(!popped) popTween(this, element, callback, this.completeParams);

			return;

		}

		delete element.cj;

		element.removeEventListener(css, cssEnded, false);
		element.className += " cj-tween";
		element.setAttribute("style", element.getAttribute("style").split(this.moves).join(";").split(";;").join(";"));

		checkElement(element);

		if(!popped) popTween(this, element, callback, this.completeParams);

	};

	// special call for animating percentages
	this.CJpercentage = function(obj, to, sets) {

		length = dictionary.length;

		var $this = obj.cj = dictionary[length++] = this;

		this.obj = obj;
		this.complete = sets.callback;
		this.completeParams = sets.callbackParams;

		this.runner = function() {

			var i = 0,
			ar = [],
			prop, begin, end,
			newbs = to.to,
			from = to.from,
			duration = sets.duration || defaultDuration,
			easing = (sets.ease || defaultEase).toLowerCase().split(".");
			easing = PennerEasing[easing[0]][easing[1]];

			for(prop in from) {

				if(!from.hasOwnProperty(prop)) continue;

				end = parseInt(newbs[prop], 10);
				begin = parseInt(from[prop], 10);

				ar[i++] = [end > begin, prop, end, begin];

			}

			obj.style.visibility = "visible";
			$this.transitions = $this.animate(obj, ar, duration, easing);
			(engineRunning) ? setTimeout(checkEngine, 10) : engine();

		};

		if(sets.duration === 0) {

			this.stop();
			return;

		}

		if(!sets.delay) {

			this.runner();

		}
		else {

			this.delayed = setTimeout(this.runner, sets.delay);

		}

	};

	CJpercentage.prototype.cycle = function() {

		return this.transitions();

	};

	// animate percentages
	CJpercentage.prototype.animate = function(obj, to, duration, ease) {

		var tick, timed = 0, then = Date.now(), now, i, style = obj.style, leg = to.length, itm, begin;

		return function(force) {

			now = Date.now();
			timed += now - then;
			then = now;

			tick = ease(timed, 0, 1, duration);
			i = leg;

			if(tick < 0.99 && !force) {

				while(i--) {

					itm = to[i];
					begin = itm[3];

					if(itm[0]) {

						style[itm[1]] = (begin + ((itm[2] - begin) * tick)) + "%";

					}
					else {

						style[itm[1]] = (begin - ((begin - itm[2]) * tick)) + "%";

					}

				}

				return true;

			}
			else {

				while(i--) {

					itm = to[i];
					style[itm[1]] = itm[2] + "%";

				}

				return false;

			}

		};

	};

	// stop a percentage animation
	CJpercentage.prototype.stop = function(complete, callback, popped) {

		if("delayed" in this) clearTimeout(this.delayed);
		var element = this.obj;

		delete element.cj;
		if(complete && this.transitions) this.transitions(true);

		if(callback) callback = this.complete;
		if(!popped) popTween(this, element, callback, this.completeParams);

	};

	// extends Jacked
	this.CJspecial = function(obj, sets) {

		if(!sets || !sets.callback) return;

		length = dictionary.length;
		dictionary[length++] = obj.cj = this;

		var callback = this.complete = sets.callback,
		easing = sets.ease || defaultEase;
		easing = easing.toLowerCase().split(".");
		easing = PennerEasing[easing[0]][easing[1]];

		this.obj = obj;
		this.transitions = this.numbers(obj, sets.duration || defaultDuration, easing, callback);

		(engineRunning) ? setTimeout(checkEngine, 10) : engine();

	};

	// extender cycle
	CJspecial.prototype.cycle = function() {

		return this.transitions();

	};

	// extender step
	CJspecial.prototype.numbers = function(obj, duration, ease, callback) {

		var tick, timed = 0, then = Date.now(), now;

		return function() {

			now = Date.now();
			timed += now - then;
			then = now;

			tick = ease(timed, 0, 1, duration);

			if(tick < 0.97) {

				callback(obj, tick);
				return true;

			}
			else {

				return false;

			}

		};

	};

	// stop extender
	CJspecial.prototype.stop = function(complete, callback, popped, finished) {

		var obj = this.obj;

		if(!obj) return;
		delete obj.cj;

		if(!popped) popTween(this);
		if(complete || finished) this.complete(obj, 1, callback);

	};

	// if CSS3 fadeIn/fadeOut gets aborted, restore the properties
	function checkElement(element) {

		if(element.cjFadeIn) {

			delete element.cjFadeIn;
			element.style.opacity = 1;
			element.style.visibility = "visible";

		}
		else if(element.cjFadeOut) {

			delete element.cjFadeOut;
			element.style.display = "none";

		}

	}

	// checks to make sure the timeline engine starts
	function checkEngine() {

		if(!engineRunning) engine();

	}

	// removes the tween from memory when finished
	function popTween($this, element, callback, params) {

		dictionary.splice(dictionary.indexOf($this), 1);
		length = dictionary.length;

		if(callback) callback(element, params);

	}

	// CSS3 onEnded event
	function cssEnded(event) {

		event.stopPropagation();

		var $this = this.cj;

		if($this) $this.stop($this.complete);

	}

	// transform a CSS3 percentage call to a regular tween
	function percCSS(obj, to, settings) {

		var newTo = {}, prop, goTo = to.to;

		for(prop in goTo) {

			if(!goTo.hasOwnProperty(prop)) continue;

			newTo[prop] = goTo[prop];

		}

		Jacked.tween(obj, newTo, settings);

	}

	// checks for requestAnimstionFrame support
	function timeline(req, st) {

		return this["webkit" + req + st] || this["moz" + req + st] || this["o" + req + st] || this[req + st] || null;

	}

	// parse hex color
	// credit: http://www.bitstorm.org/jquery/color-animation/
	function getColor(color) {

		var matched;

		if(matched = color1.exec(color)) {

			return [parseInt(matched[1], 16), parseInt(matched[2], 16), parseInt(matched[3], 16), 1];

		}
		else if(matched = color2.exec(color)) {

			return [parseInt(matched[1], 16) * 17, parseInt(matched[2], 16) * 17, parseInt(matched[3], 16) * 17, 1];

		}

	}

	// IE9 uses a fast timer, legacy IE uses a slow timer
	function getSpeed() {

		var point = agent.search("msie");

		if(point === -1) {

			return [33.3, 0];

		}
		else {

			var ver = parseInt(agent.substr(point + 4, point + 5), 10), speed = ver >= 9 ? 16.6 : 33.3;

			return [speed, ver];

		}

	}

	// sets the default tween behaviour (CSS3, timeline, timer)
	function setDefaults() {

		for(var prop in defaults) {

			if(!defaults.hasOwnProperty(prop)) continue;

			if(prop === browser) {

				useCSS = defaults[prop];
				break;

			}

		}

	}

	// tests for mobile support
	function getMobile() {

		if(!("ontouchend" in document)) {

			return null;

		}
		else {

			if(agent.search("iphone") !== -1 || agent.search("ipad") !== -1) {

				return "ios";

			}
			else if(agent.search("android") !== -1 || agent.search("applewebkit") !== -1) {

				return "android";

			}
			else if(agent.search("msie") !== -1) {

				return "winMobile";

			}

			return null;

		}

	}

	// tests for CSS3 Transition support
	function getCSS() {

		if("WebkitTransition" in temp) {

			return ["webkitTransitionEnd", "-webkit-transition", agent.search("chrome") !== -1 ? "chrome" : "safari"];

		}
		else if("MozTransition" in temp) {

			return ["transitionend", "-moz-transition", "firefox"];

		}
		else if("MSTransition" in temp) {

			return ["transitionend", "-ms-transition", "ie"];

		}
		else if('OTransition' in temp) {

			return ["otransitionend", "-o-transition", "opera"];

		}
		else if("transition" in temp) {

			return ["transitionend", "transition", null];

		}

		return null;

	}

	// tests for CSS3 transform support
	function getTransform() {

		if('WebkitTransform' in temp) {

			return 'WebkitTransform';

		}
		else if('MozTransform' in temp) {

			return 'MozTransform';

		}
		else if('msTransform' in temp) {

			return 'msTransform';

		}
		else if('OTransform' in temp) {

			return 'OTransform';

		}
		else if('transform' in temp) {

			return 'transform';

		}

		return null;

	}


	/*
	TERMS OF USE - EASING EQUATIONS

	Open source under the BSD License.

	Copyright Ã‚Â© 2001 Robert Penner
	All rights reserved.

	Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

		Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
		Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
		Neither the name of the author nor the names of contributors may be used to endorse or promote products derived from this software without specific prior written permission.

	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
	*/

	var PennerEasing = {

		linear: {

			easenone: function(t, b, c, d) {

				return c * t / d + b;

			},

			easein: function(t, b, c, d) {

				return c * t / d + b;

			},

			easeout: function(t, b, c, d) {

				return c * t / d + b;

			},

			easeinout: function(t, b, c, d) {

				return c * t / d + b;

			}

		},

		quint: {

			easeout: function (t, b, c, d) {

				return c * ((t = t / d - 1) * t * t * t * t + 1) + b;

			},

			easein: function(t, b, c, d) {

				return c * (t /= d) * t * t * t * t + b;

			},

			easeinout: function(t, b, c, d) {

				return ((t /= d / 2) < 1) ? c / 2 * t * t * t * t * t + b : c / 2 * ((t -= 2) * t * t * t * t + 2) + b;

			}

		},

		quad: {

			easein: function (t, b, c, d) {

				return c * (t /= d) * t + b;

			},

			easeout: function (t, b, c, d) {

				return -c * (t /= d) * (t - 2) + b;

			},

			easeinout: function (t, b, c, d) {

				return ((t /= d / 2) < 1) ? c / 2 * t * t + b : -c / 2 * ((--t) * (t - 2) - 1) + b;

			}

		},

		quart: {

			easein: function(t, b, c, d) {

				return c * (t /= d) * t * t * t + b;

			},

			easeout: function(t, b, c, d) {

				return -c * ((t = t / d - 1) * t * t * t - 1) + b;

			},

			easeinout: function(t, b, c, d) {

				return ((t /= d / 2) < 1) ? c / 2 * t * t * t * t + b : -c / 2 * ((t -= 2) * t * t * t - 2) + b;

			}

		},

		cubic: {

			easein: function(t, b, c, d) {

				return c * (t /= d) * t * t + b;

			},

			easeout: function(t, b, c, d) {

				return c * ((t = t / d - 1) * t * t + 1) + b;

			},

			easeinout: function(t, b, c, d) {

				return ((t /= d / 2) < 1) ? c / 2 * t * t * t + b : c / 2 * ((t -= 2) * t * t + 2) + b;

			}

		},

		circ: {

			easein: function(t, b, c, d) {

				return -c * (Math.sqrt(1 - (t /= d) * t) - 1) + b;

			},

			easeout: function(t, b, c, d) {

				return c * Math.sqrt(1 - (t = t / d - 1) * t) + b;

			},

			easeinout: function(t, b, c, d) {

				return ((t /= d / 2) < 1) ? -c / 2 * (Math.sqrt(1 - t * t) - 1) + b : c / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + b;

			}

		},

		sine: {

			easein: function(t, b, c, d) {

				return -c * Math.cos(t / d * (Math.PI / 2)) + c + b;

			},

			easeout: function(t, b, c, d) {

				return c * Math.sin(t / d * (Math.PI / 2)) + b;

			},

			easeinout: function(t, b, c, d) {

				return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b;

			}

		},

		expo: {

			easein: function(t, b, c, d) {

				return (t === 0) ? b : c * Math.pow(2, 10 * (t / d - 1)) + b;

			},

			easeout: function(t, b, c, d) {

				return (t === d) ? b + c : c * (-Math.pow(2, -10 * t / d) + 1) + b;

			},

			easeinout: function(t, b, c, d) {

				if(t === 0) return b;
				if(t === d) return b + c;
				if((t /= d / 2) < 1) return c / 2 * Math.pow(2, 10 * (t - 1)) + b;

				return c / 2 * (-Math.pow(2, -10 * --t) + 2) + b;

			}

		}

	},

	// credit: http://matthewlein.com/ceaser/

	CeaserEasing = {

		linear: {

			easenone: "0.250, 0.250, 0.750, 0.750",
			easein: "0.420, 0.000, 1.000, 1.000",
			easeout: "0.000, 0.000, 0.580, 1.000",
			easeinout: "0.420, 0.000, 0.580, 1.000"

		},

		quint: {

			easein: "0.755, 0.050, 0.855, 0.060",
			easeout: "0.230, 1.000, 0.320, 1.000",
			easeinout: "0.860, 0.000, 0.070, 1.000"

		},

		quad: {

			easein: "0.550, 0.085, 0.680, 0.530",
			easeout: "0.250, 0.460, 0.450, 0.940",
			easeinout: "0.455, 0.030, 0.515, 0.955"

		},

		quart: {

			easein: "0.895, 0.030, 0.685, 0.220",
			easeout: "0.165, 0.840, 0.440, 1.000",
			easeinout: "0.770, 0.000, 0.175, 1.000"

		},

		cubic: {

			easein: "0.550, 0.055, 0.675, 0.190",
			easeout: "0.215, 0.610, 0.355, 1.000",
			easeinout: "0.645, 0.045, 0.355, 1.000"

		},

		circ: {

			easein: "0.600, 0.040, 0.980, 0.335",
			easeout: "0.075, 0.820, 0.165, 1.000",
			easeinout: "0.785, 0.135, 0.150, 0.860"

		},

		sine: {

			easein: "0.470, 0.000, 0.745, 0.715",
			easeout: "0.390, 0.575, 0.565, 1.000",
			easeinout: "0.445, 0.050, 0.550, 0.950"

		},

		expo: {

			easein: "0.950, 0.050, 0.795, 0.035",
			easeout: "0.190, 1.000, 0.220, 1.000",
			easeinout: "1.000, 0.000, 0.000, 1.000"

		}

	};

	// *****************************************
	// JackBox doesn't use any HTMLElement calls
	// *****************************************
	/*
	element.prototype.jacked = function(to, sets) {

		Jacked.tween(this, to, sets);

	};

	element.prototype.fadeInJacked = function(sets) {

		Jacked.fadeIn(this, sets);

	};

	element.prototype.fadeOutJacked = function(sets) {

		Jacked.fadeOut(this, sets);

	};

	element.prototype.transformJacked = function(to, sets, fallback) {

		Jacked.transform(this, to, sets, fallback);

	};

	element.prototype.percentageJacked = function(to, sets) {

		Jacked.percentage(this, to, sets);

	};

	element.prototype.stopJacked = function(complete, callback) {

		Jacked.stopTween(this, complete, callback);

	};
	*/

	element = speeds = temp = null;


})(window);


// **********************************************************************************
// JackBox doesn't use any jQuery calls so no need to clutter the jQuery.fn namespace
// **********************************************************************************
/*
if(typeof jQuery !== "undefined") {

	(function($) {

		$.fn.jacked = function(to, settings) {

			return this.each(createJack, [to, settings]);

		};

		$.fn.fadeInJacked = function(settings) {

			return this.each(showJack, [settings]);

		};

		$.fn.fadeOutJacked = function(settings) {

			return this.each(hideJack, [settings]);

		};

		$.fn.transformJacked = function(to, settings, fallback) {

			return this.each(transformJack, [to, settings, fallback]);

		};

		$.fn.percentageJacked = function(to, settings) {

			return this.each(percentageJack, [to, settings]);

		};

		$.fn.stopJacked = function(complete, callback) {

			return this.each(stopJack, [complete, callback]);

		};

		$.fn.stopJackedSet = function(complete) {

			return this.each(stopSet, [complete]);

		};

		function createJack(to, sets) {

			Jacked.tween(this, to, sets);

		}

		function showJack(sets) {

			Jacked.fadeIn(this, sets);

		}

		function hideJack(sets) {

			Jacked.fadeOut(this, sets);

		}

		function transformJack(to, sets, fallback) {

			Jacked.transform(this, to, sets, fallback);

		}

		function percentageJack(to, sets) {

			Jacked.percentage(this, to, sets);

		}

		function stopJack(complete, callback) {

			Jacked.stopTween(this, complete, callback);

		}

		function stopSet(complete) {

			recursiveStop($(this), complete);

		}

		function recursiveStop($this, complete) {

			$this.children().each(stopItem, [complete]);

		}

		function stopItem(complete) {

			Jacked.stopTween(this, complete);
			recursiveStop($(this), complete);

		}

	})(jQuery);

}
*/















/*
 * jQuery Address Plugin v1.5
 * http://www.asual.com/jquery/address/
 *
 * Copyright (c) 2009-2010 Rostislav Hristov
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * Date: 2012-11-18 23:51:44 +0200 (Sun, 18 Nov 2012)
 */

;(function ($) {

    $.address = (function () {

        var _trigger = function(name) {
               var ev = $.extend($.Event(name),
                 (function() {
                            var parameters = {},
                                parameterNames = $.address.parameterNames();
                            for (var i = 0, l = parameterNames.length; i < l; i++) {
                                parameters[parameterNames[i]] = $.address.parameter(parameterNames[i]);
                            }
                            return {
                                value: $.address.value(),
                                path: $.address.path(),
                                pathNames: $.address.pathNames(),
                                parameterNames: parameterNames,
                                parameters: parameters,
                                queryString: $.address.queryString()
                            };
                        }).call($.address)
                    );

               $($.address).trigger(ev);
               return ev;
            },
            _array = function(obj) {
                return Array.prototype.slice.call(obj);
            },
            _bind = function(value, data, fn) {
                $().bind.apply($($.address), Array.prototype.slice.call(arguments));
                return $.address;
            },
            _unbind = function(value,  fn) {
                $().unbind.apply($($.address), Array.prototype.slice.call(arguments));
                return $.address;
            },
            _supportsState = function() {
                return (_h.pushState && _opts.state !== UNDEFINED);
            },
            _hrefState = function() {
                return ('/' + _l.pathname.replace(new RegExp(_opts.state), '') +
                    _l.search + (_hrefHash() ? '#' + _hrefHash() : '')).replace(_re, '/');
            },
            _hrefHash = function() {
                var index = _l.href.indexOf('#');
                return index != -1 ? _crawl(_l.href.substr(index + 1), FALSE) : '';
            },
            _href = function() {
                return _supportsState() ? _hrefState() : _hrefHash();
            },
            _window = function() {
                try {
                    return top.document !== UNDEFINED && top.document.title !== UNDEFINED ? top : window;
                } catch (e) {
                    return window;
                }
            },
            _js = function() {
                return 'javascript';
            },
            _strict = function(value) {
                value = value.toString();
                return (_opts.strict && value.substr(0, 1) != '/' ? '/' : '') + value;
            },
            _crawl = function(value, direction) {
                if (_opts.crawlable && direction) {
                    return (value !== '' ? '!' : '') + value;
                }
                return value.replace(/^\!/, '');
            },
            _cssint = function(el, value) {
                return parseInt(el.css(value), 10);
            },

            // Hash Change Callback
            _listen = function() {
                if (!_silent) {
                    var hash = _href(),
                        diff = decodeURI(_value) != decodeURI(hash);
                    if (diff) {
                        if (_msie && _version < 7) {
                            _l.reload();
                        } else {
                            if (_msie && !_hashchange && _opts.history) {
                                _st(_html, 50);
                            }
                            _old = _value;
                            _value = hash;
                            _update(FALSE);
                        }
                    }
                }
            },

            _update = function(internal) {
                var changeEv = _trigger(CHANGE),
                    xChangeEv = _trigger(internal ? INTERNAL_CHANGE : EXTERNAL_CHANGE);

                _st(_track, 10);

                if (changeEv.isDefaultPrevented() || xChangeEv.isDefaultPrevented()){
                  _preventDefault();
                }
            },

            _preventDefault = function(){
              _value = _old;

              if (_supportsState()) {
                  _h.popState({}, '', _opts.state.replace(/\/$/, '') + (_value === '' ? '/' : _value));
              } else {
                  _silent = TRUE;
                  if (_webkit) {
                      if (_opts.history) {
                          _l.hash = '#' + _crawl(_value, TRUE);
                      } else {
                          _l.replace('#' + _crawl(_value, TRUE));
                      }
                  } else if (_value != _href()) {
                      if (_opts.history) {
                          _l.hash = '#' + _crawl(_value, TRUE);
                      } else {
                          _l.replace('#' + _crawl(_value, TRUE));
                      }
                  }
                  if ((_msie && !_hashchange) && _opts.history) {
                      _st(_html, 50);
                  }
                  if (_webkit) {
                      _st(function(){ _silent = FALSE; }, 1);
                  } else {
                      _silent = FALSE;
                  }
              }

            },

            _track = function() {
                if (_opts.tracker !== 'null' && _opts.tracker !== NULL) {
                    var fn = $.isFunction(_opts.tracker) ? _opts.tracker : _t[_opts.tracker],
                        value = (_l.pathname + _l.search +
                                ($.address && !_supportsState() ? $.address.value() : ''))
                                .replace(/\/\//, '/').replace(/^\/$/, '');
                    if ($.isFunction(fn)) {
                        fn(value);
                    } else if ($.isFunction(_t.urchinTracker)) {
                        _t.urchinTracker(value);
                    } else if (_t.pageTracker !== UNDEFINED && $.isFunction(_t.pageTracker._trackPageview)) {
                        _t.pageTracker._trackPageview(value);
                    } else if (_t._gaq !== UNDEFINED && $.isFunction(_t._gaq.push)) {
                        _t._gaq.push(['_trackPageview', decodeURI(value)]);
                    }
                }
            },
            _html = function() {
                var src = _js() + ':' + FALSE + ';document.open();document.writeln(\'<html><head><title>' +
                    _d.title.replace(/\'/g, '\\\'') + '</title><script>var ' + ID + ' = "' + encodeURIComponent(_href()).replace(/\'/g, '\\\'') +
                    (_d.domain != _l.hostname ? '";document.domain="' + _d.domain : '') +
                    '";</' + 'script></head></html>\');document.close();';
                if (_version < 7) {
                    _frame.src = src;
                } else {
                    _frame.contentWindow.location.replace(src);
                }
            },
            _options = function() {
                if (_url && _qi != -1) {
                    var i, param, params = _url.substr(_qi + 1).split('&');
                    for (i = 0; i < params.length; i++) {
                        param = params[i].split('=');
                        if (/^(autoUpdate|crawlable|history|strict|wrap)$/.test(param[0])) {
                            _opts[param[0]] = (isNaN(param[1]) ? /^(true|yes)$/i.test(param[1]) : (parseInt(param[1], 10) !== 0));
                        }
                        if (/^(state|tracker)$/.test(param[0])) {
                            _opts[param[0]] = param[1];
                        }
                    }
                    _url = NULL;
                }
                _old = _value;
                _value = _href();
            },
            _load = function() {
                if (!_loaded) {
                    _loaded = TRUE;
                    _options();
                    var complete = function() {
                            _enable.call(this);
                            _unescape.call(this);
                        },
                        body = $('body').ajaxComplete(complete);
                    complete();
                    if (_opts.wrap) {
                        var wrap = $('body > *')
                            .wrapAll('<div style="padding:' +
                                (_cssint(body, 'marginTop') + _cssint(body, 'paddingTop')) + 'px ' +
                                (_cssint(body, 'marginRight') + _cssint(body, 'paddingRight')) + 'px ' +
                                (_cssint(body, 'marginBottom') + _cssint(body, 'paddingBottom')) + 'px ' +
                                (_cssint(body, 'marginLeft') + _cssint(body, 'paddingLeft')) + 'px;" />')
                            .parent()
                            .wrap('<div id="' + ID + '" style="height:100%;overflow:auto;position:relative;' +
                                (_webkit && !window.statusbar.visible ? 'resize:both;' : '') + '" />');
                        $('html, body')
                            .css({
                                height: '100%',
                                margin: 0,
                                padding: 0,
                                overflow: 'hidden'
                            });
                        if (_webkit) {
                            $('<style type="text/css" />')
                                .appendTo('head')
                                .text('#' + ID + '::-webkit-resizer { background-color: #fff; }');
                        }
                    }
                    if (_msie && !_hashchange) {
                        var frameset = _d.getElementsByTagName('frameset')[0];
                        _frame = _d.createElement((frameset ? '' : 'i') + 'frame');
                        _frame.src = _js() + ':' + FALSE;
                        if (frameset) {
                            frameset.insertAdjacentElement('beforeEnd', _frame);
                            frameset[frameset.cols ? 'cols' : 'rows'] += ',0';
                            _frame.noResize = TRUE;
                            _frame.frameBorder = _frame.frameSpacing = 0;
                        } else {
                            _frame.style.display = 'none';
                            _frame.style.width = _frame.style.height = 0;
                            _frame.tabIndex = -1;
                            _d.body.insertAdjacentElement('afterBegin', _frame);
                        }
                        _st(function() {
                            $(_frame).bind('load', function() {
                                var win = _frame.contentWindow;
                                _old = _value;
                                _value = win[ID] !== UNDEFINED ? win[ID] : '';
                                if (_value != _href()) {
                                    _update(FALSE);
                                    _l.hash = _crawl(_value, TRUE);
                                }
                            });
                            if (_frame.contentWindow[ID] === UNDEFINED) {
                                _html();
                            }
                        }, 50);
                    }
                    _st(function() {
                        _trigger('init');
                        _update(FALSE);
                    }, 1);
                    if (!_supportsState()) {
                        if ((_msie && _version > 7) || (!_msie && _hashchange)) {
                            if (_t.addEventListener) {
                                _t.addEventListener(HASH_CHANGE, _listen, FALSE);
                            } else if (_t.attachEvent) {
                                _t.attachEvent('on' + HASH_CHANGE, _listen);
                            }
                        } else {
                            _si(_listen, 50);
                        }
                    }
                    if ('state' in window.history) {
                        $(window).trigger('popstate');
                    }
                }
            },
            _enable = function() {
                var el,
                    elements = $('a'),
                    length = elements.size(),
                    delay = 1,
                    index = -1,
                    sel = '[rel*="address:"]',
                    fn = function() {
                        if (++index != length) {
                            el = $(elements.get(index));
                            if (el.is(sel)) {
                                el.address(sel);
                            }
                            _st(fn, delay);
                        }
                    };
                _st(fn, delay);
            },
            _popstate = function() {
                if (decodeURI(_value) != decodeURI(_href())) {
                    _old = _value;
                    _value = _href();
                    _update(FALSE);
                }
            },
            _unload = function() {
                if (_t.removeEventListener) {
                    _t.removeEventListener(HASH_CHANGE, _listen, FALSE);
                } else if (_t.detachEvent) {
                    _t.detachEvent('on' + HASH_CHANGE, _listen);
                }
            },
            _unescape = function() {
                if (_opts.crawlable) {
                    var base = _l.pathname.replace(/\/$/, ''),
                        fragment = '_escaped_fragment_';
                    if ($('body').html().indexOf(fragment) != -1) {
                        $('a[href]:not([href^=http]), a[href*="' + document.domain + '"]').each(function() {
                            var href = $(this).attr('href').replace(/^http:/, '').replace(new RegExp(base + '/?$'), '');
                            if (href === '' || href.indexOf(fragment) != -1) {
                                $(this).attr('href', '#' + encodeURI(decodeURIComponent(href.replace(new RegExp('/(.*)\\?' +
                                    fragment + '=(.*)$'), '!$2'))));
                            }
                        });
                    }
                }
            },
            UNDEFINED,
            NULL = null,
            ID = 'jQueryAddress',
            STRING = 'string',
            HASH_CHANGE = 'hashchange',
            INIT = 'init',
            CHANGE = 'change',
            INTERNAL_CHANGE = 'internalChange',
            EXTERNAL_CHANGE = 'externalChange',
            TRUE = true,
            FALSE = false,
            _opts = {
                autoUpdate: TRUE,
                crawlable: FALSE,
                history: TRUE,
                strict: TRUE,
                wrap: FALSE
            },
            _browser = $.browser,
            _version = parseFloat(_browser.version),
            _msie = !$.support.opacity,
            _webkit = _browser.webkit || _browser.safari,
            _t = _window(),
            _d = _t.document,
            _h = _t.history,
            _l = _t.location,
            _si = setInterval,
            _st = setTimeout,
            _re = /\/{2,9}/g,
            _agent = navigator.userAgent,
            _hashchange = 'on' + HASH_CHANGE in _t,
            _frame,
            _form,
            _url = $('script:last').attr('src'),
            _qi = _url ? _url.indexOf('?') : -1,
            _title = _d.title,
            _silent = FALSE,
            _loaded = FALSE,
            _juststart = TRUE,
            _updating = FALSE,
            _listeners = {},
            _value = _href();
            _old = _value;

        if (_msie) {
            _version = parseFloat(_agent.substr(_agent.indexOf('MSIE') + 4));
            if (_d.documentMode && _d.documentMode != _version) {
                _version = _d.documentMode != 8 ? 7 : 8;
            }
            var pc = _d.onpropertychange;
            _d.onpropertychange = function() {
                if (pc) {
                    pc.call(_d);
                }
                if (_d.title != _title && _d.title.indexOf('#' + _href()) != -1) {
                    _d.title = _title;
                }
            };
        }

        if (_h.navigationMode) {
            _h.navigationMode = 'compatible';
        }
        if (document.readyState == 'complete') {
            var interval = setInterval(function() {
                if ($.address) {
                    _load();
                    clearInterval(interval);
                }
            }, 50);
        } else {
            _options();
            $(_load);
        }
        $(window).bind('popstate', _popstate).bind('unload', _unload);

        return {
            bind: function(type, data, fn) {
                return _bind.apply(this, _array(arguments));
            },
            unbind: function(type, fn) {
                return _unbind.apply(this, _array(arguments));
            },
            init: function(data, fn) {
                return _bind.apply(this, [INIT].concat(_array(arguments)));
            },
            change: function(data, fn) {
                return _bind.apply(this, [CHANGE].concat(_array(arguments)));
            },
            internalChange: function(data, fn) {
                return _bind.apply(this, [INTERNAL_CHANGE].concat(_array(arguments)));
            },
            externalChange: function(data, fn) {
                return _bind.apply(this, [EXTERNAL_CHANGE].concat(_array(arguments)));
            },
            baseURL: function() {
                var url = _l.href;
                if (url.indexOf('#') != -1) {
                    url = url.substr(0, url.indexOf('#'));
                }
                if (/\/$/.test(url)) {
                    url = url.substr(0, url.length - 1);
                }
                return url;
            },
            autoUpdate: function(value) {
                if (value !== UNDEFINED) {
                    _opts.autoUpdate = value;
                    return this;
                }
                return _opts.autoUpdate;
            },
            crawlable: function(value) {
                if (value !== UNDEFINED) {
                    _opts.crawlable = value;
                    return this;
                }
                return _opts.crawlable;
            },
            history: function(value) {
                if (value !== UNDEFINED) {
                    _opts.history = value;
                    return this;
                }
                return _opts.history;
            },
            state: function(value) {
                if (value !== UNDEFINED) {
                    _opts.state = value;
                    var hrefState = _hrefState();
                    if (_opts.state !== UNDEFINED) {
                        if (_h.pushState) {
                            if (hrefState.substr(0, 3) == '/#/') {
                                _l.replace(_opts.state.replace(/^\/$/, '') + hrefState.substr(2));
                            }
                        } else if (hrefState != '/' && hrefState.replace(/^\/#/, '') != _hrefHash()) {
                            _st(function() {
                                _l.replace(_opts.state.replace(/^\/$/, '') + '/#' + hrefState);
                            }, 1);
                        }
                    }
                    return this;
                }
                return _opts.state;
            },
            strict: function(value) {
                if (value !== UNDEFINED) {
                    _opts.strict = value;
                    return this;
                }
                return _opts.strict;
            },
            tracker: function(value) {
                if (value !== UNDEFINED) {
                    _opts.tracker = value;
                    return this;
                }
                return _opts.tracker;
            },
            wrap: function(value) {
                if (value !== UNDEFINED) {
                    _opts.wrap = value;
                    return this;
                }
                return _opts.wrap;
            },
            update: function() {
                _updating = TRUE;
                this.value(_value);
                _updating = FALSE;
                return this;
            },
            title: function(value) {
                if (value !== UNDEFINED) {
                    _st(function() {
                        _title = _d.title = value;
                        if (_juststart && _frame && _frame.contentWindow && _frame.contentWindow.document) {
                            _frame.contentWindow.document.title = value;
                            _juststart = FALSE;
                        }
                    }, 50);
                    return this;
                }
                return _d.title;
            },
            value: function(value) {
                if (value !== UNDEFINED) {
                    value = _strict(value);
                    if (value == '/') {
                        value = '';
                    }
                    if (_value == value && !_updating) {
                        return;
                    }
                    _old = _value;
                    _value = value;
                    if (_opts.autoUpdate || _updating) {
                        _update(TRUE);
                        if (_supportsState()) {
                            _h[_opts.history ? 'pushState' : 'replaceState']({}, '',
                                    _opts.state.replace(/\/$/, '') + (_value === '' ? '/' : _value));
                        } else {
                            _silent = TRUE;
                            if (_webkit) {
                                if (_opts.history) {
                                    _l.hash = '#' + _crawl(_value, TRUE);
                                } else {
                                    _l.replace('#' + _crawl(_value, TRUE));
                                }
                            } else if (_value != _href()) {
                                if (_opts.history) {
                                    _l.hash = '#' + _crawl(_value, TRUE);
                                } else {
                                    _l.replace('#' + _crawl(_value, TRUE));
                                }
                            }
                            if ((_msie && !_hashchange) && _opts.history) {
                                _st(_html, 50);
                            }
                            if (_webkit) {
                                _st(function(){ _silent = FALSE; }, 1);
                            } else {
                                _silent = FALSE;
                            }
                        }
                    }
                    return this;
                }
                return _strict(_value);
            },
            path: function(value) {
                if (value !== UNDEFINED) {
                    var qs = this.queryString(),
                        hash = this.hash();
                    this.value(value + (qs ? '?' + qs : '') + (hash ? '#' + hash : ''));
                    return this;
                }
                return _strict(_value).split('#')[0].split('?')[0];
            },
            pathNames: function() {
                var path = this.path(),
                    names = path.replace(_re, '/').split('/');
                if (path.substr(0, 1) == '/' || path.length === 0) {
                    names.splice(0, 1);
                }
                if (path.substr(path.length - 1, 1) == '/') {
                    names.splice(names.length - 1, 1);
                }
                return names;
            },
            queryString: function(value) {
                if (value !== UNDEFINED) {
                    var hash = this.hash();
                    this.value(this.path() + (value ? '?' + value : '') + (hash ? '#' + hash : ''));
                    return this;
                }
                var arr = _value.split('?');
                return arr.slice(1, arr.length).join('?').split('#')[0];
            },
            parameter: function(name, value, append) {
                var i, params;
                if (value !== UNDEFINED) {
                    var names = this.parameterNames();
                    params = [];
                    value = value === UNDEFINED || value === NULL ? '' : value.toString();
                    for (i = 0; i < names.length; i++) {
                        var n = names[i],
                            v = this.parameter(n);
                        if (typeof v == STRING) {
                            v = [v];
                        }
                        if (n == name) {
                            v = (value === NULL || value === '') ? [] :
                                (append ? v.concat([value]) : [value]);
                        }
                        for (var j = 0; j < v.length; j++) {
                            params.push(n + '=' + v[j]);
                        }
                    }
                    if ($.inArray(name, names) == -1 && value !== NULL && value !== '') {
                        params.push(name + '=' + value);
                    }
                    this.queryString(params.join('&'));
                    return this;
                }
                value = this.queryString();
                if (value) {
                    var r = [];
                    params = value.split('&');
                    for (i = 0; i < params.length; i++) {
                        var p = params[i].split('=');
                        if (p[0] == name) {
                            r.push(p.slice(1).join('='));
                        }
                    }
                    if (r.length !== 0) {
                        return r.length != 1 ? r : r[0];
                    }
                }
            },
            parameterNames: function() {
                var qs = this.queryString(),
                    names = [];
                if (qs && qs.indexOf('=') != -1) {
                    var params = qs.split('&');
                    for (var i = 0; i < params.length; i++) {
                        var name = params[i].split('=')[0];
                        if ($.inArray(name, names) == -1) {
                            names.push(name);
                        }
                    }
                }
                return names;
            },
            hash: function(value) {
                if (value !== UNDEFINED) {
                    this.value(_value.split('#')[0] + (value ? '#' + value : ''));
                    return this;
                }
                var arr = _value.split('#');
                return arr.slice(1, arr.length).join('#');
            }
        };
    })();

    $.fn.address = function(fn) {
        var sel;
        if (typeof fn == 'string') {
            sel = fn;
            fn = undefined;
        }
        if (!$(this).attr('address')) {
            var f = function(e) {
                if (e.shiftKey || e.ctrlKey || e.metaKey || e.which == 2) {
                    return true;
                }
                if ($(this).is('a')) {
                    e.preventDefault();
                    var value = fn ? fn.call(this) :
                        /address:/.test($(this).attr('rel')) ? $(this).attr('rel').split('address:')[1].split(' ')[0] :
                        $.address.state() !== undefined && !/^\/?$/.test($.address.state()) ?
                                $(this).attr('href').replace(new RegExp('^(.*' + $.address.state() + '|\\.)'), '') :
                                $(this).attr('href').replace(/^(#\!?|\.)/, '');
                    $.address.value(value);
                }
            };
            $(sel ? sel : this).live('click', f).live('submit', function(e) {
                if ($(this).is('form')) {
                    e.preventDefault();
                    var action = $(this).attr('action'),
                        value = fn ? fn.call(this) : (action.indexOf('?') != -1 ? action.replace(/&$/, '') : action + '?') +
                            $(this).serialize();
                    $.address.value(value);
                }
            }).attr('address', true);
        }
        return this;
    };

})(jQuery);
/*
 * jQuery Hotkeys Plugin
 * Copyright 2010, John Resig
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Based upon the plugin by Tzury Bar Yochay:
 * http://github.com/tzuryby/hotkeys
 *
 * Original idea by:
 * Binny V A, http://www.openjs.com/scripts/events/keyboard_shortcuts/
*/

;(function(jQuery){

	jQuery.hotkeys = {
		version: "0.8",

		specialKeys: {
			8: "backspace", 9: "tab", 13: "return", 16: "shift", 17: "ctrl", 18: "alt", 19: "pause",
			20: "capslock", 27: "esc", 32: "space", 33: "pageup", 34: "pagedown", 35: "end", 36: "home",
			37: "left", 38: "up", 39: "right", 40: "down", 45: "insert", 46: "del",
			96: "0", 97: "1", 98: "2", 99: "3", 100: "4", 101: "5", 102: "6", 103: "7",
			104: "8", 105: "9", 106: "*", 107: "+", 109: "-", 110: ".", 111 : "/",
			112: "f1", 113: "f2", 114: "f3", 115: "f4", 116: "f5", 117: "f6", 118: "f7", 119: "f8",
			120: "f9", 121: "f10", 122: "f11", 123: "f12", 144: "numlock", 145: "scroll", 191: "/", 224: "meta"
		},

		shiftNums: {
			"`": "~", "1": "!", "2": "@", "3": "#", "4": "$", "5": "%", "6": "^", "7": "&",
			"8": "*", "9": "(", "0": ")", "-": "_", "=": "+", ";": ": ", "'": "\"", ",": "<",
			".": ">",  "/": "?",  "\\": "|"
		}
	};

	function keyHandler( handleObj ) {
		// Only care when a possible input has been specified
		if ( typeof handleObj.data !== "string" ) {
			return;
		}

		var origHandler = handleObj.handler,
			keys = handleObj.data.toLowerCase().split(" "),
			textAcceptingInputTypes = ["text", "password", "number", "email", "url", "range", "date", "month", "week", "time", "datetime", "datetime-local", "search", "color"];

		handleObj.handler = function( event ) {
			// Don't fire in text-accepting inputs that we didn't directly bind to
			if ( this !== event.target && (/textarea|select/i.test( event.target.nodeName ) ||
				jQuery.inArray(event.target.type, textAcceptingInputTypes) > -1 ) ) {
				return;
			}

			// Keypress represents characters, not special keys
			var special = event.type !== "keypress" && jQuery.hotkeys.specialKeys[ event.which ],
				character = String.fromCharCode( event.which ).toLowerCase(),
				key, modif = "", possible = {};

			// check combinations (alt|ctrl|shift+anything)
			if ( event.altKey && special !== "alt" ) {
				modif += "alt+";
			}

			if ( event.ctrlKey && special !== "ctrl" ) {
				modif += "ctrl+";
			}

			// TODO: Need to make sure this works consistently across platforms
			if ( event.metaKey && !event.ctrlKey && special !== "meta" ) {
				modif += "meta+";
			}

			if ( event.shiftKey && special !== "shift" ) {
				modif += "shift+";
			}

			if ( special ) {
				possible[ modif + special ] = true;

			} else {
				possible[ modif + character ] = true;
				possible[ modif + jQuery.hotkeys.shiftNums[ character ] ] = true;

				// "$" can be triggered as "Shift+4" or "Shift+$" or just "$"
				if ( modif === "shift+" ) {
					possible[ jQuery.hotkeys.shiftNums[ character ] ] = true;
				}
			}

			for ( var i = 0, l = keys.length; i < l; i++ ) {
				if ( possible[ keys[i] ] ) {
					return origHandler.apply( this, arguments );
				}
			}
		};
	}

	jQuery.each([ "keydown", "keyup", "keypress" ], function() {
		jQuery.event.special[ this ] = { add: keyHandler };
	});

})( jQuery );

/**
 * @package		Zen Tools
 * @subpackage	Zen Tools
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2012 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		1.7.2
 */

(function( $ ) {
	$.fn.availableTags = function() {

		available = new Array();

		if($(imageSource).is(":selected")) {
			available = ['title','image','text','more','column2','column3','column4','tweet'];
		}

		// Joomla content as a source
		if($(joomlaSource).is(":selected")){
			available = ['title','image','text','date','category','more','column2','column3','column4','tweet','isfeatured'];
		}

		if($(k2Source).is(":selected")){
			available = ['title','image','text','date','category','more','comments','extrafields','video','column2','column3','column4','tweet','isfeatured']
		}

	}

	$.fn.initSortables = function() {
		// Retrieve the cookie contents for this instance of the module
		setItems = ($(current).val()).split(",");

		//if the current contents is empty initialise the array
		if(!setItems) {
			setItems = [];
		}
	}

	$.fn.setSortables = function() {

		$(this).availableTags();

		// Empty the sortable lists in case the default button clicked
		$(usedList).empty();
		$(unusedList).empty();

		// Reinstate the instructions
		$(usedList).prepend("<li class='disabled'>Drag items here to use</li>");
		$(unusedList).prepend("<li class='disabled'>Available Items</li>");

		// Array to store the unused items
		var unusedItems = Array();


		// Look through the array of possible tags and filter out the ones that are actually being used.
		$.each(available, function(i, val){
			 if($.inArray(val, setItems) < 0)
				 unusedItems.push(val);
		});


		// Store the used items so they will be retrieved on page load.
		$(current).val(setItems);


		// Create two strings out of the arrays
		var useditems = setItems.join(',');
		var unuseditems = unusedItems.join(',')

		// Then split them again
		var used = useditems.split(",");
		var unused = unuseditems.split(",");


		// Populate the list of sortable items
		if($(current).val().length > 0)  {
										// Repopulates the list of elements. We check for the length of the textbox and if its not empty populate it.
				jQuery.each(used, function(i)
				{
					var li = $('<li/>').attr('id', used[i]).addClass(used[i]).text(used[i]).appendTo(usedList);
				});
			}

			// Sets the list of available items
			jQuery.each(unused, function(i)
			{
				var li = $('<li/>').attr('id', unused[i]).addClass(unused[i]).text(unused[i]).appendTo(unusedList);
			});
	};


	$.fn.presetMessage = function(){

		$('#default span,#Default span').html('Preset applied ...');

		window.setTimeout(function () {
			$('#default span,#Default span').html('Set default options');
		}, 2000);
	}

	$.fn.clickDefault = function(){
		$('#default span,#Default span').html('Click here to apply preset ...');
	}


	$.fn.setAccordion = function() {
		setItems = ['title','column2','image','column3','text','more'];
		$(text + ',' + image + ',' + more  + ',' + titlePanel).parent().parent().parent().parent().parent().show();
		$(paramscol1option)[11].selected = true;
		$(paramscol2option)[3].selected = true;
		$(paramscol3option)[7].selected = true;
		$(this).presetMessage();
	}


	$.fn.setGrid = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel + ',' + gridPanel).parent().parent().parent().parent().parent().show();
		$(this).presetMessage();
	}

	$.fn.gridPanel = function() {
		$(gridPanel).parent().parent().parent().parent().parent().show();
	}

			$.fn.setGridFiltered = function() {
				setItems = ['image','title','text'];
				$(image + ',' + titlePanel + ',' + text  + ',' + gridPanel + ',' + filterPanel).parent().parent().parent().parent().parent().show();
				$(paramslayoutoption)[0].selected = true;
				$(paramscatfilter)[0].selected = true;
				$(paramscatfilter)[1].selected = false;
				$(this).presetMessage();
			}

			$.fn.setGridTwoColumns = function() {
				setItems = ['image','column2','title','text'];

				$(paramscol2).show();
				$(paramscol1option)[3].selected = true;
				$(paramscol2option)[7].selected = true;
				$(paramsgridsperrow)[2].selected = true;
				$(paramscatfilter)[1].selected = true;
				$(paramslayoutoption)[0].selected = true;
				$(this).presetMessage();
			}


			$.fn.setGridCaptify = function() {
				setItems = ['image','title'];
				$(image + ',' + titlePanel + ',' + gridPanel).parent().parent().parent().parent().parent().show();
				$(overlaygridOption)[0].selected = true;
				$(paramsgridsperrow)[3].selected = true;
				$(paramslayoutoption)[0].selected = true;
				$(this).presetMessage();
			}


	$.fn.setList = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel).parent().parent().parent().parent().parent().show();
		$(overlaygridOption)[1].selected = true;
		$(this).presetMessage();
	}

			$.fn.setListTwoColumn = function() {
				setItems = ['image','column2','title','text'];
				$(image + ',' + titlePanel).parent().parent().parent().parent().parent().show();
				$(paramscol1option)[3].selected = true;
				$(paramscol2option)[7].selected = true;
				$(paramslayoutoption)[1].selected = true;

				$(this).presetMessage();

			}

			$.fn.setListThreeColumn = function() {
					setItems = ['title','date','category','column2','image','column3','text','more'];
					$(image + ',' + titlePanel +  ',' + date + ',' + more).parent().parent().parent().parent().parent().show();
					$(paramscol1option)[3].selected = true;
					$(paramscol2option)[3].selected = true;
					$(paramscol3option)[3].selected = true;
					$(paramslayoutoption)[1].selected = true;

				$(this).presetMessage();

			}


	$.fn.setCarousel = function() {
		setItems = ['image'];
		$(image + ',' + carouselPanel).parent().parent().parent().parent().parent().show();
		$(this).presetMessage();
	}

		$.fn.carouselPanel = function() {
			$(carouselPanel).parent().parent().parent().parent().parent().show();
		}

	$.fn.setMasonry = function() {
		setItems = ['image','title','text'];
		$(image + ',' + titlePanel + ',' + masonryPanel).parent().parent().parent().parent().parent().show();
		$(paramsmasonrywidths)[0].selected = true;
		$(paramsmasonrycolwidths)[3].selected = true;
		$(this).presetMessage();

	}

		$.fn.masonryPanel = function() {
			$(masonryPanel).parent().parent().parent().parent().parent().show();
		}

	$.fn.setSlideshow = function() {
		setItems = ['image','column2','title','text'];
		$(image + ',' + titlePanel + ',' + slideshowPanel).parent().parent().parent().parent().parent().show();
		$(paramscol1option)[3].selected = true;
		$(paramscol2option)[7].selected = true;
		$(paramsslideshowThemeOption)[2].selected = true;
		$(this).presetMessage();
	}

		$.fn.slideshowPanel = function() {
			$(slideshowPanel).parent().parent().parent().parent().parent().show();
		}

			$.fn.setSlideshowOverlay = function() {
				setItems = ['image','title'];
				$(image + ',' + titlePanel + ',' + slideshowPanel).parent().parent().parent().parent().parent().show();
				$(paramslayoutoption)[3].selected = true;
				$(this).presetMessage();
			}

			$.fn.setSlideshowFlat = function() {
				setItems = ['image','column2','title','text'];
				$(image + ',' + titlePanel + ',' + slideshowPanel).parent().parent().parent().parent().parent().show();
				$(paramslayoutoption)[3].selected = true;
				$(this).presetMessage();
			}

	$.fn.setLeading = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel).parent().parent().parent().parent().parent().show();
		$(this).presetMessage();
	}

	$.fn.setSingle = function() {
		setItems = ['image'];
		$(image + ',' + lightboxpanel).parent().parent().parent().parent().parent().show();
		$(linkselected)[1].selected = true;
		$(this).presetMessage();

	}

	$.fn.setPagination = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel).parent().parent().parent().parent().parent().show();
		$(this).presetMessage();
	}

	$.fn.imagePanel = function() {
		$(image).parent().parent().parent().parent().parent().show();
	}

	$.fn.k2Panel = function() {
		$(panelk2).parent().parent().parent().parent().parent().show();
	}

	$.fn.joomlaPanel = function() {
		$(panelcontent).parent().parent().parent().parent().parent().show();
	}

	$.fn.accordionPanel = function() {
		$(accordionPanel).parent().parent().parent().parent().parent().show();
	}

	$.fn.lightboxPanel = function() {
		$(lightboxpanel).parent().parent().parent().parent().parent().show();
	}
	
	$.fn.twitterPanel = function() {
		$(twitterPanel).parent().parent().parent().parent().parent().show();
	}

	$.fn.textPanel = function() {
		$(text).parent().parent().parent().parent().parent().show();
	}

	$.fn.titlePanel = function() {
		$(titlePanel).parent().parent().parent().parent().parent().show();
	}

	$.fn.datePanel = function() {
		$(date).parent().parent().parent().parent().parent().show();
	}

	$.fn.morePanel = function() {
		$(more).parent().parent().parent().parent().parent().show();
	}

	$.fn.paginationPanel = function() {
		$(pagination).parent().parent().parent().parent().parent().show();
	}

	$.fn.filterPanel = function() {
		$(filterPanel).parent().parent().parent().parent().parent().show();
	}
	$.fn.externalLinksPanel = function() {
		$(externallinkslbl).show();
		$(externallinks).show();
		$(linktarget).show();
		$(linktargetlbl).show();
	}

	$.fn.contentLinksPanel = function() {
		$(altlinks).show();
		$(altlinkslbl).show();
		$(linktarget).show();
		$(linktargetlbl).show();
	}

	$.fn.updateLink = function() {

		switch ($(linkselect).text()) {
			case 'None':
				jQuery(altlinks).hide();
				jQuery(altlinkslbl).hide();
				jQuery(linktarget).hide();
				jQuery(linktargetlbl).hide();
			break;

			case 'Lightbox':
				jQuery(this).lightboxPanel();
			break;

			case 'External Links':
				jQuery(this).externalLinksPanel();
			break;

			case 'Content item':
				jQuery(this).contentLinksPanel();
			break;
		}
	}

	$.fn.layoutSwitch= function() {
		// Figure out which default to set

		jQuery(this).hideAllPanels();

		switch ($(paramslayoutSelected).text()) {
			case 'Grid':
				$(this).gridPanel();
				$(this).filterPanel();
			break;

			case 'List':
				$(this).filterPanel();
			break;

			// Slideshow
			case 'Slideshow':
				$(this).slideshowPanel();
			break;

			// Carousel
			case 'Carousel':
				$(this).carouselPanel();
			break;

			// Masonry
			case 'Masonry':
				$(this).masonryPanel();
			break;

			// Masonry
			case 'Pagination':
				$(this).gridPanel();
				$(this).paginationPanel();
			break;

			// Masonry
			case 'Accordion':
				$(this).accordionPanel();
			break;
		}


		// Directory Source
		if(jQuery(imageSource).is(":selected")) {
			jQuery(this).imagePanel();
			$(imageSourcePanel).parent().parent().parent().parent().parent().show();
		}

		// K2 Source
		if (jQuery(k2Source).is(":selected")) {
			jQuery(this).k2Panel();
		}


		// Joomla content as a source
		if(jQuery(joomlaSource).is(":selected")){
			jQuery(this).joomlaPanel();
		}

		// toggle for the k2 image option
		if((jQuery(k2Source).is(":selected")) && (jQuery("#sortable li#image").length == 1) ) {

			jQuery(k2imagetypelbl + ',' + k2imagetype + ',' + k2imageoptions).show();
		}
		else {
			jQuery(k2imagetypelbl + ',' + k2imagetype + ',' + k2imageoptions).hide();
		}


		jQuery(this).updateLink();


		// Hides text related panels if image isnt in the ordering
		if(jQuery("#sortable li#text").length == 1)  	{jQuery(this).textPanel();}
		if(jQuery("#sortable li#date").length == 1)  	{jQuery(this).datePanel();}
		if(jQuery("#sortable li#image").length == 1)  	{jQuery(this).imagePanel();}
		if(jQuery("#sortable li#title").length == 1)  	{jQuery(this).titlePanel();}
		if(jQuery("#sortable li#more").length == 1)  	{jQuery(this).morePanel();}
		if(jQuery("#sortable li#tweet").length == 1)  	{jQuery(this).twitterPanel();}
		
		
		$.fn.layoutSwitchColumns();
	};

	$.fn.hideAllPanels = function() {
		$(
			imageSourcePanel
			+ ',' + text
			+ ',' + image
			+ ',' + accordionPanel
			+ ',' + slideshowPanel
			+ ',' + gridPanel
			+ ',' + carouselPanel
			+ ',' + filterPanel
			+ ',' + masonryPanel
			+ ',' + panelcontent
			+ ',' + panelk2
			+ ',' + titlePanel
			+ ',' + more
			+ ',' + date
			+ ',' + lightboxpanel
			+ ',' + pagination
			+ ',' + twitterPanel
		).parent().parent().parent().parent().parent().hide();

		$(columnwidthPanel).parent().parent().parent().parent().parent().hide();

		$(
			thumbwidth
			+ ',' + thumbwidthlbl
			+ ',' + thumbheight
			+ ',' + thumbheightlbl
			+ ',' + slideTitleWidth
			+ ',' + slideTitleWidthlbl
			+ ',' + slideTitleWidth
			+ ',' + slideTitleWidthlbl
			+ ',' + slideTitleTheme
			+ ',' + slideTitleThemelbl
			+ ',' + slideTitleBreak
			+ ',' + slideTitleBreaklbl
			+ ',' + itemsperpage
			+ ',' + itemsperpagelbl
			+ ',' + externallinks
			+ ',' + externallinkslbl
			+ ',' + altlinks
			+ ',' + altlinkslbl
			+ ',' + linktarget
			+ ',' + linktargetlbl
			+ ',' + k2imagetypelbl
			+ ',' + k2imagetype
			+ ',' + k2imageoptions
		).hide();

		jQuery(paramscol1lbl).parent().parent().hide();
		jQuery(paramscol2lbl).parent().parent().hide();
		jQuery(paramscol3lbl).parent().parent().hide();
		jQuery(paramscol4lbl).parent().parent().hide();
	};

	$.fn.layoutSwitchColumns = function() {
		if(jQuery("#sortable li#column2").length == 1)
		{
			col2 = 1;
			jQuery(paramscol2lbl).parent().parent().show();
		}
		else
		{
			col2 = 0;
		}

		if(jQuery("#sortable li#column3").length == 1)
		{
			jQuery(paramscol3lbl).parent().parent().show();
			col3 = 1;
		}
		else
		{
			col3 = 0;
		}

		if(jQuery("#sortable li#column4").length == 1)
		{
			jQuery(paramscol4lbl).parent().parent().show();
			col4 = 1;
		}
		else
		{
			col4 = 0;
		}

		if((col2 + col3 + col4) > 0)
		{
			jQuery(paramscol1lbl).parent().parent().show();
		}
		else
		{
			jQuery(paramscol1lbl).parent().parent().hide();
		}
		if(jQuery("#sortable li#tweet").length == 1)
		{
			jQuery(twitterPanel).parent().parent().parent().parent().parent().show();
		}
	};
})( jQuery );

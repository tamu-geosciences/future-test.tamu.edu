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

		if($(imageSource).is(":checked")) {
			available = ['title','image','text','more','column2','column3','column4','tweet'];
		}

		// Joomla content as a source
		if($(joomlaSource).is(":checked")){
			available = ['title','image','text','date','category','more','column2','column3','column4','tweet','isfeatured'];
		}

		if($(k2Source).is(":checked")){
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
		$(text + ',' + image + ',' + more  + ',' + titlePanel).show();
		$(paramscol1option)[11].selected = true;
		$(paramscol2option)[3].selected = true;
		$(paramscol3option)[7].selected = true;
		$(this).presetMessage();
	}


	$.fn.setGrid = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel + ',' + gridPanel).show();
		$(this).presetMessage();
	}

	$.fn.gridPanel = function() {
		$(gridPanel).show();
	}

			$.fn.setGridFiltered = function() {
				setItems = ['image','title','text'];
				$(image + ',' + titlePanel + ',' + text  + ',' + gridPanel + ',' + filterPanel).show();
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
				$(image + ',' + titlePanel + ',' + gridPanel).show();
				$(overlaygridOption)[0].selected = true;
				$(paramsgridsperrow)[3].selected = true;
				$(paramslayoutoption)[0].selected = true;
				$(this).presetMessage();
			}


	$.fn.setList = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel).show();
		$(overlaygridOption)[1].selected = true;
		$(this).presetMessage();
	}

			$.fn.setListTwoColumn = function() {
				setItems = ['image','column2','title','text'];
				$(image + ',' + titlePanel).show();
				$(paramscol1option)[3].selected = true;
				$(paramscol2option)[7].selected = true;
				$(paramslayoutoption)[1].selected = true;

				$(this).presetMessage();

			}

			$.fn.setListThreeColumn = function() {
					setItems = ['title','date','category','column2','image','column3','text','more'];
					$(image + ',' + titlePanel +  ',' + date + ',' + more).show();
					$(paramscol1option)[3].selected = true;
					$(paramscol2option)[3].selected = true;
					$(paramscol3option)[3].selected = true;
					$(paramslayoutoption)[1].selected = true;

				$(this).presetMessage();

			}


	$.fn.setCarousel = function() {
		setItems = ['image'];
		$(image + ',' + carouselPanel).show();
		$(this).presetMessage();
	}

		$.fn.carouselPanel = function() {
			$(carouselPanel).show();
		}

	$.fn.setMasonry = function() {
		setItems = ['image','title','text'];
		$(image + ',' + titlePanel + ',' + masonryPanel).show();
		$(paramsmasonrywidths)[0].selected = true;
		$(paramsmasonrycolwidths)[3].selected = true;
		$(this).presetMessage();

	}

		$.fn.masonryPanel = function() {
			$(masonryPanel).show();
		}

	$.fn.setSlideshow = function() {
		setItems = ['image','column2','title','text'];
		$(image + ',' + titlePanel + ',' + slideshowPanel).show();
		$(paramscol1option)[3].selected = true;
		$(paramscol2option)[7].selected = true;
		$(paramsslideshowThemeOption)[2].selected = true;
		$(this).presetMessage();
	}

		$.fn.slideshowPanel = function() {
			$(slideshowPanel).show();
		}

			$.fn.setSlideshowOverlay = function() {
				setItems = ['image','title'];
				$(image + ',' + titlePanel + ',' + slideshowPanel).show();
				$(paramslayoutoption)[3].selected = true;
				$(this).presetMessage();
			}

			$.fn.setSlideshowFlat = function() {
				setItems = ['image','column2','title','text'];
				$(image + ',' + titlePanel + ',' + slideshowPanel).show();
				$(paramslayoutoption)[3].selected = true;
				$(this).presetMessage();
			}

	$.fn.setLeading = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel).show();
		$(this).presetMessage();
	}

	$.fn.setSingle = function() {
		setItems = ['image'];
		$(image + ',' + lightboxpanel).show();
		$(linkselected)[1].selected = true;
		$(this).presetMessage();

	}

	$.fn.setPagination = function() {
		setItems = ['image','title'];
		$(image + ',' + titlePanel).show();
		$(this).presetMessage();
	}

	$.fn.imagePanel = function() {
		$(image).show();
	}

	$.fn.k2Panel = function() {
		$(panelk2).show();
	}

	$.fn.joomlaPanel = function() {
		$(panelcontent).show();
	}

	$.fn.accordionPanel = function() {
		$(accordionPanel).show();
	}

	$.fn.lightboxPanel = function() {
		$(lightboxpanel).show();
	}

	$.fn.textPanel = function() {
		$(text).show();
	}

	$.fn.titlePanel = function() {
		$(titlePanel).show();
	}

	$.fn.datePanel = function() {
		$(date).show();
	}

	$.fn.morePanel = function() {
		$(more).show();
	}

	$.fn.paginationPanel = function() {
		$(pagination).show();
	}
	
	$.fn.twitterPanel = function() {
		$(twitterPanel).show();
	}

	$.fn.filterPanel = function() {
		$(filterPanel).show();
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
		if(jQuery(imageSource).is(":checked")) {
			jQuery(this).imagePanel();
			$(imageSourcePanel).show();
		}

		// K2 Source
		if (jQuery(k2Source).is(":checked")) {
			jQuery(this).k2Panel();
		}


		// Joomla content as a source
		if(jQuery(joomlaSource).is(":checked")){
			jQuery(this).joomlaPanel();
		}

		// toggle for the k2 image option
		if((jQuery(k2Source).is(":checked")) && (jQuery("#sortable li#image").length == 1) ) {

			jQuery(k2imagetypelbl + ',' + k2imagetype + ',' + k2imageoptions).show();
		}
		else {
			jQuery(k2imagetypelbl + ',' + k2imagetype + ',' + k2imageoptions).hide();
		}


			// JFancybox
		switch ($(linkselect).text()) {
			case 'Lightbox':
				jQuery(this).lightboxPanel();
			break;

			case 'External Links':
				$(this).externalLinksPanel();
			break;

			case 'Content item':
				$(this).contentLinksPanel();
				$(this).contentLinksPanel();
			break;
		}


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
		$(imageSourcePanel
			+ ',' + filterPanel
			+ ',' + masonryPanel
			+ ',' + date
			+ ',' + titlePanel
			+ ',' + text
			+ ',' + more
			+ ',' + twitterPanel
			+ ',' + lightboxpanel
			+ ',' + panelcontent
			+ ',' + panelk2
			+ ',' + image
			+ ',' + slideshowPanel
			+ ',' + columnwidthPanel
			+ ',' + carouselPanel
			+ ',' + gridPanel
			+ ',' + pagination
			+ ',' + paramscol1lbl
			+ ',' + paramscol1
			+ ',' + paramscol2lbl
			+ ',' + paramscol2
			+ ',' + paramscol3lbl
			+ ',' + paramscol3
			+ ',' + paramscol4lbl
			+ ',' + paramscol4
			+ ',' + thumbwidth
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
			+ ',' + accordionPanel
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
	};

	$.fn.layoutSwitchColumns = function() {
		if(jQuery("#sortable li#column2").length == 1)
		{
			col2 = 1;
			jQuery(paramscol2lbl + ',' + paramscol2).show();
		}
		else
		{
			col2 = 0;
		}

		if(jQuery("#sortable li#column3").length == 1)
		{
			jQuery(paramscol3lbl + ',' + paramscol3).show();
			col3 = 1;
		}
		else
		{
			col3 = 0;
		}

		if(jQuery("#sortable li#column4").length == 1)
		{
			jQuery(paramscol4lbl + ',' + paramscol4).show();
			col4 = 1;
		}
		else
		{
			col4 = 0;
		}

		if((col2 + col3 + col4) > 0)
		{
			jQuery(columnwidthPanel + ',' + paramscol1lbl + ',' + paramscol1).show();
		}
		else
		{
			jQuery(paramscol1lbl + ',' + paramscol1).hide();
		}
	};
})( jQuery );

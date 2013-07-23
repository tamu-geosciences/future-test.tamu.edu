/**
 * @package		Zen Tools
 * @subpackage	Zen Tools
 * @author		Joomla Bamboo - design@joomlabamboo.com
 * @copyright 	Copyright (c) 2013 Joomla Bamboo. All rights reserved.
 * @license		GNU General Public License version 2 or later
 * @version		1.10.5
 */

// Hide / Show relevant panels on page load
jQuery(document).ready(function() {

	// Sets the relevant selectors to associate with various parameters
	text ="#TextPanel";
	image ="#imageresizePanel";
	accordionPanel ="#accordionPanel";
	slideshowPanel ="#slideshowPanel";
	imageSourcePanel ="#imageDirectoryPanel";
	gridPanel = "#GridPanel";
	carouselPanel = "#carouselPanel";
	filterPanel = "#FilterPanel";
	masonryPanel = "#masonryPanel";
	panelcontent = "#ContentPanel";
	panelk2 = "#K2Panel";
	titlePanel = "#TitlePanel";
	more = "#ReadmorePanel";
	date = "#DatePanel";
	columnwidthPanel = "#columnwidthPanel";
	lightboxpanel = "#fancyboxPanel";
	imagedirectory = "#imageDirectoryPanel";
	imageSource = "#paramscontentSource1";
	k2Source = "#paramscontentSource3";
	joomlaSource = "#paramscontentSource2";
	lightboxActive="#paramslink1";
	linkselect="#paramslink :selected";
	linkselected="#paramslink";
	externallinks = "#paramsextlinks";
	externallinkslbl = "#paramsextlinks-lbl";
	altlinks=false;
	altlinkslbl=false;
	pagination="#paginationPanel";
	twitterPanel ="#twitterPanel";
	overlayEffect = "#paramsoverlayGrid";
	overlaygridOption = "#paramsoverlayGrid option";
	paramslayoutoption = "#paramslayout option";
	paramscol1option = "#paramscol1Width option";
	paramscol1lbl = "#paramscol1Width-lbl";
	paramscol1 = "#paramscol1Width";
	paramscol2lbl = "#paramscol2Width-lbl";
	paramscol2 = "#paramscol2Width";
	paramscol3lbl = "#paramscol3Width-lbl";
	paramscol3 = "#paramscol3Width";
	paramscol4lbl = "#paramscol4Width-lbl";
	paramscol4 = "#paramscol4Width";
	paramscol2option = "#paramscol2Width option";
	paramscol3option = "#paramscol3Width option";
	paramscol4option = "#paramscol4Width option";
	paramscatfilter = '#paramscategoryFilter option';
	paramsgridsperrow = '#paramsimagesPerRow option';
	paramsmasonrywidths = '#paramsmasonryWidths option';
	paramsmasonrywidthsselected = '#paramsmasonryWidths selected';
	paramsmasonrycolwidths = '#paramsmasonryColWidths option';
	paramsslideshowThemeOption = '#paramsslideshowTheme option';
	paramsoverlayAnimation = '#paramsoverlayAnimation1';
	paramslink1 = '#paramslink1';
	paramsmodalTitle0 = "#paramsmodalTitle";
	paramsmodalText0 = "#paramsmodalText";
	paramsmodalVideo0 = "#paramsmodalVideo";
	paramslayoutSelected = "#paramslayout :selected";
	paramsslideshowPaginationType = "#paramsslideshowPaginationType :selected";
	thumbwidth = "#paramsthumb_width";
	thumbwidthlbl = "#paramsthumb_width-lbl";
	thumbheight = "#paramsthumb_height";
	thumbheightlbl = "#paramsthumb_height-lbl";
	slideTitleWidth = "#paramsslideTitleWidth";
	slideTitleWidthlbl = "#paramsslideTitleWidth-lbl";
	itemsperpage = "#paramsitemsperpage";
	itemsperpagelbl = "#paramsitemsperpage-lbl";
	slideTitleTheme = "#paramsslideshowTitleTheme";
	slideTitleThemelbl = "#paramsslideshowTitleTheme-lbl";
	slideTitleBreak = "#paramsslideTitleHide";
	slideTitleBreaklbl = "#paramsslideTitleHide-lbl";
	linktargetlbl= "#paramslinktarget-lbl";
	linktarget= "#paramslinktarget";
	k2imagetypelbl = "#paramsitemImgSize-lbl";
	k2imagetype = "#paramsitemImgSize";
	k2imageoptions =".k2imageoptions";

	// Param where we store the information
	current = "#paramsuseditems";
	params = "#paramslayout";

	// Sortable Lists
	usedList = "#sortable";
	unusedList = "#sortable2";

	// Variables for sortable list elements
	var column2 = 0;
	var column3 = 0;
	var column4 = 0;

		col2 = 0;
		col3 = 0;
		col4 = 0;


	// Reinstates toggle in the header
	jQuery('h3').click(function() {
			jQuery(this).next('.zentools').slideToggle();
	});


	// Set all panels closed on load
	jQuery('div.zentools').slideUp();


	// Toggle all for slides
	jQuery('#toggleAll a').click(function() {
			jQuery('div.zentools').slideToggle();
	});


	jQuery(this).availableTags();
	jQuery(this).initSortables();

	// This sets the layout type correctly if the user exited the module last time without applying the preset
	switch (jQuery('#paramslayout :selected').text()) {


			case '[Preset] Two column grid':
				jQuery('#paramslayout option')[0].selected = true;
			break;

			case '[Preset] Filtered grid':
				jQuery('#paramslayout option')[0].selected = true;
			break;

			case '[Preset] Overlay Slideshow':
				jQuery('#paramslayout option')[4].selected = true;
			break;

			case '[Preset] Flat Slideshow':
				jQuery('#paramslayout option')[4].selected = true;
			break;

			case '[Preset] Two column list':
				jQuery('#paramslayout option')[3].selected = true;
			break;

			case '[Preset] Three column list':
				jQuery('#paramslayout option')[3].selected = true;
			break;

			case '[Preset] Captify content grid':
				jQuery('#paramslayout option')[0].selected = true;
			break;

	};


	// Action for the set default item or apply preset button
	jQuery('#Default a').click(function(e) {

		// Cancel the default action
		e.preventDefault();

		// Hide all open panels
		jQuery(this).hideAllPanels();

		// Figure out which default to set
		switch (jQuery('#paramslayout :selected').text()) {

			// Accordion
			case 'Accordion':
				jQuery(this).setAccordion();// Items used in the default accordion layout
				jQuery(this).accordionPanel();
			break;

			// Grid
			case 'Grid':
				jQuery(this).setGrid();
				jQuery(this).gridPanel();
			break;

				case '[Preset] Two column grid':
					jQuery(this).setGridTwoColumns();
				break;

				case '[Preset] Filtered grid':
					jQuery(this).setGridFiltered();
				break;

				case '[Preset] Captify content grid':
					jQuery(this).setGridCaptify();
				break;

			// Carousel
			case 'Carousel':
				jQuery(this).setCarousel();
			break;

			// Masonry
			case 'Masonry':
				jQuery(this).setMasonry();
			break;

			// Slideshow
			case 'Slideshow':
				jQuery(this).setSlideshow();
			break;

				case '[Preset] Overlay Slideshow':
					jQuery(this).setSlideshowOverlay();
				break;

				case '[Preset] Flat Slideshow':
					jQuery(this).setSlideshowFlat();
				break;

			// List
			case 'List':
				jQuery(this).setList();
			break;

				case '[Preset] Two column list':
					jQuery(this).setListTwoColumn();
				break;

				case '[Preset] Three column list':
					jQuery(this).setListThreeColumn();
				break;

				case '[Preset] Four column list':
					jQuery(this).setListFourColumn();
				break;


			// Single Item
			case 'Single Item Gallery':
				jQuery(this).setSingle();
			break;

			// Pagination
			case 'Pagination':
				jQuery(this).setPagination();
			break;

			// Leading Items
			case 'Leading item then list':
				jQuery(this).setLeading();
			break;
		};

		// The main function that sets the correct values for the lists
		jQuery(this).setSortables();

		// Layout Switch
		jQuery(this).layoutSwitch();
	});


	jQuery(".panel input").change(function () {
		// The main function that sets the correct values for the lists
	//	jQuery(this).setSortables();
	});


	// Hide all of the elements
	jQuery(this).hideAllPanels();

	// Initialises sortables and checks if values are assigned correctly.
	jQuery(this).setSortables();

	// Layout Switch
	jQuery(this).layoutSwitch();

	// Slideshow Pagination type
	switch (jQuery(paramsslideshowPaginationType).text()) {
			case 'Thumbs':
				jQuery(thumbwidth + ',' + thumbwidthlbl  + ',' + thumbheight  + ',' + thumbheightlbl).show();
			break;
			case 'Titles':
				jQuery(slideTitleWidth + ',' + slideTitleWidthlbl + ',' + slideTitleTheme + ',' + slideTitleThemelbl  + ',' + slideTitleBreak  + ',' + slideTitleBreaklbl ).show();
			break;
	};

	switch(jQuery(paramsmasonrywidthsselected).text()) {
	case 'Use widths specified in meta keywords':
		jQuery("#paramsmasonryColumnWidth-lbl,#paramsmasonryColumnWidth,#paramsmasonryGutter-lbl,#paramsmasonryGutter,#paramsmasonryColWidths-lbl,#paramsmasonryColWidths").show();
		jQuery("#paramsmasonryColWidths-lbl,#paramsmasonryColWidths").hide();

	break;
	case 'Equalise widths of all elements':
								jQuery("#paramsmasonryColumnWidth-lbl,#paramsmasonryColumnWidth,#paramsmasonryGutter-lbl,#paramsmasonryGutter,#paramsmasonryColWidths-lbl,#paramsmasonryColWidths").hide();
	jQuery("#paramsmasonryColWidths-lbl,#paramsmasonryColWidths").show();

	break;
	}

	// Toggle for k2 content source
	switch(jQuery('#paramsk2contentSource :selected').text()) {
		case 'Items':
			jQuery("#paramsitemid,#paramsitemid-lbl").show();
			jQuery("#paramscategory_id,#paramscategory_id-lbl,#paramsgetChildren,#paramsgetChildren-lbl").hide();

		break;
		case 'Categories':
			jQuery("#paramsitemid,#paramsitemid-lbl").hide();
			jQuery("#paramscategory_id,#paramscategory_id-lbl,#paramsgetChildren,#paramsgetChildren-lbl").show();
		break;
	}


	// Toggle for k2 content source
	switch(jQuery('#paramsjoomlaContentSource :selected').text()) {
		case 'Items':
			jQuery("#paramsartids,#paramsartids-lbl").show();
			jQuery("#paramscatid,#paramscatid-lbl").hide();

		break;
		case 'Categories':
			jQuery("#paramsartids,#paramsartids-lbl").hide();
			jQuery("#paramscatid,#paramscatid-lbl").show();
		break;
	}


	// toggle for the k2 image option
	if((jQuery("#paramscontentSource3").is(":checked")) && (jQuery("#sortable li#image").length == 1) ) {

		jQuery("#paramsitemImgSize,#paramsitemImgSize-lbl").show();
	}
	else {
		jQuery("#paramsitemImgSize,#paramsitemImgSize-lbl").hide();
	}

});


// The following is the basic sort and order script
//
//
//
// set the list selector
var setSelector = "#sortable";
var setSelector2 = "#sortable2";


// function that writes the list order to a cookie
function getOrder() {
	// save custom order to cookie
	jQuery("#paramsuseditems").val(jQuery(usedList).sortable("toArray"));
	jQuery("#paramsunuseditems").val(jQuery(unusedList).sortable("toArray"));
}


// function that restores the list order from a cookie
function restoreOrder() {
	var list = jQuery(setSelector);
	if (list == null) return

	// fetch the cookie value (saved order)
	var useditems = jQuery("#paramsuseditems").val();
	var unuseditems = jQuery("#paramsunuseditems").val();
		if (!useditems) return;

	// make array from saved order
	var IDs = useditems.split(",");
	var unusedIDs = unuseditems.split(",");


	// fetch current order
	var items = list.sortable("toArray");

	// make array from current order
	var rebuild = new Array();
	for ( var v=0, len=IDs.length; v<len; v++ ){
		rebuild[IDs[v]] = IDs[v];
	}

	for (var i = 0, n = IDs.length; i < n; i++) {

		// item id from saved order
		var itemID = IDs[i];

		if (itemID in rebuild) {

			// select item id from current order
			var item = rebuild[itemID];

			// select the item according to current order
			var child = jQuery("ui-sortable").children("#" + item);

			// select the item according to the saved order
			var savedOrd = jQuery("ui-sortable").children("#" + itemID);

			// remove all the items
			child.remove();

			// add the items in turn according to saved order
			// we need to filter here since the "ui-sortable"
			// class is applied to all ul elements and we
			// only want the very first!  You can modify this
			// to support multiple lists - not tested!
			jQuery("ul.ui-sortable").filter(":first").append(savedOrd);


		}
	}
}


// code executed when the document loads
jQuery(function() {

	setSelector="#sortable";
	setSelector2="#sortable2";
	// here, we allow the user to sort the items
	jQuery(setSelector).sortable({

			connectWith: ".connectedLists",
			cursor: "move",
			placeholder: "ui-state-highlight",
			scope: "tags",
			opacity: 0.8,
			dropOnEmpty: true,
			items: "li:not(.disabled)",
			revert: 200,

			update: function(event,ui) {

				jQuery("#zenmessage p").html("").show();
				jQuery("#zenmessage p").html("Settings updated").delay(600).fadeOut(300);

				// Layout Switch
				jQuery(this).layoutSwitch();

				getOrder();

				jQuery("li#empty").remove();

				var itemOrder = jQuery("#paramsuseditems").val();

			}
	});

	jQuery(setSelector2).sortable({
			cursor: "move",
			placeholder: "ui-state-highlight",
			scope: "tags",
			opacity: 0.8,
			dropOnEmpty: true,
			items: "li:not(.disabled)",
			revert: 200
	});


	jQuery("#sortable2,#sortable").sortable({
		connectWith: jQuery("#sortable,#sortable2")
	});

	// here, we reload the saved order
	restoreOrder();
});

jQuery(document).ready(function() {
	jQuery(".panel input,.panel select").change(function () {
		// Layout Switch
		jQuery(this).layoutSwitch();
	});

	function setFilterPanel()
	{
		if (jQuery('#paramscontentSource1').is(':checked'))
		{
			// Directory
			jQuery('#FilterPanel').hide();
		}
		else if (jQuery('#paramscontentSource2').is(':checked'))
		{
			// Joomla
			jQuery('#FilterPanel').show();
			jQuery('#paramsfilterstart_joomla-lbl').parent().parent().parent().show();
			jQuery('#paramsfilterstart_k2-lbl').parent().parent().parent().hide();
		}
		else if (jQuery('#paramscontentSource3').is(':checked'))
		{
			// K2
			jQuery('#FilterPanel').show();
			jQuery('#paramsfilterstart_joomla-lbl').parent().parent().parent().hide();
			jQuery('#paramsfilterstart_k2-lbl').parent().parent().parent().show();
		}
	}
	setFilterPanel();


	jQuery("#paramscontentSource1, #paramslayout, #paramscontentSource2, #paramscontentSource3").change(function () {

		jQuery("#zenmessage p").html("").show();
		jQuery("#zenmessage p").html("Settings updated").delay(600).fadeOut(300);

		// Layout Switch
		jQuery(this).layoutSwitch();

		// Slideshow Pagination type
		switch (jQuery(paramsslideshowPaginationType).text()) {
				case 'Thumbs':
					jQuery(thumbwidth + ',' + thumbwidthlbl  + ',' + thumbheight  + ',' + thumbheightlbl).show();
				break;
				case 'Titles':
					jQuery(slideTitleWidth + ',' + slideTitleWidthlbl + ',' + slideTitleTheme + ',' + slideTitleThemelbl  + ',' + slideTitleBreak  + ',' + slideTitleBreaklbl ).show();
				break;
		};


				switch(jQuery('#paramsmasonryWidths :selected').text()) {
				case 'Use widths specified in meta keywords':
					jQuery("#paramsmasonryColumnWidth-lbl,#paramsmasonryColumnWidth,#paramsmasonryGutter-lbl,#paramsmasonryGutter,#paramsmasonryColWidths-lbl,#paramsmasonryColWidths").show();
					jQuery("#paramsmasonryColWidths-lbl,#paramsmasonryColWidths").hide();

				break;
				case 'Equalise widths of all elements':
											jQuery("#paramsmasonryColumnWidth-lbl,#paramsmasonryColumnWidth,#paramsmasonryGutter-lbl,#paramsmasonryGutter,#paramsmasonryColWidths-lbl,#paramsmasonryColWidths").hide();
				jQuery("#paramsmasonryColWidths-lbl,#paramsmasonryColWidths").show();

				break;
				}

				// Toggle for k2 content source
				switch(jQuery('#paramsk2contentSource :selected').text()) {
					case 'Items':
						jQuery("#paramsitemid,#paramsitemid-lbl").show();
						jQuery("#paramscategory_id,#paramscategory_id-lbl,#paramsgetChildren,#paramsgetChildren-lbl").hide();
						jQuery(this).setSortables();

					break;
					case 'Categories':
						jQuery("#paramsitemid,#paramsitemid-lbl").hide();
						jQuery("#paramscategory_id,#paramscategory_id-lbl,#paramsgetChildren,#paramsgetChildren-lbl").show();
						jQuery(this).setSortables();
					break;
				}


				// Toggle for k2 content source
				switch(jQuery('#paramsjoomlaContentSource :selected').text()) {
					case 'Items':
						jQuery("#paramsartids,#paramsartids-lbl").show();
						jQuery("#paramscatid,#paramscatid-lbl").hide();
						jQuery(this).setSortables();

					break;
					case 'Categories':
						jQuery("#paramsartids,#paramsartids-lbl").hide();
						jQuery("#paramscatid,#paramscatid-lbl").show();
						jQuery(this).setSortables();
					break;
				}


				// toggle for the k2 image option
				if((jQuery("#paramscontentSource3").is(":checked")) && (jQuery("#sortable li#image").length == 1) ) {

					jQuery("#paramsitemImgSize,#paramsitemImgSize-lbl").show();
				}
				else {
					jQuery("#paramsitemImgSize,#paramsitemImgSize-lbl").hide();
				}

					// This sets the layout type correctly if the user exited the module last time without applying the preset
					switch (jQuery(paramslayoutSelected).text()) {


							case '[Preset] Two column grid':
								jQuery(this).clickDefault();
							break;

							case '[Preset] Filtered grid':
								jQuery(this).clickDefault();
							break;

							case '[Preset] Overlay Slideshow':
								jQuery(this).clickDefault();
							break;

							case '[Preset] Flat Slideshow':
								jQuery(this).clickDefault();
							break;

							case '[Preset] Two column list':
								jQuery(this).clickDefault();
							break;

							case '[Preset] Three column list':
								jQuery(this).clickDefault();
							break;

							case '[Preset] Captify content grid':
								jQuery(this).clickDefault();
							break;

					};

		setFilterPanel();
	});
});

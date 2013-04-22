var onLoad = {
    init: function(){
    
    "use strict";

	jQuery('body').append('<div class="style-switcher"><h4>Style Switcher<a class="switch-button"><i class="icon-cog"></i></a></h4><div class="switch-cont"><h5>Layout options</h5><ul class="options layout-select"><li><a class="boxed" href="#"><img src="http://themes.swiftpsd.com/shared/styleswitcher/page-bordered.png"/></a></li><li class="selected"><a class="fullwidth" href="#"><img src="http://themes.swiftpsd.com/shared/styleswitcher/page-fullwidth.png"/></a></li></ul><h5>Header Examples</h5><div class="options header-select"><select><option value="header-1">Header Example 1</option><option value="header-2">Header Example 2</option><option value="header-3">Header Example 3</option><option value="header-4">Header Example 4</option><option value="header-5">Header Example 5 (Dark)</option></select></div><h5>Background Examples (boxed-only)</h5><ul class="options bg-select"><li><a href="#" data-bgimage="tileable_wood_texture.png" class="pattern"><img src="http://themes.swiftpsd.com/shared/styleswitcher/preset-backgrounds/tileable_wood_texture.png" alt="wood"/></a></li><li><a href="#" data-bgimage="detailed.png" class="pattern"><img src="http://themes.swiftpsd.com/shared/styleswitcher/preset-backgrounds/detailed.png" alt="detailed"/></a></li><li><a href="#" data-bgimage="px_by_Gre3g.png" class="pattern"><img src="http://themes.swiftpsd.com/shared/styleswitcher/preset-backgrounds/px_by_Gre3g.png" alt="pixels"/></a></li><li><a href="#" data-bgimage="diagonal-noise.png" class="pattern"><img src="http://themes.swiftpsd.com/shared/styleswitcher/preset-backgrounds/diagonal-noise.png" alt="diagonal-noise"/></a></li><li><a href="#" data-bgimage="dark_wood.png" class="pattern"><img src="http://themes.swiftpsd.com/shared/styleswitcher/preset-backgrounds/dark_wood.png" alt="dark_wood"/></a></li><li><a href="#" data-bgimage="swoosh_b&w.jpg" class="cover"><img src="http://themes.swiftpsd.com/shared/styleswitcher/swoosh_b&w_thumb.jpg" alt="swoosh_b&w"/></a></li><li><a href="#" data-bgimage="swoosh_colour.jpg" class="cover"><img src="http://themes.swiftpsd.com/shared/styleswitcher/swoosh_colour_thumb.jpg" alt="swoosh_colour"/></a></li><li><a href="#" data-bgimage="beach.jpg" class="cover"><img src="http://themes.swiftpsd.com/shared/styleswitcher/beach_thumb.jpg" alt="beach"/></a></li><li><a href="#" data-bgimage="L1040896.jpg" class="cover"><img src="http://themes.swiftpsd.com/shared/styleswitcher/L1040896_thumb.jpg" alt="sundown"/></a></li></ul><a class="many-more" href="http://themes.swiftpsd.com/flexform/features/">Many more options included &rarr;</a></div></div>');

	jQuery('.style-switcher').on('click', 'a.switch-button', function(e) {
		e.preventDefault();
		var $style_switcher = jQuery('.style-switcher');
		if ($style_switcher.css('left') === '0px') {
			$style_switcher.animate({
				left: '-240'
			});
		} else {
			$style_switcher.animate({
				left: '0'
			});
		}
	});

	jQuery('.layout-select li').on('click', 'a', function(e) {
		e.preventDefault();
		jQuery('.layout-select li').removeClass('selected');
		jQuery(this).parent().addClass('selected');
		var selectedLayout = jQuery(this).attr('class');
		
		if (selectedLayout === "boxed") {
			jQuery("#container").addClass('boxed-layout');
		} else {
			jQuery("#container").removeClass('boxed-layout');
		}
		
		jQuery('.flexslider').each(function() {
			var slider = jQuery(this).data('flexslider');
			if (slider) {
			slider.resize();
			}
		});
		jQuery(window).resize();
	});
	
	var headerSection = jQuery('#header-section');
	var topBar = jQuery('#top-bar');
	var topBarSocial = topBar.find('#top-bar-social');
	var topBarSocialContent = '<ul class="social-icons small dark"><li class="dribbble"><a href="http://www.dribbble.com/" target="_blank">Dribbble</a></li><li class="facebook"><a href="" target="_blank">Facebook</a></li><li class="twitter"><a href="http://www.twitter.com/" target="_blank">Twitter</a></li><li class="vimeo"><a href="http://www.vimeo.com/" target="_blank">Vimeo</a></li><li class="youtube"><a href="http://www.youtube.com/user/" target="_blank">YouTube</a></li></ul>';

	jQuery('.header-select select').change(function() {
		if (jQuery(this).val() === "header-1") {
			topBar.css('display', 'block');
			headerSection.removeClass('logo-right');
			headerSection.addClass('logo-left');
			topBar.removeClass('top-bar-menu-left');
			topBar.addClass('top-bar-menu-right');
			topBarSocial.html(topBarSocialContent);
			headerColours('light');
		} else if (jQuery(this).val() === "header-2") {
			topBar.css('display', 'block');
			headerSection.removeClass('logo-right');
			headerSection.addClass('logo-left');
			topBar.removeClass('top-bar-menu-left');
			topBar.addClass('top-bar-menu-right');
			topBarSocial.html('Everything you need to build something exceptional.');
			jQuery('body').addClass('styleswitcher-tbdarkstyle');
			topBar.css({
				'background': '#000000',
				'border-bottom-color': '#333333'
			});
			topBar.find('li').css({
				'border-color': '#333333'
			});
			topBar.find('#top-bar-menu .menu ul').css({
				'background': '#222222',
				'border-color': '#333333'
			});
			jQuery('#top-bar-menu .menu li.current-menu-ancestor > a,#top-bar-menu .menu li.current-menu-item > a,#header-languages .current-language span').css('color', '#ffffff');
			topBar.find('#top-bar-social ul').removeClass('dark');
			topBar.find('#top-bar-social ul').addClass('light');
		} else if (jQuery(this).val() === "header-3") {
			topBar.css('display', 'block');
			headerSection.removeClass('logo-left');
			headerSection.addClass('logo-right');
			topBar.removeClass('top-bar-menu-right');
			topBar.addClass('top-bar-menu-left');
			topBarSocial.html(topBarSocialContent);
			headerColours('light');
		} else if (jQuery(this).val() === "header-4") {
			topBar.css('display', 'none');
			headerSection.removeClass('logo-right');
			headerSection.addClass('logo-left');
			topBar.removeClass('top-bar-menu-left');
			topBar.addClass('top-bar-menu-right');
			topBarSocial.html(topBarSocialContent);
			headerColours('light');
		} else if (jQuery(this).val() === "header-5") {
			topBar.css('display', 'block');
			headerSection.removeClass('logo-right');
			headerSection.addClass('logo-left');
			topBar.removeClass('top-bar-menu-left');
			topBar.addClass('top-bar-menu-right');
			topBarSocial.html(topBarSocialContent);
			headerColours('dark');
		}
	});
	
	var headerColours = function(style) {
		if (style === 'dark') {
			jQuery('body').addClass('styleswitcher-darkstyle');
			headerSection.css({
				'background': '#222222',
				'border-bottom-color': '#333333'
			});
			topBar.css({
				'background': '#000000',
				'border-bottom-color': '#333333'
			});
			topBar.find('li').css({
				'border-color': '#333333'
			});
			jQuery('#nav-search').css({
				'background': '#000000',
				'color': '#666666'
			});
			jQuery('nav .menu ul').css({
				'background': '#222222',
				'border-color': '#333333'
			});
			jQuery('nav .menu li.current-menu-ancestor > a,nav .menu li.current-menu-item > a,#header-languages .current-language span').css('color', '#ffffff');
			topBar.find('#top-bar-social ul').removeClass('dark');
			topBar.find('#top-bar-social ul').addClass('light');				
		} else {
			jQuery('body').removeClass('styleswitcher-darkstyle');
			jQuery('body').removeClass('styleswitcher-tbdarkstyle');
			headerSection.css({
				'background': '',
				'border-bottom-color': ''
			});
			topBar.css({
				'background': '',
				'border-bottom-color': ''
			});
			topBar.find('li').css({
				'border-color': ''
			});
			jQuery('#nav-search').css({
				'background': '',
				'color': ''
			});
			jQuery('nav .menu ul').css({
				'background': '',
				'border-color': ''
			});
			jQuery('nav .menu li.current-menu-ancestor > a,nav .menu li.current-menu-item > a').css('color', '');
			topBar.find('#top-bar-social ul').removeClass('light');
			topBar.find('#top-bar-social ul').addClass('dark');	
		}
	};
	
	jQuery('.bg-select li').on('click', 'a', function(e) {
		e.preventDefault();
		var newBackground = jQuery(this).attr('data-bgimage'),
			bgType = jQuery(this).attr('class');
				
		if (bgType === "cover") {
			jQuery('body').css('background', 'url(http://themes.swiftpsd.com/shared/styleswitcher/'+newBackground+') no-repeat center top fixed');
			jQuery('body').css('background-size', 'cover');
		} else {
			jQuery('body').css('background', 'url(http://themes.swiftpsd.com/shared/styleswitcher/preset-backgrounds/'+newBackground+') repeat center top fixed');
			jQuery('body').css('background-size', 'auto');
		}
	});
	
    }
};

jQuery(document).ready(onLoad.init);
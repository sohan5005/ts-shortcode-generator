(function() {
	'use strict';

	if( typeof generateRandomID === 'undefined' ) {
		window.generateRandomID = function( prefix, postfix ) {

			var genID = Math.floor((Math.random() * 500000) + 100000);

			prefix = ( typeof prefix === 'undefined' ? '' : prefix );
			postfix = ( typeof postfix === 'undefined' ? '' : postfix );

			while( jQuery('#' + prefix + genID + postfix).length !== 0 ) {
				genID = Math.floor((Math.random() * 500000) + 100000);
			}

			return prefix + genID + postfix;

		};
	}

	if( typeof tsg_windows === 'undefined' ) {
		window.tsg_windows = {};
	}

	function addGeneratorInstance( args ) {
		tinymce.create( 'tinymce.plugins.' + args.name, {
			init: function(ed, url) {

				ed.addCommand( args.name + '_cmd', function() {
					(function($) {
						var tsgID = generateRandomID(),
							tsgIDattr = 'tsg-window-' + tsgID,
							obj = {
								id: tsgID,
								elm: $('#' + tsgIDattr),
								activeEditor: '',
								prevEditor: tinyMCE.activeEditor
							};

						window.tsg_windows[tsgID] = obj;

						if( $('#ts-generator-root').children().length === 0 ) {

							$('#ts-generator-root').append('<div id="' + tsgIDattr + '" data-id="' + tsgID + '" class="tsg-window-root active-now"><div class="tsg-content"></div></div>');

						} else {

							$('#ts-generator-root').children('.active-now').fadeOut().removeClass('active-now');

							$('#ts-generator-root').append('<div id="' + tsgIDattr + '" data-id="' + tsgID + '" class="tsg-window-root active-now"><div class="tsg-content"></div></div>');

						}

						$('#ts-generator-overlay, #ts-generator-overlay .tsg-loader').fadeIn();

						if( !$('body').hasClass('modal-open') ) {
							$('body').addClass('modal-open');
						}

						$.ajax({
							type: "GET",
							url: ajaxurl,
							dataType: "html",
							data: {
								action : 'ts_shortcode_generator_' + args.name,
								id : tsgID
							},
							success: function(data) {
								if( $('#ts-generator-overlay').is(':visible') ) {
									$('#ts-generator-root').show();
									$('#' + tsgIDattr + ' .tsg-content').html(data);
									$('#' + tsgIDattr).fadeIn();
									$('#ts-generator-overlay .tsg-loader').fadeOut();
									$(window).trigger('tsg_pre_init');
									$(window).trigger('tsg_init');
									$(window).trigger('tsg_after_init');
									$(window).trigger('tsg_refresh_views');
								}
							}
						});

					})(jQuery);

				});

				ed.addButton( args.name, {
					title: args.title,
					cmd: args.name + '_cmd',
					image: args.icon,
					stateSelector: 'IMG'
				});
			},
			getInfo: function() {
				return {
					longname: args.name,
					author: args.author,
					authorurl: args.website,
					version: args.version
				};
			}
		});

		tinymce.PluginManager.add( args.name, tinymce.plugins[args.name] );
	}

	if( typeof ts_shortcode_generator_instances === 'undefined' ) {
		return;
	}

	if( ts_shortcode_generator_instances == null ) {
		return;
	}

	for( var i = 0; i < ts_shortcode_generator_instances.length; i++ ) {
		addGeneratorInstance( ts_shortcode_generator_instances[i] );
	}

})();
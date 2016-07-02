//
// All scripts here are used for interaction to main UI
//

(function($) {

	"use strict";

	function breakEditors( root ) {
		var textareaID;
		root.find('textarea.wp-editor-area').each(function() {
			textareaID = $(this).attr('id');
			try { tinyMCE.execCommand('mceRemoveEditor', false, textareaID); } catch(e){}
		});
	}

	function closeTSGpopup() {

		var currentWindow = $('#ts-generator-root').children('.active-now'),
			currentID = currentWindow.data('id');

		if( $('#ts-generator-root').children().length === 1 ) {

			$('#ts-generator-overlay, #ts-generator-root').fadeOut(250);

			setTimeout(function() {
				breakEditors( currentWindow );
				currentWindow.remove();
			}, 250);

			$('body').removeClass('modal-open');

		} else {

			var previousWindow = currentWindow.prev();

			currentWindow.fadeOut(250).removeClass('active-now');
			previousWindow.fadeIn().addClass('active-now');

			setTimeout(function() {
				breakEditors( currentWindow );
				currentWindow.remove();
			}, 250);

		}

		delete window.tsg_windows[currentID];

	}

	function callModuleViewAJAX( tagName, win ) {
		var dataNew,
			loader = win.find('.tsg-shortcodes-loading');
		loader.fadeIn();
		$.ajax({
			type: "GET",
			url: ajaxurl,
			dataType: "html",
			data: {
				action : 'ts_ajax_module_view_' + win.data('name'),
				ts_tag : tagName
			},
			success: function(data) {
				loader.fadeOut();
				dataNew = win.find('.ts-scg-views-canvas-root').append(data).hide();
				dataNew.fadeIn(250);
				$(window).trigger('tsg_pre_init');
				$(window).trigger('tsg_init');
				$(window).trigger('tsg_after_init');
				setTimeout(function() {
					win.animate({
						scrollTop : win.find('.ts-scg-groups-tabs').height() + win.find('.ts-scg-shortcodes-thumbs').height() + 30
					});
				}, 250);
			}
		});
	}

	function refreshShortcodeModulesVisiblity() {

		$('.tsg-window-root.active-now .ts-generator-root-js').each(function() {

			if( $(this).find('.ts-scg-groups-shortcodes li.active').length === 0 ) {
				$(this).find('.ts-scg-views-canvas-root > .ts-scg-shortcode-item-root').each(function() {
					breakEditors( $(this) );
				}).fadeOut(250).delay(250).remove();
				$(this).find('.tsg-no-shortcodes-text').delay(250).fadeIn(250);
			} else {
				var targetID = $(this).find('.ts-scg-groups-shortcodes li.active').data('target-shortcode'),
					winRoot = $(this);
				$(this).find('.tsg-no-shortcodes-text').fadeOut();
				if( $(this).find('.ts-scg-views-canvas-root > .ts-scg-shortcode-item-root').length === 1 ) {
					if( $(this).find('.ts-scg-views-canvas-root > .ts-scg-shortcode-item-root').data('shortcode-tag') === targetID ) {
						return;
					}
					$(this).find('.ts-scg-views-canvas-root > .ts-scg-shortcode-item-root').each(function() {
						breakEditors( $(this) );
					}).fadeOut(250).delay(250).remove();
					setTimeout(function() {
						callModuleViewAJAX( targetID, winRoot );
					}, 250);
				} else {
					callModuleViewAJAX( targetID, winRoot );
				}
			}

		});

	}

	$(window).on( 'tsg_refresh_views', refreshShortcodeModulesVisiblity );

	function parseTScodeFromRoot( root ) {

		var tag = root.data('shortcode-tag'),
			noEnd = root.data('no-ending-tag'),
			cont = root.data('content'),
			modules = root.children('.tsg-modules-wrapper').children('.tsg-module-inner'),
			output = '',
			atts, attrName, attrVal, attrDef , contRoot, contVal;

		modules.each(function() {

			atts = $(this).children('.ts-scg-shortcode-item-atts').children('.ts-scg-attr-item');

			output += '[' + tag;

			atts.each(function() {

				attrVal = '';
				attrDef = '';

				attrName = $(this).data('attr-name');
				attrDef += $(this).data('attr-default');
				attrVal += $(this).find('.ts-scg-value-collector').val();

				if( attrVal !== attrDef ) {
					output += ' ' + attrName + '="' + attrVal + '"';
				}

			});

			output += ']';

			if( noEnd !== true ) {
				contRoot = $(this).children('.ts-scg-shortcode-item-content');
				if( cont === 'mixed' ) {
					contVal = '';
					contRoot.children('.tsg-mixed-shortcode-content-module').each(function() {
						var moduleType = $(this).data('type'),
							moduleRoot = $(this);
						switch( moduleType ) {
								case 'shortcode' :
								contVal += parseTScodeFromRootRecall( moduleRoot.children('.ts-scg-shortcode-item-root') );
								break;
								case 'richedit' :
								var richeditID = moduleRoot.find('textarea.wp-editor-area').attr('id');
								contVal += tinyMCE.get(richeditID).getContent();
								break;
								default :
								contVal += moduleRoot.find('.ts-scg-value-collector').val();
								break;
						}
					});
				} else if( cont === 'shortcode' ) {
					contVal = parseTScodeFromRootRecall( contRoot.children('.ts-scg-shortcode-item-root') );
				} else if( cont === 'richedit' ) {
					var richeditID = contRoot.find('textarea.wp-editor-area').attr('id');
					contVal = tinyMCE.get(richeditID).getContent();
				} else if( cont == false ) {
					contVal = '';
				} else {
					contVal = contRoot.find('.ts-scg-value-collector').val();
				}
				output += contVal;
				output += '[/' + tag + ']';
			}
		});

		return output;

	}

	function parseTScodeFromRootRecall( root ) {

		var output = parseTScodeFromRoot( root );
		return output;

		var tag = root.data('shortcode-tag'),
			noEnd = root.data('no-ending-tag'),
			cont = root.data('content'),
			modules = root.children('.tsg-modules-wrapper').children('.tsg-module-inner'),
			output = '',
			atts, attrName, attrVal, attrDef, contRoot, contVal;

		modules.each(function() {

			atts = $(this).children('.ts-scg-shortcode-item-atts').children('.ts-scg-attr-item');

			output += '[' + tag;

			atts.each(function() {

				attrName = $(this).data('attr-name');
				attrDef = $(this).data('attr-default');
				attrVal = $(this).find('.ts-scg-value-collector').val();

				if( attrVal !== attrDef ) {
					output += ' ' + attrName + '="' + attrVal + '"';
				}

			});

			output += ']';

			if( noEnd !== true ) {
				contRoot = $(this).children('.ts-scg-shortcode-item-content');
				if( cont === 'shortcode' ) {
					contVal = parseTScodeFromRoot( contRoot.children('.ts-scg-shortcode-item-root') );
				} else if( cont === 'richedit' ) {
					var richeditID = contRoot.find('textarea.wp-editor-area').attr('id');
					contVal = tinyMCE.get(richeditID).getContent();
				} else {
					contVal = contRoot.find('.ts-scg-value-collector').val();
				}
				output += contVal;
				output += '[/' + tag + ']';
			}
		});

		return output;

	}

	$(document).ready(function() {

		$(window).on('tsg_init', function() {

			$('.ts-scg-groups-shortcodes.active').fadeIn(150);

			$('.ts-scg-shortcode-item-atts').each(function() {
				if( $(this).hasClass('tsg-atts-manager-initiated') ) {
					return;
				}
				var more = $(this).children('.ts-scg-shortcode-item-more-atts'),
					norm = $(this).children('.ts-scg-attr-item').not('.ts-shortcode-advanced-atts'),
					advs = $(this).children('.ts-shortcode-advanced-atts'),
					cont = $(this);
				if( advs.length > 0 && norm.length > 0 ) {
					more.show();
					advs.hide();
					cont.addClass('has-more-atts');
					more.click(function() {
						if( $(this).hasClass('tsg-more-atts-closed') && !$(this).hasClass('tsg-more-atts-opened') ) {
							$(this).removeClass('tsg-more-atts-closed').addClass('tsg-more-atts-opened');
							advs.slideDown().addClass('blink');
						} else {
							$(this).removeClass('tsg-more-atts-opened').addClass('tsg-more-atts-closed');
							advs.slideUp().removeClass('blink');
						}
					});
				}
				$(this).addClass('tsg-atts-manager-initiated');
			});

		});

		$(document).on( 'click', '.ts-scg-groups-tabs li', function() {

			var target = $(this).data('target'),
				targetGroup = $(this).closest('.ts-generator-root-js').find('.ts-scg-groups-shortcodes[data-group-id=' + target + ']');

			$(this).siblings().removeClass('active');
			$(this).addClass('active');

			targetGroup.siblings().removeClass('active').fadeOut(150);
			targetGroup.addClass('active').delay(150).fadeIn(150);

		});

		$(document).on( 'click', '.ts-scg-groups-shortcodes li', function() {
			if( $(this).hasClass('active') ) {
				$(this).removeClass('active');
			} else {
				$(this).closest('.ts-generator-root-js').find('.ts-scg-groups-shortcodes li').removeClass('active');
				$(this).addClass('active');
			}
			refreshShortcodeModulesVisiblity();
		});

		$(document).on( 'click', '.ts-reinit-sc', function() {
			$(window).trigger('tsg_init');
		});

		$(document).on( 'click', '.close-ts-generator, #ts-generator-overlay, .ts-cancel-sc', function() {

			closeTSGpopup();

		});

		$(document).on( 'click', '.button.ts_add_sc', function() {

			var currentRoot = $(this).closest('.tsg-window-root'),
				rootElm = currentRoot.find('.ts-scg-views-canvas-root > .ts-scg-shortcode-item-root'),
				contents = parseTScodeFromRoot( rootElm ),
				currentWindow = currentRoot.data('id'),
				prevTMC = window.tsg_windows[currentWindow].prevEditor;

			prevTMC.execCommand('mceInsertContent', false, contents);

			closeTSGpopup();

		});

		// init all field types and jQuery UI features

		$(window).on('tsg_init', function() {

			$('.ts-generator-root-js .tsg-slider-field').each(function() {

				if( $(this).hasClass('ts-field-activated') ) {
					return;
				}

				var init = $(this).children('.tsg-init-slider'),
					binder = $(this).children('.ts-scg-value-collector'),
					min = ( $(this).data('slider-min') ? $(this).data('slider-min') : 1 ),
					max = ( $(this).data('slider-max') ? $(this).data('slider-max') : 10 ),
					step = ( $(this).data('slider-step') ? $(this).data('slider-step') : 1 ),
					def = ( $(this).data('slider-default') ? $(this).data('slider-default') : min );

				init.slider({
					min: min,
					max: max,
					range: "min",
					value: def,
					step: step,
					slide: function( event, ui ) {
						binder.val(ui.value);
						binder.trigger('change');
					}
				});

				binder.on('change', function() {
					var val = $(this).val();
					init.slider( 'value', val );
				});

				$(this).addClass('ts-field-activated');
			});

			$('.ts-generator-root-js .tsg-icons-field').each(function() {

				if( $(this).hasClass('ts-field-activated') ) {
					return;
				}

				var selectors = $(this).find('.ts-scg-icons-collection li'),
					collection = $(this).find('.ts-scg-icons-collection'),
					binder = $(this).find('.ts-scg-value-collector'),
					header = $(this).find('.ts-scg-icons-header'),
					headerIcon = $(this).find('.ts-scg-icons-toggle i');

				selectors.click(function() {
					if( $(this).hasClass('active') ) {
						$(this).removeClass('active');
						$(this).siblings().removeClass('active');
						header.find('.ts-scg-icons-default-now').html('--');
						headerIcon.attr( 'class', '' );
						binder.val('');
						binder.trigger('change');
						return;
					}
					var val = $(this).data('value');
					binder.val( val );
					binder.trigger('change');
					$(this).addClass('active');
					$(this).siblings().removeClass('active');
					header.find('.ts-scg-icons-default-now').html(val);
					headerIcon.attr( 'class', 'fa ' + val );
				});
				header.click(function() {
					collection.slideToggle();
				});
				$(this).addClass('ts-field-activated');
			});

			$('.ts-generator-root-js .tsg-radio-field').each(function(i) {

				if( $(this).hasClass('ts-field-activated') ) {
					return;
				}
				var selectors = $(this).find('input[type=radio]'),
					binder = $(this).find('.ts-scg-value-collector'),
					parent = $(this),
					name, field, id;

				selectors.each(function() {
					field = $(this);
					name = field.attr('name');
					id = field.attr('id');
					field.attr( 'name', name + i );
					field.attr( 'id', id + i );
					field.next('label').attr( 'for', id + i );
				});

				selectors.on( 'change', function() {
					var val = parent.find('input:radio:checked').val();
					binder.val(val);
					binder.trigger('change');
				});
				$(this).addClass('ts-field-activated');
			});

			$('.ts-generator-root-js .tsg-checkbox-field').each(function(i) {

				if( $(this).hasClass('ts-field-activated') ) {
					return;
				}

				var selectors = $(this).find('input[type=checkbox]'),
					binder = $(this).find('.ts-scg-value-collector'),
					parent = $(this),
					name, field, id;

				selectors.each(function() {
					field = $(this);
					name = field.attr('name');
					id = field.attr('id');
					field.attr( 'name', name + i );
					field.attr( 'id', id + i );
					field.next('label').attr( 'for', id + i );
				});

				selectors.on( 'change', function() {
					var val = [];
					parent.find('input:checkbox:checked').each(function() {
						val.push($(this).val());
					});
					binder.val(val.join(', '));
					binder.trigger('change');
				});
				$(this).addClass('ts-field-activated');
			});

			$('.ts-generator-root-js .tsg-select-field').each(function() {

				if( $(this).hasClass('ts-field-activated') ) {
					return;
				}

				var selector = $(this).find('select.tsg-select-element'),
					binder = $(this).find('.ts-scg-value-collector');

				selector.select2().on( 'change', function() {
					var val = $(this).find('option:selected').val();
					binder.val(val);
					binder.trigger('change');
				}).trigger('change');
				$(this).addClass('ts-field-activated');
			});

			$('.ts-generator-root-js .tsg-date-field').each(function() {

				if( $(this).hasClass('ts-field-activated') ) {
					return;
				}

				var selector = $(this).find('.ts-scg-date-input'),
					binder = $(this).find('.ts-scg-value-collector');

				selector.datepicker({
					dateFormat: 'dd-mm-yy',
					onSelect: function( date ) {
						binder.val(date);
					}
				});
				$(this).addClass('ts-field-activated');
			});

			$('.ts-generator-root-js .tsg-colorpicker-field').each(function() {

				if( $(this).hasClass('ts-field-activated') ) {
					return;
				}

				var selector = $(this).find('.tsg-colorpicker-input'),
					binder = $(this).find('.ts-scg-value-collector'),
					previewer = $(this).find('.tsg-colorpicker-preview'),
					container = $(this),
					format = selector.data('format');

				selector.colorpicker({
					format: format,
					container: container,
					input: binder,
					customClass: 'ts-colorpicker'
				}).on('changeColor.colorpicker', function(event) {
					previewer.css('background', $(this).val());
					binder.val( $(this).val() );
				});

				previewer.on('click', function() {
					selector.colorpicker('show');
				});
				$(this).addClass('ts-field-activated');
			});

			$('.ts-generator-root-js .tsg-toggle-field').each(function() {

				if( $(this).hasClass('ts-field-activated') ) {
					return;
				}

				var selector = $(this).find('.tsg-toggle-field-checkbox'),
					ifTrue = selector.data('true'),
					ifFalse = selector.data('false'),
					binder = $(this).find('.ts-scg-value-collector'),
					val;

				selector.on( 'change', function() {
					if( $(this).is(':checked') ) {
						val = ifTrue;
					} else {
						val = ifFalse;
					}
					binder.val(val);
					binder.trigger('change');
				});
				$(this).addClass('ts-field-activated');
			});

			$('.ts-generator-root-js .tsg-upload-field').each(function() {

				if( $(this).hasClass('ts-field-activated') ) {
					return;
				}

				var tsFrameOpener = $(this).find('.tsg-upload-button'),
					binder = $(this).find('.ts-scg-value-collector'),
					remover = $(this).find('.tsg-upload-remove-button'),
					preview = $(this).find('.ts-scg-upload-preview'),
					ts_upload_frame = wp.media({
						title: tsFrameOpener.data('tsframe_title'),
						button: {
							text: tsFrameOpener.data('tsframe_button_text'),
						},
						multiple: false
					});

				tsFrameOpener.on('click', function() { 
					ts_upload_frame.open();
				});

				remover.on('click', function() { 
					preview.removeAttr('src').hide();
					binder.val('');
					binder.trigger('change');
				});

				ts_upload_frame.on('select', function() {

					var selection = ts_upload_frame.state().get('selection');

					selection.map( function( attachment ) {

						attachment = attachment.toJSON();

						console.log(attachment);

						if(attachment.type === 'image') {
							preview.attr('src', attachment.url).show();
						} else {
							preview.attr('src', attachment.icon).show();
						}

						binder.val(attachment.url);
						binder.trigger('change');

					});

					if( !$('body').hasClass('modal-open') ) {
						$('body').addClass('modal-open');
					}

				});
				$(this).addClass('ts-field-activated');                
			});

			$('.ts-generator-root-js .ts-scg-shortcode-item-root.tsg-sortable-module').each(function() {
				if( $(this).hasClass('tsg-sortable-activated') ) {
					return;
				}
				var modules = $(this).children('.tsg-module-inner'),
					textareaID, helperHeight;
				$(this).children('.tsg-modules-wrapper').sortable({
					handle: '.tsg-sorter-handle',
					axis: 'y',
					opacity: 0.5,
					placeholder: 'tsg-sc-module-sortable-placeholder',
					start: function(event, ui) {
						textareaID = $(ui.item).find('textarea.wp-editor-area').attr('id');
						try { tinyMCE.execCommand('mceRemoveEditor', false, textareaID); } catch(e){}
						ui.placeholder.height( ui.helper.height() );
					},
					stop: function(event, ui) {
						try { tinyMCE.execCommand('mceAddEditor', false, textareaID); } catch(e){}
					}
				});
				$(this).addClass('tsg-sortable-activated');
			});

			$('.ts-generator-root-js .ts-scg-shortcode-item-root.tsg-sortable-module > .tsg-modules-wrapper > .tsg-module-inner').each(function() {
				if( $(this).hasClass('tsg-collapsible-activated') ) {
					return;
				}
				$(this).children('.tsg-sorter-collapse').click(function() {
					$(this).siblings('.ts-scg-shortcode-item-atts, .ts-scg-shortcode-item-content').slideToggle();
				});
				$(this).addClass('tsg-collapsible-activated');
			});

			$('.ts-generator-root-js .tsg-multiple-select-field').each(function() {
				if( $(this).hasClass('ts-field-activated') ) {
					return;
				}
				var allOps = $(this).children('.tsg-multiselect-select-element'),
					binder = $(this).children('.ts-scg-value-collector'),
					vals;
				allOps.select2().on('change', function() {
					vals = $(this).val();
					binder.val( vals );
				});
				$(this).addClass('ts-field-activated');
			});

		});

		$(window).on('tsg_pre_init', function() {

			$('.ts-generator-root-js .ts-scg-shortcode-item-root.tsg-repeatable-module').each(function() {
				if( $(this).hasClass('tsg-repeater-activated') ) {
					return;
				}

				var wrapper = $(this).children('.tsg-modules-wrapper'),
					toBeRepeated = wrapper.children('.tsg-module-inner').html(),
					repeat = '<div class="tsg-module-inner">' + toBeRepeated + '</div>',
					repeater = $(this).children('.repeat-add.button'),
					tobeRemoved;

				repeater.click(function() {
					wrapper.append(repeat);
					$(window).trigger('tsg_pre_init');
					$(window).trigger('tsg_init');
				});

				$(this).on('click', '.tsg-repeatable-remover', function() {
					tobeRemoved = $(this).closest('.tsg-module-inner');
					breakEditors(tobeRemoved);
					tobeRemoved.remove();
				});

				$(this).addClass('tsg-repeater-activated');
			});

		});

	});

})(jQuery);
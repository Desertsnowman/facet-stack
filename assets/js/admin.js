jQuery( function($){

	$( document ).on('click', '.facet-stack-section-wrapper > h4', function(){
		var clicked = $( this ),
			icon 	= clicked.find('.facet-stack-section-toggle'),
			wrapper = clicked.closest('.facet-stack-wrapper');
			section = clicked.closest('.facet-stack-section-wrapper').find('.facet-stack-section');

		if( icon.hasClass('dashicons-arrow-up') ){
			icon.removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');
			section.slideUp(100);
		}else{
			icon.removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
			section.slideDown(100);
		}

	});

	// functionality
	var reset_facet_ordering = function( item ){
			var selection = $( '#' + item.data('for') ),
				wrapper = item.closest('.facet-stack-section'),
				facet_enabled_tray = wrapper.find('.facet-stack-enabled-facets'),
				facets = [];

			facet_enabled_tray.find('.facet-stack-facet').each( function(){
				facets.push( $(this).data('facet') );
			})
			show_tut_message( facet_enabled_tray );

			if( selection.length ){
				selection.val( facets.join(',') ).trigger('change');
			}
	}

	var show_tut_message = function( list ){
		if( list.children().length > 1 ){
			list.find('.description').slideUp(100);
		}else{
			list.find('.description').slideDown(100);
		}
	}
	var init_sortable_facets = function( el ){
		if( !el ){
			el = $('.facet-stack-facets');
		}
		el.sortable({
			connectWith: ".facet-stack-tray",
			axis: "y",
			handle: ".sortable-item",
			placeholder: "facet-stack-highlight-placeholder",
			forcePlaceholderSize: true,
			helper: "clone",
			create : function(ev, ui){
				var list = $( this );
				if( list.hasClass( 'facet-stack-enabled-facets' ) ){
					console.log( list );
					show_tut_message( list );
				}
				//reset_facet_ordering( $( this ) );
			},
			update: function(ev, ui){
				//ui.item.find('input').trigger('change');
				reset_facet_ordering( ui.item );
			}
		});
	}

	$( document ).on('widget-added widget-updated', function( e, ui ){
		var sort_stack = ui.find('.facet-stack-facets');

		if( sort_stack.length ){
			init_sortable_facets( sort_stack );
		}
		//colors
		$('.color-field').wpColorPicker({
			change: function(obj){
				
				$( this ).trigger('change');
			}
		});		

	})

	init_sortable_facets();



	// previews
	$( document ).on('change', '.facet-stack-section [data-bind]', function( e ){
		e.stopPropagation();
		var field = $( this );
	})
	//colors
	$('.color-field').wpColorPicker({
		change: function(obj){
			
			var trigger = $(this);
			if( trigger.data('target') ){
				$( trigger.data('target') ).css( trigger.data('style'), trigger.val() );
			}
			
		}
	});

});
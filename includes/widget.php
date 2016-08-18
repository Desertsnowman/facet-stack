<?php


class Facet_Stack_Widget extends WP_Widget {

	/**
	 * Create widget
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		// Instantiate the parent object
		parent::__construct( 
			false, 
			__('Facet Stack', 'facet-stack' ), 
			array(
				'description' => __('A stack of facets for FacetWP', 'facet-stack'),
			)
		);

		/**
		 * Runs after Facet Stack widget is initialized
		 *
		 * @since 1.4.0
		 */
		do_action( 'facet_stack_widget_init' );
		
	}

	/**
	 * Widget output
	 *
	 * @since 1.0.0
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {

		if( !empty( $instance['facets'] ) ){

			extract($args, EXTR_SKIP);

			// check loading
			if( false === FWP()->display->load_assets ){
				return;
			}

			if( isset( $instance['load_style'] ) ){
				include_once FACET_STACK_PATH . 'includes/load-style.php';
			}

			$facets = explode( ',', $instance['facets'] );

			echo $before_widget;
			
			foreach( $facets as $facet ){
				$facet = $facets = FWP()->helper->get_facet_by_name( $facet );				
				
				if( isset( $instance['show_titles'] ) ){
					echo $before_title . $facet['label'] . $after_title;
				}
				echo facetwp_display( 'facet', $facet['name'] );

			}
			echo $after_widget;
		}
	}

	/**
	 * Update widget settings
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		// Save widget options
		return $new_instance;
	}

	/**
	 * Widget UI form
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance
	 */
	function form( $instance ) {

		// get settings
		$instance = wp_parse_args( (array) $instance, array( 'facets' => '' ) );

		do_action( 'facet_stack_widget_form_start', $instance );
		
		$facets = FWP()->helper->get_facets();

		echo '<div class="facet-stack-wrapper">';

			// include general settings
			include FACET_STACK_PATH . 'includes/general-settings.php';

			// include facet selection
			include FACET_STACK_PATH . 'includes/facet-selection.php';


		echo '</div>';
		// add style sheet
		wp_enqueue_style( 'facet-stack-admin', FACET_STACK_URL . 'assets/css/admin.min.css', null, FACET_STACK_VER );
		wp_enqueue_script( 'facet-stack-admin', FACET_STACK_URL . 'assets/js/admin.min.js', array('jquery'), FACET_STACK_VER );

		//end form
		do_action( 'facet_stack_widget_form_end', $instance, $this );
	}
}

function facet_stack_register_widget() {
	if( ! did_action( 'facet_stack_widget_init' ) ){
		register_widget( 'Facet_Stack_Widget' );
	}

}

add_action( 'widgets_init', 'facet_stack_register_widget' );
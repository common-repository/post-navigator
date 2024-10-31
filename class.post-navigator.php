<?php

// Core class for plugin functionality

final class Plugify_Post_Navigator {
	
	// Member constants
	const WP_EDIT_FILENAME = 'edit.php';
	const PN_POST_TYPE_KEY = 'post_navigator_post_types';
	
	// Class constructor
	public function __construct () {
		
		// Register actions
		add_action( 'init', array( __CLASS__, 'init' ) );
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		
		// UI hooks
		add_action( 'post_submitbox_misc_actions', array( __CLASS__, '_ui' ) );
		add_action( 'admin_print_footer_scripts', array( __CLASS__, '_js' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, '_css' ) );
		
		// Register AJAX hook
		add_action( 'wp_ajax_post_lookup', array( __CLASS__, 'ajax_post_lookup' ) );
		
	}
	
	public function ajax_post_lookup () {
	  
		if( !isset( $_REQUEST['id'] ) || !isset( $_REQUEST['post_type'] ) )
			wp_send_json_error();
		
		$target = get_post( absint( $_REQUEST['id'] ) );
		
		$args = array(
		
			'post_type' => $target->post_type,
			'numberposts' => -1,
		
		);
		
		$filter = ( sanitize_text_field( $_REQUEST['mode'] ) == 'go-sibling' ?
		  array( 'child_of' => $target->post_parent, 'post_parent' => $target->post_parent ) :
		  array( 'child_of' => $target->ID, 'post_parent' => $target->ID ) );
		  
		$args = array_merge( $args, $filter );
		
		if( isset( $_REQUEST['exclude'] ) )
		  $args['exclude'] = sanitize_text_field( $_REQUEST['exclude'] );
		
		if( $posts = get_posts( $args ) )
		  wp_send_json_success( $posts );
		else
		  wp_send_json_error();
	
	}
	
	public static function get_supported_post_types () {
	
		// Get user defined post types
		$post_types = get_option( self::PN_POST_TYPE_KEY );
		
		// Fallback to "post" and "page" post types if the user has not made a selection
		if( empty( $post_types ) )
			$post_types = array( 'post', 'page' );
			
		return $post_types;
	
	}
	
	public static function init () {
	
	  // Placeholder
	
	}
	
	// Handle and setup admin area dependencies, such as enqueuing styles and scripts
	public static function admin_init () {
		
		// Ensure jQuery is queued and localize javascript variables
	  wp_enqueue_script( 'jquery' );
	  
	  wp_localize_script( 'jquery', 'AJAX', array(
	  
	    'url' => admin_url( 'admin-ajax.php' ),
	    'template_directory' => get_bloginfo( 'template_directory' )
	  
	  ) );
	  
		// Enqueue plugin specific admin JS and styles
		wp_enqueue_style( 'postnavigator.css', plugins_url( 'assets/styles', __FILE__ ) . '/style.css', false, false, 'all' );
		wp_enqueue_script( 'scripts.js', plugins_url( 'assets/js', __FILE__ ) . '/scripts.js' );
		
		// Register post redirection filter hook
		add_filter( 'redirect_post_location', array( __CLASS__, '_handler' ), 10, 2 );
	
	}
	
	public static function admin_menu () {
	
		// Add menu page which allows the user to specify which post types to work with
		add_options_page( 'Post Navigator - Post Types', 'Post Navigator', 'manage_options', 'post-navigator-post-types', array( __CLASS__, '_options_page' )  );
	
	}
	
	// Build user interface portion of the plugin
	public static function _ui () {

		global $post;
		
		// Get current post type details
		$post_details = get_post_type_object( $post->post_type );
		
		// Ensure we only render the ui for user selected post types
		$post_types = self::get_supported_post_types();
			
		if( !in_array( $post->post_type, $post_types ) )
			return;
			
		// Build actions array
		$label = $post_details->labels->singular_name;
		
		$actions = array(
		
			'default' => 'Continue editing ' . $label,
			'view-in-theme' => 'View ' . $label . ' after saving',
			'add-new' => 'Add a new ' . $label,
			'go-parent' => 'Go to parent ' . $label,
			'go-sibling' => 'Go to sibling ' . $label,
			'go-child' => 'Go to child ' . $label,
			'go-next' => 'Go to next ' . $label,
			'go-prev' => 'Go to previous ' . $label,
			'go-list' => 'Go back to ' . $label . ' list'
		
		);

		// Output markup to the browser
		echo '<div id="post-navigator" class="misc-pub-section">';
			
			echo '<span id="action-box">';
			
			echo '	<span class="action-title">After I save &nbsp;</span>';
			echo '	<select name="post-save-action" id="post-save-action">';
			
			foreach( $actions as $key => $action )
				echo sprintf( '<option value="%s"%s>%s</option>', esc_attr( $key ), $key == ( isset( $_GET['navigator-action'] ) ? sanitize_text_field( $_GET['navigator-action'] ) : false ) ? ' selected="selected"' : NULL, $action );
			
			echo '	</select><br />';
			
			echo '  <div id="post-save-action-id-parent">';
			echo '	  <select name="post-save-action-id" id="post-save-action-id">';
			echo '	  </select>';
			echo '    <br clear="all" />';
			echo '  </div>';
			
			echo '</span>';
			
			echo '<input type="hidden" id="post_type" name="post_type" value="' . esc_attr( $post->post_type ) . '" />';
			echo '<input type="hidden" id="exclude" name="exclude" value="' . esc_attr( $post->ID ) . '" />';
		
		echo '</div>';
		
		echo '<div id="post-navigator-buttons" class="misc-pub-section" style="display:none;">';
		
		if( $prev = get_adjacent_post( false, NULL ) )
			echo '	<a id="post-navigator-prev" class="add-new-h2" title="Previous ' . $label . ' is: ' . $prev->post_title . '" href="' . get_edit_post_link( $prev->ID ) . '">&lsaquo; Previous</a>';
			
		if( $next = get_adjacent_post( false, NULL, false ) )
			echo '	<a id="post-navigator-next" class="add-new-h2" title="Next ' . $label . ' is: ' . $next->post_title . '" href="' . get_edit_post_link( $next->ID ) . '">Next &rsaquo;</a>';
		
		echo '</div>';
	
	}
	
	public static function _js () {
	
		?>
		
		<script type="text/javascript">
		
		jQuery( document ).ready( function($) {
		
			$( '#post-navigator-buttons *' ).appendTo( $( 'h2' ) );
			$( '#post-navigator-buttons' ).remove();

		});
		
		</script>
		
		<?php
	
	}
	
	public static function _css () {
	
		?>
		
		<style type="text/css">
		
			#post-navigator-buttons { display: none; }
			#post-navigator-prev,#post-navigator-next { margin-left: 35px; }
			#post-navigator-prev { margin-right: -30px; }
		
		</style>
		
		<?php
	
	}
	
	// Handler for rendering the options page (Settings -> Post Navigator)
	public static function _options_page () {
	
		// Process form submission if there is one
		if( !empty( $_POST ) )
			update_option( self::PN_POST_TYPE_KEY, $_POST['post_types'] );
		
		// Get all post types and filter by what the user has selected
		// Fallback to "post" and "page" types if no selection has been made yet
		$_post_types 	= get_post_types();
		$post_types 	= self::get_supported_post_types();
		
		// Render options in-line with WordPress core styling
		echo '<div class="wrap" id="post-navigator-settings">';
		
		echo '	<div class="icon32" id="icon-options-general"><br></div>';
		echo '	<h2>Post Navigator</h2>';
		
		echo '	<form id="post-navigator" method="post" action="#">';
		echo '	<table class="form-table">';
	
		echo '		<tr valign="top">';	
		echo '			<th scope="row">';
		echo '				<strong>Supported Post Types</strong><br /><em>choose which post types Post Navigator should work with</em>';
		echo '			</th>';
		echo '			<td valign="top" align="left">';
		
		foreach( $_post_types as $post_type_slug ) {
			
			$post_type = get_post_type_object( $post_type_slug );
			
			if( $post_type->public == 1 )
				echo '<input type="checkbox" name="post_types[]" value="' . esc_attr( $post_type->name ) . '"' . ( in_array( $post_type_slug, $post_types ) ? ' checked="checked"' : NULL ) . '>&nbsp;&nbsp;' . $post_type->labels->singular_name . '<br />';
		
		}
		
		echo '			</td>';
		echo '		</tr>';
		
		echo '		<tr>';
		echo '			<td valign="top" align="left" colspan="100">';
		echo '				<input type="submit" class="button button-primary" value="Save Changes" />';
		echo '			</td>';
		echo '		</tr>';
		
		echo '	</table>';
		echo '</form>';
		
		echo '</div>';
	
	}
	
	// Handle post update/insert
	// Apply navigation handler if necessary
	public static function _handler ( $location, $post_id ) {
    
		global $post;
		
		// Ensure this is a post type we want to handle
		if( !$post )
			$post_type = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : 'post';
		else
			$post_type = $post->post_type;
		
		if( !in_array( $post_type, self::get_supported_post_types() ) )
			return $location;

		// Determine user selection and take appropriate action
		if( isset( $_REQUEST['post-save-action'] ) ) {
		
			switch( $_REQUEST['post-save-action'] ) {
			
				case 'default':
					
					// Do nothing, "continue editing" is default WordPress behaviour
					break;
				
				case 'view-in-theme':
					$location = get_permalink( $post->ID );
					break;
				
				case 'add-new':
					// Redirect to new post
					$location = admin_url( 'post-new.php?post_type=' . $post->post_type );
					break;
					
				case 'go-parent':
				
					// Redirect to parent post if it exists
					$parent_id = isset( $_POST['parent_id'] ) ? absint( $_POST['parent_id'] ) : ( $post ? $post->post_parent : NULL );
					
					if( $parent_id > 0 ) {
					
						$location = admin_url( 'post.php?post=' . $parent_id . '&action=edit' );
						
					}
					
					break;
					
				case 'go-child':
				case 'go-sibling':
				
					if( $_REQUEST['post-save-action-id'] != '' )
						$location = admin_url( 'post.php?post=' . absint( $_REQUEST['post-save-action-id'] ) . '&action=edit' );
					
					break;
					
				case 'go-next':
				case 'go-prev':
				
					$adjacent = get_adjacent_post( false, NULL, $_REQUEST['post-save-action'] == 'go-prev' );
					
					if( $adjacent != '' )
						$location = admin_url( 'post.php?post=' . $adjacent->ID . '&action=edit' );
					
					break;
					
				case 'go-list':
					$location = admin_url( 'edit.php?post_type=' . $post->post_type );
					break;
					
			}
			
			if( $_REQUEST['post-save-action'] != 'view-in-theme' && $_REQUEST['post-save-action'] != 'go-list' )
			$location = add_query_arg( 'navigator-action', sanitize_text_field( $_REQUEST['post-save-action'] ), $location );
		
		}
		
		return $location;
	
	}

}

?>
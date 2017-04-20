<?php
	// Template Files
	add_filter( 'template_include', 'include_album_template', 1 );
	function include_album_template( $template_path ) {
		if ( get_post_type() == 'album' ) {
			if ( is_single() ) {
				// checks if the file exists in the theme first,
				// otherwise serve the file from the plugin
				if ( $theme_file = locate_template( array ( 'single-album.php' ) ) ) {
					$template_path = $theme_file;
				} else {
					$template_path = plugin_dir_path( __FILE__ ) . '../templates/single-album.php';
				}
			}
			elseif ( is_archive() ) {
				if ( $theme_file = locate_template( array ( 'archive-album.php' ) ) ) {
					$template_path = $theme_file;
				} else { $template_path = plugin_dir_path( __FILE__ ) . '../templates/archive-album.php';
	 
				}
			}
		}
		return $template_path;
	}

	// Script Loader
    function music_script_loader() {
		$script_path = plugin_dir_url( __FILE__ ).'../lib/';
		wp_enqueue_script( 'isotope-js', $script_path  . 'js/isotope.min.js', array( 'jquery' ), '3.0.1', true );
		wp_enqueue_script( 'plugin-js', $script_path . 'js/discography.js', array( 'jquery' ), '1.0.0', true );
    }
    add_action('wp_enqueue_scripts', 'music_script_loader');

	// Get ID of Slug
	function get_music_id_by_slug( $page_slug ) {
		$page = get_page_by_path( $page_slug );
		if( $page ) {
			return $page->ID;
		} else {
			return null;
		}
	}
<?php
	// Start Plugin
	function create_album_posttype() {
		register_post_type( 'album',
		// CPT Options
			array(
				'description' => __( 'Album Releases' ),
				'labels' => array(
					'name' => ( 'Albums' ),
					'slug' => 'album',
					'singular_name' => __( 'Album' ),
					'add_new' => 'Add New',
					'add_new_item' => 'Add New Album',
					'edit' => 'Edit',
					'edit_item' => 'Edit Album',
					'new_item' => 'New Album',
					'view' => 'View',
					'view_item' => 'View Album',
					'search_items' => 'Search Albums',
					'not_found' => 'No Albums found',
					'not_found_in_trash' => 'No Albums found in Trash',
					'parent' => 'Parent Album'
				),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'music'),
				'supports' => array('title','editor','thumbnail','revisions','custom-fields'),
				'taxonomies' => array( 'albumtype' ),
				'menu_position' => 4,
				'menu_icon' => 'dashicons-album',
				//'register_meta_box_cb' => 'tracklisting'
			)
		);
	}
	// Hooking up our function to theme setup
	add_action( 'init', 'create_album_posttype' );

	// Order by Alphabet
	function album_order_classes( $query ) {
		if ( $query->is_post_type_archive('album') && $query->is_main_query() ) {
			$query->set( 'meta_key', 'album_info_release-date' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', 'DESC' );
		}
	}
	add_action( 'pre_get_posts', 'album_order_classes' );

	// Implementing Taxonomies
	add_action( 'init', 'create_album_taxonomies', 0 );
	function create_album_taxonomies() {
		register_taxonomy(
			'albumtype',
			'albumtype',
			array(
				'labels' => array(
					'name' => 'Album Type',
					'add_new_item' => 'Add New Album Format',
					'new_item_name' => "New Album Format"
				),
				'show_ui' => true,
				'show_tagcloud' => false,
				'hierarchical' => true
			)
		);
	}
	// Implementing Taxonomy Filters
	add_action( 'restrict_manage_posts', 'my_filter_list' );
	function my_filter_list() {
		$screen = get_current_screen();
		global $wp_query;
		if ( $screen->post_type == 'album' ) {
			wp_dropdown_categories( array(
				'show_option_all' => 'Show All Album Formats',
				'taxonomy' => 'albumtype',
				'name' => 'albumtype',
				'orderby' => 'name',
				'selected' => ( isset( $wp_query->query['albumtype'] ) ? $wp_query->query['albumtype'] : '' ),
				'hierarchical' => false,
				'depth' => 3,
				'show_count' => false,
				'hide_empty' => true,
			) );
		}
	}
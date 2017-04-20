<?php
	// Album Info Metabox
	class Album_Info_Meta_Box {
		private $screens = array(
			'album',
		);
		private $fields = array(
			array(
				'id' => 'release-date',
				'label' => 'Release Date',
				'type' => 'date',
			),
			array(
				'id' => 'label',
				'label' => 'Label',
				'type' => 'text',
			),
			array(
				'id' => 'download-link',
				'label' => 'Download Link',
				'type' => 'url',
			),
			array(
				'id' => 'alternate-link',
				'label' => 'Alternate Link',
				'type' => 'url',
			),
			array(
				'id' => 'itunes',
				'label' => 'iTunes',
				'type' => 'url',
			),
			array(
				'id' => 'apple-music',
				'label' => 'Apple Music',
				'type' => 'url',
			),
			array(
				'id' => 'spotify',
				'label' => 'Spotify',
				'type' => 'url',
			),
			array(
				'id' => 'google-play',
				'label' => 'Google Play',
				'type' => 'url',
			),
			array(
				'id' => 'pandora',
				'label' => 'Pandora',
				'type' => 'url',
			),
		);
		// Class construct method. Adds actions to their respective WordPress hooks.
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_post' ) );
		}
		// Hooks into WordPress' add_meta_boxes function.
		// Goes through screens (post types) and adds the meta box.
		public function add_meta_boxes() {
			foreach ( $this->screens as $screen ) {
				add_meta_box(
					'album-info',
					__( 'Album Info', 'album_info' ),
					array( $this, 'add_meta_box_callback' ),
					$screen,
					'normal',
					'high'
				);
			}
		}
		// Generates the HTML for the meta box
		// @param object $post WordPress post object
		public function add_meta_box_callback( $post ) {
			wp_nonce_field( 'album_info_data', 'album_info_nonce' );
			echo 'What is the release date, label info, and where can people find your album? Enter all of that information here.';
			$this->generate_fields( $post );
		}
		// Generates the field's HTML for the meta box.
		public function generate_fields( $post ) {
			$output = '';
			foreach ( $this->fields as $field ) {
				$label = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
				$db_value = get_post_meta( $post->ID, 'album_info_' . $field['id'], true );
				switch ( $field['type'] ) {
					default:
						$input = sprintf(
							'<input %s id="%s" name="%s" type="%s" value="%s">',
							$field['type'] !== 'color' ? 'class="regular-text"' : '',
							$field['id'],
							$field['id'],
							$field['type'],
							$db_value
						);
				}
				$output .= $this->row_format( $label, $input );
			}
			echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
		}
		// Generates the HTML for table rows.
		public function row_format( $label, $input ) {
			return sprintf(
				'<tr><th scope="row">%s</th><td>%s</td></tr>',
				$label,
				$input
			);
		}
		// Hooks into WordPress' save_post function
		public function save_post( $post_id ) {
			if ( ! isset( $_POST['album_info_nonce'] ) )
				return $post_id;

			$nonce = $_POST['album_info_nonce'];
			if ( !wp_verify_nonce( $nonce, 'album_info_data' ) )
				return $post_id;

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return $post_id;

			foreach ( $this->fields as $field ) {
				if ( isset( $_POST[ $field['id'] ] ) ) {
					switch ( $field['type'] ) {
						case 'email':
							$_POST[ $field['id'] ] = sanitize_email( $_POST[ $field['id'] ] );
							break;
						case 'text':
							$_POST[ $field['id'] ] = sanitize_text_field( $_POST[ $field['id'] ] );
							break;
					}
					update_post_meta( $post_id, 'album_info_' . $field['id'], $_POST[ $field['id'] ] );
				} else if ( $field['type'] === 'checkbox' ) {
					update_post_meta( $post_id, 'album_info_' . $field['id'], '0' );
				}
			}
		}
	}
	new Album_Info_Meta_Box;

	// Tracklisting Metabox
	add_action( 'add_meta_boxes', 'tracklist_add_custom_box' );
	/* Do something with the data entered */
	add_action( 'save_post', 'tracklist_save_postdata' );
	/* Adds a box to the main column on the Post and Page edit screens */
	function tracklist_add_custom_box() {
		add_meta_box(
			'tracklist',
			__( 'Tracklisting', 'tracklisting_textdomain' ),
			'tracklist_inner_custom_box',
			'album',
			'normal',
			'high'
		);
	}
	/* Prints the box content */
	function tracklist_inner_custom_box() {
		global $post;
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'tracklisting_noncename' );
		?>
		<div id="meta_inner">
			<div id="here">
				<?php
					// Get the saved meta as an array
					$songs = get_post_meta($post->ID,'songs',true);
					$c = 1;
					if(is_array($songs)){
						foreach( $songs as $track ) {
							if ( isset( $track['title'] ) || isset( $track['track'] ) ) {
								printf( '<p class="parent"> Track # <input type="number" name="songs[%1$s][track]" value="%3$s" class="track-no" /> Song Title <input type="text" name="songs[%1$s][title]" value="%2$s" class="track-name" /> File Name <input type="url" name="songs[%1$s][url]" value="%6$s" class="track-url" id="track_%1$s" data-trackno="%1$s" /><input type="button" class="rational-metabox-media button" id="track_%1$s_button" value="%5$s" data-trackno="%1$s" /> <input type="button" class="remove button" value="%4$s" /></p>', $c, $track['title'], $track['track'], __( 'Remove Track' ), __( 'Add MP3' ), $track['url'] );
								$c = $c +1;
							}
						}
					}
				?>
			</div>
			<input type="button" class="add button" id="add_tracks" value="Add Tracks" />
			<script type="text/javascript">
				jQuery(function($){
					// Adds new track line with click
					var count = <?php echo $c; ?>;
					$(".add").click(function() {
						count = count + 1;
						$('#here').append('<p class="parent"> Track # <input type="number" name="songs['+count+'][track]" value="" class="track-no" /> Song Title <input type="text" name="songs['+count+'][title]" value="" class="track-name" /> File Name <input type="url" name="songs['+count+'][url]" value="" class="track-url" data-trackno="'+count+'" /><input type="button" class="rational-metabox-media button" id="tracklist_button" value="Add MP3" data-trackno="'+count+'" /> <input type="button" class="remove button" value="Remove Track" /></p>' );
						return false;
					});
					// Removes track line with click
					$(".remove").live('click', function() {
						$(this).parent().remove();
					});
					// Adds media to input
					if (typeof wp.media !== 'undefined') {
						var _custom_media = true,
							_orig_send_attachment = wp.media.editor.send.attachment;
						$('.rational-metabox-media').click(function(e) {
							var send_attachment_bkp = wp.media.editor.send.attachment;
							var button = $(this);
							var id = button.attr('id').replace('_button', '');
							_custom_media = true;
							wp.media.editor.send.attachment = function(props, attachment) {
								if (_custom_media) {
									$("#" + id).val(attachment.url);
								} else {
									return _orig_send_attachment.apply(this, [props, attachment]);
								};
							}
							wp.media.editor.open(button);
							return false;
						});
						$('.add_media').on('click', function() {
							_custom_media = false;
						});
					}
				});
			</script>
			<style type="text/css">
				#tracklist .track-no {
					width: 35px;
				}
			</style>
		</div>
		<?php
	}
	/* When the post is saved, saves our custom data */
	function tracklist_save_postdata( $post_id ) {
		// Verify if this is an auto save routine. 
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;
		// Verify this came from the our screen and with proper authorization,
		// Because save_post can be triggered at other times
		if ( !isset( $_POST['tracklisting_noncename'] ) )
			return;
		if ( !wp_verify_nonce( $_POST['tracklisting_noncename'], plugin_basename( __FILE__ ) ) )
			return;

		// OK, we're authenticated: we need to find and save the data
		$songs = $_POST['songs'];
		update_post_meta($post_id,'songs',$songs);
	}
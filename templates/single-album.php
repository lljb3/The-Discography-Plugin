<?php
/*
Template Name: Single - Album Page
*/
?>

<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Please see /external/starkers-utilities.php for info on Starkers_Utilities::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/html-header' ) ); ?>

<!-- Main Information -->
<div class="container-fluid" id="main">
    <div <?php body_class('row'); ?>>
        <div class="col-md-12 page-container">

			<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/header' ) ); ?>
            <!-- Content Information -->
            <?php $attachment_id = get_post_thumbnail_id(); $bg_url = wp_get_attachment_image_src($attachment_id, 'full', false); ?>
            <div class="row <?php global $post; echo get_post($post)->post_name; ?>-main single-album-main" id="content">
                <div class="col-md-10 col-md-offset-1" id="container">
                    <div class="content-box">
                        <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
                            <div class="has-text row">
                                <div class="col-md-4">
                                    <div class="album-thumb">
                                        <?php $album_url = wp_get_attachment_image_src($attachment_id, 'large', false); ?>
                                        <img src="<?php echo $album_url[0]; ?>" class="background img-responsive center-block" />
                                    </div>
                                <!-- end .col-md-6 --></div>
                                <div class="col-md-8">
                                    <h3 class="has-title"><?php the_title(''); ?></h3>
                                    <div class="album-info">
                                        <?php
                                            $post_id = get_the_id(); 
                                            $release_date = get_post_meta($post_id, 'album_info_release-date', true);
                                            $label = get_post_meta($post_id, 'album_info_label', true);
                                            echo 'Released ' . date("F jS, Y", strtotime($release_date)); echo '<br />';
                                            echo 'Label: ' . $label; echo '<br />';
                                        ?>
                                    <!-- end .album-info --></div>
                                    <?php $content = the_content(); if ( isset( $content ) ) { ?>
                                        <div class="album-content">
                                            <?php echo $content; ?>
                                        <!-- end .album-content --></div>
                                    <?php } ?>
                                    <div class="album-tracklist">
                                        <?php
                                            $post_id = get_the_id(); $songs = get_post_meta($post_id, 'songs', true);
                                            if ( is_array ( $songs ) ) {
                                                foreach( $songs as $track ) {
                                                    if ( isset( $track['title'] ) || isset( $track['track'] ) ) {
                                                        echo $track['track']; echo '.&nbsp;'; echo $track['title'];
                                                        /*
                                                        if ( isset( $track['url'] ) ) {
                                                            echo ' <a href="' . $track['url'] . '"><i class="glyphicon glyphicon-play"></i></a>';
                                                        }
                                                        */
                                                        echo '<br />';
                                                    }
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div class="album-links">
                                        <?php
                                            $post_id = get_the_id();
                                            $itunes = get_post_meta($post_id,'album_info_itunes',true);
                                            $apple = get_post_meta($post_id,'album_info_apple-music',true);
                                            $spotify = get_post_meta($post_id,'album_info_spotify',true);
                                            $gplay = get_post_meta($post_id,'album_info_google-play',true);
                                            $pandora = get_post_meta($post_id,'album_info_pandora',true);

                                            if ( !empty( $itunes ) ) {
                                                echo '<a href="' . $itunes . '" target="_blank"><span class="socicon socicon-itunes">O</span></a> ';
                                            }
                                            if ( !empty( $apple ) ) {
                                                echo '<a href="' . $apple . '" target="_blank"><span class="glyphicon glyphicon-music"></span></a> ';
                                            }
                                            if ( !empty( $spotify ) ) {
                                                echo '<a href="' . $spotify . '" target="_blank"><span class="socicon socicon-spotify">o</span></a> ';
                                            }
                                            if ( !empty( $gplay ) ) {
                                                echo '<a href="' . $gplay . '" target="_blank"><span class="socicon socicon-play">L</span></a> ';
                                            }
                                            if ( !empty( $pandora ) ) {
                                                echo '<a href="' . $pandora . '" target="_blank"><span class="glyphicon glyphicon-cd"></span></a>';
                                            }
                                        ?>
                                    <!-- end .album-links --></div>
                                <!-- end .col-md-6 --></div>
                            <!-- end .has-text --></div>
                            <div class="has-text go-back">
                                <a href="/music">Go Back!</a>
                            </div>
                        <?php endwhile; ?>
                    <!-- end .content-box --></div>
                <!-- end .col-md-8 --></div>
            <!-- end .row --></div>
            <!-- Background Information -->
            <div class="<?php global $post; echo get_post($post)->post_name; ?>-container single-album-container" id="content-bg">
                <img src="<?php echo $bg_url[0]; ?>" class="background img-responsive center-block" />
            <!-- end #content-bg --></div>
        <!-- end .col-md-12 --></div>
    <!-- end .body-class --></div>
<!-- end #main --></div>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
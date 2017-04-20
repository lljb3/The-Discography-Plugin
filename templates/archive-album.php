<?php
/*
Template Name: Template - Music
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
            <?php $slug_id = get_music_id_by_slug('music'); $attachment_id = get_post_thumbnail_id($slug_id); $bg_url = wp_get_attachment_image_src($attachment_id, 'full', false); ?>
            <div class="row music-main" id="content">
                <div class="col-md-10 col-md-offset-1" id="container">
                    <div class="content-box">
                        <?php if ( have_posts() ): ?>
                        <div class="row" id="album-title">
                            <div class="col-md-12">
                                <h3 class="has-title archive-title pull-left">Music</h3>
                                <div id="album-types" class="button-group">
                                    <button class="btn btn-link" data-filter="*">All</button>
                                    <button class="btn btn-link" data-filter=".album">Album</button>
                                    <button class="btn btn-link" data-filter=".single">Single</button>
                                    <button class="btn btn-link hidden" data-filter=".mixtape">Mixtape</button>
                                    <button class="btn btn-link" data-filter=".ep">EP</button>
                                <!-- end .button-group --></div>
                            <!-- col-md-12 --></div>
                        <!-- end #album-title --></div>
                        <ol class="row isotope">
                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php $terms = get_the_terms($post->ID ,'albumtype'); ?>
                            <li class="isotope-item transition <?php foreach ( $terms as $term ) { echo $term->slug; } ?> col-md-4 col-xs-4" id="album-post" data-category="transition">
                                <article class="album-item">
                                    <h4 class="has-title hidden">
                                        <a href="<?php esc_url( the_permalink() ); ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a>
                                        <span class="has-title"><?php foreach ( $terms as $term ) { echo $term->name; } ?></span>
                                    <!-- end .has-text --></h4>
                                    <div class="album-thumbnail" style="float:left;">
                                        <a href="<?php esc_url( the_permalink() ); ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_post_thumbnail( 'thumbnail', array('class'=>"img-responsive attachment-post-thumbnail center-block")); ?></a>
                                    <!-- end .thumbnail --></div>
                                <!-- end .post --></article>
                            <!-- end #album-post --></li>
                        <?php endwhile; ?>
                        <!-- end .row --></ol>
                        <?php else: ?>
                        <h4 class="has-title">No posts to display</h4>
                        <?php endif; ?>
                    <!-- end .content-box --></div>
                <!-- end .col-md-12 --></div>
            <!-- end .row --></div>
            <!-- Background Information -->
            <div class="music-container" id="content-bg">
                <img src="<?php echo $bg_url[0]; ?>" class="background img-responsive center-block" />
            <!-- end #content-bg --></div>
        <!-- end .col-md-12 --></div>
    <!-- end .body-class --></div>
<!-- end #main --></div>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
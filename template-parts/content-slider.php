<?php
/**
 * The template for displaying articles in the slideshow loop
 *
 * @package Gambit
 */

?>

<li id="slide-<?php the_ID(); ?>" class="zeeslide clearfix">

	<?php // Display Post Thumbnail or default thumbnail.
	if ( has_post_thumbnail() ) :

		the_post_thumbnail( 'post-thumbnail', array( 'class' => 'slide-image' ) );

	else : ?>

		<img src="<?php echo get_template_directory_uri(); ?>/images/default-slider-image.png" class="slide-image default-slide-image wp-post-image" alt="default-image" />

	<?php endif;?>

	<div class="slide-post clearfix">

		<header class="entry-header">

			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<?php gambit_entry_meta(); ?>

		</header><!-- .entry-header -->

		<div class="entry-content clearfix">

			<?php the_excerpt(); ?>
			<?php gambit_more_link(); ?>

		</div><!-- .entry-content -->

	</div>

</li>

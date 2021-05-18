<?php
/**
 * Custom Markup for Search form
 *
 * @version 1.1
 * @package Gambit
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'gambit' ); ?></span>
		<input type="search" class="search-field"
			placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'gambit' ); ?>"
			value="<?php echo esc_html( get_search_query() ); ?>" name="s"
			title="<?php echo esc_attr_x( 'Search for:', 'label', 'gambit' ); ?>" />
	</label>
	<button type="submit" class="search-submit">
		<?php echo gambit_get_svg( 'search' ); ?>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search', 'submit button', 'gambit' ); ?></span>
	</button>
</form>

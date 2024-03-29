<?php
/**
 * Magazine Posts Sidebar Widget
 *
 * Display the latest posts from a selected category.
 * Intented to be used in one of the sidebar widget areas.
 *
 * @package Gambit
 */

/**
 * Magazine Widget Class
 */
class Gambit_Magazine_Posts_Sidebar_Widget extends WP_Widget {

	/**
	 * Widget Constructor
	 */
	function __construct() {

		// Setup Widget.
		parent::__construct(
			'gambit-magazine-posts-sidebar', // ID.
			esc_html__( 'Magazine (Sidebar)', 'gambit' ), // Name.
			array(
				'classname'                   => 'gambit-magazine-sidebar-widget',
				'description'                 => esc_html__( 'Displays your posts from a selected category. You can use this widget in the Main Sidebar widget area.', 'gambit' ),
				'customize_selective_refresh' => true,
			) // Args.
		);
	}

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'title'    => esc_html__( 'Magazine (Sidebar)', 'gambit' ),
			'category' => 0,
			'number'   => 4,
			'excerpt'  => false,
		);

		return $defaults;
	}

	/**
	 * Main Function to display the widget
	 *
	 * @uses this->render()
	 *
	 * @param array $args / Parameters from widget area created with register_sidebar().
	 * @param array $instance / Settings for this widget instance.
	 */
	function widget( $args, $instance ) {

		// Start Output Buffering.
		ob_start();

		// Get Widget Settings.
		$settings = wp_parse_args( $instance, $this->default_settings() );

		// Output.
		echo $args['before_widget'];
		?>

		<div class="widget-magazine-posts-sidebar widget-magazine-posts clearfix">

			<?php
			// Display Title.
			$this->widget_title( $args, $settings );
			?>

			<div class="widget-magazine-posts-content">

				<?php $this->render( $settings ); ?>

			</div>

		</div>

		<?php
		echo $args['after_widget'];

		// End Output Buffering.
		ob_end_flush();
	}

	/**
	 * Renders the Widget Content
	 *
	 * Switches between two or three column layout style based on widget settings
	 *
	 * @uses this->magazine_posts_two_column_sidebar() or this->magazine_posts_three_column_sidebar()
	 * @used-by this->widget()
	 *
	 * @param array $settings / Settings for this widget instance.
	 */
	function render( $settings ) {

		// Get cached post ids.
		$post_ids = gambit_get_magazine_post_ids( $this->id, $settings['category'], $settings['number'] );

		// Fetch posts from database.
		$query_arguments = array(
			'post__in'            => $post_ids,
			'posts_per_page'      => absint( $settings['number'] ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);
		$posts_query     = new WP_Query( $query_arguments );

		// Check if there are posts.
		if ( $posts_query->have_posts() ) :

			// Limit the number of words for the excerpt.
			add_filter( 'excerpt_length', 'gambit_magazine_posts_excerpt_length' );

			// Pass Excerpt Setting to template file.
			set_query_var( 'gambit_post_excerpt', (bool) $settings['excerpt'] );

			// Display Posts.
			while ( $posts_query->have_posts() ) :
				$posts_query->the_post();

				get_template_part( 'template-parts/widgets/magazine-large-post', 'sidebar' );

			endwhile;

			// Remove excerpt filter.
			remove_filter( 'excerpt_length', 'gambit_magazine_posts_excerpt_length' );

		endif;

		// Reset Postdata.
		wp_reset_postdata();
	}

	/**
	 * Displays Widget Title
	 *
	 * @param array $args / Parameters from widget area created with register_sidebar().
	 * @param array $settings / Settings for this widget instance.
	 */
	function widget_title( $args, $settings ) {

		// Add Widget Title Filter.
		$widget_title = apply_filters( 'widget_title', $settings['title'], $settings, $this->id_base );

		if ( ! empty( $widget_title ) ) :

			// Link Widget Title to category archive when possible.
			$widget_title = gambit_magazine_widget_title( $widget_title, $settings['category'] );

			// Display Widget Title.
			echo $args['before_title'] . $widget_title . $args['after_title'];

		endif;
	}

	/**
	 * Update Widget Settings
	 *
	 * @param array $new_instance / New Settings for this widget instance.
	 * @param array $old_instance / Old Settings for this widget instance.
	 * @return array $instance
	 */
	function update( $new_instance, $old_instance ) {

		$instance             = $old_instance;
		$instance['title']    = sanitize_text_field( $new_instance['title'] );
		$instance['category'] = (int) $new_instance['category'];
		$instance['number']   = (int) $new_instance['number'];
		$instance['excerpt']  = ! empty( $new_instance['excerpt'] );

		gambit_flush_magazine_post_ids();

		return $instance;
	}

	/**
	 * Displays Widget Settings Form in the Backend
	 *
	 * @param array $instance / Settings for this widget instance.
	 */
	function form( $instance ) {

		// Get Widget Settings.
		$settings = wp_parse_args( $instance, $this->default_settings() );
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'gambit' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php esc_html_e( 'Category:', 'gambit' ); ?></label><br/>
			<?php
			// Display Category Select.
				$args = array(
					'show_option_all' => esc_html__( 'All Categories', 'gambit' ),
					'show_count'      => true,
					'hide_empty'      => false,
					'selected'        => $settings['category'],
					'name'            => $this->get_field_name( 'category' ),
					'id'              => $this->get_field_id( 'category' ),
				);
				wp_dropdown_categories( $args );
				?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of posts:', 'gambit' ); ?>
				<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo (int) $settings['number']; ?>" size="3" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'excerpt' ); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['excerpt'] ); ?> id="<?php echo $this->get_field_id( 'excerpt' ); ?>" name="<?php echo $this->get_field_name( 'excerpt' ); ?>" />
				<?php esc_html_e( 'Display post excerpt', 'gambit' ); ?>
			</label>
		</p>

		<?php
	}
}

/**
 * Register Widget
 */
function gambit_register_magazine_posts_sidebar_widget() {

	register_widget( 'Gambit_Magazine_Posts_Sidebar_Widget' );

}
add_action( 'widgets_init', 'gambit_register_magazine_posts_sidebar_widget' );

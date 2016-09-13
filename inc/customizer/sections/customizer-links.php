<?php
/**
 * Theme Links Section
 *
 * Registers Theme Info Section for theme
 *
 * @package Gambit
 */

/**
 * Adds theme links
 *
 * @param object $wp_customize / Customizer Object.
 */
function gambit_customize_register_links_settings( $wp_customize ) {

	// Add Upgrade / More Features Section.
	$wp_customize->add_section( 'gambit_section_links', array(
		'title'    => esc_html__( 'Theme Info', 'gambit' ),
		'priority' => 100,
		'panel' => 'gambit_options_panel',
		)
	);

	// Add custom Theme Links Content control.
	$wp_customize->add_setting( 'gambit_theme_options[links]', array(
		'default'           => '',
		'type'           	=> 'option',
		'transport'         => 'refresh',
		'sanitize_callback' => 'esc_attr',
		)
	);
	$wp_customize->add_control( new Gambit_Customize_Theme_Links_Control(
		$wp_customize, 'gambit_theme_options[links]', array(
		'section' => 'gambit_section_links',
		'settings' => 'gambit_theme_options[links]',
		'priority' => 1,
		)
	) );

}
add_action( 'customize_register', 'gambit_customize_register_links_settings' );

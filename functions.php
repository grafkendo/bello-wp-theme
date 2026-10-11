<?php
/**
 * Bello child theme for Astra.
 * Loads Sora (display) + Inter (body) and the Bello brand stylesheet.
 */

add_action( 'wp_enqueue_scripts', function() {
	// Brand fonts: Sora + Inter from Google Fonts.
	wp_enqueue_style(
		'bello-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Sora:wght@400;600;700&display=swap',
		array(),
		null
	);

	// Parent theme stylesheet.
	wp_enqueue_style(
		'astra-theme-css',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'astra' )->get( 'Version' )
	);

	// Child theme stylesheet (this file's style.css), after the parent.
	wp_enqueue_style(
		'bello-style',
		get_stylesheet_uri(),
		array( 'astra-theme-css', 'bello-fonts' ),
		wp_get_theme()->get( 'Version' )
	);
}, 20 );

/**
 * Preconnect to Google Fonts for a touch more speed.
 */
add_action( 'wp_head', function() {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1 );

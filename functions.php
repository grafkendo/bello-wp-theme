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

/**
 * SEO additions (2026-10-15):
 * 1. Complete Service schema on /featured-partner/ — Rank Math's free builder
 *    could not carry price/provider/areaServed, so the theme emits the
 *    canonical copy (Rank Math's partial Service on that page gets removed).
 * 2. "Is this your business?" owner CTA appended to GeoDirectory listing
 *    content — a real internal link from every listing to /featured-partner/.
 */
add_action( 'wp_head', function() {
	if ( ! is_page( 'featured-partner' ) ) {
		return;
	}
	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Service',
		'@id'         => 'https://bellolist.com/featured-partner/#service',
		'name'        => 'Bello Featured Partner',
		'serviceType' => 'Featured plumber listing and lead matching',
		'description' => "Own the #1 match slot in Bello's Des Moines plumber quiz plus every callback lead for your trade — one Featured Partner per category, never shared with competing shops.",
		'provider'    => array(
			'@type'     => 'Organization',
			'name'      => 'Bello',
			'url'       => 'https://bellolist.com',
			'telephone' => '+1-515-329-6211',
			'email'     => 'hello@bellolist.com',
		),
		'areaServed'  => array(
			'@type'            => 'City',
			'name'             => 'Des Moines',
			'containedInPlace' => array(
				'@type' => 'State',
				'name'  => 'Iowa',
			),
		),
		'offers'      => array(
			'@type'              => 'Offer',
			'price'              => '750',
			'priceCurrency'      => 'USD',
			'description'        => 'Flat monthly Featured Partner rate. No per-lead fees for matched quiz and callback leads.',
			'priceSpecification' => array(
				'@type'         => 'UnitPriceSpecification',
				'price'         => '750',
				'priceCurrency' => 'USD',
				'unitText'      => 'MON',
			),
		),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}, 30 );

add_filter( 'the_content', function( $content ) {
	if ( is_singular( 'gd_place' ) && in_the_loop() && is_main_query() ) {
		$content .= '<div class="bello-owner-cta" style="margin:28px 0 8px;padding:18px 20px;background:#FAF6EE;border-left:4px solid #FB4F14;border-radius:6px;">'
			. '<p style="margin:0 0 6px;font-family:Sora,sans-serif;font-weight:700;font-size:18px;color:#0A1628;">Is this your business?</p>'
			. '<p style="margin:0;font-size:15px;line-height:1.55;color:#0A1628;">Claim your free listing — or own the #1 quiz match as Bello\'s <a href="https://bellolist.com/featured-partner/" style="color:#FB4F14;font-weight:600;">Featured Partner</a>. One partner per trade; the slot is never shared.</p>'
			. '</div>';
	}
	return $content;
} );

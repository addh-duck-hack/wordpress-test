<?php
/**
 * Setup del tema de propuesta "Nocturno Directo" para De Reporteros.
 */

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	register_nav_menus( [
		'primary' => __( 'Menú principal', 'dereporteros-nocturno-directo' ),
	] );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'dereporteros-nocturno-directo-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow:wght@700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap',
		[],
		null
	);
	wp_enqueue_style(
		'dereporteros-nocturno-directo-style',
		get_stylesheet_uri(),
		[ 'dereporteros-nocturno-directo-fonts' ],
		wp_get_theme()->get( 'Version' )
	);
} );

/**
 * Menú de respaldo cuando no se ha asignado un menú en Apariencia > Menús:
 * lista las categorías existentes para que la nav nunca se vea vacía.
 */
function dereporteros_nocturno_directo_nav_fallback() {
	echo '<nav class="main-nav">';
	$cats = get_categories( [ 'number' => 6, 'hide_empty' => false ] );
	foreach ( $cats as $i => $cat ) {
		printf(
			'<a class="%s" href="%s">%s</a>',
			$i === 0 ? 'active' : '',
			esc_url( get_category_link( $cat ) ),
			esc_html( $cat->name )
		);
	}
	echo '</nav>';
}

if ( ! function_exists( 'dereporteros_category_name' ) ) {
	/**
	 * Nombre de la primera categoría de una entrada, con fallback genérico.
	 */
	function dereporteros_category_name( $post_id ) {
		$cats = get_the_category( $post_id );
		return ! empty( $cats ) ? $cats[0]->name : 'General';
	}
}

if ( ! function_exists( 'dereporteros_pill_class' ) ) {
	/**
	 * Alterna la clase de color del pill (verde/alerta) según posición,
	 * solo para variedad visual entre tarjetas.
	 */
	function dereporteros_pill_class( $index ) {
		return ( $index % 2 === 0 ) ? 'green' : 'alert';
	}
}

if ( ! function_exists( 'dereporteros_thumb_src' ) ) {
	/**
	 * URL de imagen destacada con fallback a un placeholder propio del
	 * tema cuando la entrada no tiene una asignada.
	 */
	function dereporteros_thumb_src( $post_id, $size = 'medium' ) {
		$src = get_the_post_thumbnail_url( $post_id, $size );
		return $src ? $src : get_template_directory_uri() . '/images/placeholder.svg';
	}
}

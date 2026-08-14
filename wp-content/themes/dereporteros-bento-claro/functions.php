<?php
/**
 * Setup del tema de propuesta "Bento Claro" para De Reporteros.
 */

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	register_nav_menus( [
		'primary' => __( 'Menú principal', 'dereporteros-bento-claro' ),
	] );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'dereporteros-bento-claro-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow:wght@700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap',
		[],
		null
	);
	wp_enqueue_style(
		'dereporteros-bento-claro-style',
		get_stylesheet_uri(),
		[ 'dereporteros-bento-claro-fonts' ],
		wp_get_theme()->get( 'Version' )
	);
} );

/**
 * Menú de respaldo cuando no se ha asignado un menú en Apariencia > Menús:
 * lista las categorías existentes para que la nav nunca se vea vacía.
 */
function dereporteros_bento_claro_nav_fallback() {
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

/**
 * Nombre de la primera categoría de una entrada, con fallback genérico.
 */
function dereporteros_category_name( $post_id ) {
	$cats = get_the_category( $post_id );
	return ! empty( $cats ) ? $cats[0]->name : 'General';
}

/**
 * Categoría a mostrar junto a cada nota del cintillo de última hora.
 * "ultima-hora" es una etiqueta (no categoría), así que ya no hay
 * necesidad de excluir nada: se reutiliza el nombre de categoría normal.
 */
function dereporteros_ticker_category_name( $post_id ) {
	return dereporteros_category_name( $post_id );
}

/**
 * Nombre de la primera etiqueta (tag) de una entrada, o cadena vacía si no
 * tiene ninguna — para pills que muestran tag en vez de categoría.
 */
function dereporteros_first_tag_name( $post_id ) {
	$tags = get_the_tags( $post_id );
	return ( $tags && ! empty( $tags ) ) ? $tags[0]->name : '';
}

/**
 * Alterna la clase de color del pill (verde/alerta) según posición,
 * solo para variedad visual entre tarjetas.
 */
function dereporteros_pill_class( $index ) {
	return ( $index % 2 === 0 ) ? 'green' : 'alert';
}

/**
 * URL de imagen destacada con fallback a un placeholder propio del tema
 * cuando la entrada no tiene una asignada, para no romper el layout.
 */
function dereporteros_thumb_src( $post_id, $size = 'medium' ) {
	$src = get_the_post_thumbnail_url( $post_id, $size );
	return $src ? $src : get_template_directory_uri() . '/images/placeholder.svg';
}

/**
 * Fecha de hoy en español ("12 de agosto"), con los nombres de mes escritos
 * a mano en vez de depender del locale configurado en WordPress — el resto
 * del tema ya maneja sus textos en español así, sin i18n.
 */
function dereporteros_fecha_hoy() {
	$meses = [
		1 => 'enero',
		2 => 'febrero',
		3 => 'marzo',
		4 => 'abril',
		5 => 'mayo',
		6 => 'junio',
		7 => 'julio',
		8 => 'agosto',
		9 => 'septiembre',
		10 => 'octubre',
		11 => 'noviembre',
		12 => 'diciembre',
	];
	return sprintf( '%d de %s', (int) current_time( 'j' ), $meses[ (int) current_time( 'n' ) ] );
}

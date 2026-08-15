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

if ( ! function_exists( 'dereporteros_ticker_category_name' ) ) {
	/**
	 * Categoría a mostrar junto a cada nota del cintillo de última hora.
	 * "ultima-hora" es una etiqueta (no categoría), así que ya no hay
	 * necesidad de excluir nada: se reutiliza el nombre de categoría
	 * normal.
	 */
	function dereporteros_ticker_category_name( $post_id ) {
		return dereporteros_category_name( $post_id );
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

if ( ! function_exists( 'dereporteros_has_tagged_posts' ) ) {
	/**
	 * True si existe al menos una entrada publicada con la etiqueta dada.
	 * Query barata (solo IDs) para secciones que solo necesitan saber si
	 * van a tener contenido, sin cargar el contenido en sí (p. ej. el
	 * aviso de "sin notas" en index.php, sin repetir la consulta completa
	 * del hero).
	 */
	function dereporteros_has_tagged_posts( $tag ) {
		$query = new WP_Query( [
			'tag'                     => $tag,
			'posts_per_page'          => 1,
			'fields'                  => 'ids',
			'ignore_sticky_posts'     => true,
			'no_found_rows'           => true,
			'update_post_meta_cache'  => false,
			'update_post_term_cache'  => false,
		] );
		return ! empty( $query->posts );
	}
}

if ( ! function_exists( 'dereporteros_category_link' ) ) {
	/**
	 * URL del archivo de la primera categoría de una entrada, para que el
	 * pill de categoría (single.php, tarjetas, etc.) enlace a algo —
	 * cadena vacía si no tiene categoría (fallback "General").
	 */
	function dereporteros_category_link( $post_id ) {
		$cats = get_the_category( $post_id );
		return ! empty( $cats ) ? get_category_link( $cats[0]->term_id ) : '';
	}
}

if ( ! function_exists( 'dereporteros_author_link' ) ) {
	/**
	 * URL del archivo de autor de una entrada, para enlazar el nombre del
	 * autor en tarjetas/hero a su página de autor.
	 */
	function dereporteros_author_link( $post_id ) {
		return get_author_posts_url( get_post_field( 'post_author', $post_id ) );
	}
}

if ( ! function_exists( 'dereporteros_comment_html' ) ) {
	/**
	 * Callback de wp_list_comments() para el bloque de comentarios de la
	 * entrada (single.php) — un comentario por línea, sin hilos anidados
	 * ni "Responder".
	 */
	function dereporteros_comment_html( $comment, $args, $depth ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		?>
		<<?php echo esc_html( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item' ); ?>>
			<?php echo get_avatar( $comment, 44, '', '', [ 'class' => 'comment-avatar' ] ); ?>
			<div class="comment-body">
				<div class="comment-meta">
					<span class="comment-author"><?php comment_author(); ?></span>
					<span class="comment-date meta-mono"><?php echo esc_html( get_comment_date( 'j M Y' ) ); ?></span>
				</div>
				<?php if ( '0' === $comment->comment_approved ) : ?>
				<p class="comment-pending meta-mono">Tu comentario está pendiente de moderación.</p>
				<?php endif; ?>
				<div class="comment-text"><?php comment_text(); ?></div>
			</div>
		<?php
	}
}

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

/**
 * Oculta en todo el sitio (frontend) las notas de la categoría
 * "sin-categoria" — típicamente entradas a las que nunca se les asignó una
 * categoría real. Se aplica a nivel de pre_get_posts en vez de en cada
 * WP_Query del tema para que funcione igual en el cintillo, el hero, los
 * grids, el carousel, etc. sin tener que repetir la exclusión en cada uno.
 */
add_action( 'pre_get_posts', function ( $query ) {
	if ( is_admin() ) {
		return;
	}
	// Excepción: las notas de publicidad viven a propósito en
	// "sin-categoria" (solo las identifica su etiqueta 'publicidad1' /
	// 'publicidad2'), así que las consultas de los banners no deben
	// excluir esa categoría o se quedarían sin anuncio que mostrar.
	if ( in_array( $query->get( 'tag' ), [ 'publicidad1', 'publicidad2' ], true ) ) {
		return;
	}
	$sin_categoria = get_category_by_slug( 'sin-categoria' );
	if ( ! $sin_categoria ) {
		return;
	}
	$excluded   = $query->get( 'category__not_in' );
	$excluded   = $excluded ? (array) $excluded : [];
	$excluded[] = $sin_categoria->term_id;
	$query->set( 'category__not_in', array_unique( $excluded ) );
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
 * "Inicio" primero (destacado en verde) y, después, las categorías con las
 * notas más recientes, para que la nav siempre refleje lo que está activo
 * en el sitio en vez de un orden fijo.
 */
function dereporteros_bento_claro_nav_fallback() {
	echo '<nav class="main-nav">';
	printf(
		'<a class="active" href="%s">%s</a>',
		esc_url( home_url( '/' ) ),
		esc_html__( 'Inicio', 'dereporteros-bento-claro' )
	);
	foreach ( dereporteros_recent_categories( 4 ) as $cat ) {
		printf(
			'<a href="%s">%s</a>',
			esc_url( get_category_link( $cat ) ),
			esc_html( $cat->name )
		);
	}
	echo '</nav>';
}

/**
 * Las $limit categorías con las notas más recientes, en orden de
 * recencia (no alfabético ni por cantidad de notas): recorre las últimas
 * entradas publicadas y va tomando la primera categoría de cada una hasta
 * juntar $limit categorías distintas.
 */
function dereporteros_recent_categories( $limit = 4 ) {
	$query = new WP_Query( [
		'posts_per_page'          => 20,
		'orderby'                 => 'date',
		'order'                   => 'DESC',
		'ignore_sticky_posts'     => true,
		'no_found_rows'           => true,
		'update_post_meta_cache'  => false,
	] );

	$cats = [];
	foreach ( $query->posts as $recent_post ) {
		$post_cats = get_the_category( $recent_post->ID );
		if ( empty( $post_cats ) ) {
			continue;
		}
		$cat = $post_cats[0];
		if ( ! isset( $cats[ $cat->term_id ] ) ) {
			$cats[ $cat->term_id ] = $cat;
		}
		if ( count( $cats ) >= $limit ) {
			break;
		}
	}
	return array_values( $cats );
}

/**
 * Nombre de la primera categoría de una entrada, con fallback genérico.
 */
function dereporteros_category_name( $post_id ) {
	$cats = get_the_category( $post_id );
	return ! empty( $cats ) ? $cats[0]->name : 'General';
}

/**
 * URL del archivo de la primera categoría de una entrada, para que el pill
 * de categoría (single.php, etc.) enlace a algo — cadena vacía si no tiene
 * categoría (fallback "General"), así el pill puede quedarse como texto.
 */
function dereporteros_category_link( $post_id ) {
	$cats = get_the_category( $post_id );
	return ! empty( $cats ) ? get_category_link( $cats[0]->term_id ) : '';
}

/**
 * URL del archivo de autor de una entrada, para enlazar el nombre del
 * autor en tarjetas/hero (nota del día, tile-hero) a su página de autor.
 */
function dereporteros_author_link( $post_id ) {
	return get_author_posts_url( get_post_field( 'post_author', $post_id ) );
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
 * ID de la entrada más reciente con una etiqueta dada, o null. Query barata
 * usada por componentes que necesitan una sola nota (p. ej. "nota del día")
 * y por otras secciones que necesitan excluir esa misma nota.
 */
function dereporteros_latest_id_by_tag( $tag ) {
	$query = new WP_Query( [
		'tag'                     => $tag,
		'posts_per_page'          => 1,
		'orderby'                 => 'date',
		'order'                   => 'DESC',
		'ignore_sticky_posts'     => true,
		'no_found_rows'           => true,
		'update_post_meta_cache'  => false,
		'update_post_term_cache'  => false,
	] );
	return $query->posts[0]->ID ?? null;
}

/**
 * True si existe al menos una entrada publicada con la etiqueta dada. Query
 * barata (solo IDs) para secciones que solo necesitan saber si van a tener
 * contenido, sin cargar el contenido en sí (p. ej. el aviso de "sin notas").
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

/**
 * Primer link encontrado en el cuerpo de una nota, o cadena vacía si no
 * tiene ninguno. Usado por los espacios publicitarios dinámicos: la nota
 * que alimenta el banner trae, en su contenido, el link real de destino
 * del anuncio (a donde sea que el anunciante quiera mandar al usuario).
 */
function dereporteros_first_link_in_content( $post_id ) {
	$content = get_post_field( 'post_content', $post_id );
	if ( $content && preg_match( '/<a\s[^>]*href=["\']([^"\']+)["\']/i', $content, $matches ) ) {
		return esc_url_raw( $matches[1] );
	}
	return '';
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

/**
 * Callback de wp_list_comments() para el bloque de comentarios de la
 * entrada (template-parts/comments.php) — un comentario por línea, sin
 * hilos anidados ni "Responder", en línea con el resto del tema.
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

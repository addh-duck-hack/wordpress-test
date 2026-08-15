<?php
/**
 * Componente: grid de notas (categoría + título por tarjeta). Dos formas
 * de alimentarlo:
 *   - 'ids': recibe la lista de IDs a mostrar por parámetro, igual que
 *     latest-feed.php/trend-card.php — para "Metrópoli y México" en la
 *     portada (sobras de la consulta general) o "Recomendado para ti" en
 *     single.php (notas al azar), sin atarse a una categoría.
 *   - 'source': si no se pasan 'ids', consulta por categoría propia (ej.
 *     "Fotografía", "Seguridad") y agrega un link "Ver más →" al archivo
 *     de esa categoría.
 * (Las categorías/etiquetas/autores tienen su propio listado paginado en
 * archive.php — este componente no vive ahí.)
 *
 * $args:
 *   'title'  (string) — título de la sección.
 *   'ids'    (int[])  — IDs de las entradas a mostrar. Tiene prioridad
 *                       sobre 'source' si se pasan ambos.
 *   'source' (string) — slug de categoría a consultar (ignorado si 'ids'
 *                       trae algo).
 *   'count'  (int)    — cuántas notas traer cuando se usa 'source'. Default 4.
 *   'narrow' (bool)   — true: ancho de columna de artículo (760px, 3 por
 *                       fila) en vez del ancho completo (1200px, 4 por
 *                       fila) — para cuando el bloque va debajo de un
 *                       cuerpo de texto angosto (ej. single.php). Default false.
 */
$dereporteros_grid_args = wp_parse_args( $args ?? [], [
	'title'  => 'Metrópoli y México',
	'ids'    => [],
	'source' => '',
	'count'  => 4,
	'narrow' => false,
] );

$grid_ids               = $dereporteros_grid_args['ids'];
$dereporteros_grid_link = '';

if ( ! $grid_ids && $dereporteros_grid_args['source'] ) {
	$dereporteros_grid_query = new WP_Query( [
		'category_name'           => $dereporteros_grid_args['source'],
		'posts_per_page'          => $dereporteros_grid_args['count'],
		'ignore_sticky_posts'     => true,
		'no_found_rows'           => true,
		'update_post_meta_cache'  => false,
		'update_post_term_cache'  => false,
	] );
	$grid_ids               = wp_list_pluck( $dereporteros_grid_query->posts, 'ID' );
	$dereporteros_grid_cat  = get_category_by_slug( $dereporteros_grid_args['source'] );
	$dereporteros_grid_link = $dereporteros_grid_cat ? get_category_link( $dereporteros_grid_cat ) : '';
}

if ( $grid_ids ) :
	?>
<section class="grid-section wrap<?php echo $dereporteros_grid_args['narrow'] ? ' is-narrow' : ''; ?>">
	<div class="section-head">
		<span class="dot"></span>
		<h2><?php echo esc_html( $dereporteros_grid_args['title'] ); ?></h2>
		<?php if ( $dereporteros_grid_link ) : ?>
		<a class="section-see-all" href="<?php echo esc_url( $dereporteros_grid_link ); ?>">Ver más →</a>
		<?php endif; ?>
	</div>
	<div class="grid-4">
		<?php foreach ( $grid_ids as $i => $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
		<div class="card">
			<img src="<?php echo esc_url( dereporteros_thumb_src( $id, 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>">
			<div class="body">
				<a class="pill <?php echo esc_attr( dereporteros_pill_class( $i ) ); ?>" href="<?php echo esc_url( dereporteros_category_link( $id ) ); ?>"><?php echo esc_html( dereporteros_category_name( $id ) ); ?></a>
				<h3><a class="card-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			</div>
		</div>
		<?php endforeach; wp_reset_postdata(); ?>
	</div>
</section>
<?php endif; ?>

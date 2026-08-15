<?php
/**
 * Componente: grid de notas (categoría + título por tarjeta). No consulta
 * por categoría propia: recibe la lista de IDs a mostrar por parámetro,
 * igual que latest-feed.php/trend-card.php — así sirve tanto para
 * "Metrópoli y México" en la portada (sobras de la consulta general) como
 * para "Recomendado para ti" en single.php (notas al azar), sin atarse a
 * una única fuente de datos. (Las categorías/etiquetas/autores tienen su
 * propio listado paginado en archive.php — este componente no vive ahí.)
 *
 * $args:
 *   'title'  (string) — título de la sección.
 *   'ids'    (int[])  — IDs de las entradas a mostrar.
 *   'narrow' (bool)   — true: ancho de columna de artículo (760px, 3 por
 *                       fila) en vez del ancho completo (1200px, 4 por
 *                       fila) — para cuando el bloque va debajo de un
 *                       cuerpo de texto angosto (ej. single.php). Default false.
 */
$dereporteros_grid_args = wp_parse_args( $args ?? [], [
	'title'  => 'Metrópoli y México',
	'ids'    => [],
	'narrow' => false,
] );

if ( $dereporteros_grid_args['ids'] ) :
	?>
<section class="grid-section wrap<?php echo $dereporteros_grid_args['narrow'] ? ' is-narrow' : ''; ?>">
	<div class="section-head"><span class="dot"></span><h2><?php echo esc_html( $dereporteros_grid_args['title'] ); ?></h2></div>
	<div class="grid-4">
		<?php foreach ( $dereporteros_grid_args['ids'] as $i => $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
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

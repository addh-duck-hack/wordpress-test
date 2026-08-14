<?php
/**
 * Componente: columna tipo feed con imagen (título + categoría + hora de
 * publicación por fila). A diferencia de las demás secciones no consulta
 * por categoría/etiqueta propia: reutiliza lo que sobra de la consulta
 * general de la portada, ya resuelto por index.php.
 *
 * $args:
 *   'title' (string) — título de la sección.
 *   'ids'   (int[])  — IDs de las entradas a mostrar.
 */
$dereporteros_feed_args = wp_parse_args( $args ?? [], [
	'title' => 'Más leídas',
	'ids'   => [],
] );

if ( $dereporteros_feed_args['ids'] ) :
	?>
<div class="feed-col">
	<div class="section-head"><h2><?php echo esc_html( $dereporteros_feed_args['title'] ); ?></h2></div>
	<?php foreach ( $dereporteros_feed_args['ids'] as $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
	<div class="feed-row">
		<div class="feed-thumb"><img src="<?php echo esc_url( dereporteros_thumb_src( $id, 'thumbnail' ) ); ?>" alt="<?php the_title_attribute(); ?>"></div>
		<div class="feed-row-body">
			<span class="time mono"><?php the_time( 'H:i' ); ?></span>
			<h3><a class="feed-row-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<a class="sec" href="<?php echo esc_url( dereporteros_category_link( $id ) ); ?>"><?php echo esc_html( dereporteros_category_name( $id ) ); ?></a>
		</div>
	</div>
	<?php endforeach; wp_reset_postdata(); ?>
</div>
<?php endif; ?>

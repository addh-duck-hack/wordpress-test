<?php
/**
 * Componente: columna tipo feed con imagen (categoría + título + extracto
 * por fila). No consulta por categoría/etiqueta propia: reutiliza lo que
 * sobra de la consulta general de la portada, ya resuelto por index.php.
 *
 * $args:
 *   'title' (string) — título de la sección.
 *   'ids'   (int[])  — IDs de las entradas a mostrar.
 */
$dereporteros_feed_args = wp_parse_args( $args ?? [], [
	'title' => 'Últimas noticias',
	'ids'   => [],
] );

if ( $dereporteros_feed_args['ids'] ) :
	?>
<div class="latest-col">
	<div class="section-head"><span class="dot"></span><h2><?php echo esc_html( $dereporteros_feed_args['title'] ); ?></h2></div>
	<?php foreach ( $dereporteros_feed_args['ids'] as $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
	<div class="latest-row">
		<img src="<?php echo esc_url( dereporteros_thumb_src( $id, 'thumbnail' ) ); ?>" alt="<?php the_title_attribute(); ?>">
		<div>
			<a class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>" href="<?php echo esc_url( dereporteros_category_link( $id ) ); ?>"><?php echo esc_html( dereporteros_category_name( $id ) ); ?></a>
			<h3><a class="latest-row-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<p class="dek"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p>
		</div>
	</div>
	<?php endforeach; wp_reset_postdata(); ?>
</div>
<?php endif; ?>

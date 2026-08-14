<?php
/**
 * Componente: tarjeta de "más leídas". Igual que latest-feed.php, reutiliza
 * IDs ya resueltos por index.php en vez de consultar por categoría/etiqueta.
 *
 * $args:
 *   'title' (string) — título de la sección.
 *   'ids'   (int[])  — IDs de las entradas a mostrar.
 */
$dereporteros_trend_args = wp_parse_args( $args ?? [], [
	'title' => 'Más leídas',
	'ids'   => [],
] );

if ( $dereporteros_trend_args['ids'] ) :
	?>
<div class="trend-card">
	<h2><?php echo esc_html( $dereporteros_trend_args['title'] ); ?></h2>
	<?php foreach ( $dereporteros_trend_args['ids'] as $n => $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
	<div class="trend-item">
		<span class="n"><?php echo (int) $n + 1; ?></span>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	</div>
	<?php endforeach; wp_reset_postdata(); ?>
</div>
<?php endif; ?>

<?php
/**
 * Componente: mosaico de las últimas 4 notas del sitio (sin filtrar por
 * categoría/etiqueta) — a=grande, b=mediana, c/d=apiladas a la derecha.
 * Solo se imprime en la portada (home, sin paginar).
 *
 * $args:
 *   'count' (int) — cuántas notas mostrar. Default 4 (el layout está
 *                    pensado para exactamente 4 slots: hl-a/b/c/d).
 */
$dereporteros_hl_args = wp_parse_args( $args ?? [], [
	'count' => 4,
] );

$dereporteros_hero_latest = new WP_Query( [
	'posts_per_page'         => $dereporteros_hl_args['count'],
	'ignore_sticky_posts'    => true,
	'no_found_rows'          => true,
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
] );

if ( is_front_page() && is_home() && ! is_paged() && $dereporteros_hero_latest->have_posts() ) :
	$dereporteros_hl_slots = [ 'hl-a', 'hl-b', 'hl-c', 'hl-d' ];
	?>
<section class="hero-latest">
	<?php
	$dereporteros_hl_i = 0;
	while ( $dereporteros_hero_latest->have_posts() ) : $dereporteros_hero_latest->the_post();
		$dereporteros_hl_id = get_the_ID();
		?>
	<div class="hl-tile <?php echo esc_attr( $dereporteros_hl_slots[ $dereporteros_hl_i ] ); ?>">
		<a class="hl-cover" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>"></a>
		<img src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_hl_id, 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>">
		<div class="hl-scrim"></div>
		<div class="hl-body">
			<a class="pill green" href="<?php echo esc_url( dereporteros_category_link( $dereporteros_hl_id ) ); ?>"><?php echo esc_html( dereporteros_category_name( $dereporteros_hl_id ) ); ?></a>
			<h3 class="hl-title"><?php the_title(); ?></h3>
		</div>
	</div>
	<?php $dereporteros_hl_i++; endwhile; wp_reset_postdata(); ?>
</section>
<?php endif; ?>

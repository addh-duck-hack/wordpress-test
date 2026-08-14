<?php
/**
 * Componente: hero de 4 notas grandes con cinta/ribbon de sección. Usado
 * actualmente para "Espectáculos".
 *
 * $args:
 *   'source' (string) — slug de la categoría a consultar.
 *   'title'  (string) — texto de la cinta/ribbon.
 *   'icon'   (string) — emoji junto al texto de la cinta (opcional, por
 *                        defecto el de "Espectáculos"; cámbialo si reusas
 *                        este componente para otra categoría).
 */
$dereporteros_hl_args = wp_parse_args( $args ?? [], [
	'source' => 'espectaculos',
	'title'  => 'Espectáculos',
	'icon'   => '🎭',
] );

$dereporteros_hero_latest = new WP_Query( [
	'category_name'          => $dereporteros_hl_args['source'],
	'posts_per_page'         => 4,
	'ignore_sticky_posts'    => true,
	'no_found_rows'          => true,
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
] );

if ( is_front_page() && is_home() && ! is_paged() && $dereporteros_hero_latest->have_posts() ) :
	$dereporteros_hl_slots = [ 'hl-a', 'hl-b', 'hl-c', 'hl-d' ];
	?>
<section class="hero-latest">
	<span class="hl-ribbon"><span aria-hidden="true"><?php echo esc_html( $dereporteros_hl_args['icon'] ); ?></span> <?php echo esc_html( $dereporteros_hl_args['title'] ); ?></span>
	<?php
	$dereporteros_hl_i = 0;
	while ( $dereporteros_hero_latest->have_posts() ) : $dereporteros_hero_latest->the_post();
		$dereporteros_hl_id  = get_the_ID();
		$dereporteros_hl_tag = dereporteros_first_tag_name( $dereporteros_hl_id );
		?>
	<a class="hl-tile <?php echo esc_attr( $dereporteros_hl_slots[ $dereporteros_hl_i ] ); ?>" href="<?php the_permalink(); ?>">
		<img src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_hl_id, 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>">
		<div class="hl-scrim"></div>
		<div class="hl-body">
			<?php if ( $dereporteros_hl_tag ) : ?>
			<span class="pill green"><?php echo esc_html( $dereporteros_hl_tag ); ?></span>
			<?php endif; ?>
			<h3 class="hl-title"><?php the_title(); ?></h3>
		</div>
	</a>
	<?php $dereporteros_hl_i++; endwhile; wp_reset_postdata(); ?>
</section>
<?php endif; ?>

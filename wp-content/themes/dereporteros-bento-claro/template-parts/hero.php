<?php
/**
 * Componente: hero bento (nota principal + recomendación + mini nota).
 *
 * $args:
 *   'source'  (string) — slug de la etiqueta a consultar (curaduría manual de portada).
 *   'title'   (string) — título accesible de la sección (no se imprime visualmente,
 *                         solo como aria-label; el hero no lleva encabezado propio).
 *   'exclude' (int[])  — IDs adicionales a excluir de la recomendación aleatoria
 *                         (p. ej. la nota ya mostrada en "Nota del día").
 */
$dereporteros_hero_args = wp_parse_args( $args ?? [], [
	'source'  => 'portada',
	'title'   => 'Portada',
	'exclude' => [],
] );

$dereporteros_portada_query = new WP_Query( [
	'tag'                      => $dereporteros_hero_args['source'],
	'posts_per_page'          => 2,
	'ignore_sticky_posts'     => true,
	'no_found_rows'           => true,
	'update_post_meta_cache'  => false,
	'update_post_term_cache'  => false,
] );
$dereporteros_portada_ids = wp_list_pluck( $dereporteros_portada_query->posts, 'ID' );
$hero_id                  = $dereporteros_portada_ids[0] ?? null;
$mini_id                  = $dereporteros_portada_ids[1] ?? null;

if ( $hero_id ) :
	$post = get_post( $hero_id ); setup_postdata( $post );
	?>
<section class="bento wrap" aria-label="<?php echo esc_attr( $dereporteros_hero_args['title'] ); ?>">
	<div class="bento-grid">

		<div class="tile tile-hero">
			<div class="imgwrap">
				<img src="<?php echo esc_url( dereporteros_thumb_src( $hero_id, 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>">
				<a class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>" href="<?php echo esc_url( dereporteros_category_link( $hero_id ) ); ?>"><?php echo esc_html( dereporteros_category_name( $hero_id ) ); ?></a>
			</div>
			<div class="body">
				<h1><a class="tile-hero-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<p class="dek"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
				<span class="meta-mono"><a class="tile-hero-author" href="<?php echo esc_url( dereporteros_author_link( $hero_id ) ); ?>"><?php the_author(); ?></a> · hace <?php echo esc_html( human_time_diff( get_the_time( 'U' ) ) ); ?></span>
			</div>
		</div>

		<?php
		// Recomendación aleatoria para el tile de fecha: cualquier nota que
		// no sea la que ya se muestra en el hero, en el mini-tile, ni en
		// las secciones excluidas explícitamente vía $args['exclude'].
		$dereporteros_recommend_query = new WP_Query( [
			'posts_per_page'         => 1,
			'orderby'                => 'rand',
			'post__not_in'           => array_filter( array_merge( [ $hero_id, $mini_id ], $dereporteros_hero_args['exclude'] ) ),
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
		] );
		$dereporteros_recommend_id = $dereporteros_recommend_query->posts[0]->ID ?? null;
		?>
		<div class="tile tile-stat">
			<span class="stat-date">Hoy, <?php echo esc_html( dereporteros_fecha_hoy() ); ?></span>
			<span class="stat-lead">Te recomendamos leer</span>
			<?php if ( $dereporteros_recommend_id ) : ?>
			<a class="stat-rec" href="<?php echo esc_url( get_permalink( $dereporteros_recommend_id ) ); ?>">
				<span class="stat-rec-title"><?php echo esc_html( get_the_title( $dereporteros_recommend_id ) ); ?></span>
				<span class="stat-rec-meta">hace <?php echo esc_html( human_time_diff( get_post_time( 'U', false, $dereporteros_recommend_id ) ) ); ?></span>
			</a>
			<?php endif; ?>
		</div>

		<?php if ( $mini_id ) : $post = get_post( $mini_id ); setup_postdata( $post ); ?>
		<div class="tile tile-mini">
			<img src="<?php echo esc_url( dereporteros_thumb_src( $mini_id, 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>">
			<div class="tile-mini-scrim"></div>
			<div class="tile-mini-body">
				<a class="pill <?php echo esc_attr( dereporteros_pill_class( 1 ) ); ?>" href="<?php echo esc_url( dereporteros_category_link( $mini_id ) ); ?>"><?php echo esc_html( dereporteros_category_name( $mini_id ) ); ?></a>
				<h3><a class="tile-mini-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
				<span class="meta-mono">Actualizado hace <?php echo esc_html( human_time_diff( get_the_time( 'U' ) ) ); ?></span>
			</div>
		</div>
		<?php endif; wp_reset_postdata(); ?>

	</div>
</section>
<?php endif; wp_reset_postdata(); ?>

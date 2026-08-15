<?php
/**
 * Componente: hero principal — nota grande (hero-main) + hasta 3 mini-cards
 * a un lado (hero-side), todas con la etiqueta $args['source'] (curaduría
 * manual de portada).
 *
 * $args:
 *   'source' (string) — slug de la etiqueta a consultar.
 *   'title'  (string) — título accesible de la sección (no se imprime
 *                        visualmente, solo como aria-label).
 */
$dereporteros_hero_args = wp_parse_args( $args ?? [], [
	'source' => 'portada',
	'title'  => 'Portada',
] );

$dereporteros_portada_query = new WP_Query( [
	'tag'                      => $dereporteros_hero_args['source'],
	'posts_per_page'          => 4,
	'ignore_sticky_posts'     => true,
	'no_found_rows'           => true,
	'update_post_meta_cache'  => false,
	'update_post_term_cache'  => false,
] );
$dereporteros_portada_ids = wp_list_pluck( $dereporteros_portada_query->posts, 'ID' );
$hero_id                  = $dereporteros_portada_ids[0] ?? null;
$side_ids                 = array_slice( $dereporteros_portada_ids, 1, 3 );

if ( $hero_id ) :
	?>
<section class="hero" aria-label="<?php echo esc_attr( $dereporteros_hero_args['title'] ); ?>">
	<div class="wrap hero-grid">

		<?php $post = get_post( $hero_id ); setup_postdata( $post ); ?>
		<div class="hero-main">
			<img src="<?php echo esc_url( dereporteros_thumb_src( $hero_id, 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>">
			<div class="body">
				<a class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>" href="<?php echo esc_url( dereporteros_category_link( $hero_id ) ); ?>"><?php echo esc_html( dereporteros_category_name( $hero_id ) ); ?></a>
				<h1><a class="hero-main-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<p class="dek"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
				<span class="meta-mono"><a class="hero-main-author" href="<?php echo esc_url( dereporteros_author_link( $hero_id ) ); ?>"><?php the_author(); ?></a> · hace <?php echo esc_html( human_time_diff( get_the_time( 'U' ) ) ); ?></span>
			</div>
		</div>

		<?php if ( $side_ids ) : ?>
		<div class="hero-side">
			<?php foreach ( $side_ids as $i => $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
			<div class="mini-card">
				<img src="<?php echo esc_url( dereporteros_thumb_src( $id, 'thumbnail' ) ); ?>" alt="<?php the_title_attribute(); ?>">
				<div>
					<a class="pill <?php echo esc_attr( dereporteros_pill_class( $i + 1 ) ); ?>" style="margin-bottom:6px;" href="<?php echo esc_url( dereporteros_category_link( $id ) ); ?>"><?php echo esc_html( dereporteros_category_name( $id ) ); ?></a>
					<h3><a class="mini-card-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				</div>
			</div>
			<?php endforeach; wp_reset_postdata(); ?>

			<?php
			// Relleno del hueco que queda bajo las mini-cards (.hero-side se
			// estira a la altura de .hero-main por el grid, pero 3 mini-cards
			// casi nunca la llenan): la última nota con la etiqueta "cdmx",
			// con un tratamiento propio (mitad mapa de la ciudad, mitad la
			// imagen destacada de la nota) para diferenciarla del resto de
			// tarjetas del hero. Si no hay ninguna nota con esa etiqueta, cae
			// a una recomendación al azar para no dejar el hueco vacío.
			$dereporteros_cdmx_id = dereporteros_latest_id_by_tag( 'cdmx' );
			if ( ! $dereporteros_cdmx_id ) {
				$dereporteros_recommend_query = new WP_Query( [
					'posts_per_page'         => 1,
					'orderby'                => 'rand',
					'post__not_in'           => array_merge( [ $hero_id ], $side_ids ),
					'ignore_sticky_posts'    => true,
					'no_found_rows'          => true,
					'update_post_meta_cache' => false,
				] );
				$dereporteros_cdmx_id = $dereporteros_recommend_query->posts[0]->ID ?? null;
			}
			?>
			<?php if ( $dereporteros_cdmx_id ) : ?>
			<a class="hero-cdmx" href="<?php echo esc_url( get_permalink( $dereporteros_cdmx_id ) ); ?>">
				<div class="hero-cdmx-media">
					<img class="hero-cdmx-thumb" src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_cdmx_id, 'medium' ) ); ?>" alt="">
					<img class="hero-cdmx-map" src="<?php echo esc_url( get_template_directory_uri() . '/images/mapa-cdmx.jpg' ); ?>" alt="" aria-hidden="true">
				</div>
				<div class="hero-cdmx-body">
					<span class="pill <?php echo esc_attr( dereporteros_pill_class( 2 ) ); ?>"><?php echo esc_html( dereporteros_category_name( $dereporteros_cdmx_id ) ); ?></span>
					<h3><?php echo esc_html( get_the_title( $dereporteros_cdmx_id ) ); ?></h3>
				</div>
			</a>
			<?php endif; ?>
		</div>
		<?php endif; ?>

	</div>
</section>
<?php endif; wp_reset_postdata(); ?>

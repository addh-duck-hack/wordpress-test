<?php
/**
 * Componente: carousel "Personas Desaparecidas".
 *
 * $args:
 *   'source' (string) — slug de la categoría a consultar.
 *   'title'  (string) — título de la sección y texto del pill en cada tarjeta.
 */
$dereporteros_personas_args = wp_parse_args( $args ?? [], [
	'source' => 'personasextraviadas',
	'title'  => 'Personas Desaparecidas',
] );

$dereporteros_personas_query = new WP_Query( [
	'category_name'           => $dereporteros_personas_args['source'],
	'posts_per_page'          => 10,
	'ignore_sticky_posts'     => true,
	'no_found_rows'           => true,
	'update_post_meta_cache'  => false,
	'update_post_term_cache'  => false,
] );

if ( $dereporteros_personas_query->have_posts() ) :
	$dereporteros_personas_cat  = get_category_by_slug( $dereporteros_personas_args['source'] );
	$dereporteros_personas_link = $dereporteros_personas_cat ? get_category_link( $dereporteros_personas_cat ) : '';
	?>
<section class="personas wrap">
	<div class="personas-head">
		<div>
			<div class="personas-eyebrow"><span class="dot"></span><h2><?php echo esc_html( $dereporteros_personas_args['title'] ); ?></h2></div>
			<p class="personas-sub">Ayúdanos a difundir. Si tienes información sobre alguna de estas personas, contacta de inmediato a las autoridades.</p>
		</div>
		<?php if ( $dereporteros_personas_link ) : ?>
		<a class="personas-see-all" href="<?php echo esc_url( $dereporteros_personas_link ); ?>">Ver todas →</a>
		<?php endif; ?>
	</div>

	<div class="personas-carousel">
		<button type="button" class="personas-arrow prev" aria-label="Ver anteriores">‹</button>
		<div class="personas-track" id="personas-track">
			<?php while ( $dereporteros_personas_query->have_posts() ) : $dereporteros_personas_query->the_post(); ?>
			<a class="personas-card" href="<?php the_permalink(); ?>">
				<div class="imgwrap">
					<img src="<?php echo esc_url( dereporteros_thumb_src( get_the_ID(), 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>">
					<span class="pill alert"><?php echo esc_html( $dereporteros_personas_args['title'] ); ?></span>
				</div>
				<div class="body">
					<h3><?php the_title(); ?></h3>
					<span class="personas-cta">Ver ficha completa →</span>
				</div>
			</a>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
		<button type="button" class="personas-arrow next" aria-label="Ver siguientes">›</button>
	</div>
</section>
<script>
( function () {
	var track = document.getElementById( 'personas-track' );
	if ( ! track ) { return; }
	var prev = document.querySelector( '.personas-arrow.prev' );
	var next = document.querySelector( '.personas-arrow.next' );
	function step() {
		var card = track.querySelector( '.personas-card' );
		var gap  = 18;
		return card ? Math.round( card.getBoundingClientRect().width ) * 2 + gap * 2 : 560;
	}
	if ( prev ) { prev.addEventListener( 'click', function () { track.scrollBy( { left: -step(), behavior: 'smooth' } ); } ); }
	if ( next ) { next.addEventListener( 'click', function () { track.scrollBy( { left: step(), behavior: 'smooth' } ); } ); }
} )();
</script>
<?php endif; wp_reset_postdata(); ?>

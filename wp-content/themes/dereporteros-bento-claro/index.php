<?php
/**
 * Plantilla principal — Bento Claro.
 *
 * Sirve como home, archivo de categoría, búsqueda, etc. (no hay
 * archive.php/category.php propios: WordPress cae aquí por defecto).
 * Reparte los posts de la consulta actual en las secciones del layout
 * en vez de usar contenido fijo, para que funcione con cualquier
 * cantidad/orden de entradas reales del sitio.
 */
get_header();

/**
 * Hero principal: solo notas con la etiqueta "portada" (curaduría manual
 * de qué va arriba), independiente de la consulta general de abajo.
 */
$dereporteros_portada_query = new WP_Query( [
	'tag'                      => 'portada',
	'posts_per_page'          => 2,
	'ignore_sticky_posts'     => true,
	'no_found_rows'           => true,
	'update_post_meta_cache'  => false,
	'update_post_term_cache'  => false,
] );
$dereporteros_portada_ids = wp_list_pluck( $dereporteros_portada_query->posts, 'ID' );
$hero_id                  = $dereporteros_portada_ids[0] ?? null;
$mini_id                  = $dereporteros_portada_ids[1] ?? null;

/**
 * Nota del día: la nota más reciente con la etiqueta "nota-del-dia". Se
 * calcula aquí (antes del bento hero) para poder excluirla de la
 * recomendación aleatoria de abajo; se imprime más adelante, justo
 * después del hero, como bloque propio de alta visibilidad.
 */
$dereporteros_nota_dia_query = new WP_Query( [
	'tag'                     => 'nota-del-dia',
	'posts_per_page'          => 1,
	'orderby'                 => 'date',
	'order'                   => 'DESC',
	'ignore_sticky_posts'     => true,
	'no_found_rows'           => true,
	'update_post_meta_cache'  => false,
	'update_post_term_cache'  => false,
] );
$dereporteros_nota_dia_id = $dereporteros_nota_dia_query->posts[0]->ID ?? null;

global $wp_query;
$queried_ids = wp_list_pluck( $wp_query->posts, 'ID' );
$grid_ids    = array_slice( $queried_ids, 0, 4 );
$feed_ids    = array_slice( $queried_ids, 4, 4 );
$trend_ids   = array_slice( $queried_ids, 8, 4 );
?>

<?php if ( $hero_id ) : ?>
<section class="bento wrap">
	<div class="bento-grid">

		<?php $post = get_post( $hero_id ); setup_postdata( $post ); ?>
		<div class="tile tile-hero">
			<div class="imgwrap">
				<img src="<?php echo esc_url( dereporteros_thumb_src( $hero_id, 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>">
				<span class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>"><?php echo esc_html( dereporteros_category_name( $hero_id ) ); ?></span>
			</div>
			<div class="body">
				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<p class="dek"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
				<span class="meta-mono"><?php the_author(); ?> · hace <?php echo esc_html( human_time_diff( get_the_time( 'U' ) ) ); ?></span>
			</div>
		</div>

		<?php
		// Recomendación aleatoria para el tile de fecha: cualquier nota que
		// no sea la que ya se muestra en el hero o en el tile de al lado.
		$dereporteros_recommend_query = new WP_Query( [
			'posts_per_page'         => 1,
			'orderby'                => 'rand',
			'post__not_in'           => array_filter( [ $hero_id, $mini_id, $dereporteros_nota_dia_id ] ),
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
				<span class="pill <?php echo esc_attr( dereporteros_pill_class( 1 ) ); ?>"><?php echo esc_html( dereporteros_category_name( $mini_id ) ); ?></span>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
				<span class="meta-mono">Actualizado hace <?php echo esc_html( human_time_diff( get_the_time( 'U' ) ) ); ?></span>
			</div>
		</div>
		<?php endif; wp_reset_postdata(); ?>

	</div>
</section>
<?php endif; ?>

<?php if ( $dereporteros_nota_dia_id ) : $post = get_post( $dereporteros_nota_dia_id ); setup_postdata( $post ); ?>
<section class="nota-dia wrap">
	<a class="nota-dia-card" href="<?php the_permalink(); ?>">
		<img class="nota-dia-img" src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_nota_dia_id, 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>">
		<div class="nota-dia-scrim"></div>
		<span class="nota-dia-ribbon"><span aria-hidden="true">★</span> Nota del día</span>
		<div class="nota-dia-content">
			<span class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>"><?php echo esc_html( dereporteros_category_name( $dereporteros_nota_dia_id ) ); ?></span>
			<h2 class="nota-dia-title"><?php the_title(); ?></h2>
			<p class="nota-dia-dek"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
			<div class="nota-dia-foot">
				<span class="meta-mono"><?php the_author(); ?> · hace <?php echo esc_html( human_time_diff( get_the_time( 'U' ) ) ); ?></span>
				<span class="nota-dia-cta">Leer nota completa →</span>
			</div>
		</div>
	</a>
</section>
<?php endif; wp_reset_postdata(); ?>

<?php
/**
 * Carousel "Personas Desaparecidas": notas de la categoría
 * "personasextraviadas". Sección de alta visibilidad social, justo
 * después de "Nota del día".
 */
$dereporteros_personas_query = new WP_Query( [
	'category_name'           => 'personasextraviadas',
	'posts_per_page'          => 10,
	'ignore_sticky_posts'     => true,
	'no_found_rows'           => true,
	'update_post_meta_cache'  => false,
	'update_post_term_cache'  => false,
] );
if ( $dereporteros_personas_query->have_posts() ) :
	$dereporteros_personas_cat  = get_category_by_slug( 'personasextraviadas' );
	$dereporteros_personas_link = $dereporteros_personas_cat ? get_category_link( $dereporteros_personas_cat ) : '';
	?>
<section class="personas wrap">
	<div class="personas-head">
		<div>
			<div class="personas-eyebrow"><span class="dot"></span><h2>Personas Desaparecidas</h2></div>
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
					<span class="pill alert">Personas Desaparecidas</span>
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

<?php if ( $grid_ids ) : ?>
<section class="section-block wrap">
	<div class="section-head"><h2><?php echo is_home() || is_front_page() ? 'Portada' : esc_html( get_the_archive_title() ); ?></h2><span class="mono"><?php echo count( $grid_ids ); ?> notas</span></div>
	<div class="grid-4">
		<?php foreach ( $grid_ids as $i => $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
		<div class="card">
			<div class="imgwrap"><img src="<?php echo esc_url( dereporteros_thumb_src( $id, 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>"></div>
			<div class="body">
				<span class="pill <?php echo esc_attr( dereporteros_pill_class( $i ) ); ?>"><?php echo esc_html( dereporteros_category_name( $id ) ); ?></span>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			</div>
		</div>
		<?php endforeach; wp_reset_postdata(); ?>
	</div>
</section>
<?php endif; ?>

<?php
/**
 * Hero de últimas 4 notas — después de "Portada", como bloque
 * independiente. Usa su propia consulta para no pisar los posts que
 * reparte el bento hero de arriba.
 */
$dereporteros_hero_latest = new WP_Query( [
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
	<?php
	$dereporteros_hl_i = 0;
	while ( $dereporteros_hero_latest->have_posts() ) : $dereporteros_hero_latest->the_post();
		$dereporteros_hl_id = get_the_ID();
		?>
	<a class="hl-tile <?php echo esc_attr( $dereporteros_hl_slots[ $dereporteros_hl_i ] ); ?>" href="<?php the_permalink(); ?>">
		<img src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_hl_id, 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>">
		<div class="hl-scrim"></div>
		<div class="hl-body">
			<span class="pill green"><?php echo esc_html( dereporteros_category_name( $dereporteros_hl_id ) ); ?></span>
			<h3 class="hl-title"><?php the_title(); ?></h3>
		</div>
	</a>
	<?php $dereporteros_hl_i++; endwhile; wp_reset_postdata(); ?>
</section>
<?php endif; ?>

<?php if ( $feed_ids || $trend_ids ) : ?>
<section class="lower wrap">

	<?php if ( $feed_ids ) : ?>
	<div class="feed-col">
		<div class="section-head"><h2>Últimas noticias</h2><span class="mono">En vivo</span></div>
		<?php foreach ( $feed_ids as $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
		<div class="feed-row">
			<span class="time mono"><?php the_time( 'H:i' ); ?></span>
			<div>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<span class="sec"><?php echo esc_html( dereporteros_category_name( $id ) ); ?></span>
			</div>
		</div>
		<?php endforeach; wp_reset_postdata(); ?>
	</div>
	<?php endif; ?>

	<?php if ( $trend_ids ) : ?>
	<div class="trend-card">
		<h2>Más leídas</h2>
		<?php foreach ( $trend_ids as $n => $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
		<div class="trend-item">
			<span class="n"><?php echo (int) $n + 1; ?></span>
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		</div>
		<?php endforeach; wp_reset_postdata(); ?>
	</div>
	<?php endif; ?>

</section>
<?php endif; ?>

<?php if ( ! $hero_id && empty( $queried_ids ) ) : ?>
<div class="wrap" style="padding:70px 0;text-align:center;color:var(--ink-soft);">
	<p>Todavía no hay entradas publicadas en este sitio.</p>
</div>
<?php endif; ?>

<?php get_footer(); ?>

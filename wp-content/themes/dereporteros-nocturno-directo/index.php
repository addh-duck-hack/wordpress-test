<?php
/**
 * Plantilla principal — Nocturno Directo.
 *
 * Sirve como home, archivo de categoría, búsqueda, etc. (no hay
 * archive.php/category.php propios: WordPress cae aquí por defecto).
 * Reparte los posts de la consulta actual en las secciones del layout
 * en vez de usar contenido fijo, para que funcione con cualquier
 * cantidad/orden de entradas reales del sitio.
 */
get_header();

/**
 * Hero de últimas 4 notas — solo en la portada (primera página), como
 * bloque independiente por encima del hero de abajo. Usa su propia
 * consulta para no pisar los posts que reparte el hero principal.
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

<?php
global $wp_query;
$queried_ids = wp_list_pluck( $wp_query->posts, 'ID' );
$hero_id     = $queried_ids[0] ?? null;
$side_ids    = array_slice( $queried_ids, 1, 3 );
$grid_ids    = array_slice( $queried_ids, 4, 4 );
$feed_ids    = array_slice( $queried_ids, 8, 3 );
$trend_ids   = array_slice( $queried_ids, 11, 4 );
?>

<?php if ( $hero_id ) : ?>
<section class="hero">
	<div class="wrap hero-grid">

		<?php $post = get_post( $hero_id ); setup_postdata( $post ); ?>
		<div class="hero-main">
			<img src="<?php echo esc_url( dereporteros_thumb_src( $hero_id, 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>">
			<div class="body">
				<span class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>"><?php echo esc_html( dereporteros_category_name( $hero_id ) ); ?></span>
				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<p class="dek"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
				<span class="meta-mono"><?php the_author(); ?> · hace <?php echo esc_html( human_time_diff( get_the_time( 'U' ) ) ); ?></span>
			</div>
		</div>

		<?php if ( $side_ids ) : ?>
		<div class="hero-side">
			<?php foreach ( $side_ids as $i => $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
			<div class="mini-card">
				<img src="<?php echo esc_url( dereporteros_thumb_src( $id, 'thumbnail' ) ); ?>" alt="<?php the_title_attribute(); ?>">
				<div>
					<span class="pill <?php echo esc_attr( dereporteros_pill_class( $i + 1 ) ); ?>" style="margin-bottom:6px;"><?php echo esc_html( dereporteros_category_name( $id ) ); ?></span>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				</div>
			</div>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
		<?php endif; ?>

	</div>
</section>
<?php endif; ?>

<?php if ( $grid_ids ) : ?>
<section class="grid-section wrap">
	<div class="section-head"><span class="dot"></span><h2><?php echo is_home() || is_front_page() ? 'Metrópoli y México' : esc_html( get_the_archive_title() ); ?></h2></div>
	<div class="grid-4">
		<?php foreach ( $grid_ids as $i => $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
		<div class="card">
			<img src="<?php echo esc_url( dereporteros_thumb_src( $id, 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>">
			<div class="body">
				<span class="pill <?php echo esc_attr( dereporteros_pill_class( $i ) ); ?>"><?php echo esc_html( dereporteros_category_name( $id ) ); ?></span>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			</div>
		</div>
		<?php endforeach; wp_reset_postdata(); ?>
	</div>
</section>
<?php endif; ?>

<?php if ( $feed_ids || $trend_ids ) : ?>
<section class="body-cols wrap">

	<?php if ( $feed_ids ) : ?>
	<div class="latest-col">
		<div class="section-head"><span class="dot"></span><h2>Últimas noticias</h2></div>
		<?php foreach ( $feed_ids as $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
		<div class="latest-row">
			<img src="<?php echo esc_url( dereporteros_thumb_src( $id, 'thumbnail' ) ); ?>" alt="<?php the_title_attribute(); ?>">
			<div>
				<span class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>"><?php echo esc_html( dereporteros_category_name( $id ) ); ?></span>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<p class="dek"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p>
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

<?php if ( ! $hero_id ) : ?>
<div class="wrap" style="padding:70px 0;text-align:center;color:var(--text-soft);">
	<p>Todavía no hay entradas publicadas en este sitio.</p>
</div>
<?php endif; ?>

<?php get_footer(); ?>

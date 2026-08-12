<?php
/**
 * Cabecera — Nocturno Directo.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
/**
 * Cintillo "Última hora": solo la nota más reciente de la categoría
 * "ultima-hora" publicada en las últimas 48 horas. Si no hay ninguna, el
 * cintillo no se imprime (queda solo la navbar dentro del wrapper sticky).
 */
$dereporteros_ticker = new WP_Query( [
	'category_name'           => 'ultima-hora',
	'date_query'               => [ [ 'after' => '2 days ago' ] ],
	'orderby'                  => 'date',
	'order'                    => 'DESC',
	'posts_per_page'           => 1,
	'ignore_sticky_posts'      => true,
	'no_found_rows'            => true,
	'update_post_meta_cache'   => false,
	'update_post_term_cache'   => false,
] );
?>

<div class="site-topbar">
<?php if ( $dereporteros_ticker->have_posts() ) : ?>
<div class="ticker">
	<div class="wrap">
		<span class="badge">Última hora</span>
		<div class="items">
			<?php
			while ( $dereporteros_ticker->have_posts() ) :
				$dereporteros_ticker->the_post();
				$dereporteros_ticker_cat   = dereporteros_ticker_category_name( get_the_ID() );
				$dereporteros_ticker_title = get_the_title();
				$dereporteros_ticker_meta  = sprintf( 'hace %s', human_time_diff( get_the_time( 'U' ) ) );
				$dereporteros_ticker_body  = wp_trim_words( get_the_excerpt(), 20 );
				// Marquesina más larga = recorrido más lento, para que la
				// velocidad de lectura se sienta parecida sin importar el texto.
				$dereporteros_ticker_speed = max( 16, (int) round( mb_strlen( $dereporteros_ticker_cat . $dereporteros_ticker_title . $dereporteros_ticker_meta . $dereporteros_ticker_body ) * 0.09 ) );
				?>
				<a class="ticker-item" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( $dereporteros_ticker_title ); ?>">
					<span class="ticker-marquee" aria-hidden="true" style="--marquee-duration: <?php echo esc_attr( $dereporteros_ticker_speed ); ?>s;">
						<span class="ticker-cat"><?php echo esc_html( $dereporteros_ticker_cat ); ?></span>
						<span class="ticker-sep">·</span>
						<b class="ticker-title"><?php echo esc_html( $dereporteros_ticker_title ); ?></b>
						<span class="ticker-sep">·</span>
						<span class="ticker-meta"><?php echo esc_html( $dereporteros_ticker_meta ); ?></span>
						<span class="ticker-sep">·</span>
						<span class="ticker-body"><?php echo esc_html( $dereporteros_ticker_body ); ?></span>
					</span>
				</a>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<header class="site">
	<div class="wrap">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img class="site-logo" src="<?php echo esc_url( get_template_directory_uri() . '/images/logo-dereporteros.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
		</a>
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( [
				'theme_location' => 'primary',
				'container'      => 'nav',
				'container_class' => 'main-nav',
				'depth'          => 1,
			] );
		} else {
			dereporteros_nocturno_directo_nav_fallback();
		}
		?>
		<a class="subscribe" href="#">Suscribirme</a>
	</div>
</header>
</div><!-- .site-topbar -->

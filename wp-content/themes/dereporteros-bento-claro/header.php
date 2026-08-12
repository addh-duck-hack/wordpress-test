<?php
/**
 * Cabecera — Bento Claro.
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
$dereporteros_ticker = new WP_Query( [
	'posts_per_page'      => 3,
	'ignore_sticky_posts'  => true,
	'no_found_rows'        => true,
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
] );
if ( $dereporteros_ticker->have_posts() ) : ?>
<div class="ticker">
	<div class="wrap">
		<span class="badge">Última hora</span>
		<div class="items">
			<?php while ( $dereporteros_ticker->have_posts() ) : $dereporteros_ticker->the_post(); ?>
				<span><a href="<?php the_permalink(); ?>"><b><?php the_title(); ?></b></a></span>
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
			dereporteros_bento_claro_nav_fallback();
		}
		?>
		<a class="subscribe" href="#">Suscribirme</a>
	</div>
</header>

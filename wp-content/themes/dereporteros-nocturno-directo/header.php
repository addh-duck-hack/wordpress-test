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

<div class="site-topbar">
<?php get_template_part( 'template-parts/ticker', null, [
	'source' => 'ultima-hora',
	'title'  => 'Última hora',
] ); ?>

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

<main class="site-main">

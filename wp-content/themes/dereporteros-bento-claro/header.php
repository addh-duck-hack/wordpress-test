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

		<button type="button" class="nav-toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="site-nav-panel">
			<span class="nav-toggle-bars" aria-hidden="true"></span>
		</button>

		<div class="nav-panel" id="site-nav-panel">
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
			<nav class="social-nav" aria-label="Redes sociales">
				<a href="https://www.facebook.com/DeReporteros/" target="_blank" rel="noopener" aria-label="Facebook">
					<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12Z"/></svg>
				</a>
				<a href="https://twitter.com/DeReporteros" target="_blank" rel="noopener" aria-label="X (Twitter)">
					<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 3H21l-6.6 7.55L22.2 21h-6.2l-4.85-6.35L5.6 21H3.5l7.05-8.06L2.5 3h6.35l4.4 5.82L18.9 3Zm-1.08 16.2h1.15L7.3 4.72H6.06L17.82 19.2Z"/></svg>
				</a>
				<a href="https://www.youtube.com/channel/UC6TUcekaT0b0-uUrdB2xFyQ" target="_blank" rel="noopener" aria-label="YouTube">
					<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12s0-3.4-.43-5a2.9 2.9 0 0 0-2.04-2.04C17.9 4.5 12 4.5 12 4.5s-5.9 0-7.53.46A2.9 2.9 0 0 0 2.43 7C2 8.6 2 12 2 12s0 3.4.43 5a2.9 2.9 0 0 0 2.04 2.04C6.1 19.5 12 19.5 12 19.5s5.9 0 7.53-.46A2.9 2.9 0 0 0 21.57 17c.43-1.6.43-5 .43-5Zm-12.3 3.2V8.8L15.4 12l-5.7 3.2Z"/></svg>
				</a>
			</nav>
		</div>
	</div>
</header>
<script>
( function () {
	var toggle = document.querySelector( '.nav-toggle' );
	var panel  = document.getElementById( 'site-nav-panel' );
	if ( ! toggle || ! panel ) { return; }
	toggle.addEventListener( 'click', function () {
		var isOpen = panel.classList.toggle( 'is-open' );
		toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
	} );
} )();
</script>
</div><!-- .site-topbar -->

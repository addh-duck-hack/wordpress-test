<?php
/**
 * Plantilla principal — tema de prueba site2.
 *
 * Suficiente para validar activación independiente por subsitio;
 * no incluye archivos adicionales (header.php/footer.php) a propósito.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php wp_title( '|', true, 'right' ); ?><?php bloginfo( 'name' ); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<header class="site-header">
		<span class="badge">SITE2</span>
		<h1><?php bloginfo( 'name' ); ?></h1>
		<p><?php bloginfo( 'description' ); ?></p>
	</header>

	<main class="site-content">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<h2><?php the_title(); ?></h2>
					<?php the_content(); ?>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p>Tema <strong>Site2 Prototipo</strong> activo en este subsitio. Aún no hay contenido publicado.</p>
		<?php endif; ?>
	</main>

	<footer class="site-footer">
		Tema de prueba <strong>site2</strong> — <?php echo esc_html( date_i18n( 'Y' ) ); ?>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>

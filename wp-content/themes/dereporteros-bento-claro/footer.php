<?php
/**
 * Pie — Bento Claro.
 */
?>
<footer>
	<div class="wrap foot-grid">
		<div>
			<a class="foot-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/images/logo-dereporteros.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
			</a>
			<p><?php bloginfo( 'description' ); ?></p>
		</div>
		<div class="foot-col">
			<h4>Más</h4>
			<?php foreach ( get_pages( [ 'sort_column' => 'post_date', 'sort_order' => 'DESC' ] ) as $dereporteros_page ) : ?>
				<a href="<?php echo esc_url( get_permalink( $dereporteros_page ) ); ?>"><?php echo esc_html( $dereporteros_page->post_title ); ?></a>
			<?php endforeach; ?>
		</div>
		<div class="foot-col">
			<h4>Síguenos</h4>
			<a href="https://www.facebook.com/DeReporteros/" target="_blank" rel="noopener">Facebook</a>
			<a href="https://twitter.com/DeReporteros" target="_blank" rel="noopener">Twitter / X</a>
			<a href="https://www.youtube.com/channel/UC6TUcekaT0b0-uUrdB2xFyQ" target="_blank" rel="noopener">YouTube</a>
			<a href="<?php bloginfo( 'rss2_url' ); ?>">RSS</a>
		</div>
	</div>
	<div class="wrap foot-bottom">
		<span>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. El contenido de este sitio es responsabilidad y derecho exclusivo de <?php bloginfo( 'name' ); ?>.</span>
		<span>Diseño © <a href="https://mx.duck-hack.cloud" target="_blank" rel="noopener">Duck-Hack</a>. Todos los derechos de diseño reservados.</span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

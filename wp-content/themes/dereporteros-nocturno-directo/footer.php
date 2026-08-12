<?php
/**
 * Pie — Nocturno Directo.
 */
?>
<footer>
	<div class="wrap foot-grid">
		<div>
			<p><?php bloginfo( 'description' ); ?></p>
		</div>
		<div class="foot-col">
			<h4>Secciones</h4>
			<?php foreach ( get_categories( [ 'number' => 4 ] ) as $cat ) : ?>
				<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
			<?php endforeach; ?>
		</div>
		<div class="foot-col">
			<h4>Más</h4>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Portada</a>
			<a href="<?php echo esc_url( home_url( '/directorio/' ) ); ?>">Directorio</a>
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
		<span>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. Todos los derechos reservados.</span>
		<span>Diseño: <a href="https://mx.duck-hack.cloud" target="_blank" rel="noopener">Duck-Hack.cloud</a></span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

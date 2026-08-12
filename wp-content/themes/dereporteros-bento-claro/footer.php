<?php
/**
 * Pie — Bento Claro.
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
			<a href="#">Contacto</a>
		</div>
		<div class="foot-col">
			<h4>Síguenos</h4>
			<a href="#">Facebook</a>
			<a href="#">Twitter</a>
			<a href="<?php bloginfo( 'rss2_url' ); ?>">RSS</a>
		</div>
	</div>
	<div class="wrap foot-bottom">
		<span>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. Todos los derechos reservados.</span>
		<span>Aviso de privacidad · Contacto</span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

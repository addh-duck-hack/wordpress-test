<?php
/**
 * Componente: espacio publicitario estático. A diferencia de las demás
 * secciones no se alimenta de una categoría/etiqueta, así que en vez de
 * 'source'/'title' recibe la imagen y el texto de la etiqueta pequeña.
 *
 * $args:
 *   'image' (string) — URL de la imagen del banner.
 *   'label' (string) — texto pequeño mostrado sobre la imagen (transparencia).
 */
$dereporteros_ad_args = wp_parse_args( $args ?? [], [
	'image' => get_template_directory_uri() . '/images/publicidad-banner.png',
	'label' => 'Publicidad',
] );
?>
<section class="ad-banner wrap">
	<span class="ad-banner-label mono"><?php echo esc_html( $dereporteros_ad_args['label'] ); ?></span>
	<img class="ad-banner-img" src="<?php echo esc_url( $dereporteros_ad_args['image'] ); ?>" alt="Espacio publicitario disponible — aquí te puedes anunciar" loading="lazy">
</section>

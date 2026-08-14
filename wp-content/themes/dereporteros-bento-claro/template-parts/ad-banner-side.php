<?php
/**
 * Componente: espacio publicitario vertical, pensado para la columna
 * angosta de una sección de dos columnas (p. ej. junto a "Más leídas").
 * Igual que ad-banner.php, no se alimenta de categoría/etiqueta: recibe
 * la imagen y el texto de la etiqueta pequeña.
 *
 * $args:
 *   'image' (string) — URL de la imagen del banner.
 *   'label' (string) — texto pequeño mostrado sobre la imagen (transparencia).
 */
$dereporteros_ad_side_args = wp_parse_args( $args ?? [], [
	'image' => get_template_directory_uri() . '/images/publicidad-banner-vertical.png',
	'label' => 'Publicidad',
] );
?>
<div class="ad-slot">
	<span class="ad-slot-label mono"><?php echo esc_html( $dereporteros_ad_side_args['label'] ); ?></span>
	<img class="ad-slot-img" src="<?php echo esc_url( $dereporteros_ad_side_args['image'] ); ?>" alt="Espacio publicitario disponible — aquí te puedes anunciar" loading="lazy">
</div>

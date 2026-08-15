<?php
/**
 * Componente: espacio publicitario vertical, dinámico. Igual que
 * ad-banner.php pero para la columna angosta de una sección de dos
 * columnas (p. ej. junto a "Más leídas"): la nota más reciente con la
 * etiqueta $args['source'] aporta la imagen destacada como creativo y el
 * primer link de su contenido como destino del anuncio.
 *
 * $args:
 *   'source' (string) — slug de la etiqueta a consultar.
 *   'label'  (string) — texto pequeño mostrado sobre la imagen.
 */
$dereporteros_ad_side_args = wp_parse_args( $args ?? [], [
	'source' => 'publicidad2',
	'label'  => 'Publicidad',
] );

$dereporteros_ad_side_id = dereporteros_latest_id_by_tag( $dereporteros_ad_side_args['source'] );

if ( $dereporteros_ad_side_id ) :
	$dereporteros_ad_side_link = dereporteros_first_link_in_content( $dereporteros_ad_side_id );
	?>
<div class="ad-slot">
	<span class="ad-slot-label mono"><?php echo esc_html( $dereporteros_ad_side_args['label'] ); ?></span>
	<?php if ( $dereporteros_ad_side_link ) : ?>
	<a href="<?php echo esc_url( $dereporteros_ad_side_link ); ?>" target="_blank" rel="noopener sponsored">
		<img class="ad-slot-img" src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_ad_side_id, 'large' ) ); ?>" alt="Espacio publicitario">
	</a>
	<?php else : ?>
	<img class="ad-slot-img" src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_ad_side_id, 'large' ) ); ?>" alt="Espacio publicitario">
	<?php endif; ?>
</div>
<?php endif; ?>

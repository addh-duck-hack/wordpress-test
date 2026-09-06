<?php
/**
 * Componente: espacio publicitario vertical, dinámico. Igual que
 * promo-banner.php pero para la columna angosta de una sección de dos
 * columnas (p. ej. junto a "Más leídas"): la nota más reciente con la
 * etiqueta $args['source'] aporta la imagen destacada como creativo y el
 * primer link de su contenido como destino del anuncio.
 *
 * Clases con prefijo "promo-" (no "ad-") a propósito — ver nota en
 * promo-banner.php: es publicidad propia del sitio y el prefijo "ad-"
 * dispara los bloqueadores de anuncios por CSS aunque no haya ninguna red
 * externa de por medio.
 *
 * $args:
 *   'source' (string) — slug de la etiqueta a consultar.
 *   'label'  (string) — texto pequeño mostrado sobre la imagen (transparencia).
 */
$dereporteros_promo_side_args = wp_parse_args( $args ?? [], [
	'source' => 'publicidad2',
	'label'  => 'Publicidad',
] );

$dereporteros_promo_side_id = dereporteros_latest_id_by_tag( $dereporteros_promo_side_args['source'] );

if ( $dereporteros_promo_side_id ) :
	$dereporteros_promo_side_link = dereporteros_first_link_in_content( $dereporteros_promo_side_id );
	?>
<div class="promo-slot">
	<span class="promo-slot-label mono"><?php echo esc_html( $dereporteros_promo_side_args['label'] ); ?></span>
	<?php if ( $dereporteros_promo_side_link ) : ?>
	<a href="<?php echo esc_url( $dereporteros_promo_side_link ); ?>" target="_blank" rel="noopener sponsored">
		<img class="promo-slot-img" src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_promo_side_id, 'large' ) ); ?>" alt="Espacio publicitario">
	</a>
	<?php else : ?>
	<img class="promo-slot-img" src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_promo_side_id, 'large' ) ); ?>" alt="Espacio publicitario">
	<?php endif; ?>
</div>
<?php endif; ?>

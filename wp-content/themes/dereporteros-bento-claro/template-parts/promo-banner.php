<?php
/**
 * Componente: espacio publicitario horizontal, dinámico. Cada anuncio es
 * una nota normal con la etiqueta $args['source']: su imagen destacada es
 * el creativo del banner y el primer link en su contenido es el destino
 * del anuncio. Se muestra la nota más reciente con esa etiqueta; si no hay
 * ninguna, la sección no se imprime.
 *
 * Clases con prefijo "promo-" (no "ad-") a propósito: es publicidad propia
 * del sitio, no de una red externa, y los bloqueadores de anuncios ocultan
 * por CSS cualquier elemento cuyo class/id contenga patrones como "ad-" —
 * con ese prefijo el bloque desaparecía en navegadores con un adblocker
 * activo aunque el HTML se sirviera bien.
 *
 * $args:
 *   'source' (string) — slug de la etiqueta a consultar.
 *   'label'  (string) — texto pequeño mostrado sobre la imagen (transparencia).
 */
$dereporteros_promo_args = wp_parse_args( $args ?? [], [
	'source' => 'publicidad1',
	'label'  => 'Publicidad',
] );

$dereporteros_promo_id = dereporteros_latest_id_by_tag( $dereporteros_promo_args['source'] );

if ( $dereporteros_promo_id ) :
	$dereporteros_promo_link = dereporteros_first_link_in_content( $dereporteros_promo_id );
	?>
<section class="promo-banner wrap">
	<span class="promo-banner-label mono"><?php echo esc_html( $dereporteros_promo_args['label'] ); ?></span>
	<?php if ( $dereporteros_promo_link ) : ?>
	<a href="<?php echo esc_url( $dereporteros_promo_link ); ?>" target="_blank" rel="noopener sponsored">
		<img class="promo-banner-img" src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_promo_id, 'large' ) ); ?>" alt="Espacio publicitario">
	</a>
	<?php else : ?>
	<img class="promo-banner-img" src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_promo_id, 'large' ) ); ?>" alt="Espacio publicitario">
	<?php endif; ?>
</section>
<?php endif; ?>

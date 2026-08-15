<?php
/**
 * Componente: espacio publicitario horizontal, dinámico. Cada anuncio es
 * una nota normal con la etiqueta $args['source']: su imagen destacada es
 * el creativo del banner y el primer link en su contenido es el destino
 * del anuncio. Se muestra la nota más reciente con esa etiqueta; si no hay
 * ninguna, la sección no se imprime.
 *
 * $args:
 *   'source' (string) — slug de la etiqueta a consultar.
 *   'label'  (string) — texto pequeño mostrado sobre la imagen.
 */
$dereporteros_ad_args = wp_parse_args( $args ?? [], [
	'source' => 'publicidad1',
	'label'  => 'Publicidad',
] );

$dereporteros_ad_id = dereporteros_latest_id_by_tag( $dereporteros_ad_args['source'] );

if ( $dereporteros_ad_id ) :
	$dereporteros_ad_link = dereporteros_first_link_in_content( $dereporteros_ad_id );
	?>
<section class="ad-banner wrap">
	<span class="ad-banner-label mono"><?php echo esc_html( $dereporteros_ad_args['label'] ); ?></span>
	<?php if ( $dereporteros_ad_link ) : ?>
	<a href="<?php echo esc_url( $dereporteros_ad_link ); ?>" target="_blank" rel="noopener sponsored">
		<img class="ad-banner-img" src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_ad_id, 'large' ) ); ?>" alt="Espacio publicitario">
	</a>
	<?php else : ?>
	<img class="ad-banner-img" src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_ad_id, 'large' ) ); ?>" alt="Espacio publicitario">
	<?php endif; ?>
</section>
<?php endif; ?>

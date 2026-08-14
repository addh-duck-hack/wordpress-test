<?php
/**
 * Componente: "Nota del día" — banner inmersivo de una sola nota, la más
 * reciente con la etiqueta $args['source'].
 *
 * $args:
 *   'source' (string) — slug de la etiqueta a consultar.
 *   'title'  (string) — texto de la cinta/ribbon sobre la imagen.
 */
$dereporteros_nota_dia_args = wp_parse_args( $args ?? [], [
	'source' => 'nota-del-dia',
	'title'  => 'Nota del día',
] );

$dereporteros_nota_dia_id = dereporteros_latest_id_by_tag( $dereporteros_nota_dia_args['source'] );

if ( $dereporteros_nota_dia_id ) :
	$post = get_post( $dereporteros_nota_dia_id ); setup_postdata( $post );
	?>
<section class="nota-dia wrap">
	<div class="nota-dia-card">
		<a class="nota-dia-cover" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>"></a>
		<img class="nota-dia-img" src="<?php echo esc_url( dereporteros_thumb_src( $dereporteros_nota_dia_id, 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>">
		<div class="nota-dia-scrim"></div>
		<span class="nota-dia-ribbon"><span aria-hidden="true">★</span> <?php echo esc_html( $dereporteros_nota_dia_args['title'] ); ?></span>
		<div class="nota-dia-content">
			<a class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>" href="<?php echo esc_url( dereporteros_category_link( $dereporteros_nota_dia_id ) ); ?>"><?php echo esc_html( dereporteros_category_name( $dereporteros_nota_dia_id ) ); ?></a>
			<h2 class="nota-dia-title"><?php the_title(); ?></h2>
			<p class="nota-dia-dek"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
			<div class="nota-dia-foot">
				<span class="meta-mono"><a href="<?php echo esc_url( dereporteros_author_link( $dereporteros_nota_dia_id ) ); ?>"><?php the_author(); ?></a> · hace <?php echo esc_html( human_time_diff( get_the_time( 'U' ) ) ); ?></span>
				<a class="nota-dia-cta" href="<?php the_permalink(); ?>">Leer nota completa →</a>
			</div>
		</div>
	</div>
</section>
<?php endif; wp_reset_postdata(); ?>

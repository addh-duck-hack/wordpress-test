<?php
/**
 * Componente: cintillo "última hora".
 *
 * Muestra la nota más reciente con la etiqueta $args['source'] publicada en
 * las últimas 48 horas, con marquesina. Si no hay ninguna, no imprime nada.
 *
 * $args:
 *   'source' (string) — slug de la etiqueta a consultar.
 *   'title'  (string) — texto del badge mostrado antes de la nota.
 */
$dereporteros_ticker_args = wp_parse_args( $args ?? [], [
	'source' => 'ultima-hora',
	'title'  => 'Última hora',
] );

$dereporteros_ticker = new WP_Query( [
	'tag'                      => $dereporteros_ticker_args['source'],
	'date_query'               => [ [ 'after' => '2 days ago' ] ],
	'orderby'                  => 'date',
	'order'                    => 'DESC',
	'posts_per_page'           => 1,
	'ignore_sticky_posts'      => true,
	'no_found_rows'            => true,
	'update_post_meta_cache'   => false,
	'update_post_term_cache'   => false,
] );

if ( $dereporteros_ticker->have_posts() ) :
	?>
<div class="ticker">
	<div class="wrap">
		<span class="badge"><?php echo esc_html( $dereporteros_ticker_args['title'] ); ?></span>
		<div class="items">
			<?php
			while ( $dereporteros_ticker->have_posts() ) :
				$dereporteros_ticker->the_post();
				$dereporteros_ticker_cat   = dereporteros_ticker_category_name( get_the_ID() );
				$dereporteros_ticker_title = get_the_title();
				$dereporteros_ticker_body  = wp_trim_words( get_the_excerpt(), 20 );
				// Extracto más largo = tecleo más lento, para que la velocidad
				// de lectura se sienta pareja sin importar el texto. El ancho
				// final (en "ch", ~1 carácter) es lo que hace que el efecto de
				// máquina de escribir vaya revelando el texto completo.
				$dereporteros_ticker_chars = max( 1, mb_strlen( $dereporteros_ticker_body ) );
				$dereporteros_ticker_speed = max( 3, round( $dereporteros_ticker_chars * 0.09, 1 ) );
				?>
				<a class="ticker-item" href="<?php the_permalink(); ?>">
					<span class="ticker-static">
						<span class="ticker-cat"><?php echo esc_html( $dereporteros_ticker_cat ); ?></span>
						<span class="ticker-sep">·</span>
						<b class="ticker-title"><?php echo esc_html( $dereporteros_ticker_title ); ?></b>
					</span>
					<span class="ticker-marquee-wrap">
						<span class="ticker-marquee" aria-hidden="true" style="--ticker-width: <?php echo esc_attr( $dereporteros_ticker_chars ); ?>ch; --marquee-duration: <?php echo esc_attr( $dereporteros_ticker_speed ); ?>s;">
							<span class="ticker-body"><?php echo esc_html( $dereporteros_ticker_body ); ?></span>
						</span>
					</span>
				</a>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</div>
<?php endif; ?>

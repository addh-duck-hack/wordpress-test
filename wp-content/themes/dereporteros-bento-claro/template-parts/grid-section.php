<?php
/**
 * Componente: grid de 4 notas con encabezado y link "Ver más" al archivo de
 * la categoría consultada. Usado actualmente para "Metrópoli", pero sirve
 * para cualquier categoría con solo cambiar $args.
 *
 * $args:
 *   'source' (string) — slug de la categoría a consultar.
 *   'title'  (string) — título de la sección.
 */
$dereporteros_grid_args = wp_parse_args( $args ?? [], [
	'source' => 'metropoli',
	'title'  => 'Metrópoli',
] );

$dereporteros_grid_query = new WP_Query( [
	'category_name'           => $dereporteros_grid_args['source'],
	'posts_per_page'          => 4,
	'ignore_sticky_posts'     => true,
	'no_found_rows'           => true,
	'update_post_meta_cache'  => false,
	'update_post_term_cache'  => false,
] );
$grid_ids               = wp_list_pluck( $dereporteros_grid_query->posts, 'ID' );
$dereporteros_grid_cat  = get_category_by_slug( $dereporteros_grid_args['source'] );
$dereporteros_grid_link = $dereporteros_grid_cat ? get_category_link( $dereporteros_grid_cat ) : '';

if ( $grid_ids ) :
	?>
<section class="section-block wrap">
	<div class="section-head">
		<h2><?php echo is_home() || is_front_page() ? esc_html( $dereporteros_grid_args['title'] ) : esc_html( get_the_archive_title() ); ?></h2>
		<?php if ( $dereporteros_grid_link ) : ?>
		<a class="section-see-all" href="<?php echo esc_url( $dereporteros_grid_link ); ?>">Ver más →</a>
		<?php endif; ?>
	</div>
	<div class="grid-4">
		<?php foreach ( $grid_ids as $i => $id ) : $post = get_post( $id ); setup_postdata( $post ); ?>
		<div class="card">
			<div class="imgwrap"><img src="<?php echo esc_url( dereporteros_thumb_src( $id, 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>"></div>
			<div class="body">
				<span class="meta-mono">hace <?php echo esc_html( human_time_diff( get_the_time( 'U' ) ) ); ?></span>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			</div>
		</div>
		<?php endforeach; wp_reset_postdata(); ?>
	</div>
</section>
<?php endif; ?>

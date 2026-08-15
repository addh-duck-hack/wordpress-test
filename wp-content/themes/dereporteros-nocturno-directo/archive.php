<?php
/**
 * Archivo genérico — Nocturno Directo.
 *
 * Cubre categorías, etiquetas y autores en un solo archivo: no hay
 * category.php/tag.php/author.php propios, así que WordPress cae aquí
 * según la jerarquía de plantillas (antes de caer a index.php). Encabezado
 * con el nombre/descripción del término o autor + grid con todas las
 * notas asignadas, paginado.
 */
get_header();

global $wp_query;

$dereporteros_archive_kind = is_author() ? 'Autor' : ( is_tag() ? 'Etiqueta' : ( is_category() ? 'Categoría' : '' ) );

$dereporteros_archive_title = is_author()
	? get_the_author_meta( 'display_name', get_queried_object_id() )
	: ( ( is_category() || is_tag() ) ? single_term_title( '', false ) : get_the_archive_title() );

$dereporteros_archive_desc = is_author()
	? get_the_author_meta( 'description', get_queried_object_id() )
	: term_description();

$dereporteros_found = (int) $wp_query->found_posts;
?>

<header class="archive-head wrap">
	<?php if ( is_author() ) : ?>
	<?php echo get_avatar( get_queried_object_id(), 72, '', '', [ 'class' => 'archive-avatar' ] ); ?>
	<?php endif; ?>

	<?php if ( $dereporteros_archive_kind ) : ?>
	<span class="meta-mono archive-kind"><?php echo esc_html( $dereporteros_archive_kind ); ?></span>
	<?php endif; ?>

	<h1><?php echo esc_html( $dereporteros_archive_title ); ?></h1>

	<?php if ( $dereporteros_archive_desc ) : ?>
	<div class="archive-desc"><?php echo wp_kses_post( wpautop( $dereporteros_archive_desc ) ); ?></div>
	<?php endif; ?>

	<span class="meta-mono archive-count"><?php echo esc_html( $dereporteros_found ); ?> <?php echo esc_html( 1 === $dereporteros_found ? 'nota' : 'notas' ); ?></span>
</header>

<?php if ( have_posts() ) : ?>
<section class="grid-section wrap">
	<div class="grid-4">
		<?php $dereporteros_archive_i = 0; while ( have_posts() ) : the_post(); ?>
		<div class="card">
			<img src="<?php echo esc_url( dereporteros_thumb_src( get_the_ID(), 'medium' ) ); ?>" alt="<?php the_title_attribute(); ?>">
			<div class="body">
				<a class="pill <?php echo esc_attr( dereporteros_pill_class( $dereporteros_archive_i ) ); ?>" href="<?php echo esc_url( dereporteros_category_link( get_the_ID() ) ); ?>"><?php echo esc_html( dereporteros_category_name( get_the_ID() ) ); ?></a>
				<h3><a class="card-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			</div>
		</div>
		<?php $dereporteros_archive_i++; endwhile; ?>
	</div>

	<?php
	the_posts_pagination( [
		'prev_text' => '← Anterior',
		'next_text' => 'Siguiente →',
	] );
	?>
</section>
<?php else : ?>
<div class="wrap" style="padding:70px 0;text-align:center;color:var(--text-soft);">
	<p>Todavía no hay notas aquí.</p>
</div>
<?php endif; ?>

<?php get_footer(); ?>

<?php
/**
 * Entrada individual — Bento Claro.
 */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>
<article class="single-wrap">

	<?php if ( has_post_thumbnail() ) : ?>
	<div class="single-featured">
		<?php the_post_thumbnail( 'large', array( 'class' => 'single-featured-img' ) ); ?>
		<div class="single-featured-scrim"></div>
		<div class="single-featured-content">
			<a class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>" href="<?php echo esc_url( dereporteros_category_link( get_the_ID() ) ); ?>"><?php echo esc_html( dereporteros_category_name( get_the_ID() ) ); ?></a>
			<h1><?php the_title(); ?></h1>
			<span class="meta-mono"><?php the_author(); ?> · <?php echo esc_html( get_the_date() ); ?></span>
		</div>
	</div>
	<?php else : ?>
	<a class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>" href="<?php echo esc_url( dereporteros_category_link( get_the_ID() ) ); ?>"><?php echo esc_html( dereporteros_category_name( get_the_ID() ) ); ?></a>
	<h1><?php the_title(); ?></h1>
	<span class="meta-mono"><?php the_author(); ?> · <?php echo esc_html( get_the_date() ); ?></span>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/share-bar', null, [ 'variant' => 'bottom' ] ); ?>

	<div class="content">
		<?php the_content(); ?>
	</div>

	<?php get_template_part( 'template-parts/share-bar', null, [ 'variant' => 'top' ] ); ?>
</article>

<?php
get_template_part( 'template-parts/grid-section', null, [
	'title'   => 'Recomendado para ti',
	'random'  => true,
	'exclude' => [ get_the_ID() ],
	'narrow'  => true,
	'count'   => 3,
] );
get_template_part( 'template-parts/comments' );
?>

<?php endwhile; ?>

<?php get_footer(); ?>

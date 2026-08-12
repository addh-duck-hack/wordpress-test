<?php
/**
 * Entrada individual — Bento Claro.
 */
get_header();
?>

<div class="wrap" style="padding-top:24px;">
	<a class="back-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">← Portada</a>
</div>

<?php while ( have_posts() ) : the_post(); ?>
<article class="single-wrap">
	<span class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>"><?php echo esc_html( dereporteros_category_name( get_the_ID() ) ); ?></span>
	<h1><?php the_title(); ?></h1>
	<span class="meta-mono"><?php the_author(); ?> · <?php echo esc_html( get_the_date() ); ?></span>

	<?php if ( has_post_thumbnail() ) : ?>
	<div class="featured"><?php the_post_thumbnail( 'large' ); ?></div>
	<?php endif; ?>

	<div class="content">
		<?php the_content(); ?>
	</div>
</article>
<?php endwhile; ?>

<?php get_footer(); ?>

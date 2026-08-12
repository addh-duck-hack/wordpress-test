<?php
/**
 * Página estática — Bento Claro.
 */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>
<article class="single-wrap">
	<h1><?php the_title(); ?></h1>
	<div class="content">
		<?php the_content(); ?>
	</div>
</article>
<?php endwhile; ?>

<?php get_footer(); ?>

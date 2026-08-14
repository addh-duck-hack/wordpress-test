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
			<span class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>"><?php echo esc_html( dereporteros_category_name( get_the_ID() ) ); ?></span>
			<h1><?php the_title(); ?></h1>
			<span class="meta-mono"><?php the_author(); ?> · <?php echo esc_html( get_the_date() ); ?></span>
		</div>
	</div>
	<?php else : ?>
	<span class="pill <?php echo esc_attr( dereporteros_pill_class( 0 ) ); ?>"><?php echo esc_html( dereporteros_category_name( get_the_ID() ) ); ?></span>
	<h1><?php the_title(); ?></h1>
	<span class="meta-mono"><?php the_author(); ?> · <?php echo esc_html( get_the_date() ); ?></span>
	<?php endif; ?>

	<?php
	$share_url   = rawurlencode( get_permalink() );
	$share_title = rawurlencode( get_the_title() );
	?>
	<div class="share-bar">
		<span class="share-label meta-mono">Compartir</span>
		<a class="share-btn" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" rel="noopener" aria-label="Compartir en Facebook">
			<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12Z"/></svg>
			<span>Facebook</span>
		</a>
		<a class="share-btn" href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&amp;text=<?php echo $share_title; ?>" target="_blank" rel="noopener" aria-label="Compartir en X">
			<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 3H21l-6.6 7.55L22.2 21h-6.2l-4.85-6.35L5.6 21H3.5l7.05-8.06L2.5 3h6.35l4.4 5.82L18.9 3Zm-1.08 16.2h1.15L7.3 4.72H6.06L17.82 19.2Z"/></svg>
			<span>X</span>
		</a>
		<a class="share-btn" href="https://api.whatsapp.com/send?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>" target="_blank" rel="noopener" aria-label="Compartir por WhatsApp">
			<svg viewBox="0 0 448 512" fill="currentColor" aria-hidden="true"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.8-186.6 184.8zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
			<span>WhatsApp</span>
		</a>
		<a class="share-btn" href="mailto:?subject=<?php echo $share_title; ?>&amp;body=<?php echo $share_url; ?>" aria-label="Compartir por correo">
			<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
			<span>Correo</span>
		</a>
	</div>

	<div class="content">
		<?php the_content(); ?>
	</div>
</article>
<?php endwhile; ?>

<?php get_footer(); ?>

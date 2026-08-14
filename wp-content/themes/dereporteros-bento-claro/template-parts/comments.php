<?php
/**
 * Bloque de comentarios de una entrada (llamado desde single.php): botón
 * para desplegar el formulario —solo si la entrada admite comentarios—,
 * el formulario en sí y el listado de comentarios ya publicados. Si la
 * entrada no admite comentarios y tampoco tiene ninguno, no se imprime nada.
 */
$dereporteros_comments_open = comments_open();
$dereporteros_comments      = get_comments( [
	'post_id' => get_the_ID(),
	'status'  => 'approve',
] );
$dereporteros_comments_count = count( $dereporteros_comments );

if ( ! $dereporteros_comments_open && ! $dereporteros_comments_count ) {
	return;
}
?>
<section class="comments-section">
	<div class="section-head">
		<h2><?php echo esc_html( $dereporteros_comments_count === 1 ? '1 comentario' : $dereporteros_comments_count . ' comentarios' ); ?></h2>
	</div>

	<?php if ( $dereporteros_comments_open ) : ?>
	<button type="button" class="comments-toggle" aria-expanded="false" aria-controls="comments-form-wrap">Agregar comentario</button>

	<div class="comments-form-wrap" id="comments-form-wrap">
		<?php
		comment_form( [
			'title_reply'          => '',
			'label_submit'         => 'Publicar comentario',
			'class_submit'         => 'comments-submit',
			'comment_notes_before' => '',
			'comment_notes_after'  => '',
			'class_form'           => 'comments-form',
			'comment_field'        => '<p class="comment-form-comment"><label for="comment" class="screen-reader-text">Comentario</label><textarea id="comment" name="comment" placeholder="Escribe tu comentario…" rows="4" required></textarea></p>',
		] );
		?>
	</div>
	<?php endif; ?>

	<?php if ( $dereporteros_comments_count ) : ?>
	<ol class="comments-list">
		<?php
		wp_list_comments( [
			'style'       => 'ol',
			'short_ping'  => true,
			'avatar_size' => 44,
			'max_depth'   => 1,
			'callback'    => 'dereporteros_comment_html',
		], $dereporteros_comments );
		?>
	</ol>
	<?php elseif ( $dereporteros_comments_open ) : ?>
	<p class="comments-empty meta-mono">Sé el primero en comentar.</p>
	<?php endif; ?>
</section>

<script>
( function () {
	var toggle = document.querySelector( '.comments-toggle' );
	var wrap   = document.getElementById( 'comments-form-wrap' );
	if ( ! toggle || ! wrap ) { return; }
	toggle.addEventListener( 'click', function () {
		var isOpen = wrap.classList.toggle( 'is-open' );
		toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		if ( isOpen ) {
			var field = wrap.querySelector( 'textarea, input' );
			if ( field ) { field.focus(); }
		}
	} );
} )();
</script>

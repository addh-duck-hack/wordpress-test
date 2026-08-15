<?php
/**
 * Plantilla principal — Nocturno Directo.
 *
 * Sirve como home, búsqueda, etc. (category.php/tag.php/author.php no
 * hacen falta: esas caen en archive.php). Cada sección de la página vive
 * en su propio archivo dentro de template-parts/ y se invoca aquí con
 * get_template_part().
 *
 * Reparte los posts de la consulta actual en las secciones del layout en
 * vez de usar contenido fijo, para que funcione con cualquier
 * cantidad/orden de entradas reales del sitio.
 */
get_header();

global $wp_query;
$queried_ids = wp_list_pluck( $wp_query->posts, 'ID' );
$grid_ids    = array_slice( $queried_ids, 0, 4 );
// "Últimas noticias" ya no se imprime (su lugar ahora lo ocupa "Más
// leídas"), así que "Más leídas" toma la siguiente tanda de posts justo
// después de $grid_ids en vez de dejar ese rango sin usar.
$trend_ids   = array_slice( $queried_ids, 4, 4 );

// Solo para decidir si mostrar el aviso de "sin entradas" de más abajo
// (sin repetir la consulta completa del hero, que hace su propio query).
$dereporteros_has_hero = dereporteros_has_tagged_posts( 'portada' );
?>

<?php get_template_part( 'template-parts/hero', null, [
	'source' => 'portada',
	'title'  => 'Portada',
] ); ?>

<?php get_template_part( 'template-parts/nota-del-dia', null, [
	'source' => 'nota-del-dia',
	'title'  => 'Nota del día',
] ); ?>

<?php get_template_part( 'template-parts/personas-desaparecidas', null, [
	'source' => 'personasextraviadas',
	'title'  => 'Personas Desaparecidas',
] ); ?>

<?php get_template_part( 'template-parts/grid-section', null, [
	'title' => 'Metrópoli y México',
	'ids'   => $grid_ids,
] ); ?>

<?php get_template_part( 'template-parts/ad-banner', null, [
	'source' => 'publicidad1',
	'label'  => 'Publicidad',
] ); ?>

<?php
/**
 * Hero de últimas 4 notas — después de "Metrópoli y México", como bloque
 * independiente. Usa su propia consulta para no pisar los posts que
 * reparte el hero principal de arriba.
 */
get_template_part( 'template-parts/hero-latest' );
?>

<?php get_template_part( 'template-parts/grid-section', null, [
	'title'  => 'Fotografía',
	'source' => 'fotografia',
] ); ?>

<?php if ( $trend_ids ) : ?>
<section class="body-cols wrap">
	<?php
	get_template_part( 'template-parts/trend-card', null, [ 'title' => 'Más leídas', 'ids' => $trend_ids ] );
	get_template_part( 'template-parts/ad-banner-side', null, [
		'source' => 'publicidad2',
		'label'  => 'Publicidad',
	] );
	?>
</section>
<?php endif; ?>

<?php get_template_part( 'template-parts/grid-section', null, [
	'title'  => 'Seguridad',
	'source' => 'seguridad',
] ); ?>

<?php if ( ! $dereporteros_has_hero && empty( $queried_ids ) ) : ?>
<div class="wrap" style="padding:70px 0;text-align:center;color:var(--text-soft);">
	<p>Todavía no hay entradas publicadas en este sitio.</p>
</div>
<?php endif; ?>

<?php get_footer(); ?>

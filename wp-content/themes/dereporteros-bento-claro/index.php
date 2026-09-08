<?php
/**
 * Plantilla principal — Bento Claro.
 *
 * Sirve como home, archivo de categoría, búsqueda, etc. (no hay
 * archive.php/category.php propios: WordPress cae aquí por defecto).
 *
 * Cada sección de la página vive en su propio archivo dentro de
 * template-parts/ y se invoca aquí con get_template_part(), pasándole la
 * categoría/etiqueta de origen ('source') y el título a mostrar ('title').
 * Cambiar de qué se alimenta una sección o cómo se llama es cuestión de
 * tocar esta lista, no el HTML de cada componente.
 */
get_header();

// Consulta general de la portada (la que WordPress ya resuelve para este
// template): lo que sobra después del hero/nota del día/carousel/grid
// alimenta la columna "Más leídas" de abajo, que no tiene categoría propia.
global $wp_query;
$queried_ids = wp_list_pluck( $wp_query->posts, 'ID' );
$feed_ids    = array_slice( $queried_ids, 0, 4 );

// Se calcula aparte (antes del hero) solo para poder excluir esta nota de
// la recomendación aleatoria del hero; el componente de "Nota del día" hace
// su propia consulta al imprimirse más abajo.
$dereporteros_nota_dia_id = dereporteros_latest_id_by_tag(
	get_theme_mod( 'dereporteros_home_notadia_source', 'nota-del-dia' )
);
$dereporteros_has_hero    = dereporteros_has_tagged_posts(
	get_theme_mod( 'dereporteros_home_hero_source', 'portada' )
);
?>

<?php get_template_part( 'template-parts/hero', null, [
	'source'  => get_theme_mod( 'dereporteros_home_hero_source', 'portada' ),
	'title'   => get_theme_mod( 'dereporteros_home_hero_title', 'Portada' ),
	'exclude' => [ $dereporteros_nota_dia_id ],
] ); ?>

<?php get_template_part( 'template-parts/nota-del-dia', null, [
	'source' => get_theme_mod( 'dereporteros_home_notadia_source', 'nota-del-dia' ),
	'title'  => get_theme_mod( 'dereporteros_home_notadia_title', 'Nota del día' ),
] ); ?>

<?php get_template_part( 'template-parts/personas-desaparecidas', null, [
	'source' => get_theme_mod( 'dereporteros_home_personas_source', 'personasextraviadas' ),
	'title'  => get_theme_mod( 'dereporteros_home_personas_title', 'Personas Desaparecidas' ),
] ); ?>

<?php get_template_part( 'template-parts/grid-section', null, [
	'source' => get_theme_mod( 'dereporteros_home_metropoli_source', 'metropoli' ),
	'title'  => get_theme_mod( 'dereporteros_home_metropoli_title', 'Metrópoli' ),
] ); ?>

<?php get_template_part( 'template-parts/promo-banner', null, [
	'source' => get_theme_mod( 'dereporteros_home_promo1_source', 'publicidad1' ),
	'label'  => get_theme_mod( 'dereporteros_home_promo1_label', 'Publicidad' ),
] ); ?>

<?php get_template_part( 'template-parts/hero-latest', null, [
	'source' => get_theme_mod( 'dereporteros_home_herolatest_source', 'espectaculos' ),
	'title'  => get_theme_mod( 'dereporteros_home_herolatest_title', 'Espectáculos' ),
] ); ?>

<?php get_template_part( 'template-parts/grid-section', null, [
	'source' => get_theme_mod( 'dereporteros_home_fotografia_source', 'fotografia' ),
	'title'  => get_theme_mod( 'dereporteros_home_fotografia_title', 'Fotografía' ),
] ); ?>

<?php if ( $feed_ids ) : ?>
<section class="lower wrap">
	<?php
	get_template_part( 'template-parts/latest-feed', null, [
		'title' => get_theme_mod( 'dereporteros_home_feed_title', 'Más leídas' ),
		'ids'   => $feed_ids,
	] );
	get_template_part( 'template-parts/promo-banner-side', null, [
		'source' => get_theme_mod( 'dereporteros_home_promo2_source', 'publicidad2' ),
		'label'  => get_theme_mod( 'dereporteros_home_promo2_label', 'Publicidad' ),
	] );
	?>
</section>
<?php endif; ?>

<?php get_template_part( 'template-parts/grid-section', null, [
	'source' => get_theme_mod( 'dereporteros_home_seguridad_source', 'seguridad' ),
	'title'  => get_theme_mod( 'dereporteros_home_seguridad_title', 'Seguridad' ),
] ); ?>

<?php get_template_part( 'template-parts/grid-section', null, [
	'source' => get_theme_mod( 'dereporteros_home_clima_source', 'clima' ),
	'title'  => get_theme_mod( 'dereporteros_home_clima_title', 'Clima' ),
] ); ?>

<?php if ( ! $dereporteros_has_hero && empty( $queried_ids ) ) : ?>
<div class="wrap" style="padding:70px 0;text-align:center;color:var(--ink-soft);">
	<p>Todavía no hay entradas publicadas en este sitio.</p>
</div>
<?php endif; ?>

<?php get_footer(); ?>

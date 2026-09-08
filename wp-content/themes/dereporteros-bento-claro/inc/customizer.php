<?php
/**
 * Personalizador — secciones del home.
 *
 * Cada sección del home (index.php) y el ticker (header.php) reciben su
 * categoría/etiqueta de origen ('source') y su título/label como valores
 * hardcodeados en el PHP. Aquí se registran esos mismos valores como
 * ajustes de Apariencia → Personalizar, con default idéntico al literal
 * que reemplazan — instalar esto sin tocar nada en Personalizar no cambia
 * el sitio en vivo.
 *
 * Los campos son de texto simple (no un <select> de categorías/etiquetas):
 * 'source' mezcla dos taxonomías según el componente (categoría para
 * personas-desaparecidas/grid-section/hero-latest, etiqueta para
 * ticker/hero/nota-del-dia/promo-banner/promo-banner-side) y es un campo
 * que se toca con poca frecuencia — un dropdown correcto por taxonomía
 * duplicaría el registro para poco beneficio.
 *
 * transport => 'refresh' en todos: cada ajuste alimenta una WP_Query del
 * lado del servidor, así que la vista previa recarga completa al cambiar
 * un campo (no hay postMessage/render_callback por sección).
 */
add_action( 'customize_register', function ( WP_Customize_Manager $wp_customize ) {

	$wp_customize->add_panel( 'dereporteros_home', [
		'title'       => __( 'Portada', 'dereporteros-bento-claro' ),
		'description' => __( 'Qué categoría o etiqueta alimenta cada sección del home, y su título visible.', 'dereporteros-bento-claro' ),
		'priority'    => 30,
	] );

	// -- Última hora (ticker, se imprime desde header.php) --------------
	$wp_customize->add_section( 'dereporteros_home_ticker', [
		'title'       => __( 'Última hora', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/ticker.php (impreso desde header.php).', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 10,
	] );
	$wp_customize->add_setting( 'dereporteros_home_ticker_source', [
		'default'           => 'ultima-hora',
		'sanitize_callback' => 'sanitize_title',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_ticker_source', [
		'section'     => 'dereporteros_home_ticker',
		'type'        => 'text',
		'label'       => __( 'Etiqueta de origen (slug)', 'dereporteros-bento-claro' ),
		'description' => __( 'Etiqueta, no categoría.', 'dereporteros-bento-claro' ),
	] );
	$wp_customize->add_setting( 'dereporteros_home_ticker_title', [
		'default'           => 'Última hora',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_ticker_title', [
		'section' => 'dereporteros_home_ticker',
		'type'    => 'text',
		'label'   => __( 'Texto del badge', 'dereporteros-bento-claro' ),
	] );

	// -- Portada (hero) ---------------------------------------------------
	$wp_customize->add_section( 'dereporteros_home_hero', [
		'title'       => __( 'Portada (hero)', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/hero.php.', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 20,
	] );
	$wp_customize->add_setting( 'dereporteros_home_hero_source', [
		'default'           => 'portada',
		'sanitize_callback' => 'sanitize_title',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_hero_source', [
		'section'     => 'dereporteros_home_hero',
		'type'        => 'text',
		'label'       => __( 'Etiqueta de origen (slug)', 'dereporteros-bento-claro' ),
		'description' => __( 'Etiqueta, no categoría.', 'dereporteros-bento-claro' ),
	] );
	$wp_customize->add_setting( 'dereporteros_home_hero_title', [
		'default'           => 'Portada',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_hero_title', [
		'section' => 'dereporteros_home_hero',
		'type'    => 'text',
		'label'   => __( 'Título', 'dereporteros-bento-claro' ),
	] );

	// -- Nota del día -------------------------------------------------
	$wp_customize->add_section( 'dereporteros_home_notadia', [
		'title'       => __( 'Nota del día', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/nota-del-dia.php.', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 30,
	] );
	$wp_customize->add_setting( 'dereporteros_home_notadia_source', [
		'default'           => 'nota-del-dia',
		'sanitize_callback' => 'sanitize_title',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_notadia_source', [
		'section'     => 'dereporteros_home_notadia',
		'type'        => 'text',
		'label'       => __( 'Etiqueta de origen (slug)', 'dereporteros-bento-claro' ),
		'description' => __( 'Etiqueta, no categoría. También determina qué nota excluye el hero de su recomendación.', 'dereporteros-bento-claro' ),
	] );
	$wp_customize->add_setting( 'dereporteros_home_notadia_title', [
		'default'           => 'Nota del día',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_notadia_title', [
		'section' => 'dereporteros_home_notadia',
		'type'    => 'text',
		'label'   => __( 'Texto del ribbon', 'dereporteros-bento-claro' ),
	] );

	// -- Personas Desaparecidas -----------------------------------------
	$wp_customize->add_section( 'dereporteros_home_personas', [
		'title'       => __( 'Personas Desaparecidas', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/personas-desaparecidas.php.', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 40,
	] );
	$wp_customize->add_setting( 'dereporteros_home_personas_source', [
		'default'           => 'personasextraviadas',
		'sanitize_callback' => 'sanitize_title',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_personas_source', [
		'section'     => 'dereporteros_home_personas',
		'type'        => 'text',
		'label'       => __( 'Categoría de origen (slug)', 'dereporteros-bento-claro' ),
		'description' => __( 'Categoría, no etiqueta.', 'dereporteros-bento-claro' ),
	] );
	$wp_customize->add_setting( 'dereporteros_home_personas_title', [
		'default'           => 'Personas Desaparecidas',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_personas_title', [
		'section' => 'dereporteros_home_personas',
		'type'    => 'text',
		'label'   => __( 'Título de la sección', 'dereporteros-bento-claro' ),
	] );

	// -- Metrópoli (grid-section #1) --------------------------------------
	$wp_customize->add_section( 'dereporteros_home_metropoli', [
		'title'       => __( 'Metrópoli', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/grid-section.php.', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 50,
	] );
	$wp_customize->add_setting( 'dereporteros_home_metropoli_source', [
		'default'           => 'metropoli',
		'sanitize_callback' => 'sanitize_title',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_metropoli_source', [
		'section'     => 'dereporteros_home_metropoli',
		'type'        => 'text',
		'label'       => __( 'Categoría de origen (slug)', 'dereporteros-bento-claro' ),
		'description' => __( 'Categoría, no etiqueta.', 'dereporteros-bento-claro' ),
	] );
	$wp_customize->add_setting( 'dereporteros_home_metropoli_title', [
		'default'           => 'Metrópoli',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_metropoli_title', [
		'section' => 'dereporteros_home_metropoli',
		'type'    => 'text',
		'label'   => __( 'Título de la sección', 'dereporteros-bento-claro' ),
	] );

	// -- Publicidad, banner horizontal (promo-banner) --------------------
	$wp_customize->add_section( 'dereporteros_home_promo1', [
		'title'       => __( 'Publicidad (banner horizontal)', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/promo-banner.php.', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 60,
	] );
	$wp_customize->add_setting( 'dereporteros_home_promo1_source', [
		'default'           => 'publicidad1',
		'sanitize_callback' => 'sanitize_title',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_promo1_source', [
		'section'     => 'dereporteros_home_promo1',
		'type'        => 'text',
		'label'       => __( 'Etiqueta de origen (slug)', 'dereporteros-bento-claro' ),
		'description' => __( 'Etiqueta, no categoría.', 'dereporteros-bento-claro' ),
	] );
	$wp_customize->add_setting( 'dereporteros_home_promo1_label', [
		'default'           => 'Publicidad',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_promo1_label', [
		'section' => 'dereporteros_home_promo1',
		'type'    => 'text',
		'label'   => __( 'Texto sobre la imagen', 'dereporteros-bento-claro' ),
	] );

	// -- Espectáculos (hero-latest) ---------------------------------------
	$wp_customize->add_section( 'dereporteros_home_herolatest', [
		'title'       => __( 'Espectáculos', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/hero-latest.php.', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 70,
	] );
	$wp_customize->add_setting( 'dereporteros_home_herolatest_source', [
		'default'           => 'espectaculos',
		'sanitize_callback' => 'sanitize_title',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_herolatest_source', [
		'section'     => 'dereporteros_home_herolatest',
		'type'        => 'text',
		'label'       => __( 'Categoría de origen (slug)', 'dereporteros-bento-claro' ),
		'description' => __( 'Categoría, no etiqueta.', 'dereporteros-bento-claro' ),
	] );
	$wp_customize->add_setting( 'dereporteros_home_herolatest_title', [
		'default'           => 'Espectáculos',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_herolatest_title', [
		'section' => 'dereporteros_home_herolatest',
		'type'    => 'text',
		'label'   => __( 'Texto del ribbon', 'dereporteros-bento-claro' ),
	] );

	// -- Fotografía (grid-section #2) -------------------------------------
	$wp_customize->add_section( 'dereporteros_home_fotografia', [
		'title'       => __( 'Fotografía', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/grid-section.php.', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 80,
	] );
	$wp_customize->add_setting( 'dereporteros_home_fotografia_source', [
		'default'           => 'fotografia',
		'sanitize_callback' => 'sanitize_title',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_fotografia_source', [
		'section'     => 'dereporteros_home_fotografia',
		'type'        => 'text',
		'label'       => __( 'Categoría de origen (slug)', 'dereporteros-bento-claro' ),
		'description' => __( 'Categoría, no etiqueta.', 'dereporteros-bento-claro' ),
	] );
	$wp_customize->add_setting( 'dereporteros_home_fotografia_title', [
		'default'           => 'Fotografía',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_fotografia_title', [
		'section' => 'dereporteros_home_fotografia',
		'type'    => 'text',
		'label'   => __( 'Título de la sección', 'dereporteros-bento-claro' ),
	] );

	// -- Más leídas (latest-feed) — solo título, sin source ---------------
	$wp_customize->add_section( 'dereporteros_home_feed', [
		'title'       => __( 'Más leídas', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/latest-feed.php. Sin fuente propia: reutiliza lo que sobra de la consulta general de la portada.', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 90,
	] );
	$wp_customize->add_setting( 'dereporteros_home_feed_title', [
		'default'           => 'Más leídas',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_feed_title', [
		'section' => 'dereporteros_home_feed',
		'type'    => 'text',
		'label'   => __( 'Título de la sección', 'dereporteros-bento-claro' ),
	] );

	// -- Publicidad, banner lateral (promo-banner-side) -------------------
	$wp_customize->add_section( 'dereporteros_home_promo2', [
		'title'       => __( 'Publicidad (banner lateral)', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/promo-banner-side.php.', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 100,
	] );
	$wp_customize->add_setting( 'dereporteros_home_promo2_source', [
		'default'           => 'publicidad2',
		'sanitize_callback' => 'sanitize_title',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_promo2_source', [
		'section'     => 'dereporteros_home_promo2',
		'type'        => 'text',
		'label'       => __( 'Etiqueta de origen (slug)', 'dereporteros-bento-claro' ),
		'description' => __( 'Etiqueta, no categoría.', 'dereporteros-bento-claro' ),
	] );
	$wp_customize->add_setting( 'dereporteros_home_promo2_label', [
		'default'           => 'Publicidad',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_promo2_label', [
		'section' => 'dereporteros_home_promo2',
		'type'    => 'text',
		'label'   => __( 'Texto sobre la imagen', 'dereporteros-bento-claro' ),
	] );

	// -- Seguridad (grid-section #3) --------------------------------------
	$wp_customize->add_section( 'dereporteros_home_seguridad', [
		'title'       => __( 'Seguridad', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/grid-section.php.', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 110,
	] );
	$wp_customize->add_setting( 'dereporteros_home_seguridad_source', [
		'default'           => 'seguridad',
		'sanitize_callback' => 'sanitize_title',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_seguridad_source', [
		'section'     => 'dereporteros_home_seguridad',
		'type'        => 'text',
		'label'       => __( 'Categoría de origen (slug)', 'dereporteros-bento-claro' ),
		'description' => __( 'Categoría, no etiqueta.', 'dereporteros-bento-claro' ),
	] );
	$wp_customize->add_setting( 'dereporteros_home_seguridad_title', [
		'default'           => 'Seguridad',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_seguridad_title', [
		'section' => 'dereporteros_home_seguridad',
		'type'    => 'text',
		'label'   => __( 'Título de la sección', 'dereporteros-bento-claro' ),
	] );

	// -- Clima (grid-section #4) -------------------------------------------
	$wp_customize->add_section( 'dereporteros_home_clima', [
		'title'       => __( 'Clima', 'dereporteros-bento-claro' ),
		'description' => __( 'Corresponde a template-parts/grid-section.php.', 'dereporteros-bento-claro' ),
		'panel'       => 'dereporteros_home',
		'priority'    => 120,
	] );
	$wp_customize->add_setting( 'dereporteros_home_clima_source', [
		'default'           => 'clima',
		'sanitize_callback' => 'sanitize_title',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_clima_source', [
		'section'     => 'dereporteros_home_clima',
		'type'        => 'text',
		'label'       => __( 'Categoría de origen (slug)', 'dereporteros-bento-claro' ),
		'description' => __( 'Categoría, no etiqueta.', 'dereporteros-bento-claro' ),
	] );
	$wp_customize->add_setting( 'dereporteros_home_clima_title', [
		'default'           => 'Clima',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'dereporteros_home_clima_title', [
		'section' => 'dereporteros_home_clima',
		'type'    => 'text',
		'label'   => __( 'Título de la sección', 'dereporteros-bento-claro' ),
	] );
} );

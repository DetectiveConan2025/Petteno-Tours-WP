<?php
/**
 * Template Name: Pettenò Tours — Rotte scolastiche
 *
 * Pagina dedicata alla tabella orari del trasporto scolastico: creata
 * automaticamente alla prima attivazione del tema (vedi
 * petteno_tours_seed_routes_page() in inc/post-types.php). Utile come link
 * a sé stante, ad esempio nella documentazione per un bando comunale.
 *
 * @package PettenoTours
 */

get_header();
?>
<main id="main">
	<?php get_template_part( 'template-parts/routes' ); ?>
</main>
<?php
get_footer();

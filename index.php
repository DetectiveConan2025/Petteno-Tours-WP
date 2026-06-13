<?php
/**
 * Template di fallback.
 *
 * Identico alla home: il sito è una landing page singola, quindi anche
 * quando WordPress non usa front-page.php (es. nessuna pagina statica
 * impostata come home) mostriamo comunque tutte le sezioni in ordine.
 *
 * @package PettenoTours
 */

get_header();
?>
<main>
	<?php
	get_template_part( 'template-parts/hero' );
	get_template_part( 'template-parts/services' );
	get_template_part( 'template-parts/fleet' );
	get_template_part( 'template-parts/about' );
	get_template_part( 'template-parts/contact' );
	?>
</main>
<?php
get_footer();

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
	// Le sezioni si attivano/disattivano da Aspetto → Personalizza → Pettenò Tours.
	get_template_part( 'template-parts/hero' );
	if ( petteno_show( 'show_servizi' ) ) {
		get_template_part( 'template-parts/services' );
	}
	if ( petteno_show( 'show_flotta' ) ) {
		get_template_part( 'template-parts/fleet' );
	}
	if ( petteno_show( 'show_rotte' ) ) {
		get_template_part( 'template-parts/routes' );
	}
	if ( petteno_show( 'show_chi_siamo' ) ) {
		get_template_part( 'template-parts/about' );
	}
	get_template_part( 'template-parts/contact' );
	?>
</main>
<?php
get_footer();

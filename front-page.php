<?php
/**
 * Template della home page: assembla le sezioni della landing in ordine.
 *
 * @package PettenoTours
 */

get_header();
?>
<main id="main">
	<?php
	// Le sezioni si attivano/disattivano da Aspetto → Personalizza → Pettenò Tours.
	get_template_part( 'template-parts/hero' );

	// Sezioni attive, nell'ordine. Lo sfondo si alterna in base a quelle
	// effettivamente mostrate (bianco, azzurrino, bianco, …): spegnendone
	// una, le successive mantengono l'alternanza corretta.
	$petteno_sections = array(
		'show_servizi'   => 'template-parts/services',
		'show_flotta'    => 'template-parts/fleet',
		'show_rotte'     => 'template-parts/routes-teaser',
		'show_chi_siamo' => 'template-parts/about',
	);
	$petteno_i = 0;
	foreach ( $petteno_sections as $petteno_key => $petteno_part ) {
		if ( ! petteno_show( $petteno_key ) ) {
			continue;
		}
		get_template_part( $petteno_part, null, array( 'tone' => ( 0 === $petteno_i % 2 ) ? 'tone-light' : 'tone-tint' ) );
		++$petteno_i;
	}

	// Il modulo di preventivo vive nella pagina dedicata "Contatti"
	// (template-contatto.php): qui resta solo il banner che vi rimanda.
	get_template_part( 'template-parts/cta-band' );
	?>
</main>
<?php
get_footer();

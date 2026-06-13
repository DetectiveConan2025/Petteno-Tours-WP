<?php
/**
 * Template della home page: assembla le sezioni della landing in ordine.
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

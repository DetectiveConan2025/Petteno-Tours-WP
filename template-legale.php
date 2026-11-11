<?php
/**
 * Template Name: Pettenò Tours — Pagina legale
 *
 * Usato da Privacy policy e Cookie policy (create automaticamente, vedi
 * inc/legal-pages.php). Il testo si modifica dall'editor della pagina.
 *
 * @package PettenoTours
 */

get_header();
?>
<main id="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<section class="section legal">
			<div class="wrap legal-wrap">
				<span class="kicker"><?php esc_html_e( 'Informazioni legali', 'petteno-tours' ); ?></span>
				<h1 class="h2"><?php the_title(); ?></h1>
				<p class="legal-date">
					<?php
					/* translators: %s: data dell'ultimo aggiornamento. */
					printf( esc_html__( 'Ultimo aggiornamento: %s', 'petteno-tours' ), esc_html( get_the_modified_date( 'j F Y' ) ) );
					?>
				</p>
				<div class="legal-body">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
	<?php endwhile; ?>
</main>
<?php
get_footer();

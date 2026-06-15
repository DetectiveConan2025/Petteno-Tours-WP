<?php
/**
 * Sezione Hero: slideshow di sfondo, titolo, CTA, freccia scroll.
 * Contenuti modificabili da Aspetto → Personalizza → Pettenò Tours → Hero.
 *
 * @package PettenoTours
 */

// Slideshow a immagini illimitate: lista di ID dal Customizer; se vuota,
// si usano le due foto predefinite (sito invariato).
$petteno_ids    = array_filter( array_map( 'absint', explode( ',', (string) get_theme_mod( 'hero_images', '' ) ) ) );
$petteno_slides = array();
foreach ( $petteno_ids as $petteno_id ) {
	$petteno_url = wp_get_attachment_image_url( $petteno_id, 'full' );
	if ( $petteno_url ) {
		$petteno_alt      = get_post_meta( $petteno_id, '_wp_attachment_image_alt', true );
		$petteno_slides[] = array(
			'src' => $petteno_url,
			'alt' => $petteno_alt ? $petteno_alt : 'Pullman Pettenò Tours',
		);
	}
}
if ( ! $petteno_slides ) {
	$petteno_slides = array(
		array( 'src' => petteno_opt( 'hero_img_1' ), 'alt' => 'Pullman Pettenò Tours con palme al sole' ),
		array( 'src' => petteno_opt( 'hero_img_2' ), 'alt' => 'Pullman Pettenò Tours con panorama alpino' ),
	);
}
?>
<section id="top" class="hero" aria-label="Pettenò Tours — noleggio pullman">
	<!-- Slideshow fotografico in background (N immagini, crossfade via JS) -->
	<div class="slides" data-slideshow aria-hidden="true">
		<?php foreach ( $petteno_slides as $i => $slide ) : ?>
			<div
				class="slide<?php echo 0 === $i ? ' is-active' : ''; ?>"
				style="background-image: url('<?php echo esc_url( $slide['src'] ); ?>')"
				role="img"
				aria-label="<?php echo esc_attr( $slide['alt'] ); ?>"
			></div>
		<?php endforeach; ?>
	</div>

	<!-- Overlay scuro sfumato -->
	<div class="overlay" aria-hidden="true"></div>

	<!-- Contenuto centrato -->
	<div class="wrap hero-content">
		<div class="hero-copy">
			<span class="pill pill-light"><?php echo esc_html( petteno_opt( 'hero_badge' ) ); ?></span>
			<h1>
				<?php echo esc_html( petteno_opt( 'hero_title_l1' ) ); ?><br />
				<?php echo esc_html( petteno_opt( 'hero_title_pre' ) ); ?> <span class="accent-w"><?php echo esc_html( petteno_opt( 'hero_accent' ) ); ?></span><?php echo esc_html( petteno_opt( 'hero_title_post' ) ); ?>
			</h1>
			<p class="lead"><?php echo esc_html( petteno_opt( 'hero_lead' ) ); ?></p>
			<div class="hero-actions">
				<a href="#contatti" class="btn btn-white"><?php echo esc_html( petteno_opt( 'hero_cta1' ) ); ?></a>
				<a href="#flotta" class="btn btn-ghost-white"><?php echo esc_html( petteno_opt( 'hero_cta2' ) ); ?></a>
			</div>
		</div>
	</div>

	<!-- Freccia "scorri giù" -->
	<a href="#servizi" class="scroll-down" aria-label="Scorri alla sezione Servizi">
		<svg width="30" height="30" viewBox="0 0 24 24" fill="none"
			stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
			<polyline points="6 9 12 15 18 9" />
		</svg>
	</a>
</section>

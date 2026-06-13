<?php
/**
 * Sezione Hero: slideshow di sfondo, titolo, CTA, freccia scroll.
 *
 * @package PettenoTours
 */

$petteno_slides = array(
	array(
		'src' => petteno_tours_img( 'hero-1.jpg' ),
		'alt' => 'Pullman Pettenò Tours con palme al sole',
	),
	array(
		'src' => petteno_tours_img( 'hero-2.jpg' ),
		'alt' => 'Pullman Pettenò Tours con panorama alpino',
	),
);
?>
<section id="top" class="hero" aria-label="Pettenò Tours — noleggio pullman">
	<!-- Slideshow fotografico in background -->
	<div class="slides" aria-hidden="true">
		<?php foreach ( $petteno_slides as $i => $slide ) : ?>
			<div
				class="slide slide-<?php echo (int) ( $i + 1 ); ?>"
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
			<span class="pill pill-light">Noleggio pullman con conducente · Veneto</span>
			<h1>
				Il tuo viaggio<br />
				in <span class="accent-w">buone mani</span>.
			</h1>
			<p class="lead">
				Da Robegano di Salzano, Pettenò Tours porta gruppi, scuole e aziende
				dove devono andare — in Italia e in tutta Europa, con pullman Gran
				Turismo, Scuolabus e autisti esperti.
			</p>
			<div class="hero-actions">
				<a href="#contatti" class="btn btn-white">Richiedi un preventivo</a>
				<a href="#flotta" class="btn btn-ghost-white">Scopri la flotta</a>
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

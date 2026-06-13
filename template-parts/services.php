<?php
/**
 * Sezione Servizi: griglia "bento" di sei servizi con icone inline.
 *
 * @package PettenoTours
 */

$petteno_services = array(
	array(
		'title'   => 'Gite ed escursioni turistiche',
		'desc'    => "Siamo specializzati nell'organizzazione di tour di gruppo e viaggi scolastici, ma offriamo anche soluzioni di trasporto personalizzate per soddisfare qualsiasi esigenza. I nostri autisti conoscono bene le rotte europee: viaggiate in sicurezza e comodità.",
		'feature' => true,
		'icon'    => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20"/>',
	),
	array(
		'title'   => 'Trasporti scolastici',
		'desc'    => 'Mettiamo a disposizione autobus, minibus e autisti esperti per garantire un servizio scolastico puntuale e sicuro, con la tranquillità dei genitori e il comfort degli studenti.',
		'feature' => false,
		'icon'    => '<path d="M22 10v6"/><path d="M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1 2.7 2.5 6 2.5s6-1.5 6-2.5v-5"/>',
	),
	array(
		'title'   => 'Transfer aeroporti e stazioni',
		'desc'    => 'Collegamenti puntuali da e per Venezia, Verona, Bergamo, Bologna e le principali stazioni.',
		'feature' => false,
		'icon'    => '<path d="M17.8 19.2 16 11l3.5-3.5a2.1 2.1 0 0 0-3-3L13 8 4.8 6.2a1 1 0 0 0-1 .3l-.9.9 6 3.4-2.4 2.4-2.4-.6-.9.9 3 1.7 1.7 3 .9-.9-.6-2.4 2.4-2.4 3.4 6 .9-.9a1 1 0 0 0 .3-1Z"/>',
	),
	array(
		'title'   => 'Eventi e cerimonie',
		'desc'    => 'Matrimoni, congressi, concerti: navette dedicate perché nessuno pensi al parcheggio.',
		'feature' => false,
		'icon'    => '<path d="M12 2 9.2 8.6 2 9.2l5.5 4.7L5.8 21 12 17.3 18.2 21l-1.7-7.1L22 9.2l-7.2-.6z"/>',
	),
	array(
		'title'   => 'Tour in Italia e in Europa',
		'desc'    => 'Itinerari di più giorni con un unico interlocutore: mezzo, autista e logistica coordinati.',
		'feature' => false,
		'icon'    => '<circle cx="6" cy="19" r="3"/><path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"/><circle cx="18" cy="5" r="3"/>',
	),
	array(
		'title'   => 'Trasferte aziendali',
		'desc'    => 'Shuttle per dipendenti, fiere e team building, con fatturazione e referente unico.',
		'feature' => false,
		'icon'    => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M3 13h18"/>',
	),
);

// SVG inline consentiti nelle icone: tag e attributi usati dai path.
$petteno_svg_allowed = array(
	'svg'      => array( 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'width' => true, 'height' => true ),
	'circle'   => array( 'cx' => true, 'cy' => true, 'r' => true, 'fill' => true ),
	'path'     => array( 'd' => true, 'fill' => true ),
	'rect'     => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'fill' => true ),
	'polyline' => array( 'points' => true ),
);
?>
<section id="servizi" class="section services">
	<div class="wrap">
		<div class="section-head reveal">
			<span class="kicker">Cosa facciamo</span>
			<h2>Un mezzo e un autista per ogni occasione</h2>
			<p class="lead">
				Dalla gita di un giorno al tour di una settimana, gestiamo noi mezzo,
				conducente e tempi. Tu pensi alle persone da portare.
			</p>
		</div>

		<div class="bento">
			<?php foreach ( $petteno_services as $service ) : ?>
				<article class="tile reveal <?php echo $service['feature'] ? 'tile-feature' : ''; ?>">
					<span class="tile-icon">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo wp_kses( $service['icon'], $petteno_svg_allowed ); ?></svg>
					</span>
					<h3><?php echo esc_html( $service['title'] ); ?></h3>
					<p><?php echo esc_html( $service['desc'] ); ?></p>
					<a href="#contatti" class="tile-link">Richiedi info
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
					</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

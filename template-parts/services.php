<?php
/**
 * Sezione Servizi: griglia "bento" di sei servizi con icone inline.
 *
 * @package PettenoTours
 */

// Le icone restano fisse (parte del design); titoli e descrizioni sono
// modificabili dal Customizer (Pettenò Tours → Servizi).
$petteno_icons = array(
	'<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20"/>',
	'<path d="M22 10v6"/><path d="M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1 2.7 2.5 6 2.5s6-1.5 6-2.5v-5"/>',
	'<path d="M17.8 19.2 16 11l3.5-3.5a2.1 2.1 0 0 0-3-3L13 8 4.8 6.2a1 1 0 0 0-1 .3l-.9.9 6 3.4-2.4 2.4-2.4-.6-.9.9 3 1.7 1.7 3 .9-.9-.6-2.4 2.4-2.4 3.4 6 .9-.9a1 1 0 0 0 .3-1Z"/>',
	'<path d="M12 2 9.2 8.6 2 9.2l5.5 4.7L5.8 21 12 17.3 18.2 21l-1.7-7.1L22 9.2l-7.2-.6z"/>',
	'<circle cx="6" cy="19" r="3"/><path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"/><circle cx="18" cy="5" r="3"/>',
	'<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M3 13h18"/>',
);

$petteno_services = array();
foreach ( $petteno_icons as $idx => $icon ) {
	$n                   = $idx + 1;
	$petteno_services[] = array(
		'title'   => petteno_opt( "serv_{$n}_title" ),
		'desc'    => petteno_opt( "serv_{$n}_desc" ),
		'feature' => ( 1 === $n ), // la prima scheda è quella in evidenza.
		'icon'    => $icon,
	);
}

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
			<span class="kicker"><?php echo esc_html( petteno_opt( 'serv_kicker' ) ); ?></span>
			<h2><?php echo esc_html( petteno_opt( 'serv_title' ) ); ?></h2>
			<p class="lead"><?php echo esc_html( petteno_opt( 'serv_lead' ) ); ?></p>
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

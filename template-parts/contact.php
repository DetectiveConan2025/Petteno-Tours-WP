<?php
/**
 * Sezione Contatti: recapiti + form preventivo (invio via admin-ajax).
 *
 * @package PettenoTours
 */

// Recapiti dal Customizer (Pettenò Tours → Azienda e contatti).
$petteno_phone_icon = '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.1-8.7A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.4-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2Z"/>';
$petteno_contacts   = array(
	array(
		'label'  => 'Telefono',
		'value'  => petteno_opt( 'tel_mobile' ),
		'href'   => petteno_tel_href( petteno_opt( 'tel_mobile' ) ),
		'target' => '',
		'icon'   => $petteno_phone_icon,
	),
	array(
		'label'  => 'Telefono fisso',
		'value'  => petteno_opt( 'tel_fisso' ),
		'href'   => petteno_tel_href( petteno_opt( 'tel_fisso' ) ),
		'target' => '',
		'icon'   => $petteno_phone_icon,
	),
	array(
		'label'  => 'Email',
		'value'  => petteno_opt( 'email' ),
		'href'   => 'mailto:' . petteno_opt( 'email' ),
		'target' => '',
		'icon'   => '<rect x="2" y="4" width="20" height="16" rx="3"/><path d="m3 6 9 7 9-7"/>',
	),
	array(
		'label'  => 'Sede',
		'value'  => petteno_address_short(),
		'href'   => petteno_opt( 'maps_url' ),
		'target' => '_blank',
		'icon'   => '<path d="M12 21s-7-5.7-7-11a7 7 0 0 1 14 0c0 5.3-7 11-7 11Z"/><circle cx="12" cy="10" r="2.6"/>',
	),
);

$petteno_svg_allowed = array(
	'svg'    => array( 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true, 'stroke-linecap' => true, 'stroke-linejoin' => true, 'width' => true, 'height' => true, 'aria-hidden' => true, 'class' => true ),
	'circle' => array( 'cx' => true, 'cy' => true, 'r' => true, 'fill' => true ),
	'path'   => array( 'd' => true, 'fill' => true ),
	'rect'   => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'fill' => true ),
);
?>
<section id="contatti" class="contact">
	<div class="wrap contact-grid">
		<div class="contact-intro">
			<span class="kicker kicker-light"><?php echo esc_html( petteno_opt( 'contact_kicker' ) ); ?></span>
			<h2><?php echo esc_html( petteno_opt( 'contact_title' ) ); ?></h2>
			<p><?php echo esc_html( petteno_opt( 'contact_intro' ) ); ?></p>

			<ul class="contact-cards">
				<?php foreach ( $petteno_contacts as $contact ) : ?>
					<li>
						<span class="ccard-icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo wp_kses( $contact['icon'], $petteno_svg_allowed ); ?></svg>
						</span>
						<div>
							<span class="ccard-label"><?php echo esc_html( $contact['label'] ); ?></span>
							<a href="<?php echo esc_url( $contact['href'] ); ?>" class="ccard-value"<?php echo '_blank' === $contact['target'] ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( $contact['value'] ); ?></a>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<form class="contact-form" id="preventivo" novalidate>
			<div class="field">
				<label for="nome">Nome e cognome <span class="req" aria-hidden="true">*</span></label>
				<input id="nome" name="nome" type="text" autocomplete="name" required />
			</div>
			<div class="field-row">
				<div class="field">
					<label for="email">Email <span class="req" aria-hidden="true">*</span></label>
					<input id="email" name="email" type="email" autocomplete="email" required />
				</div>
				<div class="field">
					<label for="telefono">Telefono <span class="req" aria-hidden="true">*</span></label>
					<input id="telefono" name="telefono" type="tel" autocomplete="tel" required />
				</div>
			</div>
			<div class="field-row">
				<div class="field">
					<label for="persone">Numero di persone</label>
					<input id="persone" name="persone" type="number" min="1" inputmode="numeric" />
				</div>
				<div class="field">
					<label for="data">Data di partenza</label>
					<div class="date-wrap">
						<input id="data" name="data" type="date" />
						<svg class="date-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
					</div>
				</div>
			</div>
			<div class="field">
				<label for="messaggio">Dove andate e per quale occasione? <span class="req" aria-hidden="true">*</span></label>
				<textarea id="messaggio" name="messaggio" rows="4" placeholder="Es. Gita scolastica Venezia – Firenze, andata e ritorno in giornata" required></textarea>
			</div>
			<button type="submit" class="btn btn-primary form-submit">Invia richiesta</button>
			<p class="form-note"><span class="req" aria-hidden="true">*</span> Campi obbligatori</p>
			<p class="form-hint" role="status" aria-live="polite"></p>
		</form>
	</div>
</section>

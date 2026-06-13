<?php
/**
 * Footer del tema: colonne link, recapiti, riga legale, chiusura <body>.
 *
 * @package PettenoTours
 */

$petteno_cols = array(
	array(
		'title' => __( 'Servizi', 'petteno-tours' ),
		'links' => array(
			array( 'label' => 'Gite ed escursioni turistiche', 'href' => '#servizi' ),
			array( 'label' => 'Trasporti scolastici', 'href' => '#servizi' ),
			array( 'label' => 'Transfer aeroporti', 'href' => '#servizi' ),
			array( 'label' => 'Eventi e cerimonie', 'href' => '#servizi' ),
			array( 'label' => 'Tour in Europa', 'href' => '#servizi' ),
		),
	),
	array(
		'title' => __( 'Azienda', 'petteno-tours' ),
		'links' => array(
			array( 'label' => 'Chi siamo', 'href' => '#chi-siamo' ),
			array( 'label' => 'La flotta', 'href' => '#flotta' ),
			array( 'label' => 'Contatti', 'href' => '#contatti' ),
		),
	),
);
?>
<footer class="site-footer">
	<div class="wrap footer-grid">
		<div class="footer-brand">
			<div class="brand">
				<img
					src="<?php echo esc_url( petteno_tours_img( 'logo.png' ) ); ?>"
					alt="Pettenò Tours"
					class="footer-logo"
					onerror="this.style.display='none';document.getElementById('brand-fallback-f').style.display='flex'"
				/>
				<span class="brand-text-fallback" id="brand-fallback-f" style="display:none">
					<strong>Pettenò</strong>
					<span>Tours</span>
				</span>
			</div>
			<p><?php esc_html_e( 'Noleggio autobus con conducente per gruppi, scuole e aziende. In viaggio in Italia e in Europa, dal Veneto.', 'petteno-tours' ); ?></p>
			<a href="#contatti" class="btn btn-primary footer-cta"><?php esc_html_e( 'Richiedi un preventivo', 'petteno-tours' ); ?></a>
		</div>

		<?php foreach ( $petteno_cols as $col ) : ?>
			<nav class="footer-col" aria-label="<?php echo esc_attr( $col['title'] ); ?>">
				<h3><?php echo esc_html( $col['title'] ); ?></h3>
				<ul>
					<?php foreach ( $col['links'] as $link ) : ?>
						<li><a href="<?php echo esc_url( $link['href'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endforeach; ?>

		<div class="footer-col">
			<h3><?php esc_html_e( 'Contatti', 'petteno-tours' ); ?></h3>
			<ul>
				<li><a href="tel:+393489280768">+39 348 928 0768</a></li>
				<li><a href="tel:+39041482231">041 482231</a></li>
				<li><a href="mailto:pettenotours@gmail.com">pettenotours@gmail.com</a></li>
				<li>Via Leonardo da Vinci 39/B, 30030 Salzano (VE)</li>
			</ul>
		</div>
	</div>

	<div class="wrap footer-bottom">
		<p>© <span id="footer-year"><?php echo esc_html( gmdate( 'Y' ) ); ?></span> Pettenò Tours S.a.s. di Pettenò Luca &amp; C. — P.IVA 02172370278</p>
		<p>Via Leonardo da Vinci 39/B, 30030 Salzano (VE)</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

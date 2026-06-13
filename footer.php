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
			<a href="#contatti" class="btn btn-primary footer-cta"><?php echo esc_html( petteno_opt( 'hero_cta1' ) ); ?></a>
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
				<li><a href="<?php echo esc_url( petteno_tel_href( petteno_opt( 'tel_mobile' ) ) ); ?>"><?php echo esc_html( petteno_opt( 'tel_mobile' ) ); ?></a></li>
				<li><a href="<?php echo esc_url( petteno_tel_href( petteno_opt( 'tel_fisso' ) ) ); ?>"><?php echo esc_html( petteno_opt( 'tel_fisso' ) ); ?></a></li>
				<li><a href="mailto:<?php echo esc_attr( petteno_opt( 'email' ) ); ?>"><?php echo esc_html( petteno_opt( 'email' ) ); ?></a></li>
				<li><?php echo esc_html( petteno_address_full() ); ?></li>
			</ul>
		</div>
	</div>

	<div class="wrap footer-bottom">
		<p>© <span id="footer-year"><?php echo esc_html( gmdate( 'Y' ) ); ?></span> <?php echo esc_html( petteno_opt( 'ragione_sociale' ) ); ?> — P.IVA <?php echo esc_html( petteno_opt( 'piva' ) ); ?></p>
		<p><?php echo esc_html( petteno_address_full() ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

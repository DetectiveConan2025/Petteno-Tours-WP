<?php
/**
 * Footer del tema: colonne link, recapiti, riga legale, chiusura <body>.
 *
 * @package PettenoTours
 */

$petteno_cols = array();

// Colonna Servizi (solo se la sezione è attiva).
if ( petteno_show( 'show_servizi' ) ) {
	$petteno_cols[] = array(
		'title' => __( 'Servizi', 'petteno-tours' ),
		'links' => array(
			array( 'label' => 'Gite ed escursioni turistiche', 'href' => petteno_tours_anchor( '#servizi' ) ),
			array( 'label' => 'Trasporti scolastici', 'href' => petteno_tours_anchor( '#servizi' ) ),
			array( 'label' => 'Transfer aeroporti', 'href' => petteno_tours_anchor( '#servizi' ) ),
			array( 'label' => 'Eventi e cerimonie', 'href' => petteno_tours_anchor( '#servizi' ) ),
			array( 'label' => 'Tour in Europa', 'href' => petteno_tours_anchor( '#servizi' ) ),
		),
	);
}

// Colonna Azienda: voci coerenti con le sezioni attive.
$petteno_azienda_links = array();
if ( petteno_show( 'show_chi_siamo' ) ) {
	$petteno_azienda_links[] = array( 'label' => 'Chi siamo', 'href' => petteno_tours_anchor( '#chi-siamo' ) );
}
if ( petteno_show( 'show_flotta' ) ) {
	$petteno_azienda_links[] = array( 'label' => 'La flotta', 'href' => petteno_tours_anchor( '#flotta' ) );
}
if ( petteno_show( 'show_rotte' ) ) {
	$petteno_azienda_links[] = array( 'label' => 'Rotte scolastiche', 'href' => petteno_tours_routes_url() );
}
$petteno_azienda_links[] = array( 'label' => 'Contatti', 'href' => petteno_tours_contact_url() );
$petteno_cols[]          = array(
	'title' => __( 'Azienda', 'petteno-tours' ),
	'links' => $petteno_azienda_links,
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
		<p class="footer-legal">
			<a href="<?php echo esc_url( petteno_tours_privacy_url() ); ?>"><?php esc_html_e( 'Privacy policy', 'petteno-tours' ); ?></a>
			<span aria-hidden="true">·</span>
			<a href="<?php echo esc_url( petteno_tours_cookie_url() ); ?>"><?php esc_html_e( 'Cookie policy', 'petteno-tours' ); ?></a>
		</p>
	</div>
</footer>

<?php
// Pulsante WhatsApp flottante (solo se il numero è impostato nel Customizer).
$petteno_wa = preg_replace( '/\D/', '', (string) petteno_opt( 'whatsapp' ) );
if ( $petteno_wa ) :
	?>
	<a href="<?php echo esc_url( 'https://wa.me/' . $petteno_wa ); ?>" class="wa-float" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Scrivici su WhatsApp', 'petteno-tours' ); ?>">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 21l1.7-5A8.5 8.5 0 1 1 8 19.3z"/><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-2-2l.8-1-1-2z"/></svg>
	</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>

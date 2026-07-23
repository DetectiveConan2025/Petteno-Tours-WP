<?php
/**
 * Richiamo "Rotte scolastiche" in home: la tabella completa vive nella
 * pagina dedicata (template-rotte.php, linkabile a sé stante per i bandi).
 * Qui resta solo un blocco compatto che rimanda a quella pagina.
 *
 * @package PettenoTours
 */

// Conteggio linee attive, solo per il badge (stesso parsing di routes.php).
$petteno_lines = preg_split( '/\r\n|\r|\n/', (string) petteno_opt( 'rotte_list' ) );
$petteno_count = 0;
foreach ( $petteno_lines as $petteno_line ) {
	if ( '' !== trim( $petteno_line ) ) {
		++$petteno_count;
	}
}
?>
<section id="rotte" class="section routes-teaser-section">
	<div class="wrap routes-teaser">
		<div class="routes-teaser-copy reveal">
			<span class="kicker"><?php echo esc_html( petteno_opt( 'rotte_kicker' ) ); ?></span>
			<h2><?php echo esc_html( petteno_opt( 'rotte_title' ) ); ?></h2>
			<p class="lead"><?php echo esc_html( petteno_opt( 'rotte_lead' ) ); ?></p>
		</div>
		<div class="routes-teaser-actions reveal">
			<?php if ( $petteno_count > 0 ) : ?>
				<span class="pill">
					<?php
					echo esc_html(
						sprintf(
							/* translators: %d: numero di linee attive. */
							_n( '%d linea attiva', '%d linee attive', $petteno_count, 'petteno-tours' ),
							$petteno_count
						)
					);
					?>
				</span>
			<?php endif; ?>
			<a href="<?php echo esc_url( petteno_tours_routes_url() ); ?>" class="btn btn-primary">
				<?php echo esc_html( petteno_opt( 'rotte_cta' ) ); ?>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
			</a>
		</div>
	</div>
</section>

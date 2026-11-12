<?php
/**
 * Sezione "Dicono di noi": fino a 3 recensioni + link al profilo Google.
 * Nessun widget esterno: testi copiati nel menu "Recensioni".
 *
 * @package PettenoTours
 */

$petteno_reviews = petteno_tours_get_reviews();
if ( ! $petteno_reviews ) {
	return;
}
$petteno_star = '<path d="M12 2 9.2 8.6 2 9.2l5.5 4.7L5.8 21 12 17.3 18.2 21l-1.7-7.1L22 9.2l-7.2-.6z"/>';
?>
<section id="recensioni" class="section reviews <?php echo esc_attr( isset( $args['tone'] ) ? $args['tone'] : '' ); ?>">
	<div class="wrap">
		<div class="section-head reveal">
			<span class="kicker"><?php echo esc_html( petteno_opt( 'rev_kicker' ) ); ?></span>
			<h2><?php echo esc_html( petteno_opt( 'rev_title' ) ); ?></h2>
		</div>

		<div class="reviews-grid">
			<?php foreach ( $petteno_reviews as $review ) : ?>
				<figure class="review-card reveal">
					<div class="review-stars" role="img" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: numero di stelle. */ __( '%d stelle su 5', 'petteno-tours' ), $review['stelle'] ) ); ?>">
						<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
							<svg viewBox="0 0 24 24" aria-hidden="true" class="<?php echo $i <= $review['stelle'] ? 'on' : 'off'; ?>"><?php echo $petteno_star; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></svg>
						<?php endfor; ?>
					</div>
					<blockquote class="review-text"><p><?php echo nl2br( esc_html( $review['testo'] ) ); ?></p></blockquote>
					<figcaption class="review-meta">
						<strong><?php echo esc_html( $review['nome'] ); ?></strong>
						<?php
						$petteno_meta = array_filter( array( $review['fonte'], $review['data'] ) );
						if ( $petteno_meta ) :
							?>
							<span><?php echo esc_html( implode( ' · ', $petteno_meta ) ); ?></span>
						<?php endif; ?>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>

		<div class="reviews-footer reveal">
			<?php if ( petteno_opt( 'rev_note' ) ) : ?>
				<p class="fleet-note"><?php echo esc_html( petteno_opt( 'rev_note' ) ); ?></p>
			<?php endif; ?>
			<div class="reviews-actions">
				<?php if ( petteno_opt( 'rev_google_url' ) ) : ?>
					<a class="btn btn-primary" href="<?php echo esc_url( petteno_opt( 'rev_google_url' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Leggi tutte le recensioni su Google', 'petteno-tours' ); ?></a>
				<?php endif; ?>
				<?php if ( petteno_opt( 'rev_write_url' ) ) : ?>
					<a class="btn btn-ghost" href="<?php echo esc_url( petteno_opt( 'rev_write_url' ) ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Lascia una recensione', 'petteno-tours' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

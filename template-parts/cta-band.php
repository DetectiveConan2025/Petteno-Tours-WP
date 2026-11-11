<?php
/**
 * Banner CTA finale: fascia stretta prima del footer che rimanda alla
 * pagina dedicata al modulo di contatto (template-contatto.php), invece di
 * avere un pulsante "richiedi preventivo" infilato nel footer.
 *
 * @package PettenoTours
 */
?>
<section class="cta-band" aria-label="<?php esc_attr_e( 'Richiedi un preventivo', 'petteno-tours' ); ?>">
	<div class="wrap cta-band-inner">
		<div class="cta-band-copy reveal">
			<span class="kicker kicker-light"><?php echo esc_html( petteno_opt( 'ctaband_kicker' ) ); ?></span>
			<h2><?php echo esc_html( petteno_opt( 'ctaband_title' ) ); ?></h2>
		</div>
		<div class="cta-band-actions reveal">
			<a href="<?php echo esc_url( petteno_tel_href( petteno_opt( 'tel_mobile' ) ) ); ?>" class="cta-band-phone">
				<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.1-8.7A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.4-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2Z"/></svg>
				<?php echo esc_html( petteno_opt( 'tel_mobile' ) ); ?>
			</a>
			<a href="<?php echo esc_url( petteno_tours_contact_url() ); ?>" class="btn btn-white">
				<?php echo esc_html( petteno_opt( 'ctaband_button' ) ); ?>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
			</a>
		</div>
	</div>
</section>

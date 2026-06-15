<?php
/**
 * Sezione Flotta: griglia di mezzi che si adatta al numero di veicoli.
 *
 * I veicoli si gestiscono dal menu "Mezzi" (post type petteno_mezzo): foto
 * principale, posti, descrizione, dotazioni e album. Se non c'è ancora nessun
 * mezzo, si usano i due predefiniti del Customizer (sito invariato).
 * Le intestazioni restano modificabili dal Customizer.
 *
 * @package PettenoTours
 */

$petteno_fleet = array();

// 1) Mezzi gestiti dall'admin.
$petteno_q = new WP_Query(
	array(
		'post_type'      => 'petteno_mezzo',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		'no_found_rows'  => true,
	)
);
if ( $petteno_q->have_posts() ) {
	while ( $petteno_q->have_posts() ) {
		$petteno_q->the_post();
		$petteno_pid = get_the_ID();
		$petteno_cov = get_post_thumbnail_id( $petteno_pid );

		// Album: copertina + foto della galleria (senza duplicare la copertina).
		$petteno_gids   = array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $petteno_pid, '_petteno_gallery', true ) ) ) );
		$petteno_images = array();
		if ( $petteno_cov ) {
			$petteno_images[] = $petteno_cov;
		}
		foreach ( $petteno_gids as $petteno_gid ) {
			if ( $petteno_gid !== (int) $petteno_cov ) {
				$petteno_images[] = $petteno_gid;
			}
		}

		$petteno_fleet[] = array(
			'name'     => get_the_title(),
			'seats'    => get_post_meta( $petteno_pid, '_petteno_posti', true ),
			'desc'     => get_post_meta( $petteno_pid, '_petteno_desc', true ),
			'specs'    => petteno_split_list( get_post_meta( $petteno_pid, '_petteno_dotazioni', true ) ),
			'cover_id' => $petteno_cov,
			'cover'    => '',
			'images'   => $petteno_images,
		);
	}
	wp_reset_postdata();
}

// 2) Fallback: i due mezzi predefiniti del Customizer.
if ( ! $petteno_fleet ) {
	foreach ( array( 1, 2 ) as $n ) {
		$petteno_fleet[] = array(
			'name'     => petteno_opt( "fleet_{$n}_name" ),
			'seats'    => petteno_opt( "fleet_{$n}_seats" ),
			'desc'     => petteno_opt( "fleet_{$n}_desc" ),
			'specs'    => petteno_split_list( petteno_opt( "fleet_{$n}_specs" ) ),
			'cover_id' => 0,
			'cover'    => petteno_opt( "fleet_{$n}_img" ),
			'images'   => array(),
		);
	}
}
?>
<section id="flotta" class="section fleet">
	<div class="wrap">
		<div class="section-head reveal">
			<span class="kicker"><?php echo esc_html( petteno_opt( 'fleet_kicker' ) ); ?></span>
			<h2><?php echo esc_html( petteno_opt( 'fleet_title' ) ); ?></h2>
			<p class="lead"><?php echo esc_html( petteno_opt( 'fleet_lead' ) ); ?></p>
		</div>

		<div class="fleet-grid">
			<?php
			foreach ( $petteno_fleet as $vehicle ) :
				$alt = sprintf(
					/* translators: %s: nome del mezzo. */
					__( '%s — Pettenò Tours', 'petteno-tours' ),
					$vehicle['name']
				);

				// Immagini dell'album in formato grande per il lightbox.
				$album = array();
				foreach ( $vehicle['images'] as $img_id ) {
					$src = wp_get_attachment_image_url( $img_id, 'large' );
					if ( $src ) {
						$album[] = array( 'src' => $src, 'alt' => $alt );
					}
				}
				$has_album = count( $album ) > 1;

				// Markup della copertina (da ID allegato o da URL di fallback).
				if ( $vehicle['cover_id'] ) {
					$cover_html = wp_get_attachment_image( $vehicle['cover_id'], 'large', false, array( 'alt' => $alt, 'loading' => 'lazy' ) );
				} elseif ( $vehicle['cover'] ) {
					$cover_html = '<img src="' . esc_url( $vehicle['cover'] ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy" width="800" height="600" />';
				} else {
					$cover_html = '';
				}
				?>
				<article class="fleet-card reveal">
					<div class="fleet-art">
						<?php if ( $has_album ) : ?>
							<button type="button" class="fleet-album" data-album="<?php echo esc_attr( wp_json_encode( $album ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: nome del mezzo. */ __( 'Apri la galleria di %s', 'petteno-tours' ), $vehicle['name'] ) ); ?>">
								<?php echo $cover_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span class="fleet-album-badge">
									<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
									<?php echo esc_html( count( $album ) ); ?>
								</span>
							</button>
						<?php else : ?>
							<?php echo $cover_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endif; ?>
					</div>
					<div class="fleet-body">
						<div class="fleet-top">
							<h3><?php echo esc_html( $vehicle['name'] ); ?></h3>
							<?php if ( $vehicle['seats'] ) : ?>
								<span class="seats"><?php echo esc_html( $vehicle['seats'] ); ?></span>
							<?php endif; ?>
						</div>
						<?php if ( $vehicle['desc'] ) : ?>
							<p><?php echo esc_html( $vehicle['desc'] ); ?></p>
						<?php endif; ?>
						<?php if ( $vehicle['specs'] ) : ?>
							<ul class="specs">
								<?php foreach ( $vehicle['specs'] as $spec ) : ?>
									<li><?php echo esc_html( $spec ); ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<?php if ( petteno_opt( 'fleet_note' ) ) : ?>
			<p class="fleet-note"><?php echo esc_html( petteno_opt( 'fleet_note' ) ); ?></p>
		<?php endif; ?>
	</div>
</section>

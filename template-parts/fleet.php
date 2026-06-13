<?php
/**
 * Sezione Flotta: elenco mezzi con foto, posti, descrizione e specifiche.
 *
 * @package PettenoTours
 */

// Contenuti modificabili dal Customizer (Pettenò Tours → Flotta).
// Le dotazioni si inseriscono come elenco separato da virgole.
$petteno_fleet = array();
foreach ( array( 1, 2 ) as $n ) {
	$specs              = array_filter( array_map( 'trim', explode( ',', petteno_opt( "fleet_{$n}_specs" ) ) ) );
	$name               = petteno_opt( "fleet_{$n}_name" );
	$petteno_fleet[]    = array(
		'name'     => $name,
		'seats'    => petteno_opt( "fleet_{$n}_seats" ),
		'photo'    => petteno_opt( "fleet_{$n}_img" ),
		/* translators: %s: nome del mezzo. */
		'photoAlt' => sprintf( __( '%s — Pettenò Tours', 'petteno-tours' ), $name ),
		'desc'     => petteno_opt( "fleet_{$n}_desc" ),
		'specs'    => $specs,
	);
}
?>
<section id="flotta" class="section fleet">
	<div class="wrap">
		<div class="section-head reveal">
			<span class="kicker"><?php echo esc_html( petteno_opt( 'fleet_kicker' ) ); ?></span>
			<h2><?php echo esc_html( petteno_opt( 'fleet_title' ) ); ?></h2>
			<p class="lead"><?php echo esc_html( petteno_opt( 'fleet_lead' ) ); ?></p>
		</div>

		<div class="fleet-list">
			<?php foreach ( $petteno_fleet as $vehicle ) : ?>
				<article class="fleet-row reveal">
					<div class="fleet-art">
						<img src="<?php echo esc_url( $vehicle['photo'] ); ?>" alt="<?php echo esc_attr( $vehicle['photoAlt'] ); ?>" loading="lazy" width="800" height="600" />
					</div>
					<div class="fleet-body">
						<div class="fleet-top">
							<h3><?php echo esc_html( $vehicle['name'] ); ?></h3>
							<span class="seats"><?php echo esc_html( $vehicle['seats'] ); ?></span>
						</div>
						<p><?php echo esc_html( $vehicle['desc'] ); ?></p>
						<ul class="specs">
							<?php foreach ( $vehicle['specs'] as $spec ) : ?>
								<li><?php echo esc_html( $spec ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="fleet-note"><?php echo esc_html( petteno_opt( 'fleet_note' ) ); ?></p>
	</div>
</section>

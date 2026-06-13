<?php
/**
 * Sezione Flotta: elenco mezzi con foto, posti, descrizione e specifiche.
 *
 * @package PettenoTours
 */

$petteno_fleet = array(
	array(
		'name'     => 'Gran Turismo',
		'seats'    => '48 – 54 posti',
		'photo'    => petteno_tours_img( 'hero-1.jpg' ),
		'photoAlt' => 'Pullman Gran Turismo Pettenò Tours',
		'desc'     => 'Il pullman per i grandi gruppi e i lunghi tragitti. Poltrone reclinabili, ampia bagagliera e tutti i comfort per viaggiare riposati.',
		'specs'    => array( 'Climatizzato', 'WC a bordo', 'Pedana disabili*' ),
	),
	array(
		'name'     => 'Scuolabus',
		'seats'    => '16 – 30 posti',
		'photo'    => petteno_tours_img( 'hero-3.jpg' ),
		'photoAlt' => 'Scuolabus Pettenò Tours',
		'desc'     => 'Dedicato al trasporto scolastico, con tutte le omologazioni di legge. Puntuale, sicuro e confortevole per gli studenti.',
		'specs'    => array( 'Omologato scuolabus', 'Climatizzato', 'Cinture di sicurezza' ),
	),
);
?>
<section id="flotta" class="section fleet">
	<div class="wrap">
		<div class="section-head reveal">
			<span class="kicker">La flotta</span>
			<h2>Mezzi giusti, controllati, sempre puliti</h2>
			<p class="lead">
				Ogni veicolo passa la revisione e una pulizia accurata prima di ogni
				partenza. Scegliamo con te la taglia migliore per il tuo gruppo.
			</p>
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
		<p class="fleet-note">* Allestimenti come pedana per disabili e WC dipendono dal mezzo: indicaci le tue esigenze e troviamo la soluzione adatta.</p>
	</div>
</section>

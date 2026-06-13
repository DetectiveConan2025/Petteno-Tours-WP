<?php
/**
 * Sezione Chi siamo: testo aziendale + elenco valori.
 *
 * @package PettenoTours
 */

$petteno_values = array(
	array(
		'title' => 'Sicurezza prima di tutto',
		'desc'  => 'Mezzi revisionati, autisti con CQC e rispetto dei tempi di guida e riposo. Non si tratta sulla sicurezza.',
	),
	array(
		'title' => 'Una persona che risponde',
		'desc'  => 'Niente call center. Parli con chi organizza davvero il tuo viaggio e ti segue fino al rientro.',
	),
);
?>
<section id="chi-siamo" class="section about">
	<div class="wrap about-grid">
		<div class="about-copy reveal">
			<span class="kicker">Chi siamo</span>
			<h2>Attivi in tutto il territorio veneziano, dalla sede di Salzano</h2>
			<p>
				Pettenò Tours è un'azienda attiva in tutto il territorio veneziano, con
				la sede principale a Salzano (VE). Da sempre siamo appassionati di
				viaggi e sempre alla ricerca di nuove opportunità per scoprire luoghi
				nuovi e interessanti.
			</p>
			<p>
				Siamo un'azienda dinamica e innovativa, sempre pronta ad affrontare nuove
				sfide e a offrire servizi di alta qualità. Siamo orgogliosi di essere un
				punto di riferimento per il trasporto di gruppi in tutto il Veneto e
				oltre, e ci impegniamo a soddisfare le esigenze di ogni singolo cliente
				per garantire che ogni viaggio sia indimenticabile.
			</p>
			<a href="#contatti" class="btn btn-primary">Parla con noi</a>
		</div>

		<ul class="values reveal">
			<?php foreach ( $petteno_values as $value ) : ?>
				<li>
					<span class="dot" aria-hidden="true"></span>
					<div>
						<h3><?php echo esc_html( $value['title'] ); ?></h3>
						<p><?php echo esc_html( $value['desc'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

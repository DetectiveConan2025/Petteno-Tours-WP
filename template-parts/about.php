<?php
/**
 * Sezione Chi siamo: testo aziendale + elenco valori.
 *
 * @package PettenoTours
 */

// Contenuti modificabili dal Customizer (Pettenò Tours → Chi siamo).
$petteno_values = array(
	array(
		'title' => petteno_opt( 'about_val_1_title' ),
		'desc'  => petteno_opt( 'about_val_1_desc' ),
	),
	array(
		'title' => petteno_opt( 'about_val_2_title' ),
		'desc'  => petteno_opt( 'about_val_2_desc' ),
	),
);
?>
<section id="chi-siamo" class="section about">
	<div class="wrap about-grid">
		<div class="about-copy reveal">
			<span class="kicker"><?php echo esc_html( petteno_opt( 'about_kicker' ) ); ?></span>
			<h2><?php echo esc_html( petteno_opt( 'about_title' ) ); ?></h2>
			<p><?php echo esc_html( petteno_opt( 'about_p1' ) ); ?></p>
			<p><?php echo esc_html( petteno_opt( 'about_p2' ) ); ?></p>
			<a href="#contatti" class="btn btn-primary"><?php echo esc_html( petteno_opt( 'about_cta' ) ); ?></a>
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

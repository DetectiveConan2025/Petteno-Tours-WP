<?php
/**
 * Sezione "Rotte scolastiche" — corse del trasporto scolastico.
 *
 * Attivabile/disattivabile e modificabile da Aspetto → Personalizza →
 * Pettenò Tours → Rotte scolastiche. Stesso linguaggio visivo delle altre
 * sezioni. Ogni riga dell'elenco è una rotta: "Nome | dettagli/orari".
 *
 * @package PettenoTours
 */

// Una rotta per riga; la parte dopo "|" (orari/dettagli) è facoltativa.
$petteno_lines  = preg_split( '/\r\n|\r|\n/', (string) petteno_opt( 'rotte_list' ) );
$petteno_routes = array();
foreach ( $petteno_lines as $line ) {
	$line = trim( $line );
	if ( '' === $line ) {
		continue;
	}
	$parts            = array_map( 'trim', explode( '|', $line ) );
	$name             = array_shift( $parts );
	$petteno_routes[] = array(
		'name' => $name,
		'meta' => implode( ' · ', array_filter( $parts ) ),
	);
}
?>
<section id="rotte" class="section routes">
	<div class="wrap">
		<div class="section-head reveal">
			<span class="kicker"><?php echo esc_html( petteno_opt( 'rotte_kicker' ) ); ?></span>
			<h2><?php echo esc_html( petteno_opt( 'rotte_title' ) ); ?></h2>
			<p class="lead"><?php echo esc_html( petteno_opt( 'rotte_lead' ) ); ?></p>
		</div>

		<?php if ( $petteno_routes ) : ?>
			<div class="routes-table-wrap reveal">
				<table class="routes-table">
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Linea', 'petteno-tours' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Orari', 'petteno-tours' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $petteno_routes as $route ) : ?>
							<tr>
								<th scope="row" class="route-name"><?php echo esc_html( $route['name'] ); ?></th>
								<td class="route-meta"><?php echo '' !== $route['meta'] ? esc_html( $route['meta'] ) : '—'; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<?php if ( petteno_opt( 'rotte_note' ) ) : ?>
			<p class="fleet-note"><?php echo esc_html( petteno_opt( 'rotte_note' ) ); ?></p>
		<?php endif; ?>
	</div>
</section>

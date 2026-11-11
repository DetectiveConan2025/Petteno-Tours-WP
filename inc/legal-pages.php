<?php
/**
 * Pagine "Privacy policy" e "Cookie policy".
 *
 * Alla prima attivazione del tema crea le due pagine con un testo di base
 * già adattato a Pettenò Tours (file in inc/legal/*.html, in blocchi
 * Gutenberg), assegna loro il template "Pagina legale" e imposta la Privacy
 * come pagina privacy ufficiale di WordPress. Da lì in poi si modificano
 * come qualsiasi pagina: Pagine → Privacy policy / Cookie policy.
 *
 * @package PettenoTours
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elenco delle pagine legali gestite dal tema.
 *
 * @return array chiave => [opzione, titolo, slug, file contenuto]
 */
function petteno_tours_legal_pages() {
	return array(
		'privacy' => array(
			'option' => 'petteno_tours_privacy_page_id',
			'title'  => __( 'Privacy policy', 'petteno-tours' ),
			'slug'   => 'privacy',
			'file'   => 'privacy.html',
		),
		'cookie'  => array(
			'option' => 'petteno_tours_cookie_page_id',
			'title'  => __( 'Cookie policy', 'petteno-tours' ),
			'slug'   => 'cookie',
			'file'   => 'cookie.html',
		),
	);
}

/**
 * URL di una pagina legale (fallback: home).
 *
 * @param string $key privacy|cookie.
 * @return string
 */
function petteno_tours_legal_url( $key ) {
	$pages = petteno_tours_legal_pages();
	if ( ! isset( $pages[ $key ] ) ) {
		return home_url( '/' );
	}
	$id = (int) get_option( $pages[ $key ]['option'] );
	if ( $id && 'publish' === get_post_status( $id ) ) {
		$url = get_permalink( $id );
		if ( $url ) {
			return $url;
		}
	}
	if ( 'privacy' === $key ) {
		$url = get_privacy_policy_url();
		if ( $url ) {
			return $url;
		}
	}
	return home_url( '/' );
}

/**
 * URL della Privacy policy.
 *
 * @return string
 */
function petteno_tours_privacy_url() {
	return petteno_tours_legal_url( 'privacy' );
}

/**
 * URL della Cookie policy.
 *
 * @return string
 */
function petteno_tours_cookie_url() {
	return petteno_tours_legal_url( 'cookie' );
}

/**
 * Crea le pagine legali (una sola volta). Stesso pattern delle altre
 * funzioni "seed_*": agganciata all'attivazione del tema e ad admin_init.
 */
function petteno_tours_seed_legal_pages() {
	if ( ! is_admin() || get_option( 'petteno_tours_legal_seeded' ) ) {
		return;
	}

	$created = array();
	foreach ( petteno_tours_legal_pages() as $key => $page ) {
		$id = (int) get_option( $page['option'] );
		if ( $id && get_post( $id ) ) {
			continue;
		}
		$file    = get_template_directory() . '/inc/legal/' . $page['file'];
		$content = file_exists( $file ) ? file_get_contents( $file ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		$post_id = wp_insert_post(
			array(
				'post_type'     => 'page',
				'post_status'   => 'publish',
				'post_title'    => $page['title'],
				'post_name'     => $page['slug'],
				'post_content'  => $content,
				'page_template' => 'template-legale.php',
			)
		);
		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}
		update_option( $page['option'], $post_id );
		$created[ $key ] = $post_id;
	}

	// I testi si citano a vicenda con link "/privacy/" e "/cookie/":
	// li sostituiamo con gli indirizzi reali (lo slug potrebbe essere diverso).
	foreach ( $created as $post_id ) {
		$content = get_post_field( 'post_content', $post_id );
		$content = str_replace(
			array( 'href="/privacy/"', 'href="/cookie/"' ),
			array( 'href="' . esc_url( petteno_tours_privacy_url() ) . '"', 'href="' . esc_url( petteno_tours_cookie_url() ) . '"' ),
			$content
		);
		wp_update_post(
			array(
				'ID'           => $post_id,
				'post_content' => $content,
			)
		);
	}

	// La nostra Privacy diventa la pagina privacy ufficiale di WordPress.
	$privacy_id = (int) get_option( 'petteno_tours_privacy_page_id' );
	if ( $privacy_id ) {
		update_option( 'wp_page_for_privacy_policy', $privacy_id );
	}

	update_option( 'petteno_tours_legal_seeded', 1 );
}
add_action( 'after_switch_theme', 'petteno_tours_seed_legal_pages' );
add_action( 'admin_init', 'petteno_tours_seed_legal_pages' );

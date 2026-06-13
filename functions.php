<?php
/**
 * Pettenò Tours — funzioni del tema.
 *
 * Replica in WordPress del sito vetrina Pettenò Tours (noleggio bus turistici
 * con conducente). Landing page singola, palette azzurro/bianco.
 *
 * @package PettenoTours
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Accesso diretto non consentito.
}

if ( ! defined( 'PETTENO_TOURS_VERSION' ) ) {
	define( 'PETTENO_TOURS_VERSION', '1.0.0' );
}

/**
 * Setup di base del tema.
 */
function petteno_tours_setup() {
	// Tag <title> gestito da WordPress.
	add_theme_support( 'title-tag' );

	// Immagini in evidenza.
	add_theme_support( 'post-thumbnails' );

	// Markup HTML5 pulito.
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	// Logo personalizzato (sostituisce il PNG di default se impostato).
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Menù di navigazione (facoltativo: di default usa i link alle ancore).
	register_nav_menus(
		array(
			'primary' => __( 'Menù principale', 'petteno-tours' ),
		)
	);

	// Traduzioni.
	load_theme_textdomain( 'petteno-tours', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'petteno_tours_setup' );

/**
 * Caricamento di stili e script.
 */
function petteno_tours_assets() {
	// Font Google: Bricolage Grotesque (display) + Albert Sans (body).
	wp_enqueue_style(
		'petteno-tours-fonts',
		'https://fonts.googleapis.com/css2?family=Albert+Sans:wght@400;500;600;700&family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&display=swap',
		array(),
		null
	);

	// Foglio di stile principale del tema (design token + tutte le sezioni).
	wp_enqueue_style(
		'petteno-tours-style',
		get_stylesheet_uri(),
		array( 'petteno-tours-fonts' ),
		PETTENO_TOURS_VERSION
	);

	// Script di interfaccia (menù mobile, reveal on scroll, form, anno footer).
	wp_enqueue_script(
		'petteno-tours-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		PETTENO_TOURS_VERSION,
		true
	);

	// Endpoint e nonce per l'invio del form preventivo via admin-ajax.
	wp_localize_script(
		'petteno-tours-main',
		'pettenoTours',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'petteno_tours_preventivo' ),
			'action'  => 'petteno_tours_preventivo',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'petteno_tours_assets' );

/**
 * Preconnect e preload per i font + preload immagine hero (LCP).
 */
function petteno_tours_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'petteno_tours_resource_hints', 10, 2 );

/**
 * Meta SEO/social, favicon, theme-color, JSON-LD e preload hero nel <head>.
 */
function petteno_tours_head_meta() {
	$description = __( 'Pettenò Tours: noleggio autobus, minibus e van con autisti esperti. Viaggi di gruppo, gite scolastiche, transfer aeroportuali e tour in Italia e in Europa.', 'petteno-tours' );
	$title       = wp_get_document_title();
	$canonical   = home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );
	$theme_uri   = get_template_directory_uri();
	$og_image    = $theme_uri . '/assets/images/hero-1.jpg';

	echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
	echo '<meta name="theme-color" content="#1f63c8" />' . "\n";

	// Open Graph.
	echo '<meta property="og:type" content="website" />' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $canonical ) . '" />' . "\n";
	echo '<meta property="og:locale" content="it_IT" />' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $og_image ) . '" />' . "\n";

	// Preload immagine hero principale (LCP).
	echo '<link rel="preload" as="image" href="' . esc_url( $theme_uri . '/assets/images/hero-1.jpg' ) . '" fetchpriority="high" />' . "\n";

	// Favicon e apple-touch-icon.
	echo '<link rel="icon" href="' . esc_url( $theme_uri . '/assets/images/favicon.png' ) . '" type="image/png" />' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $theme_uri . '/assets/images/apple-touch-icon.png' ) . '" />' . "\n";

	// Dati strutturati: TravelAgency.
	$jsonld = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'TravelAgency',
		'name'        => 'Pettenò Tours S.a.s. di Pettenò Luca & C.',
		'description' => $description,
		'url'         => 'https://www.pettenotours.it',
		'telephone'   => '+39 348 928 0768',
		'email'       => 'pettenotours@gmail.com',
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => 'Via Leonardo da Vinci 39/B',
			'postalCode'      => '30030',
			'addressLocality' => 'Salzano',
			'addressRegion'   => 'VE',
			'addressCountry'  => 'IT',
		),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $jsonld ) . '</script>' . "\n";
}
add_action( 'wp_head', 'petteno_tours_head_meta', 5 );

/**
 * Helper: URL di un'immagine del tema in assets/images/.
 *
 * @param string $file Nome del file.
 * @return string URL completo.
 */
function petteno_tours_img( $file ) {
	return get_template_directory_uri() . '/assets/images/' . ltrim( $file, '/' );
}

/**
 * Gestione invio form preventivo (admin-ajax), sostituisce mail.php.
 *
 * Risponde JSON { ok: bool } come si aspetta lo script lato client.
 */
function petteno_tours_handle_preventivo() {
	// Verifica nonce (sicurezza CSRF). Lo script lo invia nel campo _nonce.
	if ( ! isset( $_POST['_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_nonce'] ) ), 'petteno_tours_preventivo' ) ) {
		wp_send_json( array( 'ok' => false, 'msg' => 'Sessione scaduta, ricarica la pagina.' ), 403 );
	}

	$nome     = isset( $_POST['nome'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['nome'] ) ) ) : '';
	$email    = isset( $_POST['email'] ) ? trim( sanitize_email( wp_unslash( $_POST['email'] ) ) ) : '';
	$telefono = isset( $_POST['telefono'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['telefono'] ) ) ) : '';
	$persone  = isset( $_POST['persone'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['persone'] ) ) ) : '';
	$data     = isset( $_POST['data'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['data'] ) ) ) : '';
	$testo    = isset( $_POST['messaggio'] ) ? trim( sanitize_textarea_field( wp_unslash( $_POST['messaggio'] ) ) ) : '';

	// Validazione minima (come mail.php originale).
	if ( ! $nome || ! is_email( $email ) || ! $telefono ) {
		wp_send_json( array( 'ok' => false, 'msg' => 'Campi obbligatori mancanti.' ), 400 );
	}

	// Destinatario: filtrabile, di default l'admin del sito.
	$to = apply_filters( 'petteno_tours_recipient', get_option( 'admin_email', 'pettenotours@gmail.com' ) );

	$subject = 'Richiesta preventivo dal sito';
	$body    = implode(
		"\n",
		array(
			'Nome:     ' . $nome,
			'Email:    ' . $email,
			'Telefono: ' . $telefono,
			'Persone:  ' . ( $persone ? $persone : '-' ),
			'Data:     ' . ( $data ? $data : '-' ),
			'',
			$testo,
		)
	);

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $nome . ' <' . $email . '>',
	);

	$ok = wp_mail( $to, $subject, $body, $headers );

	wp_send_json( array( 'ok' => (bool) $ok ) );
}
add_action( 'wp_ajax_petteno_tours_preventivo', 'petteno_tours_handle_preventivo' );
add_action( 'wp_ajax_nopriv_petteno_tours_preventivo', 'petteno_tours_handle_preventivo' );

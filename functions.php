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
			'email'   => petteno_opt( 'email' ),
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

	// Dati strutturati: TravelAgency (alimentati dalle opzioni del Customizer).
	$jsonld = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'TravelAgency',
		'name'        => petteno_opt( 'ragione_sociale' ),
		'description' => $description,
		'url'         => home_url( '/' ),
		'telephone'   => petteno_opt( 'tel_mobile' ),
		'email'       => petteno_opt( 'email' ),
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => petteno_opt( 'via' ),
			'postalCode'      => petteno_opt( 'cap' ),
			'addressLocality' => petteno_opt( 'citta' ),
			'addressRegion'   => petteno_opt( 'provincia' ),
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
 * Valori predefiniti di tutti i contenuti modificabili dal Customizer.
 *
 * Unica fonte di verità: usati sia come default dei controlli del Customizer,
 * sia come fallback nei template. Se l'utente non personalizza nulla, il sito
 * mostra esattamente i contenuti originali.
 *
 * @return array
 */
function petteno_tours_defaults() {
	return array(
		// --- Azienda e contatti (riusati in Contatti, Footer, JSON-LD) ---
		'ragione_sociale'  => 'Pettenò Tours S.a.s. di Pettenò Luca & C.',
		'piva'             => '02172370278',
		'tel_mobile'       => '+39 348 928 0768',
		'tel_fisso'        => '041 482231',
		'email'            => 'pettenotours@gmail.com',
		'via'              => 'Via Leonardo da Vinci 39/B',
		'cap'              => '30030',
		'citta'            => 'Salzano',
		'provincia'        => 'VE',
		'maps_url'         => 'https://maps.google.com/?q=Via+Leonardo+da+Vinci+39B+30030+Salzano+VE',

		// --- Hero ---
		'hero_badge'       => 'Noleggio pullman con conducente · Veneto',
		'hero_title_l1'    => 'Il tuo viaggio',
		'hero_title_pre'   => 'in',
		'hero_accent'      => 'buone mani',
		'hero_title_post'  => '.',
		'hero_lead'        => 'Da Robegano di Salzano, Pettenò Tours porta gruppi, scuole e aziende dove devono andare — in Italia e in tutta Europa, con pullman Gran Turismo, Scuolabus e autisti esperti.',
		'hero_cta1'        => 'Richiedi un preventivo',
		'hero_cta2'        => 'Scopri la flotta',
		'hero_img_1'       => petteno_tours_img( 'hero-1.jpg' ),
		'hero_img_2'       => petteno_tours_img( 'hero-2.jpg' ),

		// --- Servizi (intestazione + 6 schede) ---
		'serv_kicker'      => 'Cosa facciamo',
		'serv_title'       => 'Un mezzo e un autista per ogni occasione',
		'serv_lead'        => 'Dalla gita di un giorno al tour di una settimana, gestiamo noi mezzo, conducente e tempi. Tu pensi alle persone da portare.',
		'serv_1_title'     => 'Gite ed escursioni turistiche',
		'serv_1_desc'      => "Siamo specializzati nell'organizzazione di tour di gruppo e viaggi scolastici, ma offriamo anche soluzioni di trasporto personalizzate per soddisfare qualsiasi esigenza. I nostri autisti conoscono bene le rotte europee: viaggiate in sicurezza e comodità.",
		'serv_2_title'     => 'Trasporti scolastici',
		'serv_2_desc'      => 'Mettiamo a disposizione autobus, minibus e autisti esperti per garantire un servizio scolastico puntuale e sicuro, con la tranquillità dei genitori e il comfort degli studenti.',
		'serv_3_title'     => 'Transfer aeroporti e stazioni',
		'serv_3_desc'      => 'Collegamenti puntuali da e per Venezia, Verona, Bergamo, Bologna e le principali stazioni.',
		'serv_4_title'     => 'Eventi e cerimonie',
		'serv_4_desc'      => 'Matrimoni, congressi, concerti: navette dedicate perché nessuno pensi al parcheggio.',
		'serv_5_title'     => 'Tour in Italia e in Europa',
		'serv_5_desc'      => 'Itinerari di più giorni con un unico interlocutore: mezzo, autista e logistica coordinati.',
		'serv_6_title'     => 'Trasferte aziendali',
		'serv_6_desc'      => 'Shuttle per dipendenti, fiere e team building, con fatturazione e referente unico.',

		// --- Flotta (intestazione + 2 mezzi + nota) ---
		'fleet_kicker'     => 'La flotta',
		'fleet_title'      => 'Mezzi giusti, controllati, sempre puliti',
		'fleet_lead'       => 'Ogni veicolo passa la revisione e una pulizia accurata prima di ogni partenza. Scegliamo con te la taglia migliore per il tuo gruppo.',
		'fleet_note'       => '* Allestimenti come pedana per disabili e WC dipendono dal mezzo: indicaci le tue esigenze e troviamo la soluzione adatta.',
		'fleet_1_name'     => 'Gran Turismo',
		'fleet_1_seats'    => '48 – 54 posti',
		'fleet_1_desc'     => 'Il pullman per i grandi gruppi e i lunghi tragitti. Poltrone reclinabili, ampia bagagliera e tutti i comfort per viaggiare riposati.',
		'fleet_1_specs'    => 'Climatizzato, WC a bordo, Pedana disabili*',
		'fleet_1_img'      => petteno_tours_img( 'hero-1.jpg' ),
		'fleet_2_name'     => 'Scuolabus',
		'fleet_2_seats'    => '16 – 30 posti',
		'fleet_2_desc'     => 'Dedicato al trasporto scolastico, con tutte le omologazioni di legge. Puntuale, sicuro e confortevole per gli studenti.',
		'fleet_2_specs'    => 'Omologato scuolabus, Climatizzato, Cinture di sicurezza',
		'fleet_2_img'      => petteno_tours_img( 'hero-3.jpg' ),

		// --- Chi siamo (testo + 2 valori) ---
		'about_kicker'     => 'Chi siamo',
		'about_title'      => 'Attivi in tutto il territorio veneziano, dalla sede di Salzano',
		'about_p1'         => "Pettenò Tours è un'azienda attiva in tutto il territorio veneziano, con la sede principale a Salzano (VE). Da sempre siamo appassionati di viaggi e sempre alla ricerca di nuove opportunità per scoprire luoghi nuovi e interessanti.",
		'about_p2'         => "Siamo un'azienda dinamica e innovativa, sempre pronta ad affrontare nuove sfide e a offrire servizi di alta qualità. Siamo orgogliosi di essere un punto di riferimento per il trasporto di gruppi in tutto il Veneto e oltre, e ci impegniamo a soddisfare le esigenze di ogni singolo cliente per garantire che ogni viaggio sia indimenticabile.",
		'about_cta'        => 'Parla con noi',
		'about_val_1_title' => 'Sicurezza prima di tutto',
		'about_val_1_desc'  => 'Mezzi revisionati, autisti con CQC e rispetto dei tempi di guida e riposo. Non si tratta sulla sicurezza.',
		'about_val_2_title' => 'Una persona che risponde',
		'about_val_2_desc'  => 'Niente call center. Parli con chi organizza davvero il tuo viaggio e ti segue fino al rientro.',

		// --- Contatti (intestazione sezione) ---
		'contact_kicker'   => 'Preventivo gratuito',
		'contact_title'    => 'Raccontaci il viaggio, ti rispondiamo in giornata',
		'contact_intro'    => 'Quante persone, da dove a dove, in che date. Bastano due righe e ti prepariamo un preventivo chiaro e senza impegno.',

		// --- Interruttori delle sezioni (mostra/nascondi nello scroll) ---
		'show_servizi'     => true,
		'show_flotta'      => true,
		'show_rotte'       => true,
		'show_chi_siamo'   => true,

		// --- Rotte scolastiche (sezione attivabile/disattivabile) ---
		'rotte_kicker'     => 'Servizio scolastico',
		'rotte_title'      => 'Le rotte del trasporto scolastico',
		'rotte_lead'       => 'Le corse attive per il servizio di trasporto scolastico: linee, fermate e orari. Utile anche per la partecipazione ai bandi.',
		// Una rotta per riga. Formato: "Nome linea | dettagli/orari" (la parte
		// dopo la barra "|" è facoltativa). Modificabile dal Customizer.
		'rotte_list'       => "Linea 1 — Salzano · Robegano · Mirano | Andata 07:10 · Ritorno 13:30\nLinea 2 — Salzano · Cappella · Noale | Andata 07:00 · Ritorno 13:45\nLinea 3 — Salzano · Rivale · Spinea | Andata 07:20 · Ritorno 14:00",
		'rotte_note'       => 'Orari indicativi: le corse possono variare in base al calendario scolastico e alle esigenze dell’istituto.',
	);
}

/**
 * Legge un'opzione del tema con fallback al default centralizzato.
 *
 * @param string $key Chiave dell'opzione.
 * @return string
 */
function petteno_opt( $key ) {
	$defaults = petteno_tours_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return get_theme_mod( $key, $default );
}

/**
 * Costruisce un href "tel:" a partire dal numero visualizzato.
 *
 * @param string $num Numero come mostrato (es. "+39 348 928 0768").
 * @return string
 */
function petteno_tel_href( $num ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $num );
}

/**
 * Indirizzo completo: "Via …, CAP Città (PR)".
 *
 * @return string
 */
function petteno_address_full() {
	return sprintf( '%s, %s %s (%s)', petteno_opt( 'via' ), petteno_opt( 'cap' ), petteno_opt( 'citta' ), petteno_opt( 'provincia' ) );
}

/**
 * Indirizzo breve: "Via …, Città (PR)".
 *
 * @return string
 */
function petteno_address_short() {
	return sprintf( '%s, %s (%s)', petteno_opt( 'via' ), petteno_opt( 'citta' ), petteno_opt( 'provincia' ) );
}

/**
 * Una sezione è visibile? (interruttori show_* del Customizer).
 *
 * @param string $key Chiave dell'interruttore (es. "show_rotte").
 * @return bool
 */
function petteno_show( $key ) {
	$defaults = petteno_tours_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : true;
	return (bool) get_theme_mod( $key, $default );
}

/**
 * Voci di navigazione attive, in base alle sezioni visibili.
 *
 * @return array Mappa ancora => etichetta.
 */
function petteno_tours_nav_links() {
	$links = array();
	if ( petteno_show( 'show_servizi' ) ) {
		$links['#servizi'] = __( 'Servizi', 'petteno-tours' );
	}
	if ( petteno_show( 'show_flotta' ) ) {
		$links['#flotta'] = __( 'Flotta', 'petteno-tours' );
	}
	if ( petteno_show( 'show_rotte' ) ) {
		$links['#rotte'] = __( 'Rotte scolastiche', 'petteno-tours' );
	}
	if ( petteno_show( 'show_chi_siamo' ) ) {
		$links['#chi-siamo'] = __( 'Chi siamo', 'petteno-tours' );
	}
	$links['#contatti'] = __( 'Contatti', 'petteno-tours' );
	return $links;
}

// Opzioni del Customizer (Aspetto → Personalizza → Pettenò Tours).
require get_template_directory() . '/inc/customizer.php';

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

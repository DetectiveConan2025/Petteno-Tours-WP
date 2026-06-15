<?php
/**
 * Opzioni del tema nel Customizer di WordPress.
 *
 * Aggiunge un pannello "Pettenò Tours" da cui modificare — senza toccare il
 * codice — i contenuti di tutte le sezioni: recapiti, Hero, Servizi, Flotta,
 * Chi siamo e l'intestazione dei Contatti. Il layout e il design restano fissi.
 *
 * @package PettenoTours
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helper: aggiunge un'impostazione testuale + relativo controllo.
 *
 * @param WP_Customize_Manager $wp      Manager del Customizer.
 * @param string               $section ID della sezione.
 * @param string               $id      ID dell'impostazione (chiave dei default).
 * @param string               $label   Etichetta mostrata all'utente.
 * @param string               $type    Tipo di controllo: text|textarea|url|email.
 */
function petteno_tours_add_text( $wp, $section, $id, $label, $type = 'text' ) {
	$defaults = petteno_tours_defaults();

	switch ( $type ) {
		case 'textarea':
			$sanitize = 'sanitize_textarea_field';
			break;
		case 'url':
			$sanitize = 'esc_url_raw';
			break;
		case 'email':
			$sanitize = 'sanitize_email';
			break;
		default:
			$sanitize = 'sanitize_text_field';
	}

	$wp->add_setting(
		$id,
		array(
			'default'           => isset( $defaults[ $id ] ) ? $defaults[ $id ] : '',
			'sanitize_callback' => $sanitize,
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		$id,
		array(
			'label'   => $label,
			'section' => $section,
			'type'    => $type,
		)
	);
}

/**
 * Helper: aggiunge un controllo immagine.
 *
 * @param WP_Customize_Manager $wp      Manager del Customizer.
 * @param string               $section ID della sezione.
 * @param string               $id      ID dell'impostazione.
 * @param string               $label   Etichetta.
 */
function petteno_tours_add_image( $wp, $section, $id, $label ) {
	$defaults = petteno_tours_defaults();
	$wp->add_setting(
		$id,
		array(
			'default'           => isset( $defaults[ $id ] ) ? $defaults[ $id ] : '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		new WP_Customize_Image_Control(
			$wp,
			$id,
			array(
				'label'   => $label,
				'section' => $section,
			)
		)
	);
}

/**
 * Helper: aggiunge un interruttore (checkbox) per mostrare/nascondere.
 *
 * @param WP_Customize_Manager $wp      Manager del Customizer.
 * @param string               $section ID della sezione.
 * @param string               $id      ID dell'impostazione.
 * @param string               $label   Etichetta.
 */
function petteno_tours_add_checkbox( $wp, $section, $id, $label ) {
	$defaults = petteno_tours_defaults();
	$wp->add_setting(
		$id,
		array(
			'default'           => isset( $defaults[ $id ] ) ? $defaults[ $id ] : true,
			'sanitize_callback' => 'petteno_tours_sanitize_checkbox',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		$id,
		array(
			'label'   => $label,
			'section' => $section,
			'type'    => 'checkbox',
		)
	);
}

/**
 * Sanitizza un valore checkbox in booleano.
 *
 * @param mixed $value Valore grezzo.
 * @return bool
 */
function petteno_tours_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Sanitizza una lista di ID allegati separati da virgola.
 *
 * @param string $value Valore grezzo.
 * @return string
 */
function petteno_tours_sanitize_id_list( $value ) {
	$ids = array_filter( array_map( 'absint', explode( ',', (string) $value ) ) );
	return implode( ',', $ids );
}

/**
 * Carica gli script del controllo "lista immagini" nel Customizer.
 */
function petteno_tours_customize_control_assets() {
	wp_enqueue_media();
	wp_enqueue_style( 'petteno-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), PETTENO_TOURS_VERSION );
	wp_enqueue_script(
		'petteno-customizer-control',
		get_template_directory_uri() . '/assets/js/customizer-control.js',
		array( 'jquery', 'customize-controls' ),
		PETTENO_TOURS_VERSION,
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'petteno_tours_customize_control_assets' );

/**
 * Registra pannello, sezioni e controlli.
 *
 * @param WP_Customize_Manager $wp Manager del Customizer.
 */
function petteno_tours_customize_register( $wp ) {
	require_once get_template_directory() . '/inc/class-petteno-image-list-control.php';

	$wp->add_panel(
		'petteno_tours',
		array(
			'title'       => __( 'Pettenò Tours', 'petteno-tours' ),
			'description' => __( 'Modifica testi, foto e recapiti delle sezioni della home. Il design resta invariato.', 'petteno-tours' ),
			'priority'    => 20,
		)
	);

	/* ---- Azienda e contatti ------------------------------------------- */
	$wp->add_section( 'petteno_contatti', array( 'title' => __( 'Azienda e contatti', 'petteno-tours' ), 'panel' => 'petteno_tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti', 'ragione_sociale', __( 'Ragione sociale', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti', 'piva', __( 'Partita IVA', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti', 'tel_mobile', __( 'Telefono (cellulare)', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti', 'tel_fisso', __( 'Telefono fisso', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti', 'email', __( 'Email', 'petteno-tours' ), 'email' );
	petteno_tours_add_text( $wp, 'petteno_contatti', 'via', __( 'Via e civico', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti', 'cap', __( 'CAP', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti', 'citta', __( 'Città', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti', 'provincia', __( 'Provincia (sigla)', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti', 'maps_url', __( 'Link Google Maps', 'petteno-tours' ), 'url' );

	/* ---- Hero ---------------------------------------------------------- */
	$wp->add_section( 'petteno_hero', array( 'title' => __( 'Hero (sezione iniziale)', 'petteno-tours' ), 'panel' => 'petteno_tours' ) );
	petteno_tours_add_text( $wp, 'petteno_hero', 'hero_badge', __( 'Badge superiore', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_hero', 'hero_title_l1', __( 'Titolo — prima riga', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_hero', 'hero_title_pre', __( 'Titolo — parola prima dell’accento', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_hero', 'hero_accent', __( 'Titolo — parola evidenziata', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_hero', 'hero_title_post', __( 'Titolo — testo dopo l’accento', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_hero', 'hero_lead', __( 'Testo descrittivo', 'petteno-tours' ), 'textarea' );
	petteno_tours_add_text( $wp, 'petteno_hero', 'hero_cta1', __( 'Bottone principale (testo)', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_hero', 'hero_cta2', __( 'Bottone secondario (testo)', 'petteno-tours' ) );

	// Slideshow a immagini illimitate (lista di ID allegati).
	$wp->add_setting(
		'hero_images',
		array(
			'default'           => '',
			'sanitize_callback' => 'petteno_tours_sanitize_id_list',
			'transport'         => 'refresh',
		)
	);
	$wp->add_control(
		new Petteno_Image_List_Control(
			$wp,
			'hero_images',
			array(
				'label'       => __( 'Immagini dello slideshow', 'petteno-tours' ),
				'description' => __( 'Aggiungi quante foto vuoi: scorrono in dissolvenza. Se lasci vuoto, vengono usate le due foto predefinite.', 'petteno-tours' ),
				'section'     => 'petteno_hero',
			)
		)
	);

	/* ---- Servizi ------------------------------------------------------- */
	$wp->add_section( 'petteno_servizi', array( 'title' => __( 'Servizi', 'petteno-tours' ), 'panel' => 'petteno_tours' ) );
	petteno_tours_add_checkbox( $wp, 'petteno_servizi', 'show_servizi', __( 'Mostra questa sezione nel sito', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_servizi', 'serv_kicker', __( 'Occhiello', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_servizi', 'serv_title', __( 'Titolo sezione', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_servizi', 'serv_lead', __( 'Testo introduttivo', 'petteno-tours' ), 'textarea' );
	for ( $i = 1; $i <= 6; $i++ ) {
		/* translators: %d: numero del servizio. */
		petteno_tours_add_text( $wp, 'petteno_servizi', "serv_{$i}_title", sprintf( __( 'Servizio %d — titolo', 'petteno-tours' ), $i ) );
		/* translators: %d: numero del servizio. */
		petteno_tours_add_text( $wp, 'petteno_servizi', "serv_{$i}_desc", sprintf( __( 'Servizio %d — descrizione', 'petteno-tours' ), $i ), 'textarea' );
	}

	/* ---- Flotta -------------------------------------------------------- */
	$wp->add_section(
		'petteno_flotta',
		array(
			'title'       => __( 'Flotta', 'petteno-tours' ),
			'panel'       => 'petteno_tours',
			'description' => __( 'Intestazione della sezione. I veicoli ora si gestiscono dal menu “Mezzi” (aggiungi/riordina foto e dettagli). I due campi mezzo qui sotto si usano solo finché non hai creato nessun mezzo.', 'petteno-tours' ),
		)
	);
	petteno_tours_add_checkbox( $wp, 'petteno_flotta', 'show_flotta', __( 'Mostra questa sezione nel sito', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_flotta', 'fleet_kicker', __( 'Occhiello', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_flotta', 'fleet_title', __( 'Titolo sezione', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_flotta', 'fleet_lead', __( 'Testo introduttivo', 'petteno-tours' ), 'textarea' );
	for ( $i = 1; $i <= 2; $i++ ) {
		/* translators: %d: numero del mezzo. */
		petteno_tours_add_text( $wp, 'petteno_flotta', "fleet_{$i}_name", sprintf( __( 'Mezzo %d — nome', 'petteno-tours' ), $i ) );
		/* translators: %d: numero del mezzo. */
		petteno_tours_add_text( $wp, 'petteno_flotta', "fleet_{$i}_seats", sprintf( __( 'Mezzo %d — posti', 'petteno-tours' ), $i ) );
		/* translators: %d: numero del mezzo. */
		petteno_tours_add_text( $wp, 'petteno_flotta', "fleet_{$i}_desc", sprintf( __( 'Mezzo %d — descrizione', 'petteno-tours' ), $i ), 'textarea' );
		/* translators: %d: numero del mezzo. */
		petteno_tours_add_text( $wp, 'petteno_flotta', "fleet_{$i}_specs", sprintf( __( 'Mezzo %d — dotazioni (separate da virgola)', 'petteno-tours' ), $i ) );
		/* translators: %d: numero del mezzo. */
		petteno_tours_add_image( $wp, 'petteno_flotta', "fleet_{$i}_img", sprintf( __( 'Mezzo %d — foto', 'petteno-tours' ), $i ) );
	}
	petteno_tours_add_text( $wp, 'petteno_flotta', 'fleet_note', __( 'Nota a piè di sezione', 'petteno-tours' ), 'textarea' );

	/* ---- Rotte scolastiche (sezione attivabile/disattivabile) ---------- */
	$wp->add_section( 'petteno_rotte', array( 'title' => __( 'Rotte scolastiche', 'petteno-tours' ), 'panel' => 'petteno_tours', 'description' => __( 'Sezione dedicata alle corse del trasporto scolastico (utile per i bandi). Disattiva l’interruttore per nasconderla dallo scroll.', 'petteno-tours' ) ) );
	petteno_tours_add_checkbox( $wp, 'petteno_rotte', 'show_rotte', __( 'Mostra questa sezione nel sito', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_rotte', 'rotte_kicker', __( 'Occhiello', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_rotte', 'rotte_title', __( 'Titolo sezione', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_rotte', 'rotte_lead', __( 'Testo introduttivo', 'petteno-tours' ), 'textarea' );
	petteno_tours_add_text( $wp, 'petteno_rotte', 'rotte_list', __( 'Elenco rotte — una per riga. Formato: "Nome linea | orari/dettagli" (la parte dopo "|" è facoltativa)', 'petteno-tours' ), 'textarea' );
	petteno_tours_add_text( $wp, 'petteno_rotte', 'rotte_note', __( 'Nota a piè di sezione', 'petteno-tours' ), 'textarea' );

	/* ---- Chi siamo ----------------------------------------------------- */
	$wp->add_section( 'petteno_about', array( 'title' => __( 'Chi siamo', 'petteno-tours' ), 'panel' => 'petteno_tours' ) );
	petteno_tours_add_checkbox( $wp, 'petteno_about', 'show_chi_siamo', __( 'Mostra questa sezione nel sito', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_about', 'about_kicker', __( 'Occhiello', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_about', 'about_title', __( 'Titolo', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_about', 'about_p1', __( 'Paragrafo 1', 'petteno-tours' ), 'textarea' );
	petteno_tours_add_text( $wp, 'petteno_about', 'about_p2', __( 'Paragrafo 2', 'petteno-tours' ), 'textarea' );
	petteno_tours_add_text( $wp, 'petteno_about', 'about_cta', __( 'Bottone (testo)', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_about', 'about_val_1_title', __( 'Valore 1 — titolo', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_about', 'about_val_1_desc', __( 'Valore 1 — descrizione', 'petteno-tours' ), 'textarea' );
	petteno_tours_add_text( $wp, 'petteno_about', 'about_val_2_title', __( 'Valore 2 — titolo', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_about', 'about_val_2_desc', __( 'Valore 2 — descrizione', 'petteno-tours' ), 'textarea' );

	/* ---- Contatti (intestazione) -------------------------------------- */
	$wp->add_section( 'petteno_contatti_head', array( 'title' => __( 'Contatti — intestazione', 'petteno-tours' ), 'panel' => 'petteno_tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti_head', 'contact_kicker', __( 'Occhiello', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti_head', 'contact_title', __( 'Titolo', 'petteno-tours' ) );
	petteno_tours_add_text( $wp, 'petteno_contatti_head', 'contact_intro', __( 'Testo introduttivo', 'petteno-tours' ), 'textarea' );
}
add_action( 'customize_register', 'petteno_tours_customize_register' );

<?php
/**
 * Recensioni dei clienti.
 *
 * Menu "Recensioni" in bacheca: ogni recensione è una scheda con nome
 * (titolo), testo, stelle, data e provenienza. Vanno copiate fedelmente
 * dal profilo Google (solo recensioni vere: Codice del Consumo / direttiva
 * Omnibus). In home compare la sezione "Dicono di noi" solo se ce n'è almeno
 * una pubblicata. Nessun widget o script esterno: zero cookie.
 *
 * @package PettenoTours
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra il post type "petteno_recensione".
 */
function petteno_tours_register_reviews() {
	register_post_type(
		'petteno_recensione',
		array(
			'labels'        => array(
				'name'          => __( 'Recensioni', 'petteno-tours' ),
				'singular_name' => __( 'Recensione', 'petteno-tours' ),
				'menu_name'     => __( 'Recensioni', 'petteno-tours' ),
				'add_new'       => __( 'Aggiungi recensione', 'petteno-tours' ),
				'add_new_item'  => __( 'Aggiungi recensione', 'petteno-tours' ),
				'edit_item'     => __( 'Modifica recensione', 'petteno-tours' ),
				'all_items'     => __( 'Tutte le recensioni', 'petteno-tours' ),
				'not_found'     => __( 'Nessuna recensione', 'petteno-tours' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'show_in_rest'  => false,
			'menu_icon'     => 'dashicons-star-filled',
			'menu_position' => 22,
			'supports'      => array( 'title', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'petteno_tours_register_reviews' );

/**
 * Etichetta del campo titolo: qui va il nome di chi ha scritto la recensione.
 *
 * @param string  $text Testo segnaposto.
 * @param WP_Post $post Post.
 * @return string
 */
function petteno_tours_review_title_placeholder( $text, $post ) {
	if ( 'petteno_recensione' === $post->post_type ) {
		return __( 'Nome e iniziale del cognome (es. Marco R.)', 'petteno-tours' );
	}
	return $text;
}
add_filter( 'enter_title_here', 'petteno_tours_review_title_placeholder', 10, 2 );

/**
 * Riquadro dei dettagli della recensione.
 */
function petteno_tours_review_metabox() {
	add_meta_box( 'petteno_recensione_dettagli', __( 'Recensione', 'petteno-tours' ), 'petteno_tours_review_metabox_render', 'petteno_recensione', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'petteno_tours_review_metabox' );

/**
 * Campi della recensione.
 *
 * @param WP_Post $post Post corrente.
 */
function petteno_tours_review_metabox_render( $post ) {
	wp_nonce_field( 'petteno_recensione_save', 'petteno_recensione_nonce' );
	$testo  = get_post_meta( $post->ID, '_petteno_rev_testo', true );
	$stelle = (int) get_post_meta( $post->ID, '_petteno_rev_stelle', true );
	$data   = get_post_meta( $post->ID, '_petteno_rev_data', true );
	$fonte  = get_post_meta( $post->ID, '_petteno_rev_fonte', true );
	if ( ! $stelle ) {
		$stelle = 5;
	}
	if ( ! $fonte ) {
		$fonte = 'Google';
	}
	?>
	<p>
		<label for="petteno_rev_testo"><strong><?php esc_html_e( 'Testo della recensione (copiato fedelmente, senza modifiche)', 'petteno-tours' ); ?></strong></label><br />
		<textarea id="petteno_rev_testo" name="petteno_rev_testo" rows="5" style="width:100%"><?php echo esc_textarea( $testo ); ?></textarea>
	</p>
	<p>
		<label for="petteno_rev_stelle"><strong><?php esc_html_e( 'Stelle', 'petteno-tours' ); ?></strong></label>
		<select id="petteno_rev_stelle" name="petteno_rev_stelle">
			<?php for ( $i = 5; $i >= 1; $i-- ) : ?>
				<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $stelle, $i ); ?>><?php echo esc_html( str_repeat( '★', $i ) ); ?></option>
			<?php endfor; ?>
		</select>
		&nbsp;
		<label for="petteno_rev_data"><strong><?php esc_html_e( 'Data', 'petteno-tours' ); ?></strong></label>
		<input type="text" id="petteno_rev_data" name="petteno_rev_data" value="<?php echo esc_attr( $data ); ?>" placeholder="<?php esc_attr_e( 'es. Marzo 2026', 'petteno-tours' ); ?>" />
		&nbsp;
		<label for="petteno_rev_fonte"><strong><?php esc_html_e( 'Provenienza', 'petteno-tours' ); ?></strong></label>
		<input type="text" id="petteno_rev_fonte" name="petteno_rev_fonte" value="<?php echo esc_attr( $fonte ); ?>" />
	</p>
	<p class="description"><?php esc_html_e( 'Per legge le recensioni devono essere vere e non modificate. Nome: solo nome e iniziale del cognome. In home ne compaiono al massimo 3: l\'ordine si cambia con il campo "Ordine" (Attributi).', 'petteno-tours' ); ?></p>
	<?php
}

/**
 * Salva i campi della recensione.
 *
 * @param int $post_id ID del post.
 */
function petteno_tours_review_save( $post_id ) {
	if ( ! isset( $_POST['petteno_recensione_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['petteno_recensione_nonce'] ) ), 'petteno_recensione_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$stelle = isset( $_POST['petteno_rev_stelle'] ) ? absint( $_POST['petteno_rev_stelle'] ) : 5;
	update_post_meta( $post_id, '_petteno_rev_testo', isset( $_POST['petteno_rev_testo'] ) ? sanitize_textarea_field( wp_unslash( $_POST['petteno_rev_testo'] ) ) : '' );
	update_post_meta( $post_id, '_petteno_rev_stelle', min( 5, max( 1, $stelle ) ) );
	update_post_meta( $post_id, '_petteno_rev_data', isset( $_POST['petteno_rev_data'] ) ? sanitize_text_field( wp_unslash( $_POST['petteno_rev_data'] ) ) : '' );
	update_post_meta( $post_id, '_petteno_rev_fonte', isset( $_POST['petteno_rev_fonte'] ) ? sanitize_text_field( wp_unslash( $_POST['petteno_rev_fonte'] ) ) : '' );
}
add_action( 'save_post_petteno_recensione', 'petteno_tours_review_save' );

/**
 * Colonne dell'elenco recensioni: stelle e anteprima del testo.
 *
 * @param array $columns Colonne.
 * @return array
 */
function petteno_tours_review_columns( $columns ) {
	return array(
		'cb'               => $columns['cb'],
		'title'            => __( 'Nome', 'petteno-tours' ),
		'petteno_stelle'   => __( 'Stelle', 'petteno-tours' ),
		'petteno_testo'    => __( 'Recensione', 'petteno-tours' ),
		'date'             => $columns['date'],
	);
}
add_filter( 'manage_petteno_recensione_posts_columns', 'petteno_tours_review_columns' );

/**
 * Contenuto delle colonne.
 *
 * @param string $column  Colonna.
 * @param int    $post_id ID.
 */
function petteno_tours_review_column_content( $column, $post_id ) {
	if ( 'petteno_stelle' === $column ) {
		echo esc_html( str_repeat( '★', (int) get_post_meta( $post_id, '_petteno_rev_stelle', true ) ) );
	} elseif ( 'petteno_testo' === $column ) {
		echo esc_html( wp_trim_words( (string) get_post_meta( $post_id, '_petteno_rev_testo', true ), 18 ) );
	}
}
add_action( 'manage_petteno_recensione_posts_custom_column', 'petteno_tours_review_column_content', 10, 2 );

/**
 * Recensioni pubblicate da mostrare in home (massimo 3).
 *
 * @return array[] nome, testo, stelle, data, fonte.
 */
function petteno_tours_get_reviews() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$cache = array();
	$posts = get_posts(
		array(
			'post_type'      => 'petteno_recensione',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
			'no_found_rows'  => true,
		)
	);
	foreach ( $posts as $p ) {
		$testo = (string) get_post_meta( $p->ID, '_petteno_rev_testo', true );
		if ( '' === trim( $testo ) ) {
			continue;
		}
		$cache[] = array(
			'nome'   => get_the_title( $p ),
			'testo'  => $testo,
			'stelle' => max( 1, min( 5, (int) get_post_meta( $p->ID, '_petteno_rev_stelle', true ) ) ),
			'data'   => (string) get_post_meta( $p->ID, '_petteno_rev_data', true ),
			'fonte'  => (string) get_post_meta( $p->ID, '_petteno_rev_fonte', true ),
		);
	}
	return $cache;
}

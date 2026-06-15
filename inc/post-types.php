<?php
/**
 * Tipo di contenuto "Mezzi" (flotta) + campi e album foto.
 *
 * Trasforma la flotta in contenuti gestibili dall'admin: ogni mezzo è una
 * scheda con foto principale, posti, descrizione, dotazioni e una galleria
 * (album). La sezione Flotta li mostra in automatico, adattando il layout.
 *
 * @package PettenoTours
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra il post type "petteno_mezzo".
 */
function petteno_tours_register_mezzi() {
	$labels = array(
		'name'               => __( 'Mezzi', 'petteno-tours' ),
		'singular_name'      => __( 'Mezzo', 'petteno-tours' ),
		'menu_name'          => __( 'Mezzi', 'petteno-tours' ),
		'add_new'            => __( 'Aggiungi mezzo', 'petteno-tours' ),
		'add_new_item'       => __( 'Aggiungi nuovo mezzo', 'petteno-tours' ),
		'edit_item'          => __( 'Modifica mezzo', 'petteno-tours' ),
		'new_item'           => __( 'Nuovo mezzo', 'petteno-tours' ),
		'view_item'          => __( 'Vedi mezzo', 'petteno-tours' ),
		'search_items'       => __( 'Cerca mezzi', 'petteno-tours' ),
		'not_found'          => __( 'Nessun mezzo trovato', 'petteno-tours' ),
		'not_found_in_trash' => __( 'Nessun mezzo nel cestino', 'petteno-tours' ),
		'all_items'          => __( 'Tutti i mezzi', 'petteno-tours' ),
		'featured_image'     => __( 'Foto principale', 'petteno-tours' ),
		'set_featured_image' => __( 'Imposta foto principale', 'petteno-tours' ),
	);

	register_post_type(
		'petteno_mezzo',
		array(
			'labels'        => $labels,
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-bus',
			'menu_position' => 21,
			'has_archive'   => false,
			'rewrite'       => false,
			'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);
}
add_action( 'init', 'petteno_tours_register_mezzi' );

/**
 * Aggiunge il riquadro dei dettagli del mezzo.
 */
function petteno_tours_mezzo_metabox() {
	add_meta_box(
		'petteno_mezzo_dettagli',
		__( 'Dettagli mezzo', 'petteno-tours' ),
		'petteno_tours_mezzo_metabox_render',
		'petteno_mezzo',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'petteno_tours_mezzo_metabox' );

/**
 * Renderizza i campi del riquadro.
 *
 * @param WP_Post $post Post corrente.
 */
function petteno_tours_mezzo_metabox_render( $post ) {
	wp_nonce_field( 'petteno_mezzo_save', 'petteno_mezzo_nonce' );

	$posti     = get_post_meta( $post->ID, '_petteno_posti', true );
	$desc      = get_post_meta( $post->ID, '_petteno_desc', true );
	$dotazioni = get_post_meta( $post->ID, '_petteno_dotazioni', true );
	$gallery   = get_post_meta( $post->ID, '_petteno_gallery', true );
	$ids       = array_filter( array_map( 'absint', explode( ',', (string) $gallery ) ) );
	?>
	<div class="petteno-field">
		<label for="petteno_posti"><?php esc_html_e( 'Posti (es. 48 – 54 posti)', 'petteno-tours' ); ?></label>
		<input type="text" id="petteno_posti" name="petteno_posti" value="<?php echo esc_attr( $posti ); ?>" />
	</div>
	<div class="petteno-field">
		<label for="petteno_desc"><?php esc_html_e( 'Descrizione', 'petteno-tours' ); ?></label>
		<textarea id="petteno_desc" name="petteno_desc" rows="3"><?php echo esc_textarea( $desc ); ?></textarea>
	</div>
	<div class="petteno-field">
		<label for="petteno_dotazioni"><?php esc_html_e( 'Dotazioni (una per riga, oppure separate da virgola)', 'petteno-tours' ); ?></label>
		<textarea id="petteno_dotazioni" name="petteno_dotazioni" rows="3"><?php echo esc_textarea( $dotazioni ); ?></textarea>
	</div>
	<div class="petteno-field">
		<label><?php esc_html_e( 'Album foto (galleria)', 'petteno-tours' ); ?></label>
		<div class="petteno-gallery">
			<input type="hidden" class="petteno-gallery-ids" name="petteno_gallery" value="<?php echo esc_attr( implode( ',', $ids ) ); ?>" />
			<div class="petteno-gallery-items">
				<?php
				foreach ( $ids as $id ) {
					$thumb = wp_get_attachment_image( $id, 'thumbnail' );
					if ( $thumb ) {
						echo '<span class="petteno-gallery-item" data-id="' . esc_attr( $id ) . '">' . $thumb . '<button type="button" class="petteno-gallery-remove" aria-label="' . esc_attr__( 'Rimuovi', 'petteno-tours' ) . '">&times;</button></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				}
				?>
			</div>
			<button type="button" class="button petteno-gallery-add"><?php esc_html_e( 'Aggiungi foto', 'petteno-tours' ); ?></button>
			<p class="description"><?php esc_html_e( 'La foto principale è l’“immagine in evidenza” qui a lato. L’album aggiunge le altre foto, visibili a tutto schermo al clic.', 'petteno-tours' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Salva i campi del mezzo.
 *
 * @param int $post_id ID del post.
 */
function petteno_tours_mezzo_save( $post_id ) {
	if ( ! isset( $_POST['petteno_mezzo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['petteno_mezzo_nonce'] ) ), 'petteno_mezzo_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, '_petteno_posti', isset( $_POST['petteno_posti'] ) ? sanitize_text_field( wp_unslash( $_POST['petteno_posti'] ) ) : '' );
	update_post_meta( $post_id, '_petteno_desc', isset( $_POST['petteno_desc'] ) ? sanitize_textarea_field( wp_unslash( $_POST['petteno_desc'] ) ) : '' );
	update_post_meta( $post_id, '_petteno_dotazioni', isset( $_POST['petteno_dotazioni'] ) ? sanitize_textarea_field( wp_unslash( $_POST['petteno_dotazioni'] ) ) : '' );

	$gallery = isset( $_POST['petteno_gallery'] ) ? sanitize_text_field( wp_unslash( $_POST['petteno_gallery'] ) ) : '';
	$ids     = array_filter( array_map( 'absint', explode( ',', $gallery ) ) );
	update_post_meta( $post_id, '_petteno_gallery', implode( ',', $ids ) );
}
add_action( 'save_post_petteno_mezzo', 'petteno_tours_mezzo_save' );

/**
 * Carica media + script nell'editor del mezzo.
 *
 * @param string $hook Hook della schermata admin.
 */
function petteno_tours_admin_assets( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || 'petteno_mezzo' !== $screen->post_type ) {
		return;
	}
	if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'petteno-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), PETTENO_TOURS_VERSION );
	wp_enqueue_script( 'petteno-admin-media', get_template_directory_uri() . '/assets/js/admin-media.js', array( 'jquery' ), PETTENO_TOURS_VERSION, true );
	wp_localize_script(
		'petteno-admin-media',
		'pettenoAdmin',
		array(
			'frameTitle' => __( 'Seleziona le foto', 'petteno-tours' ),
			'addButton'  => __( 'Aggiungi alle foto', 'petteno-tours' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'petteno_tours_admin_assets' );

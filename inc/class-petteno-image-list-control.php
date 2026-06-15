<?php
/**
 * Controllo Customizer: lista di immagini (per lo slideshow dell'hero).
 *
 * Memorizza una lista di ID allegati separati da virgola; permette di
 * aggiungerne quanti se ne vuole tramite il media frame di WordPress.
 *
 * @package PettenoTours
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'Petteno_Image_List_Control' ) ) {

	/**
	 * Controllo immagini multiple.
	 */
	class Petteno_Image_List_Control extends WP_Customize_Control {

		/**
		 * Tipo di controllo.
		 *
		 * @var string
		 */
		public $type = 'petteno_image_list';

		/**
		 * Output del controllo.
		 */
		public function render_content() {
			?>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<div class="petteno-imglist">
				<div class="petteno-imglist-items"></div>
				<input type="hidden" class="petteno-imglist-ids" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>" />
				<button type="button" class="button petteno-imglist-add"><?php esc_html_e( 'Aggiungi immagini', 'petteno-tours' ); ?></button>
			</div>
			<?php
		}
	}
}

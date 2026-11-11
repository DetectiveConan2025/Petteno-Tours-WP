<?php
/**
 * Header del tema: <head>, apertura <body>, header sticky con navigazione.
 *
 * @package PettenoTours
 */

// Voci di menu generate dalle sezioni attive (vedi functions.php).
$petteno_links = petteno_tours_nav_links();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#main" class="sr-only"><?php esc_html_e( 'Vai al contenuto principale', 'petteno-tours' ); ?></a>

<header class="site-header">
	<div class="wrap header-inner">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand" aria-label="<?php esc_attr_e( 'Pettenò Tours — home', 'petteno-tours' ); ?>">
			<?php
			// Logo: quello caricato in Personalizza → Identità del sito, se c'è;
			// altrimenti il logo Pettenò Tours incluso nel tema. Solo l'immagine
			// (niente secondo link annidato), con le dimensioni del tema.
			$petteno_logo_id = (int) get_theme_mod( 'custom_logo' );
			if ( $petteno_logo_id && wp_attachment_is_image( $petteno_logo_id ) ) :
				echo wp_get_attachment_image(
					$petteno_logo_id,
					'medium',
					false,
					array(
						'class'   => 'brand-logo',
						'alt'     => 'Pettenò Tours',
						'loading' => 'eager',
					)
				);
			else :
				?>
				<img
					src="<?php echo esc_url( petteno_tours_img( 'logo.png' ) ); ?>"
					alt="Pettenò Tours"
					class="brand-logo"
					width="240"
					height="80"
				/>
			<?php endif; ?>
		</a>

		<nav class="nav" aria-label="<?php esc_attr_e( 'Navigazione principale', 'petteno-tours' ); ?>">
			<?php foreach ( $petteno_links as $href => $label ) : ?>
				<a href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</nav>

		<div class="header-actions">
			<a href="<?php echo esc_url( petteno_tours_contact_url() ); ?>" class="btn btn-primary header-cta"><?php esc_html_e( 'Richiedi preventivo', 'petteno-tours' ); ?></a>

			<button class="nav-toggle" aria-expanded="false" aria-controls="mobile-nav" aria-label="<?php esc_attr_e( 'Apri menù', 'petteno-tours' ); ?>">
				<span></span><span></span><span></span>
			</button>
		</div>
	</div>

	<nav id="mobile-nav" class="mobile-nav" aria-label="<?php esc_attr_e( 'Navigazione mobile', 'petteno-tours' ); ?>" hidden>
		<?php foreach ( $petteno_links as $href => $label ) : ?>
			<a href="<?php echo esc_url( $href ); ?>" class="mobile-link"><?php echo esc_html( $label ); ?></a>
		<?php endforeach; ?>
		<a href="<?php echo esc_url( petteno_tours_contact_url() ); ?>" class="btn btn-primary mobile-cta"><?php esc_html_e( 'Richiedi preventivo', 'petteno-tours' ); ?></a>
	</nav>
</header>
